-- ============================================================
-- CineMatch: Tabel Users & Watchlist
-- File ini TERPISAH dari cinematch_db.sql (data film)
-- Jalankan setelah cinematch_db.sql sudah ada
-- ============================================================

USE cinematch;

-- -------------------------------------------------------
-- Tabel users: menyimpan profil pengguna (nama & foto)
-- -------------------------------------------------------
CREATE TABLE IF NOT EXISTS users (
    id          INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    username    VARCHAR(100)  NOT NULL DEFAULT 'Briang',
    photo_path  VARCHAR(500)  NULL COMMENT 'Path relatif foto profil dari /public/',
    created_at  TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Satu baris default user (single-user app)
INSERT IGNORE INTO users (id, username, photo_path)
VALUES (1, 'Briang', NULL);

-- -------------------------------------------------------
-- Tabel watchlist: menyimpan film yang ditambahkan user
-- -------------------------------------------------------
CREATE TABLE IF NOT EXISTS watchlist (
    id          INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    user_id     INT UNSIGNED  NOT NULL DEFAULT 1,
    movie_id    INT UNSIGNED  NOT NULL COMMENT 'Merujuk ke kolom id tabel movies',
    title       VARCHAR(255)  NOT NULL,
    year        SMALLINT      NULL,
    duration    VARCHAR(20)   NULL,
    genre       VARCHAR(100)  NULL,
    rating      DECIMAL(3,1)  NULL,
    director    VARCHAR(100)  NULL,
    actors      TEXT          NULL,
    synopsis    TEXT          NULL,
    poster_url  VARCHAR(500)  NULL,
    added_at    TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_user_movie (user_id, movie_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data watchlist awal (8 film sesuai tampilan profile.php)
INSERT IGNORE INTO watchlist
    (user_id, movie_id, title, year, duration, genre, rating, director, actors, synopsis, poster_url)
VALUES
    (1, 4920, 'Dune: Part Two',   2024, '2j 46m', 'Sci-Fi, Petualangan, Drama', 4.8,
     'Denis Villeneuve',
     'Timothée Chalamet, Zendaya, Rebecca Ferguson, Javier Bardem',
     'Paul Atreides bersatu dengan Chani dan suku Fremen dalam perjalanan penuh takdir untuk membalas kehancuran keluarganya dan mencegah masa depan yang mengerikan.',
     'https://images.unsplash.com/photo-1446776811953-b23d57bd21aa?auto=format&fit=crop&w=600&q=85'),

    (1, 4921, 'Oppenheimer', 2023, '3j 00m', 'Biografi, Drama, Sejarah', 4.7,
     'Christopher Nolan',
     'Cillian Murphy, Emily Blunt, Matt Damon',
     'Kisah fisikawan J. Robert Oppenheimer yang memimpin Proyek Manhattan untuk mengembangkan senjata nuklir pertama di dunia pada Perang Dunia II.',
     'https://images.unsplash.com/photo-1519608487953-e999c86e7455?auto=format&fit=crop&w=600&q=85'),

    (1, 4922, 'The Batman', 2022, '2j 56m', 'Aksi, Kejahatan, Drama', 4.6,
     'Matt Reeves',
     'Robert Pattinson, Zoë Kravitz, Jeffrey Wright',
     'Ketika seorang pembunuh berantai sadis mulai membunuh tokoh-tokoh politik penting di Gotham, Batman terpaksa menyelidiki korupsi tersembunyi di kota itu.',
     'https://images.unsplash.com/photo-1484589065579-248aad0d8b13?auto=format&fit=crop&w=600&q=85'),

    (1, 4923, 'Spider-Man: Across the Spider-Verse', 2023, '2j 20m', 'Animasi, Aksi, Petualangan', 4.9,
     'Joaquim Dos Santos',
     'Shameik Moore, Hailee Steinfeld, Brian Tyree Henry',
     'Miles Morales terlempar melintasi Multiverse, di mana ia bertemu tim Spider-People yang bertugas melindungi keberadaan semesta.',
     'https://images.unsplash.com/photo-1534796636912-3b95b3ab5986?auto=format&fit=crop&w=600&q=85'),

    (1, 1879, 'Interstellar', 2014, '2j 49m', 'Sci-Fi, Petualangan, Drama', 4.9,
     'Christopher Nolan',
     'Matthew McConaughey, Anne Hathaway, Jessica Chastain',
     'Ketika Bumi menjadi tempat yang tidak dapat dihuni di masa depan, sekelompok penjelajah melintasi lubang cacing untuk mencari planet baru bagi umat manusia.',
     'https://images.unsplash.com/photo-1462331940025-496dfbfc7564?auto=format&fit=crop&w=600&q=85'),

    (1, 4924, 'Blade Runner 2049', 2017, '2j 44m', 'Sci-Fi, Misteri, Thriller', 4.6,
     'Denis Villeneuve',
     'Ryan Gosling, Harrison Ford, Ana de Armas',
     'Penemuan rahasia lama yang terkubur oleh seorang Blade Runner baru membawanya untuk melacak mantan Blade Runner Rick Deckard yang telah hilang selama tiga dekade.',
     'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=600&q=85'),

    (1, 4925, 'Arrival', 2016, '1j 56m', 'Sci-Fi, Drama, Misteri', 4.5,
     'Denis Villeneuve',
     'Amy Adams, Jeremy Renner, Forest Whitaker',
     'Seorang profesor linguistik memimpin tim penyelidik elite militer saat pesawat luar angkasa raksasa mendarat di 12 lokasi berbeda di seluruh dunia.',
     'https://images.unsplash.com/photo-1500534623283-312aade485b7?auto=format&fit=crop&w=600&q=85'),

    (1, 4926, 'Parasite', 2019, '2j 12m', 'Thriller, Drama, Komedi', 4.8,
     'Bong Joon Ho',
     'Song Kang-ho, Lee Sun-kyun, Cho Yeo-jeong',
     'Keserakahan dan diskriminasi kelas mengancam hubungan simbiosis yang baru terbentuk antara keluarga Park yang kaya raya dan klan Kim yang miskin.',
     'https://images.unsplash.com/photo-1478720568477-152d9b164e26?auto=format&fit=crop&w=600&q=85');
