"""
CineMatch - Tahap 1: Preprocessing dataset IMDb 5000.

Input  : dataset/movie_metadata.csv   (data asli, TIDAK diubah)
Output : dataset/movies_clean.csv     (data bersih untuk database)

Cara jalan (dari folder utama proyek) ada di panduan Tahap 1.
"""
import pandas as pd

INPUT_PATH = "dataset/movie_metadata.csv"
OUTPUT_PATH = "dataset/movies_clean.csv"

# Urutan prioritas genre utama untuk poster dummy.
# Genre di dataset diurutkan abjad (Action dulu, Horror belakangan),
# jadi "genre pertama" tidak bisa dipakai. Genre yang lebih khas
# diletakkan lebih awal, Drama (paling umum) paling akhir.
GENRE_PRIORITY = [
    "Horror", "Animation", "Sci-Fi", "Fantasy", "War", "Western",
    "Musical", "Music", "Crime", "Mystery", "Thriller", "Action",
    "Adventure", "Romance", "Comedy", "Family", "Documentary",
    "Biography", "History", "Sport", "Film-Noir", "Short", "News",
    "Reality-TV", "Game-Show", "Drama",
]


def pick_primary_genre(genres: str) -> str:
    """Pilih satu genre utama dari string seperti 'Action|Horror'."""
    items = [g.strip() for g in genres.split("|") if g.strip()]
    for g in GENRE_PRIORITY:
        if g in items:
            return g
    return items[0] if items else "Unknown"


def main() -> None:
    df = pd.read_csv(INPUT_PATH)
    print(f"[1] Data asli          : {df.shape[0]} baris, {df.shape[1]} kolom")

    # Rapikan teks: judul di dataset ini berakhiran spasi tersembunyi (\xa0)
    text_cols = [
        "color", "director_name", "actor_1_name", "actor_2_name",
        "actor_3_name", "genres", "movie_title", "plot_keywords",
        "movie_imdb_link", "language", "country", "content_rating",
    ]
    for col in text_cols:
        df[col] = (
            df[col]
            .astype("string")
            .str.replace("\xa0", " ", regex=False)
            .str.strip()
        )

    # Hapus duplikat: film yang sama = movie_imdb_link yang sama.
    # Dari tiap pasangan, simpan baris dengan jumlah voter terbanyak.
    before = len(df)
    df = df.sort_values("num_voted_users", ascending=False)
    df = df.drop_duplicates(subset="movie_imdb_link", keep="first")
    print(f"[2] Duplikat dibuang   : {before - len(df)} baris -> sisa {len(df)}")

    # Pilih dan ganti nama kolom yang dipakai CineMatch
    df = df.rename(columns={
        "movie_title": "title",
        "title_year": "release_year",
        "imdb_score": "imdb_score",
        "movie_imdb_link": "imdb_link",
    })
    df["release_year"] = df["release_year"].astype("Int64")  # 2009.0 -> 2009
    df["imdb_score"] = df["imdb_score"].round(1)
    df["primary_genre"] = df["genres"].apply(pick_primary_genre)

    keep = [
        "title", "release_year", "genres", "primary_genre", "imdb_score",
        "num_voted_users", "plot_keywords", "director_name",
        "actor_1_name", "actor_2_name", "actor_3_name", "duration",
        "content_rating", "language", "country", "imdb_link",
    ]
    df = df[keep].sort_values("title").reset_index(drop=True)
    df.insert(0, "id", df.index + 1)

    df.to_csv(OUTPUT_PATH, index=False, encoding="utf-8")
    print(f"[3] Disimpan           : {OUTPUT_PATH} ({len(df)} baris)")

    # Laporan pemeriksaan
    print("\n=== LAPORAN ===")
    print("Nilai kosong per kolom penting:")
    print(df[["title", "release_year", "genres", "imdb_score",
              "plot_keywords", "director_name"]].isna().sum().to_string())
    print(f"\nRentang tahun : {df['release_year'].min()} - {df['release_year'].max()}")
    print(f"Rentang rating: {df['imdb_score'].min()} - {df['imdb_score'].max()}")
    print("\nGenre utama (untuk poster dummy):")
    print(df["primary_genre"].value_counts().to_string())
    print("\n5 baris pertama:")
    print(df[["id", "title", "release_year", "primary_genre", "imdb_score"]]
          .head().to_string(index=False))


if __name__ == "__main__":
    main()
