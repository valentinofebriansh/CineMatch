# Kontrak API CineMatch

Dokumen ini untuk Role A (Front-End). Isinya: alamat, parameter, dan bentuk respons dari backend FastAPI.

- **Alamat dasar:** `http://localhost:8000`
- **Dokumentasi interaktif (Swagger):** `http://localhost:8000/docs`
- **CORS:** hanya `http://localhost:8080` dan `http://127.0.0.1:8080`, hanya method `GET`.
- **Format:** semua respons berupa JSON.
- **Data:** 4.919 film dari IMDb 5000 Movie Dataset. Data berhenti di sekitar tahun 2016, jadi tidak ada film "terbaru" atau "trending" yang nyata.

## Cara menjalankan di laptopmu

1. `git pull origin main`
2. Buat file `.env`: `Copy-Item .env.example .env`, lalu isi `DB_PASSWORD=root`.
3. Pastikan port 8000 kosong.
4. Kalau database lokalmu belum pakai SQL terbaru: `docker compose down -v` lalu `docker compose up -d db`. **Ini menghapus data database lokalmu**, dan terisi lagi dari file SQL.
5. `docker compose up -d --build`
6. Cek `http://localhost:8000/health/db`. Harus muncul `{"status":"ok","total_film":4919}`.

`GEMINI_API_KEY` di `.env` boleh dikosongkan. Fitur `/smart-recommend` tetap jalan memakai mode `rules`.

## Aturan umum untuk data film

| Field | Tipe | Catatan |
|---|---|---|
| `id` | angka | unik |
| `title` | teks | |
| `release_year` | angka atau `null` | **bisa kosong** |
| `genres` | teks | dipisah `\|`, contoh `Action\|Horror`. Pecah di JS: `genres.split("\|")` |
| `primary_genre` | teks | satu genre utama, cocok untuk warna poster dummy |
| `imdb_score` | angka | contoh `7.3` |
| `plot_keywords` | teks atau `null` | dipisah `\|`, **bisa kosong** |
| `num_voted_users` | angka | hanya ada di `/recommend`, `/smart-recommend`, dan detail |

Tidak ada poster. Buat poster dummy dari `primary_genre` (warna CSS atau SVG).

## Daftar endpoint

### 1. `GET /health/db`
Cek koneksi database.
- Sukses: `{"status":"ok","total_film":4919}`
- Gagal: status `503`, `{"detail":"Database tidak bisa dihubungi"}`

### 2. `GET /movies`
Daftar film dengan halaman, urut berdasarkan `id`.

| Parameter | Tipe | Bawaan | Batas |
|---|---|---|---|
| `page` | angka | 1 | minimal 1 |
| `limit` | angka | 12 | 1 sampai 50 |

Contoh: `/movies?page=1&limit=12`

```json
{
  "page": 1,
  "limit": 12,
  "total": 4919,
  "total_pages": 410,
  "items": [
    {
      "id": 2,
      "title": "10 Cloverfield Lane",
      "release_year": 2016,
      "genres": "Drama|Horror|Mystery|Sci-Fi|Thriller",
      "primary_genre": "Horror",
      "imdb_score": 7.3,
      "plot_keywords": "alien|bunker|car crash|kidnapping|minimal cast"
    }
  ]
}
```

Kalau `page` melebihi `total_pages`, `items` berisi daftar kosong (bukan error).

### 3. `GET /movies/{id}`
Detail satu film (untuk pop-up atau halaman detail).

Contoh: `/movies/2`

Field yang dikembalikan: `id`, `title`, `release_year`, `genres`, `primary_genre`, `imdb_score`, `num_voted_users`, `plot_keywords`, `director_name`, `actor_1_name`, `actor_2_name`, `actor_3_name`, `duration`, `content_rating`, `language`, `country`, `imdb_link`.

Anggap **semua field selain `id` dan `title` bisa `null`**, dan tampilkan teks pengganti seperti "Tidak tersedia".

- Tidak ditemukan: status `404`, `{"detail":"Film tidak ditemukan"}`
- `id` bukan angka (misalnya `/movies/abc`): status `422`

### 4. `GET /search`
Cari film berdasarkan potongan **judul**. Huruf besar atau kecil tidak berpengaruh. Hasil diurutkan dari film dengan penilai terbanyak.

| Parameter | Tipe | Bawaan | Batas |
|---|---|---|---|
| `q` | teks | wajib | 2 sampai 100 karakter |
| `limit` | angka | 12 | 1 sampai 50 |

Contoh: `/search?q=batman`

```json
{ "query": "batman", "total": 0, "items": [ { "id": 0, "title": "...", "...": "..." } ] }
```

(Angka dan isi di atas hanya contoh bentuk. Nilai aslinya bergantung pada data.)

`total` adalah jumlah semua yang cocok, sedangkan `items` dibatasi `limit`. Kalau tidak ada yang cocok: `total` bernilai 0 dan `items` berisi daftar kosong. Tampilkan pesan "tidak ditemukan" di UI.

### 5. `GET /recommend`
Filter terstruktur, urut dari rating IMDb tertinggi. Cocok untuk "Top Rated" dan filter manual.

| Parameter | Tipe | Bawaan | Keterangan |
|---|---|---|---|
| `genre` | teks | kosong | satu atau beberapa genre dipisah koma, **semua harus cocok**. Contoh `Comedy,Romance` |
| `start_year` | angka | kosong | 1900 sampai 2030 |
| `end_year` | angka | kosong | 1900 sampai 2030 |
| `min_rating` | angka | kosong | 0 sampai 10 |
| `keyword` | teks | kosong | satu atau beberapa kata kunci cerita (bahasa Inggris) dipisah koma, **salah satu cocok sudah cukup** |
| `min_votes` | angka | **1000** | minimal jumlah penilai, supaya film dengan sedikit penilai tidak naik ke puncak |
| `limit` | angka | 12 | 1 sampai 50 |

Contoh: `/recommend?genre=Horror&start_year=2000&end_year=2010`

```json
{
  "filters": { "genre": "Horror", "start_year": 2000, "end_year": 2010,
               "min_rating": null, "keyword": null, "min_votes": 1000 },
  "total": 218,
  "items": [
    { "id": 3121, "title": "Shaun of the Dead", "release_year": 2004,
      "genres": "Comedy|Horror", "primary_genre": "Horror", "imdb_score": 8.0,
      "num_voted_users": 395921,
      "plot_keywords": "british horror|pub|record collection|zombie|zombie apocalypse" }
  ]
}
```

Nama genre yang dipakai (huruf awal besar, bahasa Inggris): Action, Adventure, Animation, Biography, Comedy, Crime, Documentary, Drama, Family, Fantasy, History, Horror, Music, Mystery, Romance, Sci-Fi, Sport, Thriller, War, Western.

Error: `start_year` lebih besar dari `end_year` memberi status `422` dan `{"detail":"start_year tidak boleh lebih besar dari end_year"}`.

### 6. `GET /smart-recommend` (fitur AI)
Pengguna mengetik kalimat bebas. Backend mengubahnya menjadi filter (memakai AI, atau aturan kata kunci kalau AI gagal), lalu mengambil film dari database. **AI tidak pernah mengarang judul**, semua hasil dari database.

| Parameter | Tipe | Bawaan | Batas |
|---|---|---|---|
| `q` | teks | wajib | 2 sampai 200 karakter |
| `limit` | angka | 12 | 1 sampai 50 |

Contoh: `/smart-recommend?q=suasana hujan di malam hari dengan sedikit misteri` (di JS, bungkus `q` dengan `encodeURIComponent`).

**Respons saat kalimat dipahami:**

```json
{
  "query": "komedi romantis tahun 90an",
  "mode": "llm",
  "fallback_reason": null,
  "note": null,
  "understood": true,
  "parsed": { "genre": "Comedy,Romance", "start_year": 1990, "end_year": 1999,
              "min_rating": null, "keyword": null },
  "filters": { "genre": "Comedy,Romance", "start_year": 1990, "end_year": 1999,
               "min_rating": null, "keyword": null, "min_votes": 1000 },
  "total": 90,
  "items": [ { "id": 1563, "title": "Groundhog Day", "...": "..." } ]
}
```

**Respons saat kalimat tidak dipahami** (tidak ada filter yang bisa ditarik):

```json
{
  "query": "halo apa kabar",
  "mode": "rules",
  "fallback_reason": null,
  "understood": false,
  "message": "Kalimat belum dipahami. Coba sebut genre, tahun, atau rating.",
  "parsed": { "genre": null, "start_year": null, "end_year": null,
              "min_rating": null, "keyword": null },
  "total": 0,
  "items": []
}
```

Pada respons ini tidak ada field `filters` dan `note`.

Arti field penting:

| Field | Arti |
|---|---|
| `mode` | `"llm"` kalau AI berhasil, `"rules"` kalau memakai cadangan aturan |
| `fallback_reason` | alasan teknis kenapa AI gagal. **Jangan ditampilkan ke pengguna.** |
| `note` | pesan opsional, terisi kalau kata kunci cerita dilonggarkan karena hasilnya kosong. Boleh ditampilkan. |
| `understood` | `false` berarti tampilkan `message` dan jangan tampilkan daftar film |
| `parsed` | filter yang dipahami dari kalimat. Boleh ditampilkan sebagai "chip" supaya pengguna tahu apa yang dicari |

Hal yang perlu diperhatikan frontend:
- **Waktu tunggu.** Mode AI bisa lebih lambat. Pada kasus terburuk bisa sekitar 30 detik. Tampilkan status loading, dan jangan pasang batas waktu fetch di bawah 35 detik.
- Mode `rules` hanya memahami satu genre dan kata kunci tertentu. Hasilnya bisa lebih sempit dari mode `llm`, dan itu normal.
- Kalimat yang sama bisa menghasilkan filter sedikit berbeda antar pemanggilan karena AI tidak selalu 100% konsisten.

## Penanganan error (ringkas)

| Status | Arti | Bentuk `detail` |
|---|---|---|
| 404 | film tidak ada | teks |
| 422 | parameter tidak valid | teks, atau daftar objek (format bawaan FastAPI) |
| 503 | database tidak bisa dihubungi | teks |

Selalu cek `res.ok` sebelum membaca JSON.

## Contoh pemakaian di JavaScript

```js
const API = "http://localhost:8000";

async function getMovies(page = 1) {
  const res = await fetch(`${API}/movies?page=${page}&limit=12`);
  if (!res.ok) throw new Error("Gagal memuat film");
  return res.json();
}

async function smartRecommend(q) {
  const res = await fetch(`${API}/smart-recommend?q=${encodeURIComponent(q)}`);
  if (!res.ok) throw new Error("Gagal memproses permintaan");
  const data = await res.json();
  if (!data.understood) return { message: data.message, items: [] };
  return data;
}
```

## Saran untuk beranda

- **Top Rated:** `/recommend?limit=12`
- **Film 2010 ke atas:** `/recommend?start_year=2010&limit=12`
- **Per genre:** `/recommend?genre=Horror&limit=12`
- **Katalog:** `/movies?page=N&limit=12`
