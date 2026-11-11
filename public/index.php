<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CineMatch - Beranda</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: "Plus Jakarta Sans", sans-serif;
        }
    </style>
</head>
<body class="relative isolate min-h-screen overflow-x-hidden bg-[#f0f4f8] text-slate-900 antialiased">
    <!-- LATAR HIASAN: Bentuk dekoratif tidak dibaca pembaca layar dan tidak menghalangi klik. -->
    <div aria-hidden="true" class="pointer-events-none fixed inset-0 z-0 overflow-hidden">
        <span class="absolute -left-32 -top-32 h-96 w-96 rounded-full bg-cyan-200/40 blur-3xl"></span>
        <span class="absolute -right-36 top-1/3 h-96 w-96 rounded-full bg-teal-200/30 blur-3xl"></span>
    </div>

    <!-- BAGIAN NAVBAR: Berisi navigasi beranda, search bar interaktif, dan tautan profil. -->
    <nav class="sticky top-0 z-40 border-b border-slate-200/60 bg-white/80 backdrop-blur-md">
        <div class="mx-auto flex h-16 max-w-6xl items-center justify-between gap-5 px-5 sm:px-6">
            <!-- Logo mengarah kembali ke halaman beranda. -->
            <a href="index.php" class="shrink-0 text-2xl font-extrabold tracking-tight text-slate-900">
                CineMatch<span class="text-indigo-600">.</span>
            </a>

            <!-- FORMULIR PENCARIAN FILM INTERAKTIF -->
            <div class="relative hidden w-80 sm:block">
                <form id="searchForm" class="relative" onsubmit="return tanganiSubmitPencarian(event)">
                    <input
                        id="searchInput"
                        type="search"
                        autocomplete="off"
                        placeholder="Cari judul film (mis: Batman, Dune)..."
                        class="w-full rounded-full border border-slate-200/90 bg-white px-4 py-2.5 pr-10 text-sm text-slate-700 shadow-md shadow-slate-200/60 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-cyan-200"
                    >
                    <button type="submit" aria-label="Cari" class="absolute right-4 top-3 text-slate-400 hover:text-cyan-600">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                            <circle cx="11" cy="11" r="7"/>
                            <path d="m21 21-4.3-4.3"/>
                        </svg>
                    </button>
                </form>
                <!-- Dropdown Hasil Pencarian Cepat Langsung -->
                <div id="searchResultsDropdown" class="hidden absolute left-0 right-0 top-12 z-50 max-h-80 overflow-y-auto rounded-2xl border border-slate-200 bg-white p-2 shadow-2xl">
                </div>
            </div>

            <!-- Membuka profile.php -->
            <a href="profile.php" class="flex shrink-0 items-center gap-3 text-sm font-semibold text-slate-700 transition-colors hover:text-slate-900">
                Briang
                <span class="relative flex h-10 w-10 items-center justify-center rounded-full bg-slate-200 text-sm font-bold text-slate-700">
                    B
                    <span class="absolute bottom-0 right-0 h-3 w-3 rounded-full border-2 border-white bg-[#ccff00]"></span>
                </span>
            </a>
        </div>
    </nav>

    <!-- KONTEN UTAMA: Mengelompokkan hero rekomendasi dan koleksi film. -->
    <main class="relative z-10 mx-auto max-w-6xl px-5 pb-24 sm:px-6">
        <!-- BAGIAN HERO: Memperkenalkan CineMatch dan menampilkan film pilihan. -->
        <section class="grid items-center gap-10 py-10 sm:py-14 lg:grid-cols-[1.05fr_.95fr] lg:gap-16">
            <div class="max-w-xl">
                <h1 class="text-4xl font-extrabold leading-tight tracking-tight text-slate-900 sm:text-5xl">
                    Rekomendasi Film Pintar.
                    <span class="bg-gradient-to-r from-cyan-600 to-blue-600 bg-clip-text text-transparent">Sesuai Mood Kamu.</span>
                </h1>
                <p class="mt-5 max-w-lg text-base leading-7 text-slate-500">
                    Temukan cerita berikutnya yang pas untukmu, dari pilihan para cinephile hingga rekomendasi pintar berbasis AI.
                </p>
                <div class="mt-7 flex flex-wrap items-center gap-4">
                    <!-- Menggulir halaman ke bagian koleksi film dengan ID movieGrid. -->
                    <a href="#movieGrid" class="rounded-full bg-gradient-to-r from-cyan-500 to-teal-500 px-6 py-2.5 text-sm font-bold text-white shadow-md shadow-cyan-500/20 transition-all duration-300 hover:-translate-y-0.5 hover:from-cyan-600 hover:to-teal-600 hover:shadow-xl hover:shadow-cyan-100/50">
                        Jelajahi Sekarang
                    </a>
                    <div class="flex items-center gap-2 text-sm text-slate-600">
                        <span class="text-amber-400">★</span>
                        <span><strong class="font-bold text-slate-900">4.9</strong> dari 1200+ Cinephile</span>
                    </div>
                </div>
            </div>

            <div class="relative mx-auto w-full max-w-lg">
                <div class="absolute -left-4 top-12 z-10 rounded-2xl border border-slate-200/90 bg-white p-3 shadow-md shadow-slate-200/60 sm:-left-8">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Pilihan minggu ini</p>
                    <p class="mt-1 text-sm font-bold text-slate-900">Dune: Part Two</p>
                    <p class="mt-1 text-xs text-amber-500">★★★★★ <span class="text-slate-500">4.8</span></p>
                </div>
                <div class="overflow-hidden rounded-3xl border border-slate-200/90 bg-white p-3 shadow-md shadow-slate-200/60 transition-all duration-300 hover:shadow-xl hover:shadow-cyan-100/50">
                    <img
                        src="https://images.unsplash.com/photo-1446776811953-b23d57bd21aa?auto=format&amp;fit=crop&amp;w=1200&amp;q=85"
                        alt="Pemandangan planet sinematik untuk Dune: Part Two"
                        class="h-64 w-full rounded-2xl object-cover sm:h-80"
                    >
                    <div class="flex items-center justify-between gap-4 px-2 py-4">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-indigo-600">Sci-Fi · Petualangan</p>
                            <h2 class="mt-1 text-lg font-extrabold text-slate-900">Cerita besar. Pilihan tepat.</h2>
                        </div>
                        <button type="button" class="kartu-film flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-[#0f172a] text-white transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md" aria-label="Lihat detail film"
                            data-id="4920"
                            data-title="Dune: Part Two"
                            data-year="2024"
                            data-duration="2j 46m"
                            data-genre="Sci-Fi, Petualangan, Drama"
                            data-rating="4.8"
                            data-director="Denis Villeneuve"
                            data-actors="Timothée Chalamet, Zendaya, Rebecca Ferguson, Javier Bardem"
                            data-synopsis="Paul Atreides bersatu dengan Chani dan suku Fremen dalam perjalanan penuh takdir untuk membalas kehancuran keluarganya dan mencegah masa depan yang mengerikan."
                            data-poster="https://images.unsplash.com/photo-1446776811953-b23d57bd21aa?auto=format&amp;fit=crop&amp;w=600&amp;q=85">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M8 5v14l11-7z"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- BAGIAN GRID FILM: Menampilkan koleksi film atau hasil pencarian. -->
        <section id="movieGrid" class="scroll-mt-24 mb-12">
            <div class="mb-5 flex items-end justify-between">
                <div>
                    <p id="gridHeaderCategory" class="mb-1 text-xs font-bold uppercase tracking-[.16em] text-indigo-600">Pilihan populer</p>
                    <h2 id="gridHeaderTitle" class="text-2xl font-extrabold tracking-tight text-slate-900">Trending sekarang</h2>
                </div>
                <div id="gridHeaderSub" class="text-sm text-slate-500">
                    <span class="hidden sm:inline">Dikurasi dari Database CineMatch</span>
                </div>
            </div>

            <div id="gridItemsContainer" class="grid grid-cols-2 gap-4 sm:grid-cols-3 sm:gap-5 lg:grid-cols-5">
                <article class="kartu-film cursor-pointer rounded-2xl border border-slate-200/90 bg-white p-3 shadow-md shadow-slate-200/60 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-cyan-100/50"
                    data-id="4920"
                    data-title="Dune: Part Two"
                    data-year="2024"
                    data-duration="2j 46m"
                    data-genre="Sci-Fi, Petualangan, Drama"
                    data-rating="4.8"
                    data-director="Denis Villeneuve"
                    data-actors="Timothée Chalamet, Zendaya, Rebecca Ferguson, Javier Bardem"
                    data-synopsis="Paul Atreides bersatu dengan Chani dan suku Fremen dalam perjalanan penuh takdir untuk membalas kehancuran keluarganya dan mencegah masa depan yang mengerikan."
                    data-poster="https://images.unsplash.com/photo-1446776811953-b23d57bd21aa?auto=format&amp;fit=crop&amp;w=600&amp;q=85">
                    <div class="relative overflow-hidden rounded-xl">
                        <img src="https://images.unsplash.com/photo-1446776811953-b23d57bd21aa?auto=format&amp;fit=crop&amp;w=600&amp;q=85" alt="Pemandangan planet untuk Dune: Part Two" class="aspect-[2/3] w-full object-cover">
                        <span class="absolute right-2 top-2 rounded-full bg-slate-900/80 px-2.5 py-1 text-xs font-bold text-cyan-300 shadow-sm backdrop-blur-md">★ 4.8</span>
                    </div>
                    <h3 class="mt-3 truncate text-sm font-bold text-slate-900">Dune: Part Two</h3>
                    <p class="mt-1 text-xs text-slate-500">2024 <span class="px-1 text-slate-300">•</span> 2j 46m</p>
                    <span class="mt-2 inline-flex rounded-full border border-cyan-200/60 bg-cyan-50 px-2.5 py-0.5 text-[10px] font-semibold text-cyan-700">Sci-Fi</span>
                    <p class="mt-2 text-xs tracking-wide text-amber-400">★★★★★</p>
                </article>

                <article class="kartu-film cursor-pointer rounded-2xl border border-slate-200/90 bg-white p-3 shadow-md shadow-slate-200/60 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-cyan-100/50"
                    data-id="4921"
                    data-title="Oppenheimer"
                    data-year="2023"
                    data-duration="3j 00m"
                    data-genre="Biografi, Drama, Sejarah"
                    data-rating="4.7"
                    data-director="Christopher Nolan"
                    data-actors="Cillian Murphy, Emily Blunt, Matt Damon"
                    data-synopsis="Kisah fisikawan J. Robert Oppenheimer yang memimpin Proyek Manhattan untuk mengembangkan senjata nuklir pertama di dunia pada Perang Dunia II."
                    data-poster="https://images.unsplash.com/photo-1519608487953-e999c86e7455?auto=format&amp;fit=crop&amp;w=600&amp;q=85">
                    <div class="relative overflow-hidden rounded-xl">
                        <img src="https://images.unsplash.com/photo-1519608487953-e999c86e7455?auto=format&amp;fit=crop&amp;w=600&amp;q=85" alt="Langit malam untuk Oppenheimer" class="aspect-[2/3] w-full object-cover">
                        <span class="absolute right-2 top-2 rounded-full bg-slate-900/80 px-2.5 py-1 text-xs font-bold text-cyan-300 shadow-sm backdrop-blur-md">★ 4.7</span>
                    </div>
                    <h3 class="mt-3 truncate text-sm font-bold text-slate-900">Oppenheimer</h3>
                    <p class="mt-1 text-xs text-slate-500">2023 <span class="px-1 text-slate-300">•</span> 3j 00m</p>
                    <span class="mt-2 inline-flex rounded-full border border-cyan-200/60 bg-cyan-50 px-2.5 py-0.5 text-[10px] font-semibold text-cyan-700">Drama</span>
                    <p class="mt-2 text-xs tracking-wide text-amber-400">★★★★★</p>
                </article>

                <article class="kartu-film cursor-pointer rounded-2xl border border-slate-200/90 bg-white p-3 shadow-md shadow-slate-200/60 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-cyan-100/50"
                    data-id="4922"
                    data-title="The Batman"
                    data-year="2022"
                    data-duration="2j 56m"
                    data-genre="Aksi, Kejahatan, Drama"
                    data-rating="4.6"
                    data-director="Matt Reeves"
                    data-actors="Robert Pattinson, Zoë Kravitz, Jeffrey Wright"
                    data-synopsis="Ketika seorang pembunuh berantai sadis mulai membunuh tokoh-tokoh politik penting di Gotham, Batman terpaksa menyelidiki korupsi tersembunyi di kota itu."
                    data-poster="https://images.unsplash.com/photo-1484589065579-248aad0d8b13?auto=format&amp;fit=crop&amp;w=600&amp;q=85">
                    <div class="relative overflow-hidden rounded-xl">
                        <img src="https://images.unsplash.com/photo-1484589065579-248aad0d8b13?auto=format&amp;fit=crop&amp;w=600&amp;q=85" alt="Kota malam untuk The Batman" class="aspect-[2/3] w-full object-cover">
                        <span class="absolute right-2 top-2 rounded-full bg-slate-900/80 px-2.5 py-1 text-xs font-bold text-cyan-300 shadow-sm backdrop-blur-md">★ 4.6</span>
                    </div>
                    <h3 class="mt-3 truncate text-sm font-bold text-slate-900">The Batman</h3>
                    <p class="mt-1 text-xs text-slate-500">2022 <span class="px-1 text-slate-300">•</span> 2j 56m</p>
                    <span class="mt-2 inline-flex rounded-full border border-cyan-200/60 bg-cyan-50 px-2.5 py-0.5 text-[10px] font-semibold text-cyan-700">Aksi</span>
                    <p class="mt-2 text-xs tracking-wide text-amber-400">★★★★★</p>
                </article>

                <article class="kartu-film cursor-pointer rounded-2xl border border-slate-200/90 bg-white p-3 shadow-md shadow-slate-200/60 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-cyan-100/50"
                    data-id="4923"
                    data-title="Spider-Man: Across the Spider-Verse"
                    data-year="2023"
                    data-duration="2j 20m"
                    data-genre="Animasi, Aksi, Petualangan"
                    data-rating="4.9"
                    data-director="Joaquim Dos Santos"
                    data-actors="Shameik Moore, Hailee Steinfeld, Brian Tyree Henry"
                    data-synopsis="Miles Morales terlempar melintasi Multiverse, di mana ia bertemu tim Spider-People yang bertugas melindungi keberadaan semesta."
                    data-poster="https://images.unsplash.com/photo-1534796636912-3b95b3ab5986?auto=format&amp;fit=crop&amp;w=600&amp;q=85">
                    <div class="relative overflow-hidden rounded-xl">
                        <img src="https://images.unsplash.com/photo-1534796636912-3b95b3ab5986?auto=format&amp;fit=crop&amp;w=600&amp;q=85" alt="Galaksi berwarna untuk Spider-Man: Across the Spider-Verse" class="aspect-[2/3] w-full object-cover">
                        <span class="absolute right-2 top-2 rounded-full bg-slate-900/80 px-2.5 py-1 text-xs font-bold text-cyan-300 shadow-sm backdrop-blur-md">★ 4.9</span>
                    </div>
                    <h3 class="mt-3 truncate text-sm font-bold text-slate-900">Spider-Man: Across the Spider-Verse</h3>
                    <p class="mt-1 text-xs text-slate-500">2023 <span class="px-1 text-slate-300">•</span> 2j 20m</p>
                    <span class="mt-2 inline-flex rounded-full border border-cyan-200/60 bg-cyan-50 px-2.5 py-0.5 text-[10px] font-semibold text-cyan-700">Animasi</span>
                    <p class="mt-2 text-xs tracking-wide text-amber-400">★★★★★</p>
                </article>

                <article class="kartu-film cursor-pointer rounded-2xl border border-slate-200/90 bg-white p-3 shadow-md shadow-slate-200/60 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-cyan-100/50"
                    data-id="1879"
                    data-title="Interstellar"
                    data-year="2014"
                    data-duration="2j 49m"
                    data-genre="Sci-Fi, Petualangan, Drama"
                    data-rating="4.9"
                    data-director="Christopher Nolan"
                    data-actors="Matthew McConaughey, Anne Hathaway, Jessica Chastain"
                    data-synopsis="Ketika Bumi menjadi tempat yang tidak dapat dihuni di masa depan, sekelompok penjelajah melintasi lubang cacing untuk mencari planet baru bagi umat manusia."
                    data-poster="https://images.unsplash.com/photo-1462331940025-496dfbfc7564?auto=format&amp;fit=crop&amp;w=600&amp;q=85">
                    <div class="relative overflow-hidden rounded-xl">
                        <img src="https://images.unsplash.com/photo-1462331940025-496dfbfc7564?auto=format&amp;fit=crop&amp;w=600&amp;q=85" alt="Galaksi untuk Interstellar" class="aspect-[2/3] w-full object-cover">
                        <span class="absolute right-2 top-2 rounded-full bg-slate-900/80 px-2.5 py-1 text-xs font-bold text-cyan-300 shadow-sm backdrop-blur-md">★ 4.9</span>
                    </div>
                    <h3 class="mt-3 truncate text-sm font-bold text-slate-900">Interstellar</h3>
                    <p class="mt-1 text-xs text-slate-500">2014 <span class="px-1 text-slate-300">•</span> 2j 49m</p>
                    <span class="mt-2 inline-flex rounded-full border border-cyan-200/60 bg-cyan-50 px-2.5 py-0.5 text-[10px] font-semibold text-cyan-700">Sci-Fi</span>
                    <p class="mt-2 text-xs tracking-wide text-amber-400">★★★★★</p>
                </article>
            </div>
        </section>

        <!-- BAGIAN BARU DI INDONESIA -->
        <section class="mb-12">
            <div class="mb-5">
                <p class="mb-1 text-xs font-bold uppercase tracking-[.16em] text-indigo-600">Temukan kisah baru</p>
                <h2 class="text-2xl font-extrabold tracking-tight text-slate-900">Baru di Indonesia</h2>
            </div>
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 sm:gap-5 lg:grid-cols-5">
                <article class="kartu-film cursor-pointer rounded-2xl border border-slate-200/90 bg-white p-3 shadow-md shadow-slate-200/60 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-cyan-100/50"
                    data-id="4924"
                    data-title="Blade Runner 2049"
                    data-year="2017"
                    data-duration="2j 44m"
                    data-genre="Sci-Fi, Misteri, Thriller"
                    data-rating="4.6"
                    data-director="Denis Villeneuve"
                    data-actors="Ryan Gosling, Harrison Ford, Ana de Armas"
                    data-synopsis="Penemuan rahasia lama yang terkubur oleh seorang Blade Runner baru membawanya untuk melacak mantan Blade Runner Rick Deckard yang telah hilang selama tiga dekade."
                    data-poster="https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&amp;fit=crop&amp;w=600&amp;q=85">
                    <div class="relative overflow-hidden rounded-xl"><img src="https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&amp;fit=crop&amp;w=600&amp;q=85" alt="Bentang alam untuk Blade Runner 2049" class="aspect-[2/3] w-full object-cover"><span class="absolute right-2 top-2 rounded-full bg-slate-900/80 px-2.5 py-1 text-xs font-bold text-cyan-300 shadow-sm backdrop-blur-md">★ 4.6</span></div>
                    <h3 class="mt-3 truncate text-sm font-bold text-slate-900">Blade Runner 2049</h3>
                    <p class="mt-1 text-xs text-slate-500">2017 <span class="px-1 text-slate-300">•</span> 2j 44m</p>
                    <span class="mt-2 inline-flex rounded-full border border-cyan-200/60 bg-cyan-50 px-2.5 py-0.5 text-[10px] font-semibold text-cyan-700">Sci-Fi</span>
                    <p class="mt-2 text-xs tracking-wide text-amber-400">★★★★★</p>
                </article>

                <article class="kartu-film cursor-pointer rounded-2xl border border-slate-200/90 bg-white p-3 shadow-md shadow-slate-200/60 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-cyan-100/50"
                    data-id="4925"
                    data-title="Arrival"
                    data-year="2016"
                    data-duration="1j 56m"
                    data-genre="Sci-Fi, Drama, Misteri"
                    data-rating="4.5"
                    data-director="Denis Villeneuve"
                    data-actors="Amy Adams, Jeremy Renner, Forest Whitaker"
                    data-synopsis="Seorang profesor linguistik memimpin tim penyelidik elite militer saat pesawat luar angkasa raksasa mendarat di 12 lokasi berbeda di seluruh dunia."
                    data-poster="https://images.unsplash.com/photo-1500534623283-312aade485b7?auto=format&amp;fit=crop&amp;w=600&amp;q=85">
                    <div class="relative overflow-hidden rounded-xl"><img src="https://images.unsplash.com/photo-1500534623283-312aade485b7?auto=format&amp;fit=crop&amp;w=600&amp;q=85" alt="Pemandangan alam untuk Arrival" class="aspect-[2/3] w-full object-cover"><span class="absolute right-2 top-2 rounded-full bg-slate-900/80 px-2.5 py-1 text-xs font-bold text-cyan-300 shadow-sm backdrop-blur-md">★ 4.5</span></div>
                    <h3 class="mt-3 truncate text-sm font-bold text-slate-900">Arrival</h3>
                    <p class="mt-1 text-xs text-slate-500">2016 <span class="px-1 text-slate-300">•</span> 1j 56m</p>
                    <span class="mt-2 inline-flex rounded-full border border-cyan-200/60 bg-cyan-50 px-2.5 py-0.5 text-[10px] font-semibold text-cyan-700">Sci-Fi</span>
                    <p class="mt-2 text-xs tracking-wide text-amber-400">★★★★★</p>
                </article>

                <article class="kartu-film cursor-pointer rounded-2xl border border-slate-200/90 bg-white p-3 shadow-md shadow-slate-200/60 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-cyan-100/50"
                    data-id="4926"
                    data-title="Parasite"
                    data-year="2019"
                    data-duration="2j 12m"
                    data-genre="Thriller, Drama, Komedi"
                    data-rating="4.8"
                    data-director="Bong Joon Ho"
                    data-actors="Song Kang-ho, Lee Sun-kyun, Cho Yeo-jeong"
                    data-synopsis="Keserakahan dan diskriminasi kelas mengancam hubungan simbiosis yang baru terbentuk antara keluarga Park yang kaya raya dan klan Kim yang miskin."
                    data-poster="https://images.unsplash.com/photo-1478720568477-152d9b164e26?auto=format&amp;fit=crop&amp;w=600&amp;q=85">
                    <div class="relative overflow-hidden rounded-xl"><img src="https://images.unsplash.com/photo-1478720568477-152d9b164e26?auto=format&amp;fit=crop&amp;w=600&amp;q=85" alt="Suasana bioskop untuk Parasite" class="aspect-[2/3] w-full object-cover"><span class="absolute right-2 top-2 rounded-full bg-slate-900/80 px-2.5 py-1 text-xs font-bold text-cyan-300 shadow-sm backdrop-blur-md">★ 4.8</span></div>
                    <h3 class="mt-3 truncate text-sm font-bold text-slate-900">Parasite</h3>
                    <p class="mt-1 text-xs text-slate-500">2019 <span class="px-1 text-slate-300">•</span> 2j 12m</p>
                    <span class="mt-2 inline-flex rounded-full border border-cyan-200/60 bg-cyan-50 px-2.5 py-0.5 text-[10px] font-semibold text-cyan-700">Thriller</span>
                    <p class="mt-2 text-xs tracking-wide text-amber-400">★★★★★</p>
                </article>

                <article class="kartu-film cursor-pointer rounded-2xl border border-slate-200/90 bg-white p-3 shadow-md shadow-slate-200/60 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-cyan-100/50"
                    data-id="3121"
                    data-title="Shaun of the Dead"
                    data-year="2004"
                    data-duration="1j 39m"
                    data-genre="Komedi, Horor"
                    data-rating="4.7"
                    data-director="Edgar Wright"
                    data-actors="Simon Pegg, Nick Frost, Kate Ashfield"
                    data-synopsis="Seorang pria biasa di London harus menghadapi wabah kiamat zombie bersama sahabatnya demi menyelamatkan mantan pacar dan ibunya."
                    data-poster="https://images.unsplash.com/photo-1509281373149-e957c6296406?auto=format&amp;fit=crop&amp;w=600&amp;q=85">
                    <div class="relative overflow-hidden rounded-xl"><img src="https://images.unsplash.com/photo-1509281373149-e957c6296406?auto=format&amp;fit=crop&amp;w=600&amp;q=85" alt="Suasana horor komedi untuk Shaun of the Dead" class="aspect-[2/3] w-full object-cover"><span class="absolute right-2 top-2 rounded-full bg-slate-900/80 px-2.5 py-1 text-xs font-bold text-cyan-300 shadow-sm backdrop-blur-md">★ 4.7</span></div>
                    <h3 class="mt-3 truncate text-sm font-bold text-slate-900">Shaun of the Dead</h3>
                    <p class="mt-1 text-xs text-slate-500">2004 <span class="px-1 text-slate-300">•</span> 1j 39m</p>
                    <span class="mt-2 inline-flex rounded-full border border-cyan-200/60 bg-cyan-50 px-2.5 py-0.5 text-[10px] font-semibold text-cyan-700">Horor</span>
                    <p class="mt-2 text-xs tracking-wide text-amber-400">★★★★★</p>
                </article>

                <article class="kartu-film cursor-pointer rounded-2xl border border-slate-200/90 bg-white p-3 shadow-md shadow-slate-200/60 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-cyan-100/50"
                    data-id="4302"
                    data-title="The Shawshank Redemption"
                    data-year="1994"
                    data-duration="2j 22m"
                    data-genre="Drama, Kejahatan"
                    data-rating="4.9"
                    data-director="Frank Darabont"
                    data-actors="Tim Robbins, Morgan Freeman, Bob Gunton"
                    data-synopsis="Kisah harapan dan persahabatan seorang bankir yang dijatuhi hukuman penjara seumur hidup atas kejahatan yang tidak ia lakukan di penjara Shawshank."
                    data-poster="https://images.unsplash.com/photo-1485846234645-a62644f84728?auto=format&amp;fit=crop&amp;w=600&amp;q=85">
                    <div class="relative overflow-hidden rounded-xl"><img src="https://images.unsplash.com/photo-1485846234645-a62644f84728?auto=format&amp;fit=crop&amp;w=600&amp;q=85" alt="Suasana penjara untuk The Shawshank Redemption" class="aspect-[2/3] w-full object-cover"><span class="absolute right-2 top-2 rounded-full bg-slate-900/80 px-2.5 py-1 text-xs font-bold text-cyan-300 shadow-sm backdrop-blur-md">★ 4.9</span></div>
                    <h3 class="mt-3 truncate text-sm font-bold text-slate-900">The Shawshank Redemption</h3>
                    <p class="mt-1 text-xs text-slate-500">1994 <span class="px-1 text-slate-300">•</span> 2j 22m</p>
                    <span class="mt-2 inline-flex rounded-full border border-cyan-200/60 bg-cyan-50 px-2.5 py-0.5 text-[10px] font-semibold text-cyan-700">Drama</span>
                    <p class="mt-2 text-xs tracking-wide text-amber-400">★★★★★</p>
                </article>
            </div>
        </section>
    </main>

    <!-- TOMBOL CHATBOT MELAYANG: Membuka halaman chat.php. -->
    <a href="chat.php" class="fixed bottom-6 right-6 z-30 flex items-end gap-3">
        <span class="mb-3 max-w-[190px] rounded-2xl border border-slate-200 bg-white px-3 py-2 text-xs text-slate-600 shadow-md">
            Lagi pengen nonton film seperti apa hari ini?
        </span>
        <span class="relative flex h-14 w-14 items-center justify-center rounded-full bg-[#0f172a] text-white shadow-md transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md">
            <svg viewBox="0 0 64 64" class="h-9 w-9" fill="currentColor" aria-hidden="true">
                <circle cx="18" cy="6" r="4"/><circle cx="46" cy="6" r="4"/><rect x="16" y="9" width="3" height="9"/><rect x="45" y="9" width="3" height="9"/><rect x="6" y="18" width="52" height="40" rx="8"/><rect x="16" y="30" width="10" height="8" rx="2" fill="#334155"/><rect x="38" y="30" width="10" height="8" rx="2" fill="#334155"/><rect x="18" y="46" width="28" height="5" rx="2" fill="#334155"/>
            </svg>
            <span class="absolute right-0 top-0 h-3.5 w-3.5 rounded-full border-2 border-[#0f172a] bg-[#ccff00]"></span>
        </span>
    </a>

    <!-- MODAL DETAIL FILM: Tersembunyi sampai kartu film dipilih; JavaScript mengatur class hidden. -->
    <div id="movieModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4 backdrop-blur-sm sm:p-6">
        <div class="relative max-h-[90vh] w-full max-w-4xl overflow-y-auto rounded-3xl border border-slate-200 bg-white p-5 shadow-2xl sm:p-8">
            <button id="closeModal" aria-label="Tutup" class="absolute right-5 top-4 text-2xl text-slate-400 transition-colors hover:text-slate-900">&times;</button>
            <div class="flex flex-col gap-6 sm:flex-row sm:gap-8">
                <div class="w-full shrink-0 space-y-4 sm:w-52">
                    <img id="modalPoster" src="https://images.unsplash.com/photo-1446776811953-b23d57bd21aa?auto=format&amp;fit=crop&amp;w=600&amp;q=85" alt="Poster film" class="aspect-[2/3] w-full rounded-2xl object-cover">
                    <button type="button" class="w-full rounded-full bg-[#ccff00] px-3 py-2.5 text-xs font-extrabold text-slate-900 transition-all duration-200 hover:-translate-y-0.5 hover:bg-[#b8e600] hover:shadow-md">
                        <span class="text-amber-500">★</span> Tambahkan ke list menonton
                    </button>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-3 text-xs">
                        <p class="mb-2 font-bold text-slate-900">Tempat menonton</p>
                        <p class="flex items-center gap-2 text-slate-500"><span class="h-3 w-4 rounded-sm bg-indigo-500"></span>Bioskop &amp; Streaming</p>
                    </div>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="mb-2 text-xs font-bold uppercase tracking-[.16em] text-indigo-600">Detail film</p>
                    <h2 id="modalTitle" class="text-3xl font-extrabold text-slate-900">Dune: Part Two</h2>
                    <p id="modalMeta" class="mt-2 text-xs text-slate-500">2024 <span class="px-1 text-slate-300">•</span> Sci-Fi <span class="px-1 text-slate-300">•</span> 2j 46m <span class="px-1 text-slate-300">•</span> <span class="text-amber-500">★ 4.8</span></p>
                    <h3 class="mt-6 text-sm font-bold text-slate-900">Aktor</h3>
                    <p id="modalActors" class="mt-1 text-sm leading-6 text-slate-600">Timothée Chalamet, Zendaya, Rebecca Ferguson, Javier Bardem</p>
                    <h3 class="mt-5 text-sm font-bold text-slate-900">Sinopsis</h3>
                    <p id="modalSynopsis" class="mt-1 text-sm leading-6 text-slate-600">Paul Atreides bersatu dengan Chani dan suku Fremen dalam perjalanan penuh takdir untuk membalas kehancuran keluarganya dan mencegah masa depan yang mengerikan.</p>
                    <h3 class="mt-5 text-sm font-bold text-slate-900">Genre</h3>
                    <p id="modalGenre" class="mt-1 text-sm leading-6 text-slate-600">Sci-Fi, Petualangan, Drama</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        const modalDetailFilm = document.getElementById("movieModal");
        const tombolTutupModal = document.getElementById("closeModal");
        const searchInput = document.getElementById("searchInput");
        const searchResultsDropdown = document.getElementById("searchResultsDropdown");

        // Simpan markup awal grid trending agar bisa dikembalikan kapan saja
        const gridContainerEl = document.getElementById("gridItemsContainer");
        const kontenAwalGrid = gridContainerEl ? gridContainerEl.innerHTML : "";

        // Peta poster tematik sesuai genre utama
        const posterPerGenre = {
            'Action': 'https://images.unsplash.com/photo-1534447677768-be436bb09401?auto=format&fit=crop&w=600&q=85',
            'Adventure': 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?auto=format&fit=crop&w=600&q=85',
            'Animation': 'https://images.unsplash.com/photo-1534796636912-3b95b3ab5986?auto=format&fit=crop&w=600&q=85',
            'Biography': 'https://images.unsplash.com/photo-1455390582262-044cdead277a?auto=format&fit=crop&w=600&q=85',
            'Comedy': 'https://images.unsplash.com/photo-1514306191717-452ec28c7814?auto=format&fit=crop&w=600&q=85',
            'Crime': 'https://images.unsplash.com/photo-1453873531674-2151bcd01707?auto=format&fit=crop&w=600&q=85',
            'Documentary': 'https://images.unsplash.com/photo-1492691527719-9d1e07e534b4?auto=format&fit=crop&w=600&q=85',
            'Drama': 'https://images.unsplash.com/photo-1485846234645-a62644f84728?auto=format&fit=crop&w=600&q=85',
            'Family': 'https://images.unsplash.com/photo-1476900543704-4312b78632f8?auto=format&fit=crop&w=600&q=85',
            'Fantasy': 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?auto=format&fit=crop&w=600&q=85',
            'History': 'https://images.unsplash.com/photo-1461360370896-922624d12aa1?auto=format&fit=crop&w=600&q=85',
            'Horror': 'https://images.unsplash.com/photo-1509281373149-e957c6296406?auto=format&fit=crop&w=600&q=85',
            'Music': 'https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?auto=format&fit=crop&w=600&q=85',
            'Mystery': 'https://images.unsplash.com/photo-1478720568477-152d9b164e26?auto=format&fit=crop&w=600&q=85',
            'Romance': 'https://images.unsplash.com/photo-1518895949257-7621c3c786d7?auto=format&fit=crop&w=600&q=85',
            'Sci-Fi': 'https://images.unsplash.com/photo-1446776811953-b23d57bd21aa?auto=format&fit=crop&w=600&q=85',
            'Sport': 'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?auto=format&fit=crop&w=600&q=85',
            'Thriller': 'https://images.unsplash.com/photo-1484589065579-248aad0d8b13?auto=format&fit=crop&w=600&q=85',
            'War': 'https://images.unsplash.com/photo-1543083477-4f785aeafaa9?auto=format&fit=crop&w=600&q=85',
            'Western': 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=600&q=85',
        };

        function dapatkanPoster(genre) {
            return posterPerGenre[genre] || 'https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?auto=format&fit=crop&w=600&q=85';
        }

        // ==========================================
        // FITUR LIVE SEARCH PADA NAVBAR
        // ==========================================
        let debounceTimer;
        searchInput.addEventListener("input", () => {
            clearTimeout(debounceTimer);
            const q = searchInput.value.trim();
            if (q.length < 2) {
                searchResultsDropdown.classList.add("hidden");
                searchResultsDropdown.innerHTML = "";
                return;
            }
            debounceTimer = setTimeout(async () => {
                try {
                    const res = await fetch(`/api/get_movies.php?q=${encodeURIComponent(q)}&limit=6`);
                    if (!res.ok) return;
                    const data = await res.json();
                    tampilkanDropdownHasil(data.items || []);
                } catch (err) {
                    console.error("Gagal memuat hasil pencarian:", err);
                }
            }, 250);
        });

        function tampilkanDropdownHasil(items) {
            searchResultsDropdown.innerHTML = "";
            if (!items || items.length === 0) {
                searchResultsDropdown.innerHTML = `<p class="p-3 text-center text-xs text-slate-500">Tidak ada film yang cocok.</p>`;
                searchResultsDropdown.classList.remove("hidden");
                return;
            }
            items.forEach((film) => {
                const itemBtn = document.createElement("button");
                itemBtn.type = "button";
                itemBtn.className = "flex w-full items-center gap-3 rounded-xl p-2 text-left transition hover:bg-cyan-50";
                const poster = dapatkanPoster(film.primary_genre);
                itemBtn.innerHTML = `
                    <img src="${poster}" alt="${film.title}" class="h-12 w-8 shrink-0 rounded-md object-cover">
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-bold text-slate-900">${film.title}</p>
                        <p class="text-xs text-slate-500">${film.release_year || "-"} • ${film.primary_genre || "-"}</p>
                    </div>
                    <span class="shrink-0 text-xs font-bold text-amber-500">★ ${film.imdb_score || "0.0"}</span>
                `;
                itemBtn.addEventListener("click", () => {
                    bukaDetailFilmDenganId(film.id, film);
                    searchResultsDropdown.classList.add("hidden");
                });
                searchResultsDropdown.appendChild(itemBtn);
            });
            searchResultsDropdown.classList.remove("hidden");
        }

        // Tutup dropdown jika klik di luar
        document.addEventListener("click", (e) => {
            if (!searchInput.contains(e.target) && !searchResultsDropdown.contains(e.target)) {
                searchResultsDropdown.classList.add("hidden");
            }
        });

        // ==========================================
        // SUBMIT PENCARIAN (UPDATE GRID FILM)
        // ==========================================
        async function tanganiSubmitPencarian(event) {
            if (event) event.preventDefault();
            searchResultsDropdown.classList.add("hidden");
            const q = searchInput.value.trim();
            if (q.length < 2) return false;

            const gridContainer = document.getElementById("movieGrid");
            const gridHeaderCategory = document.getElementById("gridHeaderCategory");
            const gridHeaderTitle = document.getElementById("gridHeaderTitle");
            const gridHeaderSub = document.getElementById("gridHeaderSub");

            if (gridHeaderCategory) gridHeaderCategory.textContent = "Hasil Pencarian";
            if (gridHeaderTitle) gridHeaderTitle.textContent = `Menampilkan: "${q}"`;
            if (gridHeaderSub) {
                gridHeaderSub.innerHTML = `<button type="button" onclick="muatKembaliTrending()" class="font-bold text-indigo-600 transition hover:underline">← Kembali ke Trending</button>`;
            }
            if (gridContainerEl) {
                gridContainerEl.innerHTML = `<div class="col-span-full py-16 text-center text-sm font-semibold text-slate-500">Sedang mencari film di database...</div>`;
            }

            gridContainer.scrollIntoView({ behavior: "smooth" });

            try {
                const res = await fetch(`/api/get_movies.php?q=${encodeURIComponent(q)}&limit=15`);
                const data = await res.json();
                const items = data.items || [];

                if (items.length === 0) {
                    gridContainerEl.innerHTML = `
                        <div class="col-span-full py-16 text-center">
                            <p class="text-base font-bold text-slate-800">Tidak ada film yang cocok dengan "${q}".</p>
                            <p class="mt-1 text-xs text-slate-500">Coba gunakan kata kunci judul film lainnya.</p>
                            <button type="button" onclick="muatKembaliTrending()" class="mt-4 rounded-full bg-slate-900 px-5 py-2 text-xs font-bold text-white transition hover:bg-slate-800">
                                Kembali ke Trending
                            </button>
                        </div>
                    `;
                    return false;
                }

                gridContainerEl.innerHTML = "";
                items.forEach((film) => {
                    const poster = dapatkanPoster(film.primary_genre);
                    const card = document.createElement("article");
                    card.className = "kartu-film cursor-pointer rounded-2xl border border-slate-200/90 bg-white p-3 shadow-md shadow-slate-200/60 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-cyan-100/50";
                    card.innerHTML = `
                        <div class="relative overflow-hidden rounded-xl">
                            <img src="${poster}" alt="${film.title}" class="aspect-[2/3] w-full object-cover">
                            <span class="absolute right-2 top-2 rounded-full bg-slate-900/80 px-2.5 py-1 text-xs font-bold text-cyan-300 shadow-sm backdrop-blur-md">★ ${film.imdb_score || "0.0"}</span>
                        </div>
                        <h3 class="mt-3 truncate text-sm font-bold text-slate-900">${film.title}</h3>
                        <p class="mt-1 text-xs text-slate-500">${film.release_year || "-"} • ${film.primary_genre || "-"}</p>
                        <span class="mt-2 inline-flex rounded-full border border-cyan-200/60 bg-cyan-50 px-2.5 py-0.5 text-[10px] font-semibold text-cyan-700">${film.primary_genre || "Film"}</span>
                    `;
                    card.addEventListener("click", () => bukaDetailFilmDenganId(film.id, film));
                    gridContainerEl.appendChild(card);
                });
            } catch (err) {
                console.error("Gagal memuat hasil pencarian:", err);
                gridContainerEl.innerHTML = `<div class="col-span-full py-16 text-center text-sm font-semibold text-rose-600">Terjadi gangguan saat mengambil data film.</div>`;
            }
            return false;
        }

        // Fungsi mengembalikan grid ke daftar trending semula
        function muatKembaliTrending() {
            document.getElementById("gridHeaderCategory").textContent = "Pilihan populer";
            document.getElementById("gridHeaderTitle").textContent = "Trending sekarang";
            document.getElementById("gridHeaderSub").innerHTML = `<span class="hidden sm:inline">Dikurasi dari Database CineMatch</span>`;
            if (gridContainerEl && kontenAwalGrid) {
                gridContainerEl.innerHTML = kontenAwalGrid;
                // Pasang kembali event listener klik kartu
                gridContainerEl.querySelectorAll(".kartu-film").forEach((kartu) => {
                    kartu.addEventListener("click", bukaDetailFilm);
                });
            }
            searchInput.value = "";
        }

        // ==========================================
        // DETAIL MODAL FILM
        // ==========================================
        function bukaDetailFilm(event) {
            const kartu = event.currentTarget;
            if (kartu && kartu.dataset.title) {
                document.getElementById("modalTitle").textContent = kartu.dataset.title;
                document.getElementById("modalMeta").innerHTML = `${kartu.dataset.year || ""} <span class="px-1 text-slate-300">•</span> ${kartu.dataset.genre || ""} <span class="px-1 text-slate-300">•</span> ${kartu.dataset.duration || ""} <span class="px-1 text-slate-300">•</span> <span class="text-amber-500">★ ${kartu.dataset.rating || ""}</span>`;
                document.getElementById("modalActors").textContent = kartu.dataset.actors || "Tidak tersedia";
                document.getElementById("modalSynopsis").textContent = kartu.dataset.synopsis || "Sinopsis belum tersedia.";
                document.getElementById("modalGenre").textContent = kartu.dataset.genre || "-";
                if (kartu.dataset.poster) {
                    const posterEl = document.getElementById("modalPoster");
                    posterEl.src = kartu.dataset.poster;
                    posterEl.alt = `Poster ${kartu.dataset.title}`;
                }
            }
            modalDetailFilm.classList.remove("hidden");
            document.body.style.overflow = "hidden";
        }

        async function bukaDetailFilmDenganId(id, fallbackFilm) {
            // Tampilkan modal langsung dengan data cepat
            if (fallbackFilm) {
                document.getElementById("modalTitle").textContent = fallbackFilm.title;
                document.getElementById("modalMeta").innerHTML = `${fallbackFilm.release_year || "-"} <span class="px-1 text-slate-300">•</span> ${fallbackFilm.primary_genre || "-"} <span class="px-1 text-slate-300">•</span> <span class="text-amber-500">★ ${fallbackFilm.imdb_score || "-"}</span>`;
                document.getElementById("modalActors").textContent = "Memuat data aktor...";
                document.getElementById("modalSynopsis").textContent = fallbackFilm.plot_keywords ? `Tema cerita: ${fallbackFilm.plot_keywords.replace(/\|/g, ", ")}.` : "Memuat sinopsis...";
                document.getElementById("modalGenre").textContent = fallbackFilm.primary_genre || "-";
                const posterEl = document.getElementById("modalPoster");
                posterEl.src = dapatkanPoster(fallbackFilm.primary_genre);
                posterEl.alt = `Poster ${fallbackFilm.title}`;
            }
            modalDetailFilm.classList.remove("hidden");
            document.body.style.overflow = "hidden";

            // Ambil rincian lengkap dari database API
            try {
                const res = await fetch(`/api/get_movies.php?id=${id}`);
                if (res.ok) {
                    const detail = await res.json();
                    document.getElementById("modalTitle").textContent = detail.title;
                    const durasi = detail.duration ? `${detail.duration} menit` : "-";
                    const genreLengkap = detail.genres ? detail.genres.replace(/\|/g, ", ") : detail.primary_genre;
                    document.getElementById("modalMeta").innerHTML = `${detail.release_year || "-"} <span class="px-1 text-slate-300">•</span> ${genreLengkap} <span class="px-1 text-slate-300">•</span> ${durasi} <span class="px-1 text-slate-300">•</span> <span class="text-amber-500">★ ${detail.imdb_score || "-"}</span>`;

                    const actors = [detail.actor_1_name, detail.actor_2_name, detail.actor_3_name].filter(Boolean);
                    document.getElementById("modalActors").textContent = actors.length > 0 ? actors.join(", ") : "Informasi pemeran belum tersedia.";

                    let sinopsis = "";
                    if (detail.plot_keywords) {
                        sinopsis += `Tema cerita: ${detail.plot_keywords.replace(/\|/g, ", ")}. `;
                    }
                    if (detail.director_name) {
                        sinopsis += `Disutradarai oleh ${detail.director_name}. `;
                    }
                    if (detail.country) {
                        sinopsis += `Produksi ${detail.country}.`;
                    }
                    document.getElementById("modalSynopsis").textContent = sinopsis || "Sinopsis belum tersedia.";
                    document.getElementById("modalGenre").textContent = genreLengkap;
                }
            } catch (e) {
                console.warn("Gagal memuat rincian film tambahan:", e);
            }
        }

        function tutupDetailFilm() {
            modalDetailFilm.classList.add("hidden");
            document.body.style.overflow = "";
        }

        function tanganiKlikLatarModal(event) {
            if (event.target === modalDetailFilm) {
                tutupDetailFilm();
            }
        }

        function tanganiTombolEscape(event) {
            if (event.key === "Escape") {
                tutupDetailFilm();
            }
        }

        // Pasang event listener awal untuk kartu film
        document.querySelectorAll(".kartu-film").forEach((kartuFilm) => {
            kartuFilm.addEventListener("click", bukaDetailFilm);
        });

        tombolTutupModal.addEventListener("click", tutupDetailFilm);
        modalDetailFilm.addEventListener("click", tanganiKlikLatarModal);
        document.addEventListener("keydown", tanganiTombolEscape);

        // Jika halaman dibuka dengan parameter ?search=... jalankan pencarian langsung
        const parameterUrl = new URLSearchParams(window.location.search);
        const kueriCari = parameterUrl.get("search");
        if (kueriCari && kueriCari.trim() !== "") {
            searchInput.value = kueriCari.trim();
            tanganiSubmitPencarian(null);
        }
    </script>
</body>
</html>
