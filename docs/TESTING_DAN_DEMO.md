# Pengujian dan Skenario Demo CineMatch (Backend)

Alamat dasar: `http://localhost:8000`. Buka tiap alamat di browser atau di `/docs`.

Kolom **Status**:
- **Terlihat** = hasilnya sudah muncul di percakapan pengembangan dan sesuai harapan.
- **Belum** = belum dicatat. Jalankan, lalu isi sendiri hasilnya.

## A. Pengujian

| No | Yang diuji | Alamat | Hasil yang diharapkan | Status |
|---|---|---|---|---|
| 1 | Koneksi database | `/health/db` | `{"status":"ok","total_film":4919}` | Terlihat |
| 2 | Katalog halaman 1 | `/movies?page=1&limit=12` | `total` 4919, `total_pages` 410, 12 film | Terlihat |
| 3 | Katalog halaman 2 | `/movies?page=2&limit=5` | 5 film berbeda dari halaman 1, `"page":2` | Terlihat |
| 4 | Input salah katalog | `/movies?page=0` | status 422 | Terlihat |
| 5 | Detail film ada | `/movies/2` | data lengkap *10 Cloverfield Lane* | Terlihat |
| 6 | Detail film tidak ada | `/movies/99999` | status 404, `"Film tidak ditemukan"` | Terlihat |
| 7 | Detail id salah tipe | `/movies/abc` | status 422 | Terlihat |
| 8 | Pencarian judul | `/search?q=batman` | `total` lebih dari 0, judul mengandung "batman" | Terlihat |
| 9 | Pencarian tanpa hasil | `/search?q=zzzzzz` | `total` 0, `items` kosong | Terlihat |
| 10 | Pencarian terlalu pendek | `/search?q=a` | status 422 | Terlihat |
| 11 | Rekomendasi filter | `/recommend?genre=Horror&start_year=2000&end_year=2010&min_votes=0` | `total` 224, teratas *Shaun of the Dead*, *Zombieland*, *Saw* | Terlihat |
| 12 | Syarat voter bawaan | `/recommend` | `min_votes` 1000, film dengan sedikit penilai tidak di puncak | Terlihat |
| 13 | Banyak genre | `/recommend?genre=Comedy,Romance&min_votes=0&limit=1` | `total` 586, film punya Comedy dan Romance | Terlihat |
| 14 | Kata kunci cerita | `/recommend?keyword=bunker&min_votes=0` | 3 film, termasuk *10 Cloverfield Lane* | Terlihat |
| 15 | Rentang tahun terbalik | `/recommend?start_year=2010&end_year=2000` | status 422 dan pesan jelas | Terlihat |
| 16 | AI: genre dan tahun | `/smart-recommend?q=komedi romantis tahun 90an` | `mode` llm, genre `Comedy,Romance`, 1990 sampai 1999, `total` 90 | Terlihat |
| 17 | AI: suasana hati | `/smart-recommend?q=suasana hujan di malam hari dengan sedikit misteri` | `mode` llm, genre `Mystery`, keyword `rain,night` | Terlihat |
| 18 | AI: kalimat tidak jelas | `/smart-recommend?q=halo apa kabar` | `understood` false, `items` kosong | Terlihat |
| 19 | AI gagal, jalur cadangan | (terjadi saat Google sibuk) | `mode` rules, `fallback_reason` terisi, hasil tetap muncul | Terlihat |
| 20 | Tanpa API key | kosongkan `GEMINI_API_KEY`, recreate backend, buka `/smart-recommend?q=film horor tahun 2000 sampai 2010` | `mode` rules dan hasil tetap muncul | Terlihat |
| 21 | Urutan rating | `/recommend?genre=Drama&limit=5` | `imdb_score` menurun dari atas ke bawah | Terlihat |
| 22 | CORS diizinkan | `curl.exe -s -D - -o NUL -H "Origin: http://localhost:8080" "http://localhost:8000/movies?limit=1"` | ada baris `access-control-allow-origin` | Terlihat |
| 23 | CORS ditolak | sama seperti di atas dengan `Origin: http://situs-asing.com` | baris `access-control-allow-origin` tidak ada | Terlihat |

Catatan nomor 20: setelah tes, isi lagi key-nya dan jalankan `docker compose up -d --force-recreate backend`.

## B. Skenario demo (urutan saran, sekitar 5 menit)

1. **Katalog.** Tunjukkan grid film (halaman 1, lalu pindah halaman).
2. **Detail film.** Klik satu film, tunjukkan sutradara, aktor, genre, dan rating.
3. **Pencarian judul.** Ketik "batman" atau judul lain yang sudah dicoba.
4. **Smart Recommender, kalimat sederhana.** "komedi romantis tahun 90an", lalu tunjukkan filter yang dipahami (`parsed`).
5. **Smart Recommender, suasana hati.** "suasana hujan di malam hari dengan sedikit misteri". Ini inti judul proyek.
6. **Kalimat tidak jelas.** "halo apa kabar", tunjukkan pesan "belum dipahami".
7. **Ketahanan sistem.** Jelaskan bahwa kalau layanan AI sedang sibuk, sistem otomatis memakai parser aturan sehingga pengguna tetap mendapat hasil (`mode: rules`).

Tips demo:
- Coba kalimat demo beberapa menit sebelum presentasi. Layanan AI gratis bisa lambat atau sibuk.
- Siapkan kalimat cadangan yang sudah terbukti jalan (nomor 16 dan 17).
- Kalau AI gagal di tengah demo, jelaskan itu fitur cadangan yang disengaja.

## C. Keterbatasan (jujur untuk laporan)

- **Fine-tuning dilakukan di Google Colab.** Model Qwen3-4B-Instruct-2507 dilatih dengan SFT + QLoRA pada 1.500 contoh (1.200 train, 150 val, 150 test). Pada data test, exact match naik dari 36,0% menjadi 91,3% dan skema JSON valid dari 66,7% menjadi 100%; hasil lengkap ada di `EVALUATION_REPORT.md` paket handover. Model dijalankan di server inferensi terpisah (butuh GPU, mis. Colab T4) dan dipanggil lewat `QWEN_API_URL`. Kelemahan: kalimat suasana hati tertentu ("baper", "galau"), ucapan terima kasih, dan permintaan yang tidak didukung (mis. "reality show") masih sering meleset, dan data uji berasal dari templat sehingga angka ini bisa lebih tinggi daripada pada pengguna nyata.
- **Server model tidak selalu menyala.** Kalau `QWEN_API_URL` kosong atau server mati, sistem otomatis memakai parser aturan (`mode: rules`). Nyalakan server sebelum demo.
- **Dataset IMDb 5000, bukan TMDB 5000.** Tidak ada kolom sinopsis, jadi `plot_keywords` menjadi pengganti. Sebagian film `plot_keywords`-nya kosong dan tidak akan cocok dengan pencarian kata kunci.
- **Data berhenti sekitar 2016.** Tidak ada film terbaru atau trending yang nyata.
- **Pencocokan kata kunci sebagian.** Contohnya `night` ikut cocok dengan `nightclub`.
- **AI tidak 100% konsisten.** Kalimat yang sama bisa menghasilkan filter yang sedikit berbeda. Hasil AI selalu divalidasi (genre dari daftar tetap, tahun dan rating dalam rentang wajar) sebelum dipakai.
- **Ketersediaan layanan AI bergantung pada pihak ketiga** (kuota gratis, beban server, timeout). Karena itu ada jalur cadangan berbasis aturan.
- **Parser aturan terbatas:** hanya satu genre dan satu kata kunci dari daftar tetap per kalimat.
- **Rekomendasi hanya berdasarkan filter,** belum ada personalisasi per pengguna (profil dan riwayat di luar MVP).
- **Syarat minimal 1.000 penilai** membuat film dengan sedikit penilai tidak muncul di rekomendasi (angka `min_votes` bisa diubah lewat parameter), tapi tetap ada di katalog dan pencarian.
