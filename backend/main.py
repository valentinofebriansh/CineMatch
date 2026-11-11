"""
CineMatch API - Tahap 3A: kerangka FastAPI + cek koneksi database.
"""
import os

import pymysql
from fastapi import FastAPI, HTTPException

app = FastAPI(title="CineMatch API")


def get_connection():
    """Buka koneksi ke MySQL memakai pengaturan dari environment variable."""
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