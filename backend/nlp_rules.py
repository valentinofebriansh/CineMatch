import re

# Kata di kalimat pengguna -> nama genre di database
GENRES = {
    "Horror": ["horor", "horror", "seram", "menyeramkan", "takut", "menakutkan"],
    "Comedy": ["komedi", "comedy", "lucu", "kocak", "ketawa"],
    "Romance": ["romantis", "romance", "cinta", "percintaan"],
    "Action": ["aksi", "action", "laga"],
    "Adventure": ["petualangan", "adventure"],
    "Animation": ["animasi", "kartun", "animation"],
    "Documentary": ["dokumenter", "documentary"],
    "Drama": ["drama", "sedih", "mengharukan", "galau"],
    "Fantasy": ["fantasi", "fantasy"],
    "Mystery": ["misteri", "misterius", "mystery", "teka-teki"],
    "Thriller": ["thriller", "tegang", "menegangkan", "deg-degan"],
    "Sci-Fi": ["sci-fi", "scifi", "fiksi ilmiah"],
    "War": ["perang", "war"],
    "Crime": ["kriminal", "kejahatan", "crime"],
    "Family": ["keluarga", "family"],
    "History": ["sejarah", "history"],
    "Music": ["musik", "music"],
    "Sport": ["olahraga", "sport"],
    "Biography": ["biografi", "biography"],
    "Western": ["western"],
}

# Kata di kalimat pengguna -> kata kunci cerita (bahasa Inggris, seperti di database)
KEYWORDS = {
    "serial killer": ["pembunuh berantai", "serial killer"],
    "murder": ["pembunuhan", "pembunuh", "murder"],
    "ghost": ["hantu", "ghost"],
    "zombie": ["zombie"],
    "alien": ["alien"],
    "space": ["luar angkasa", "antariksa", "space"],
    "revenge": ["balas dendam", "revenge"],
    "detective": ["detektif", "detective"],
    "kidnapping": ["penculikan", "kidnapping"],
    "school": ["sekolah", "school"],
    "prison": ["penjara", "prison"],
    "vampire": ["vampir", "vampire"],
    "robot": ["robot"],
    "rain": ["hujan", "rain"],
    "friendship": ["persahabatan", "sahabat", "friendship"],
    "monster": ["monster"],
    "bunker": ["bunker"],
}


def _first_match(text, table):
    """Cari nilai yang katanya muncul paling awal di kalimat."""
    best = None
    for value, words in table.items():
        for w in words:
            m = re.search(r"(?<!\w)" + re.escape(w) + r"(?!\w)", text)
            if m and (best is None or m.start() < best[0]):
                best = (m.start(), value)
    return best[1] if best else None


def _parse_years(text):
    # 1) Rentang: "2000 sampai 2010", "2000-2010"
    m = re.search(
        r"\b(19\d{2}|20\d{2})\s*(?:-|sampai|hingga|dan|to)\s*(19\d{2}|20\d{2})\b", text
    )
    if m:
        a, b = int(m.group(1)), int(m.group(2))
        return min(a, b), max(a, b)
    # 2) Dekade: "90an", "2000an"
    m = re.search(r"\b(19|20)?(\d)0\s*-?\s*an\b", text)
    if m:
        d = int(m.group(2))
        if m.group(1):
            century = int(m.group(1)) * 100
        else:
            century = 2000 if d <= 2 else 1900
        start = century + d * 10
        return start, start + 9
    # 3) Satu tahun: "tahun 2015"
    m = re.search(r"\b(19\d{2}|20\d{2})\b", text)
    if m:
        y = int(m.group(1))
        return y, y
    # 4) Kata umum
    if re.search(r"(?<!\w)(jadul|klasik|lawas|zaman dulu)(?!\w)", text):
        return None, 1989
    if re.search(r"(?<!\w)(terbaru|baru)(?!\w)", text):
        return 2010, None
    return None, None


def _parse_rating(text):
    m = re.search(
        r"(?:rating|skor|nilai|imdb)\s*(?:di atas|diatas|minimal|min|lebih dari|>=|>|:)?\s*(\d+(?:[.,]\d+)?)",
        text,
    )
    if m:
        value = float(m.group(1).replace(",", "."))
        if 0 <= value <= 10:
            return value
    if re.search(r"(?<!\w)(bagus|terbaik|top|berkualitas)(?!\w)", text):
        return 7.0
    return None


def parse_query(q):
    """Ubah kalimat pengguna menjadi filter. Nilai yang tidak dikenali = None."""
    text = q.lower().strip()
    start_year, end_year = _parse_years(text)
    if start_year is not None and not (1900 <= start_year <= 2030):
        start_year = None
    if end_year is not None and not (1900 <= end_year <= 2030):
        end_year = None
    return {
        "genre": _first_match(text, GENRES),
        "start_year": start_year,
        "end_year": end_year,
        "min_rating": _parse_rating(text),
        "keyword": _first_match(text, KEYWORDS),
    }