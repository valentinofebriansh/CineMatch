"""Pengganti usulan untuk backend/llm_parser.py: memakai model Qwen hasil fine-tuning CineMatch.

TIDAK menimpa file asli. Antarmukanya sama dengan llm_parser.parse_with_llm(q), sehingga main.py
cukup mengganti satu baris impor setelah persetujuan.

Dua cara memanggil model (dipilih lewat env):
  OLLAMA_URL    (disarankan) Ollama di laptop sendiri, mis. http://host.docker.internal:11434
  OLLAMA_MODEL  nama model di Ollama (bawaan: cinematch)
  OLLAMA_TIMEOUT  detik, bawaan 28 (harus < 35 detik batas api/recommend.php)
  QWEN_API_URL  (opsional, cara lama via server Colab) alamat server inferensi, contoh https://xxxx.trycloudflare.com (kosong = gagal -> backend
                otomatis memakai mode 'rules' yang sudah ada)
  QWEN_API_KEY  opsional, dikirim lewat header X-API-Key
Pemetaan: intent selain movie_recommendation -> semua None (backend memakai fallback rules).
Kunci director dan actor diteruskan ke /recommend (parameter director dan actor).
"""
import json
import os
import re
import time
import urllib.error
import urllib.request

try:
    from nlp_rules import GENRES  # dipakai backend asli
    _ALLOWED = {g.lower(): g for g in GENRES}
except ImportError:  # saat diuji terpisah
    _ALLOWED = {}

EMPTY = {"genre": None, "start_year": None, "end_year": None, "min_rating": None, "keyword": None,
         "director": None, "actor": None}


def _name(v):
    v = str(v).strip() if v else ""
    return v[:80] or None


def _map(parsed):
    if not isinstance(parsed, dict):
        raise ValueError("Jawaban server bukan objek JSON")
    if parsed.get("intent") != "movie_recommendation":
        return dict(EMPTY)
    genres = []
    for g in parsed.get("genres") or []:
        name = _ALLOWED.get(str(g).strip().lower(), g if not _ALLOWED else None)
        if name and name not in genres:
            genres.append(name)
    keywords = [str(k).strip().lower() for k in parsed.get("keywords") or [] if str(k).strip()]
    return {
        "genre": ",".join(genres[:2]) or None,
        "start_year": parsed.get("start_year"),
        "end_year": parsed.get("end_year"),
        "min_rating": parsed.get("min_rating"),
        "keyword": ",".join(keywords[:4]) or None,
        "director": _name(parsed.get("director")),
        "actor": _name(parsed.get("actor")),
    }


GENRES_OK = ["Horror", "Comedy", "Romance", "Action", "Adventure", "Animation", "Documentary",
             "Drama", "Fantasy", "Mystery", "Thriller", "Sci-Fi", "War", "Crime", "Family",
             "History", "Music", "Sport", "Biography", "Western"]
INTENTS = {"movie_recommendation", "clarification_needed", "out_of_scope"}


def _extract_json(text):
    text = text.strip()
    try:
        return json.loads(text)
    except json.JSONDecodeError:
        m = re.search(r"\{.*\}", text, re.S)
        if not m:
            raise ValueError("tidak ada JSON pada keluaran model")
        return json.loads(m.group(0))


def _validate(raw):
    """Rapikan keluaran model mentah (Ollama tidak punya validasi seperti inference_server.py)."""
    if not isinstance(raw, dict):
        raise ValueError("keluaran model bukan objek JSON")
    allowed = {g.lower(): g for g in GENRES_OK}
    genres = []
    for g in raw.get("genres") or []:
        name = allowed.get(str(g).strip().lower())
        if name and name not in genres:
            genres.append(name)

    def num(v, lo, hi, cast):
        try:
            x = cast(v)
        except (TypeError, ValueError):
            return None
        return x if lo <= x <= hi else None

    kws = []
    for k in raw.get("keywords") or []:
        k = str(k).strip().lower()
        if k and len(k) <= 30 and re.fullmatch(r"[a-z0-9 \-]+", k) and k not in kws:
            kws.append(k)
    out = {"intent": raw.get("intent") if raw.get("intent") in INTENTS else "clarification_needed",
           "genres": genres[:2], "start_year": num(raw.get("start_year"), 1900, 2030, int),
           "end_year": num(raw.get("end_year"), 1900, 2030, int),
           "min_rating": num(raw.get("min_rating"), 0, 10, float),
           "keywords": kws[:4], "director": _name(raw.get("director")), "actor": _name(raw.get("actor"))}
    if out["start_year"] and out["end_year"] and out["start_year"] > out["end_year"]:
        out["start_year"], out["end_year"] = out["end_year"], out["start_year"]
    return out


def _parse_ollama(q, base):
    """Panggil Ollama lokal. System prompt sudah tertanam di Modelfile (SYSTEM)."""
    timeout = float(os.environ.get("OLLAMA_TIMEOUT", "28"))
    body = {"model": os.environ.get("OLLAMA_MODEL", "cinematch").strip() or "cinematch",
            "messages": [{"role": "user", "content": q}], "stream": False, "keep_alive": "30m",
            "options": {"temperature": 0, "num_predict": 160, "num_ctx": 2048}}
    req = urllib.request.Request(base + "/api/chat", data=json.dumps(body).encode("utf-8"),
                                 headers={"Content-Type": "application/json"}, method="POST")
    try:
        with urllib.request.urlopen(req, timeout=timeout) as resp:
            data = json.loads(resp.read().decode("utf-8"))
    except urllib.error.HTTPError as e:
        raise RuntimeError(f"Ollama HTTP {e.code}: {e.read().decode('utf-8', 'replace')[:300]}") from None
    except (urllib.error.URLError, TimeoutError, OSError) as e:
        raise RuntimeError(f"Ollama tidak terjangkau atau terlalu lambat: {e}") from None
    content = (data.get("message") or {}).get("content", "")
    try:
        return _map(_validate(_extract_json(content)))
    except (ValueError, json.JSONDecodeError) as e:
        raise RuntimeError(f"Keluaran model tidak valid: {e}") from None


def parse_with_llm(q):
    ollama = os.environ.get("OLLAMA_URL", "").strip().rstrip("/")
    if ollama:
        return _parse_ollama(q, ollama)
    base = os.environ.get("QWEN_API_URL", "").strip().rstrip("/")
    if not base:
        raise RuntimeError("OLLAMA_URL dan QWEN_API_URL belum diisi")
    headers = {"Content-Type": "application/json"}
    key = os.environ.get("QWEN_API_KEY", "").strip()
    if key:
        headers["X-API-Key"] = key
    req = urllib.request.Request(base + "/parse", data=json.dumps({"text": q}).encode("utf-8"),
                                 headers=headers, method="POST")
    data = None
    for attempt in range(2):
        try:
            with urllib.request.urlopen(req, timeout=12) as resp:
                data = json.loads(resp.read().decode("utf-8"))
            break
        except urllib.error.HTTPError as e:
            if e.code in (429, 502, 503) and attempt == 0:
                time.sleep(1.5)
                continue
            raise RuntimeError(f"HTTP {e.code}: {e.read().decode('utf-8', 'replace')[:300]}") from None
        except urllib.error.URLError as e:
            if attempt == 0:
                time.sleep(1.5)
                continue
            raise RuntimeError(f"Server AI tidak terjangkau: {e.reason}") from None
    return _map(data.get("parsed"))
