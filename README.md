# CineMatch

Sistem rekomendasi film berbasis PHP, MySQL, dan Tailwind CSS.

## Menjalankan dengan Docker

Pastikan Docker dan Docker Compose sudah terpasang, lalu jalankan dari direktori proyek:

```bash
docker compose up --build
```

Buka [http://localhost:8080](http://localhost:8080) untuk melihat aplikasi. MySQL tersedia di port `3306`. Skrip `database/cinematch_db.sql` dijalankan saat volume database pertama kali dibuat.

Untuk menghentikan layanan, tekan `Ctrl+C`, lalu jalankan:

```bash
docker compose down
```