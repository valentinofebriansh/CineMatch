<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CineMatch - Profil</title>
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
    <div aria-hidden="true" class="pointer-events-none fixed inset-0 z-0 overflow-hidden">
        <span class="absolute -left-32 -top-32 h-96 w-96 rounded-full bg-cyan-200/40 blur-3xl"></span>
        <span class="absolute -right-36 top-1/3 h-96 w-96 rounded-full bg-teal-200/30 blur-3xl"></span>
    </div>
    <!-- BAGIAN NAVBAR: Menyediakan tautan beranda, pencarian, dan akses ke profil pengguna. -->
    <nav class="sticky top-0 z-40 border-b border-slate-200/60 bg-white/80 backdrop-blur-md">
        <div class="mx-auto flex h-16 max-w-6xl items-center justify-between gap-5 px-5 sm:px-6">
            <a href="index.php" class="shrink-0 text-2xl font-extrabold tracking-tight text-slate-900">
                CineMatch<span class="text-indigo-600">.</span>
            </a>

            <form class="relative hidden w-72 sm:block" onsubmit="return false">
                <input type="search" placeholder="Cari film..." class="w-full rounded-full border border-slate-200/90 bg-white px-4 py-2.5 pr-10 text-sm text-slate-700 shadow-md shadow-slate-200/60 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-cyan-200">
                <svg class="absolute right-4 top-3 h-4 w-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <circle cx="11" cy="11" r="7"/>
                    <path d="m21 21-4.3-4.3"/>
                </svg>
            </form>

            <a href="profile.php" class="flex shrink-0 items-center gap-3 text-sm font-semibold text-slate-700 transition-colors hover:text-slate-900">
                Briang
                <span class="relative flex h-10 w-10 items-center justify-center rounded-full bg-slate-200 text-sm font-bold text-slate-700">
                    B
                    <span class="absolute bottom-0 right-0 h-3 w-3 rounded-full border-2 border-white bg-[#ccff00]"></span>
                </span>
            </a>
        </div>
    </nav>

    <!-- KONTEN PROFIL: Menampilkan ringkasan akun dan koleksi film pengguna. -->
    <main class="relative z-10 mx-auto max-w-6xl px-5 py-9 sm:px-6 sm:py-12">
        <!-- RINGKASAN PROFIL: Tombol editBtn membuka formulir edit pada editModal. -->
        <section class="mb-10 flex flex-col gap-6 rounded-3xl border border-slate-200/90 bg-white p-6 shadow-md shadow-slate-200/60 transition-all duration-300 hover:shadow-xl hover:shadow-cyan-100/50 sm:flex-row sm:items-center sm:p-8">
            <div class="relative flex h-24 w-24 shrink-0 items-center justify-center rounded-full border-4 border-white bg-slate-100 shadow-sm ring-1 ring-slate-200 sm:h-32 sm:w-32">
                <svg class="h-14 w-14 text-slate-400 sm:h-20 sm:w-20" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <circle cx="12" cy="8" r="4"/>
                    <path d="M4 21c0-4.4 3.6-7 8-7s8 2.6 8 7z"/>
                </svg>
                <span class="absolute bottom-1 right-1 h-4 w-4 rounded-full border-2 border-white bg-[#ccff00]"></span>
            </div>
            <div class="flex-1">
                <p class="mb-2 text-xs font-bold uppercase tracking-[.16em] text-indigo-600">Profil cinephile</p>
                <h1 class="mb-4 text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl" id="profileName">Briang</h1>
                <button id="editBtn" type="button" class="rounded-full bg-gradient-to-r from-cyan-500 to-teal-500 px-6 py-2.5 text-sm font-bold text-white shadow-md shadow-cyan-500/20 transition-all duration-300 hover:-translate-y-0.5 hover:from-cyan-600 hover:to-teal-600 hover:shadow-xl hover:shadow-cyan-100/50">
                    Edit profil
                </button>
            </div>
            <div class="flex gap-7 border-t border-slate-100 pt-5 text-sm sm:border-l sm:border-t-0 sm:pl-8 sm:pt-0">
                <div><p class="text-xl font-extrabold text-slate-900">12</p><p class="mt-1 text-xs text-slate-500">Film disimpan</p></div>
                <div><p class="text-xl font-extrabold text-slate-900">5</p><p class="mt-1 text-xs text-slate-500">Genre favorit</p></div>
            </div>
        </section>

        <!-- GRID FILM: Setiap class kartu-film membuka modal detail film. -->
        <section class="mb-12">
            <div class="mb-5 flex items-end justify-between border-b border-slate-200/80 pb-4">
                <div>
                    <p class="mb-1 text-xs font-bold uppercase tracking-[.16em] text-indigo-600">Koleksi pribadimu</p>
                    <h2 class="text-2xl font-extrabold tracking-tight text-slate-900">List menonton</h2>
                </div>
                <span class="text-xs font-medium text-slate-500">12 film</span>
            </div>

            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 sm:gap-5 md:grid-cols-4 lg:grid-cols-5">
                <article class="kartu-film cursor-pointer rounded-2xl border border-slate-200/90 bg-white p-3 shadow-md shadow-slate-200/60 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-cyan-100/50">
                    <div class="relative overflow-hidden rounded-xl"><img src="https://images.unsplash.com/photo-1446776811953-b23d57bd21aa?auto=format&amp;fit=crop&amp;w=600&amp;q=85" alt="Pemandangan planet untuk Dune: Part Two" class="aspect-[2/3] w-full object-cover"><span class="absolute right-2 top-2 rounded-full bg-slate-900/80 px-2.5 py-1 text-xs font-bold text-cyan-300 shadow-sm backdrop-blur-md">★ 4.8</span></div>
                    <h3 class="mt-3 truncate text-sm font-bold text-slate-900">Dune: Part Two</h3><p class="mt-1 text-xs text-slate-500">2024 <span class="px-1 text-slate-300">•</span> 2j 46m</p><span class="mt-2 inline-flex rounded-full border border-cyan-200/60 bg-cyan-50 px-2.5 py-0.5 text-[10px] font-semibold text-cyan-700">Sci-Fi</span><p class="mt-2 text-xs tracking-wide text-amber-400">★★★★★</p>
                </article>
                <article class="kartu-film cursor-pointer rounded-2xl border border-slate-200/90 bg-white p-3 shadow-md shadow-slate-200/60 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-cyan-100/50">
                    <div class="relative overflow-hidden rounded-xl"><img src="https://images.unsplash.com/photo-1519608487953-e999c86e7455?auto=format&amp;fit=crop&amp;w=600&amp;q=85" alt="Langit malam untuk Oppenheimer" class="aspect-[2/3] w-full object-cover"><span class="absolute right-2 top-2 rounded-full bg-slate-900/80 px-2.5 py-1 text-xs font-bold text-cyan-300 shadow-sm backdrop-blur-md">★ 4.7</span></div>
                    <h3 class="mt-3 truncate text-sm font-bold text-slate-900">Oppenheimer</h3><p class="mt-1 text-xs text-slate-500">2023 <span class="px-1 text-slate-300">•</span> 3j 00m</p><span class="mt-2 inline-flex rounded-full border border-cyan-200/60 bg-cyan-50 px-2.5 py-0.5 text-[10px] font-semibold text-cyan-700">Drama</span><p class="mt-2 text-xs tracking-wide text-amber-400">★★★★★</p>
                </article>
                <article class="kartu-film cursor-pointer rounded-2xl border border-slate-200/90 bg-white p-3 shadow-md shadow-slate-200/60 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-cyan-100/50">
                    <div class="relative overflow-hidden rounded-xl"><img src="https://images.unsplash.com/photo-1484589065579-248aad0d8b13?auto=format&amp;fit=crop&amp;w=600&amp;q=85" alt="Kota malam untuk The Batman" class="aspect-[2/3] w-full object-cover"><span class="absolute right-2 top-2 rounded-full bg-slate-900/80 px-2.5 py-1 text-xs font-bold text-cyan-300 shadow-sm backdrop-blur-md">★ 4.6</span></div>
                    <h3 class="mt-3 truncate text-sm font-bold text-slate-900">The Batman</h3><p class="mt-1 text-xs text-slate-500">2022 <span class="px-1 text-slate-300">•</span> 2j 56m</p><span class="mt-2 inline-flex rounded-full border border-cyan-200/60 bg-cyan-50 px-2.5 py-0.5 text-[10px] font-semibold text-cyan-700">Aksi</span><p class="mt-2 text-xs tracking-wide text-amber-400">★★★★★</p>
                </article>
                <article class="kartu-film cursor-pointer rounded-2xl border border-slate-200/90 bg-white p-3 shadow-md shadow-slate-200/60 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-cyan-100/50">
                    <div class="relative overflow-hidden rounded-xl"><img src="https://images.unsplash.com/photo-1534796636912-3b95b3ab5986?auto=format&amp;fit=crop&amp;w=600&amp;q=85" alt="Galaksi berwarna untuk Spider-Man: Across the Spider-Verse" class="aspect-[2/3] w-full object-cover"><span class="absolute right-2 top-2 rounded-full bg-slate-900/80 px-2.5 py-1 text-xs font-bold text-cyan-300 shadow-sm backdrop-blur-md">★ 4.9</span></div>
                    <h3 class="mt-3 truncate text-sm font-bold text-slate-900">Spider-Man: Across the Spider-Verse</h3><p class="mt-1 text-xs text-slate-500">2023 <span class="px-1 text-slate-300">•</span> 2j 20m</p><span class="mt-2 inline-flex rounded-full border border-cyan-200/60 bg-cyan-50 px-2.5 py-0.5 text-[10px] font-semibold text-cyan-700">Animasi</span><p class="mt-2 text-xs tracking-wide text-amber-400">★★★★★</p>
                </article>
                <article class="kartu-film cursor-pointer rounded-2xl border border-slate-200/90 bg-white p-3 shadow-md shadow-slate-200/60 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-cyan-100/50">
                    <div class="relative overflow-hidden rounded-xl"><img src="https://images.unsplash.com/photo-1462331940025-496dfbfc7564?auto=format&amp;fit=crop&amp;w=600&amp;q=85" alt="Galaksi untuk Interstellar" class="aspect-[2/3] w-full object-cover"><span class="absolute right-2 top-2 rounded-full bg-slate-900/80 px-2.5 py-1 text-xs font-bold text-cyan-300 shadow-sm backdrop-blur-md">★ 4.9</span></div>
                    <h3 class="mt-3 truncate text-sm font-bold text-slate-900">Interstellar</h3><p class="mt-1 text-xs text-slate-500">2014 <span class="px-1 text-slate-300">•</span> 2j 49m</p><span class="mt-2 inline-flex rounded-full border border-cyan-200/60 bg-cyan-50 px-2.5 py-0.5 text-[10px] font-semibold text-cyan-700">Sci-Fi</span><p class="mt-2 text-xs tracking-wide text-amber-400">★★★★★</p>
                </article>
                <article class="kartu-film cursor-pointer rounded-2xl border border-slate-200/90 bg-white p-3 shadow-md shadow-slate-200/60 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-cyan-100/50">
                    <div class="relative overflow-hidden rounded-xl"><img src="https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&amp;fit=crop&amp;w=600&amp;q=85" alt="Bentang alam untuk Blade Runner 2049" class="aspect-[2/3] w-full object-cover"><span class="absolute right-2 top-2 rounded-full bg-slate-900/80 px-2.5 py-1 text-xs font-bold text-cyan-300 shadow-sm backdrop-blur-md">★ 4.6</span></div>
                    <h3 class="mt-3 truncate text-sm font-bold text-slate-900">Blade Runner 2049</h3><p class="mt-1 text-xs text-slate-500">2017 <span class="px-1 text-slate-300">•</span> 2j 44m</p><span class="mt-2 inline-flex rounded-full border border-cyan-200/60 bg-cyan-50 px-2.5 py-0.5 text-[10px] font-semibold text-cyan-700">Sci-Fi</span><p class="mt-2 text-xs tracking-wide text-amber-400">★★★★★</p>
                </article>
                <article class="kartu-film cursor-pointer rounded-2xl border border-slate-200/90 bg-white p-3 shadow-md shadow-slate-200/60 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-cyan-100/50">
                    <div class="relative overflow-hidden rounded-xl"><img src="https://images.unsplash.com/photo-1500534623283-312aade485b7?auto=format&amp;fit=crop&amp;w=600&amp;q=85" alt="Pemandangan alam untuk Arrival" class="aspect-[2/3] w-full object-cover"><span class="absolute right-2 top-2 rounded-full bg-slate-900/80 px-2.5 py-1 text-xs font-bold text-cyan-300 shadow-sm backdrop-blur-md">★ 4.5</span></div>
                    <h3 class="mt-3 truncate text-sm font-bold text-slate-900">Arrival</h3><p class="mt-1 text-xs text-slate-500">2016 <span class="px-1 text-slate-300">•</span> 1j 56m</p><span class="mt-2 inline-flex rounded-full border border-cyan-200/60 bg-cyan-50 px-2.5 py-0.5 text-[10px] font-semibold text-cyan-700">Sci-Fi</span><p class="mt-2 text-xs tracking-wide text-amber-400">★★★★★</p>
                </article>
                <article class="kartu-film cursor-pointer rounded-2xl border border-slate-200/90 bg-white p-3 shadow-md shadow-slate-200/60 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-cyan-100/50">
                    <div class="relative overflow-hidden rounded-xl"><img src="https://images.unsplash.com/photo-1478720568477-152d9b164e26?auto=format&amp;fit=crop&amp;w=600&amp;q=85" alt="Suasana bioskop untuk Parasite" class="aspect-[2/3] w-full object-cover"><span class="absolute right-2 top-2 rounded-full bg-slate-900/80 px-2.5 py-1 text-xs font-bold text-cyan-300 shadow-sm backdrop-blur-md">★ 4.8</span></div>
                    <h3 class="mt-3 truncate text-sm font-bold text-slate-900">Parasite</h3><p class="mt-1 text-xs text-slate-500">2019 <span class="px-1 text-slate-300">•</span> 2j 12m</p><span class="mt-2 inline-flex rounded-full border border-cyan-200/60 bg-cyan-50 px-2.5 py-0.5 text-[10px] font-semibold text-cyan-700">Thriller</span><p class="mt-2 text-xs tracking-wide text-amber-400">★★★★★</p>
                </article>
            </div>
        </section>
    </main>

    <!-- MODAL EDIT PROFIL: ID dipakai JavaScript untuk buka/tutup dan menyimpan nama pengguna. -->
    <div id="editModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4 backdrop-blur-sm">
        <form id="editForm" class="w-full max-w-md space-y-4 rounded-3xl border border-slate-200 bg-white p-6 shadow-2xl sm:p-8">
            <div>
                <p class="mb-1 text-xs font-bold uppercase tracking-[.16em] text-indigo-600">Pengaturan akun</p>
                <h2 class="text-xl font-extrabold text-slate-900">Edit profil</h2>
            </div>
            <label class="block text-sm font-medium text-slate-700" for="nameInput">
                Nama
                <input id="nameInput" type="text" value="Briang" class="mt-1 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-slate-900 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-100">
            </label>
            <label class="block text-sm font-medium text-slate-700">
                Foto profil
                <input type="file" accept="image/*" class="mt-1 w-full text-xs text-slate-500 file:mr-3 file:rounded-full file:border-0 file:bg-slate-100 file:px-3 file:py-1.5 file:font-semibold file:text-slate-700">
            </label>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" id="cancelEdit" class="rounded-full px-4 py-2 text-sm font-semibold text-slate-500 transition-colors hover:text-slate-900">Batal</button>
                <button type="submit" class="rounded-full bg-[#ccff00] px-5 py-2 text-sm font-extrabold text-slate-900 transition-all duration-200 hover:-translate-y-0.5 hover:bg-[#b8e600] hover:shadow-md">Simpan</button>
            </div>
        </form>
    </div>

    <!-- MODAL DETAIL FILM: Dibuka saat kartu-film dipilih dan ditutup melalui tombol atau latar. -->
    <div id="movieModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4 backdrop-blur-sm sm:p-6">
        <div class="relative max-h-[90vh] w-full max-w-4xl overflow-y-auto rounded-3xl border border-slate-200 bg-white p-5 shadow-2xl sm:p-8">
            <button id="closeModal" aria-label="Tutup" class="absolute right-5 top-4 text-2xl text-slate-400 transition-colors hover:text-slate-900">&times;</button>
            <div class="flex flex-col gap-6 sm:flex-row sm:gap-8">
                <div class="w-full shrink-0 space-y-4 sm:w-52">
                    <img src="https://images.unsplash.com/photo-1446776811953-b23d57bd21aa?auto=format&amp;fit=crop&amp;w=600&amp;q=85" alt="Pemandangan planet untuk Dune: Part Two" class="aspect-[2/3] w-full rounded-2xl object-cover">
                    <button type="button" class="w-full rounded-full bg-[#ccff00] px-3 py-2.5 text-xs font-extrabold text-slate-900 transition-all duration-200 hover:-translate-y-0.5 hover:bg-[#b8e600] hover:shadow-md">
                        <span class="text-amber-500">★</span> Tambahkan ke list menonton
                    </button>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-3 text-xs">
                        <p class="mb-2 font-bold text-slate-900">Tempat menonton</p>
                        <p class="flex items-center gap-2 text-slate-500"><span class="h-3 w-4 rounded-sm bg-indigo-500"></span>Tempat menonton</p>
                    </div>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="mb-2 text-xs font-bold uppercase tracking-[.16em] text-indigo-600">Detail film</p>
                    <h2 class="text-3xl font-extrabold text-slate-900">Dune: Part Two</h2>
                    <p class="mt-2 text-xs text-slate-500">2024 <span class="px-1 text-slate-300">•</span> Sci-Fi <span class="px-1 text-slate-300">•</span> 2j 46m <span class="px-1 text-slate-300">•</span> <span class="text-amber-500">★ 4.8</span></p>
                    <h3 class="mt-6 text-sm font-bold text-slate-900">Aktor</h3>
                    <p class="mt-1 text-sm leading-6 text-slate-600">Timothée Chalamet, Zendaya, Rebecca Ferguson, Javier Bardem</p>
                    <h3 class="mt-5 text-sm font-bold text-slate-900">Sinopsis</h3>
                    <p class="mt-1 text-sm leading-6 text-slate-600">Paul Atreides bersatu dengan Chani dan suku Fremen dalam perjalanan penuh takdir untuk membalas kehancuran keluarganya dan mencegah masa depan yang mengerikan.</p>
                    <h3 class="mt-5 text-sm font-bold text-slate-900">Genre</h3>
                    <p class="mt-1 text-sm leading-6 text-slate-600">Sci-Fi, Petualangan, Drama</p>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript berikut menghubungkan kartu film dan formulir edit ke modal masing-masing. -->
    <script>
        const modalDetailFilm = document.getElementById("movieModal");
        const modalEditProfil = document.getElementById("editModal");

        /**
         * Menampilkan modal detail ketika salah satu kartu film dipilih.
         * Menghapus class "hidden" dan mencegah halaman di belakang modal bergulir.
         */
        function bukaDetailFilm() {
            modalDetailFilm.classList.remove("hidden");
            document.body.style.overflow = "hidden";
        }

        /**
         * Menyembunyikan modal detail dan memulihkan gulir halaman.
         * Dipicu tombol tutup, klik latar modal, atau tombol Escape.
         */
        function tutupDetailFilm() {
            modalDetailFilm.classList.add("hidden");
            document.body.style.overflow = "";
        }

        /**
         * Menutup modal detail hanya ketika pengguna mengeklik latar di luar kontennya.
         */
        function tanganiKlikLatarDetail(event) {
            if (event.target === modalDetailFilm) {
                tutupDetailFilm();
            }
        }

        /**
         * Menutup modal detail film saat tombol Escape ditekan.
         */
        function tanganiTombolEscape(event) {
            if (event.key === "Escape") {
                tutupDetailFilm();
                tutupEditProfil();
            }
        }

        /**
         * Membuka formulir edit setelah tombol edit profil ditekan.
         * Menghapus class "hidden" dari modal edit profil.
         */
        function bukaEditProfil() {
            modalEditProfil.classList.remove("hidden");
        }

        /**
         * Menyembunyikan formulir edit dan mengembalikan gulir halaman.
         */
        function tutupEditProfil() {
            modalEditProfil.classList.add("hidden");
            document.body.style.overflow = "";
        }

        /**
         * Menutup modal edit saat pengguna mengeklik latar di luar formulir.
         */
        function tanganiKlikLatarEdit(event) {
            if (event.target === modalEditProfil) {
                tutupEditProfil();
            }
        }

        /**
         * Menyimpan nama profil di tampilan tanpa memuat ulang halaman.
         * Dipicu saat formulir edit profil dikirim.
         */
        function simpanPerubahanProfil(event) {
            event.preventDefault();

            const namaBaru = document.getElementById("nameInput").value.trim();
            if (namaBaru !== "") {
                document.getElementById("profileName").textContent = namaBaru;
            }

            tutupEditProfil();
        }

        // Setiap kartu film membuka modal detail film.
        document.querySelectorAll(".kartu-film").forEach((kartuFilm) => {
            kartuFilm.addEventListener("click", bukaDetailFilm);
        });

        // Hubungkan tombol dan latar modal detail dengan fungsi penutup.
        document.getElementById("closeModal").addEventListener("click", tutupDetailFilm);
        modalDetailFilm.addEventListener("click", tanganiKlikLatarDetail);

        // Hubungkan tombol edit, batal, latar modal, dan pengiriman formulir profil.
        document.getElementById("editBtn").addEventListener("click", bukaEditProfil);
        document.getElementById("cancelEdit").addEventListener("click", tutupEditProfil);
        modalEditProfil.addEventListener("click", tanganiKlikLatarEdit);
        document.getElementById("editForm").addEventListener("submit", simpanPerubahanProfil);

        // Tombol Escape menutup modal yang sedang aktif.
        document.addEventListener("keydown", tanganiTombolEscape);
    </script>
</body>
</html>
