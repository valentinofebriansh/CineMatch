from fastapi import FastAPI, HTTPException, Query
from fastapi.middleware.cors import CORSMiddleware

import math
import os

import pymysql
from fastapi import FastAPI, HTTPException, Query

app = FastAPI(title="CineMatch API")

app.add_middleware(
    CORSMiddleware,
    allow_origins=["http://localhost:8080", "http://127.0.0.1:8080"],
    allow_methods=["GET"],
    allow_headers=["*"],
)

def get_connection():
    return pymysql.connect(
        host=os.environ["DB_HOST"],
        port=int(os.environ.get("DB_PORT", "3306")),
        user=os.environ["DB_USER"],
        password=os.environ["DB_PASSWORD"],
        database=os.environ["DB_NAME"],
        charset="utf8mb4",
        cursorclass=pymysql.cursors.DictCursor,
    )


@app.get("/")
def root():
    return {"status": "ok", "message": "CineMatch API berjalan"}


@app.get("/health/db")
def health_db():
    try:
        conn = get_connection()
    except pymysql.MySQLError:
        raise HTTPException(status_code=503, detail="Database tidak bisa dihubungi")
    try:
        with conn.cursor() as cur:
            cur.execute("SELECT COUNT(*) AS total_film FROM movies")
            row = cur.fetchone()
        return {"status": "ok", "total_film": row["total_film"]}
    finally:
        conn.close()


@app.get("/movies")
def list_movies(
    page: int = Query(1, ge=1, description="Nomor halaman, mulai dari 1"),
    limit: int = Query(12, ge=1, le=50, description="Jumlah film per halaman"),
):
    offset = (page - 1) * limit
    try:
        conn = get_connection()
    except pymysql.MySQLError:
        raise HTTPException(status_code=503, detail="Database tidak bisa dihubungi")
    try:
        with conn.cursor() as cur:
            cur.execute("SELECT COUNT(*) AS total FROM movies")
            total = cur.fetchone()["total"]
            cur.execute(
                "SELECT id, title, release_year, genres, primary_genre, "
                "imdb_score, plot_keywords "
                "FROM movies ORDER BY id LIMIT %s OFFSET %s",
                (limit, offset),
            )
            items = cur.fetchall()
        return {
            "page": page,
            "limit": limit,
            "total": total,
            "total_pages": math.ceil(total / limit),
            "items": items,
        }
    finally:
        conn.close()

@app.get("/movies/{movie_id}")
def get_movie(movie_id: int):
    try:
        conn = get_connection()
    except pymysql.MySQLError:
        raise HTTPException(status_code=503, detail="Database tidak bisa dihubungi")
    try:
        with conn.cursor() as cur:
            cur.execute(
                "SELECT id, title, release_year, genres, primary_genre, "
                "imdb_score, num_voted_users, plot_keywords, "
                "director_name, actor_1_name, actor_2_name, actor_3_name, "
                "duration, content_rating, language, country, imdb_link "
                "FROM movies WHERE id = %s",
                (movie_id,),
            )
            movie = cur.fetchone()
    finally:
        conn.close()
    if movie is None:
        raise HTTPException(status_code=404, detail="Film tidak ditemukan")
    return movie

@app.get("/search")
def search_movies(
    q: str = Query(..., min_length=2, max_length=100, description="Kata kunci judul"),
    limit: int = Query(12, ge=1, le=50, description="Jumlah film maksimal"),
):
    keyword = q.strip()
    if len(keyword) < 2:
        raise HTTPException(status_code=422, detail="Kata kunci minimal 2 huruf")
    # Karakter khusus LIKE (% dan _) dibuat biasa, supaya dicari apa adanya
    safe = keyword.replace("\\", "\\\\").replace("%", "\\%").replace("_", "\\_")
    pattern = "%" + safe + "%"
    try:
        conn = get_connection()
    except pymysql.MySQLError:
        raise HTTPException(status_code=503, detail="Database tidak bisa dihubungi")
    try:
        with conn.cursor() as cur:
            cur.execute(
                "SELECT COUNT(*) AS total FROM movies WHERE title LIKE %s",
                (pattern,),
            )
            total = cur.fetchone()["total"]
            cur.execute(
                "SELECT id, title, release_year, genres, primary_genre, "
                "imdb_score, plot_keywords "
                "FROM movies WHERE title LIKE %s "
                "ORDER BY num_voted_users DESC, id LIMIT %s",
                (pattern, limit),
            )
            items = cur.fetchall()
        return {"query": keyword, "total": total, "items": items}
    finally:
        conn.close()

@app.get("/recommend")
def recommend_movies(
    genre: str | None = Query(None, max_length=30, description="Contoh: Horror"),
    start_year: int | None = Query(None, ge=1900, le=2030),
    end_year: int | None = Query(None, ge=1900, le=2030),
    min_rating: float | None = Query(None, ge=0, le=10),
    min_votes: int = Query(1000, ge=0, description="Minimal jumlah penilai"),
    limit: int = Query(12, ge=1, le=50),
):
    if start_year is not None and end_year is not None and start_year > end_year:
        raise HTTPException(
            status_code=422, detail="start_year tidak boleh lebih besar dari end_year"
        )

    conditions = ["num_voted_users >= %s"]
    params = [min_votes]

    if genre is not None and genre.strip():
        genre_clean = genre.strip()
        safe = (
            genre_clean.replace("\\", "\\\\").replace("%", "\\%").replace("_", "\\_")
        )
        # Pemisah '|' dipasang di kiri-kanan supaya "Music" tidak ikut "Musical"
        conditions.append("CONCAT('|', genres, '|') LIKE %s")
        params.append("%|" + safe + "|%")
    if start_year is not None:
        conditions.append("release_year >= %s")
        params.append(start_year)
    if end_year is not None:
        conditions.append("release_year <= %s")
        params.append(end_year)
    if min_rating is not None:
        conditions.append("imdb_score >= %s")
        params.append(min_rating)

    where_sql = " AND ".join(conditions)

    try:
        conn = get_connection()
    except pymysql.MySQLError:
        raise HTTPException(status_code=503, detail="Database tidak bisa dihubungi")
    try:
        with conn.cursor() as cur:
            cur.execute(
                "SELECT COUNT(*) AS total FROM movies WHERE " + where_sql,
                params,
            )
            total = cur.fetchone()["total"]
            cur.execute(
                "SELECT id, title, release_year, genres, primary_genre, "
                "imdb_score, num_voted_users, plot_keywords "
                "FROM movies WHERE " + where_sql + " "
                "ORDER BY imdb_score DESC, num_voted_users DESC LIMIT %s",
                params + [limit],
            )
            items = cur.fetchall()
        return {
            "filters": {
                "genre": genre,
                "start_year": start_year,
                "end_year": end_year,
                "min_rating": min_rating,
                "min_votes": min_votes,
            },
            "total": total,
            "items": items,
        }
    finally:
        conn.close()