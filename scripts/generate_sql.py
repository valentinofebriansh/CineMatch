"""
CineMatch - Tahap 2B: membuat file SQL (struktur tabel + data film).

Input  : dataset/movies_clean.csv   (hasil Tahap 1)
Output : database/cinematch_db.sql  (dijalankan otomatis oleh MySQL di Docker)
"""
import pandas as pd

INPUT_PATH = "dataset/movies_clean.csv"
OUTPUT_PATH = "database/cinematch_db.sql"
BATCH_SIZE = 500  # jumlah film per perintah INSERT

SCHEMA = """\
SET NAMES utf8mb4;

CREATE DATABASE IF NOT EXISTS cinematch
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE cinematch;

DROP TABLE IF EXISTS movies;

CREATE TABLE movies (
    id              INT UNSIGNED      NOT NULL AUTO_INCREMENT,
    title           VARCHAR(255)      NOT NULL,
    release_year    SMALLINT UNSIGNED NULL,
    genres          VARCHAR(100)      NOT NULL,
    primary_genre   VARCHAR(30)       NOT NULL,
    imdb_score      DECIMAL(3,1)      NOT NULL,
    num_voted_users INT UNSIGNED      NOT NULL DEFAULT 0,
    plot_keywords   VARCHAR(255)      NULL,
    director_name   VARCHAR(100)      NULL,
    actor_1_name    VARCHAR(100)      NULL,
    actor_2_name    VARCHAR(100)      NULL,
    actor_3_name    VARCHAR(100)      NULL,
    duration        SMALLINT UNSIGNED NULL,
    content_rating  VARCHAR(20)       NULL,
    language        VARCHAR(50)       NULL,
    country         VARCHAR(60)       NULL,
    imdb_link       VARCHAR(255)      NULL,
    PRIMARY KEY (id),
    INDEX idx_release_year (release_year),
    INDEX idx_imdb_score (imdb_score)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
"""

COLUMNS = [
    "id", "title", "release_year", "genres", "primary_genre", "imdb_score",
    "num_voted_users", "plot_keywords", "director_name", "actor_1_name",
    "actor_2_name", "actor_3_name", "duration", "content_rating",
    "language", "country", "imdb_link",
]
INT_COLUMNS = ["id", "release_year", "num_voted_users", "duration"]


def sql_value(value) -> str:
    """Ubah satu nilai menjadi teks SQL yang aman."""
    if pd.isna(value):
        return "NULL"
    if isinstance(value, str):
        escaped = value.replace("\\", "\\\\").replace("'", "''")
        return "'" + escaped + "'"
    return str(value)


def main() -> None:
    df = pd.read_csv(INPUT_PATH)
    for col in INT_COLUMNS:
        df[col] = df[col].astype("Int64")  # 101.0 -> 101
    df = df[COLUMNS]

    column_list = ", ".join(COLUMNS)
    parts = [SCHEMA]
    for start in range(0, len(df), BATCH_SIZE):
        chunk = df.iloc[start:start + BATCH_SIZE]
        rows = [
            "(" + ", ".join(sql_value(v) for v in row) + ")"
            for row in chunk.itertuples(index=False, name=None)
        ]
        parts.append(
            f"INSERT INTO movies ({column_list}) VALUES\n"
            + ",\n".join(rows) + ";\n"
        )

    with open(OUTPUT_PATH, "w", encoding="utf-8", newline="\n") as f:
        f.write("\n".join(parts))

    print(f"Selesai: {len(df)} film ditulis ke {OUTPUT_PATH}")


if __name__ == "__main__":
    main()
