<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CineMatch - Chat AI</title>
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

    <main class="relative z-10 mx-auto max-w-4xl px-5 py-8 sm:px-6">
        <section class="flex min-h-[min(720px,calc(100vh-7rem))] flex-col overflow-hidden rounded-3xl border border-slate-200/90 bg-white shadow-md shadow-slate-200/60 transition-all duration-300 hover:shadow-xl hover:shadow-cyan-100/50">
            <header class="flex items-center gap-4 border-b border-slate-100 px-5 py-5 sm:px-8">
                <span class="relative flex h-12 w-12 items-center justify-center rounded-full bg-[#0f172a] text-white">
                    <svg viewBox="0 0 64 64" class="h-8 w-8" fill="currentColor" aria-hidden="true">
                        <circle cx="18" cy="6" r="4"/><circle cx="46" cy="6" r="4"/><rect x="16" y="9" width="3" height="9"/><rect x="45" y="9" width="3" height="9"/><rect x="6" y="18" width="52" height="40" rx="8"/><rect x="16" y="30" width="10" height="8" rx="2" fill="#334155"/><rect x="38" y="30" width="10" height="8" rx="2" fill="#334155"/><rect x="18" y="46" width="28" height="5" rx="2" fill="#334155"/>
                    </svg>
                    <span class="absolute right-0 top-0 h-3.5 w-3.5 rounded-full border-2 border-[#0f172a] bg-[#ccff00]"></span>
                </span>
                <div>
                    <h1 class="font-bold text-slate-900">Asisten CineMatch</h1>
                    <p class="mt-1 flex items-center gap-2 text-xs text-slate-500">
                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                        Siap membantu menemukan film
                    </p>
                </div>
            </header>

            <!-- WADAH PESAN: Saat halaman dibuka, hanya pesan sambutan bot yang tampil. -->
            <div id="messagesContainer" class="flex-1 space-y-6 overflow-y-auto px-5 py-6 sm:px-8" aria-live="polite">
                <div class="flex justify-start">
                    <p class="max-w-md rounded-2xl rounded-tl-none border border-cyan-100 bg-cyan-50 px-5 py-3 text-sm font-medium leading-6 text-slate-700">
                        Halo! Mau nonton film seperti apa hari ini? Ceritakan mood atau genre favoritmu!
                    </p>
                </div>
            </div>

            <!-- FORM CHAT: chatForm mengirim prompt dari chatInput ke api/recommend.php. -->
            <form id="chatForm" class="mx-4 mb-4 flex items-center rounded-full border border-slate-200/90 bg-white shadow-md shadow-slate-200/60 transition-colors focus-within:border-cyan-300 focus-within:ring-2 focus-within:ring-cyan-100 sm:mx-6 sm:mb-6">
                <input
                    id="chatInput"
                    type="text"
                    placeholder="Ketik film yang ingin kamu tonton..."
                    class="min-w-0 flex-1 bg-transparent px-5 py-4 text-sm text-slate-900 placeholder-slate-400 focus:outline-none"
                >
                <button
                    type="submit"
                    aria-label="Kirim"
                    class="m-1.5 flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gradient-to-r from-cyan-500 to-teal-500 text-white shadow-md shadow-cyan-500/20 transition-all duration-300 hover:-translate-y-0.5 hover:from-cyan-600 hover:to-teal-600 hover:shadow-xl hover:shadow-cyan-100/50"
                >
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M2 21 23 12 2 3v7l15 2-15 2z"/>
                    </svg>
                </button>
            </form>
        </section>
    </main>

    <script>
        const formulirChat = document.getElementById("chatForm");
        const kolomPesan = document.getElementById("chatInput");
        const wadahPesan = document.getElementById("messagesContainer");
        const tombolKirim = formulirChat.querySelector('button[type="submit"]');

        /**
         * Menambahkan pesan pengguna ke sisi kanan percakapan.
         * Dipanggil saat formulir chat dikirim; teks dimasukkan sebagai textContent agar aman.
         */
        function appendUserMessage(teks) {
            const barisPesan = document.createElement("div");
            barisPesan.className = "flex justify-end";

            const gelembungPesan = document.createElement("p");
            gelembungPesan.className = "max-w-md rounded-2xl rounded-tr-none bg-[#06b6d4] px-5 py-3 text-sm font-medium leading-6 text-white shadow-md";
            gelembungPesan.textContent = teks;

            barisPesan.appendChild(gelembungPesan);
            wadahPesan.appendChild(barisPesan);
            gulirKePesanTerbaru();
        }

        /**
         * Menampilkan balasan bot dan kartu film bila respons menyediakan objek movie.
         * Dipanggil setelah api/recommend.php mengembalikan JSON sukses.
         */
        function appendBotResponse(data) {
            const kelompokBalasan = document.createElement("div");
            kelompokBalasan.className = "max-w-2xl space-y-4";

            if (typeof data.reply === "string" && data.reply.trim() !== "") {
                const gelembungBalasan = document.createElement("p");
                gelembungBalasan.className = "rounded-2xl rounded-tl-none border border-cyan-100 bg-cyan-50 px-5 py-3 text-sm leading-6 text-slate-700";
                gelembungBalasan.textContent = data.reply;
                kelompokBalasan.appendChild(gelembungBalasan);
            }

            if (data.movie && typeof data.movie === "object") {
                kelompokBalasan.appendChild(buatKartuRekomendasiFilm(data.movie));
            }

            if (kelompokBalasan.childElementCount > 0) {
                wadahPesan.appendChild(kelompokBalasan);
                gulirKePesanTerbaru();
            }
        }

        /**
         * Membuat kartu rekomendasi sesuai skema movie dari API.
         * Semua nilai teks memakai textContent; poster hanya menerima URL HTTP/HTTPS.
         */
        function buatKartuRekomendasiFilm(film) {
            const kartuFilm = document.createElement("article");
            kartuFilm.className = "kartu-film-rekomendasi rounded-3xl border border-cyan-400/30 bg-gradient-to-br from-[#0891b2] to-[#0e7490] p-6 text-white shadow-xl";

            const susunanFilm = document.createElement("div");
            susunanFilm.className = "flex flex-col gap-4 sm:flex-row sm:gap-5";

            const wadahPoster = document.createElement("div");
            wadahPoster.className = "relative w-full shrink-0 overflow-hidden rounded-2xl sm:w-32";

            const alamatPoster = typeof film.poster === "string" ? film.poster.trim() : "";
            if (alamatPoster !== "") {
                try {
                    const urlPoster = new URL(alamatPoster, window.location.origin);
                    if (urlPoster.protocol === "https:" || urlPoster.protocol === "http:") {
                        const gambarPoster = document.createElement("img");
                        gambarPoster.src = urlPoster.href;
                        gambarPoster.alt = `Poster ${teksAman(film.title, "film rekomendasi")}`;
                        gambarPoster.className = "aspect-[2/3] w-full object-cover";
                        gambarPoster.loading = "lazy";
                        wadahPoster.appendChild(gambarPoster);
                    }
                } catch (error) {
                    console.warn("Alamat poster dari API tidak valid.", error);
                }
            }

            if (!wadahPoster.hasChildNodes()) {
                const posterKosong = document.createElement("div");
                posterKosong.className = "flex aspect-[2/3] items-center justify-center bg-cyan-950/30 px-3 text-center text-xs text-cyan-100";
                posterKosong.textContent = "Poster tidak tersedia";
                wadahPoster.appendChild(posterKosong);
            }

            const nilaiRating = teksAman(film.rating);
            if (nilaiRating !== "") {
                const lencanaRating = document.createElement("span");
                lencanaRating.className = "absolute right-2 top-2 rounded-full bg-slate-900/80 px-2.5 py-1 text-xs font-bold text-cyan-300 shadow-sm backdrop-blur-md";
                lencanaRating.textContent = `★ ${nilaiRating}`;
                wadahPoster.appendChild(lencanaRating);
            }

            const rincianFilm = document.createElement("div");
            rincianFilm.className = "min-w-0";

            const labelRekomendasi = document.createElement("p");
            labelRekomendasi.className = "mb-2 text-[10px] font-bold uppercase tracking-[.16em] text-cyan-100";
            labelRekomendasi.textContent = "Rekomendasi AI";

            const judulFilm = document.createElement("h2");
            judulFilm.className = "text-xl font-extrabold text-white";
            judulFilm.textContent = teksAman(film.title, "Judul film tidak tersedia");

            const metadataFilm = document.createElement("p");
            metadataFilm.className = "mt-2 text-xs text-cyan-100";
            const metadata = [teksAman(film.year), teksAman(film.genre)].filter(Boolean);
            metadataFilm.textContent = metadata.join(" • ");

            const sinopsisFilm = document.createElement("p");
            sinopsisFilm.className = "mt-4 text-sm leading-6 text-cyan-100";
            sinopsisFilm.textContent = teksAman(film.synopsis, "Sinopsis belum tersedia.");

            rincianFilm.append(labelRekomendasi, judulFilm, metadataFilm, sinopsisFilm);
            susunanFilm.append(wadahPoster, rincianFilm);
            kartuFilm.appendChild(susunanFilm);

            return kartuFilm;
        }

        /**
         * Mengubah nilai API menjadi teks aman dan menyediakan nilai pengganti bila perlu.
         */
        function teksAman(nilai, nilaiPengganti = "") {
            if (typeof nilai === "string" || typeof nilai === "number") {
                return String(nilai).trim();
            }
            return nilaiPengganti;
        }

        /**
         * Menambahkan indikator bahwa bot sedang meminta rekomendasi.
         * Dipanggil sebelum fetch; elemennya dihapus setelah respons diterima.
         */
        function tampilkanIndikatorMemuat() {
            const barisPemuatan = document.createElement("div");
            barisPemuatan.className = "flex justify-start";
            barisPemuatan.dataset.indikatorMemuat = "true";

            const gelembungPemuatan = document.createElement("p");
            gelembungPemuatan.className = "rounded-2xl rounded-tl-none border border-cyan-100 bg-cyan-50 px-5 py-3 text-sm text-cyan-800";
            gelembungPemuatan.textContent = "Sedang mencari rekomendasi film...";

            barisPemuatan.appendChild(gelembungPemuatan);
            wadahPesan.appendChild(barisPemuatan);
            gulirKePesanTerbaru();
            return barisPemuatan;
        }

        /**
         * Menggulung area percakapan agar pesan terbaru tetap terlihat.
         */
        function gulirKePesanTerbaru() {
            wadahPesan.scrollTop = wadahPesan.scrollHeight;
        }

        /**
         * Mengirim prompt pengguna ke endpoint rekomendasi dan merender hasil atau pesan galat.
         * Dipicu oleh submit chatForm; indikator pemuatan dan tombol kirim diperbarui selama permintaan.
         */
        async function kirimPermintaanRekomendasi(event) {
            event.preventDefault();

            const teksPengguna = kolomPesan.value.trim();
            if (teksPengguna === "") {
                return;
            }

            appendUserMessage(teksPengguna);
            kolomPesan.value = "";
            kolomPesan.disabled = true;
            tombolKirim.disabled = true;

            const indikatorMemuat = tampilkanIndikatorMemuat();

            try {
                /*
                 * Untuk developer backend: api/recommend.php menerima JSON:
                 * { "prompt": "teks dari pengguna" }
                 * Respons sukses yang diharapkan:
                 * {
                 *   "status": "success",
                 *   "reply": "Ini dia rekomendasi film yang cocok untukmu!",
                 *   "movie": {
                 *     "title": "Dune: Part Two",
                 *     "year": "2024",
                 *     "genre": "Sci-Fi",
                 *     "rating": "4.8",
                 *     "synopsis": "Sinopsis film...",
                 *     "poster": "https://contoh.com/poster.jpg"
                 *   }
                 * }
                 */
                const respons = await fetch("/api/recommend.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ prompt: teksPengguna })
                });
                const data = await respons.json();

                if (!respons.ok || !data || data.status !== "success") {
                    const pesanApi = data && (data.error || data.message);
                    throw new Error(pesanApi || "Rekomendasi film tidak dapat dimuat.");
                }

                const punyaBalasan = typeof data.reply === "string" && data.reply.trim() !== "";
                const punyaDataFilm = data.movie && typeof data.movie === "object";
                if (!punyaBalasan && !punyaDataFilm) {
                    throw new Error("Respons API tidak berisi balasan atau data film.");
                }

                appendBotResponse(data);
            } catch (error) {
                console.error("Permintaan rekomendasi film gagal:", error);

                const pesanGalat = document.createElement("p");
                pesanGalat.className = "rounded-2xl rounded-tl-none border border-rose-200 bg-rose-50 px-5 py-3 text-sm leading-6 text-rose-700";
                pesanGalat.textContent = "Maaf, rekomendasi belum dapat dimuat. Silakan coba lagi.";
                wadahPesan.appendChild(pesanGalat);
                gulirKePesanTerbaru();
            } finally {
                indikatorMemuat.remove();
                kolomPesan.disabled = false;
                tombolKirim.disabled = false;
                kolomPesan.focus();
            }
        }

        // Formulir chat mengirim permintaan rekomendasi tanpa memuat ulang halaman.
        formulirChat.addEventListener("submit", kirimPermintaanRekomendasi);

        // Jika terdapat parameter pencarian di URL (?q=...), jalankan otomatis saat halaman dibuka
        const parameterUrl = new URLSearchParams(window.location.search);
        const kueriAwal = parameterUrl.get('q');
        if (kueriAwal && kueriAwal.trim() !== '') {
            kolomPesan.value = kueriAwal.trim();
            formulirChat.dispatchEvent(new Event('submit'));
        }
    </script>
</body>
</html>
