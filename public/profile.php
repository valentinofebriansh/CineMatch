<?php
/**
 * profile.php – Halaman profil CineMatch
 *
 * Perubahan:
 * 1. Data user (nama, foto) dimuat dari DB tabel `users`.
 * 2. Daftar watchlist dimuat dari DB tabel `watchlist`.
 * 3. Edit profil (nama + foto) disimpan via AJAX ke update_profile.php → DB.
 * 4. Tombol pop-up film di watchlist berubah menjadi "Hapus dari daftar"
 *    dan menghapus film tersebut dari DB + UI.
 */

require_once __DIR__ . '/db_connect.php';

$userId = 1;

// ── Ambil data user dari DB ──────────────────────────────────
$user = ['username' => 'Briang', 'photo_path' => null];

if ($pdo) {
    try {
        $stmtUser = $pdo->prepare('SELECT username, photo_path FROM users WHERE id = ?');
        $stmtUser->execute([$userId]);
        $row = $stmtUser->fetch();
        if ($row) $user = $row;
    } catch (Exception $e) { /* fallback ke default */ }
}

$userName    = htmlspecialchars($user['username'], ENT_QUOTES);
$userPhoto   = $user['photo_path'];
$userInitial = mb_strtoupper(mb_substr($user['username'], 0, 1));

// ── Ambil watchlist dari DB ──────────────────────────────────
$watchlistItems = [];

if ($pdo) {
    try {
        $stmtWl = $pdo->prepare(
            'SELECT * FROM watchlist WHERE user_id = ? ORDER BY added_at DESC'
        );
        $stmtWl->execute([$userId]);
        $watchlistItems = $stmtWl->fetchAll();
    } catch (Exception $e) { /* fallback ke kosong */ }
}

$watchlistCount = count($watchlistItems);
?>
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
        body { font-family: "Plus Jakarta Sans", sans-serif; }
    </style>
</head>
<body class="relative isolate min-h-screen overflow-x-hidden bg-[#f0f4f8] text-slate-900 antialiased">
    <!-- LATAR HIASAN -->
    <div aria-hidden="true" class="pointer-events-none fixed inset-0 z-0 overflow-hidden">
        <span class="absolute -left-32 -top-32 h-96 w-96 rounded-full bg-cyan-200/40 blur-3xl"></span>
        <span class="absolute -right-36 top-1/3 h-96 w-96 rounded-full bg-teal-200/30 blur-3xl"></span>
    </div>

    <!-- NAVBAR -->
    <nav class="sticky top-0 z-40 border-b border-slate-200/60 bg-white/80 backdrop-blur-md">
        <div class="mx-auto flex h-16 max-w-6xl items-center justify-between gap-5 px-5 sm:px-6">
            <a href="index.php" class="shrink-0 text-2xl font-extrabold tracking-tight text-slate-900">
                CineMatch<span class="text-indigo-600">.</span>
            </a>

            <form class="relative hidden w-72 sm:block" onsubmit="const q = this.querySelector('input').value.trim(); if(q) window.location.href='index.php?search=' + encodeURIComponent(q); return false;">
                <input type="search" placeholder="Cari film..." class="w-full rounded-full border border-slate-200/90 bg-white px-4 py-2.5 pr-10 text-sm text-slate-700 shadow-md shadow-slate-200/60 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-cyan-200">
                <button type="submit" aria-label="Cari" class="absolute right-4 top-3 text-slate-400 hover:text-cyan-600">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/>
                    </svg>
                </button>
            </form>

            <a href="profile.php" class="flex shrink-0 items-center gap-3 text-sm font-semibold text-slate-700 transition-colors hover:text-slate-900">
                <!-- Nama user dari DB -->
                <span id="navUserName"><?= $userName ?></span>
                <span id="navAvatarWrap" class="relative flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-slate-200 text-sm font-bold text-slate-700 overflow-hidden">
                    <?php if ($userPhoto): ?>
                        <img id="navAvatarImg" src="<?= htmlspecialchars($userPhoto) ?>" alt="Foto profil" class="h-full w-full object-cover rounded-full">
                    <?php else: ?>
                        <span id="navAvatarInitial"><?= $userInitial ?></span>
                    <?php endif; ?>
                    <span class="absolute bottom-0 right-0 h-3 w-3 rounded-full border-2 border-white bg-[#ccff00]"></span>
                </span>
            </a>
        </div>
    </nav>

    <!-- KONTEN PROFIL -->
    <main class="relative z-10 mx-auto max-w-6xl px-5 py-9 sm:px-6 sm:py-12">

        <!-- RINGKASAN PROFIL -->
        <section class="mb-10 flex flex-col gap-6 rounded-3xl border border-slate-200/90 bg-white p-6 shadow-md shadow-slate-200/60 transition-all duration-300 hover:shadow-xl hover:shadow-cyan-100/50 sm:flex-row sm:items-center sm:p-8">
            <!-- Avatar -->
            <div id="profileAvatarWrap" class="relative flex h-24 w-24 shrink-0 items-center justify-center rounded-full bg-slate-200 text-3xl font-extrabold text-slate-700 overflow-hidden">
                <?php if ($userPhoto): ?>
                    <img id="profileAvatarImg" src="<?= htmlspecialchars($userPhoto) ?>" alt="Foto profil" class="h-full w-full object-cover rounded-full">
                <?php else: ?>
                    <span id="profileAvatarInitial"><?= $userInitial ?></span>
                <?php endif; ?>
                <span class="absolute bottom-1 right-1 h-5 w-5 rounded-full border-2 border-white bg-[#ccff00]"></span>
            </div>

            <div class="flex-1">
                <div class="flex flex-wrap items-center gap-3">
                    <h1 id="profileName" class="text-2xl font-extrabold text-slate-900 sm:text-3xl"><?= $userName ?></h1>
                    <span class="rounded-full bg-cyan-100 px-3 py-1 text-xs font-semibold text-cyan-800">Cinephile</span>
                </div>
                <p class="mt-1 text-sm text-slate-500">Bergabung sejak November 2024 · 42 ulasan</p>
                <div class="mt-4 flex flex-wrap gap-4 text-xs font-semibold text-slate-600">
                    <span><strong class="font-bold text-slate-900">128</strong> Film ditonton</span>
                    <span><strong id="watchlistCountBadge" class="font-bold text-slate-900"><?= $watchlistCount ?></strong> List menonton</span>
                    <span><strong class="font-bold text-slate-900">34</strong> Mengikuti</span>
                </div>
            </div>

            <button id="editBtn" type="button" class="self-start rounded-full border border-slate-200 bg-white px-5 py-2 text-xs font-bold text-slate-700 shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:bg-slate-50 hover:shadow-md sm:self-center">
                Edit profil
            </button>
        </section>

        <!-- KOLEKSI LIST MENONTON (data dari DB) -->
        <section class="mb-12">
            <div class="mb-5 flex items-end justify-between">
                <div>
                    <p class="mb-1 text-xs font-bold uppercase tracking-[.16em] text-indigo-600">Koleksi pribadimu</p>
                    <h2 class="text-2xl font-extrabold tracking-tight text-slate-900">List menonton</h2>
                </div>
                <span id="watchlistCountLabel" class="text-xs font-medium text-slate-500"><?= $watchlistCount ?> film tersimpan</span>
            </div>

            <div id="watchlistGrid" class="grid grid-cols-2 gap-4 sm:grid-cols-3 sm:gap-5 md:grid-cols-4 lg:grid-cols-5">
                <?php if (empty($watchlistItems)): ?>
                    <p id="emptyWatchlist" class="col-span-full py-12 text-center text-sm text-slate-400">Belum ada film di daftar menontonmu.</p>
                <?php else: ?>
                    <?php foreach ($watchlistItems as $film): ?>
                        <?php
                            $genre1 = explode(',', $film['genre'])[0] ?? 'Film';
                            $genre1 = trim($genre1);
                        ?>
                        <article class="kartu-film cursor-pointer rounded-2xl border border-slate-200/90 bg-white p-3 shadow-md shadow-slate-200/60 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-cyan-100/50"
                            data-id="<?= (int)$film['movie_id'] ?>"
                            data-title="<?= htmlspecialchars($film['title'], ENT_QUOTES) ?>"
                            data-year="<?= (int)$film['year'] ?>"
                            data-duration="<?= htmlspecialchars($film['duration'] ?? '', ENT_QUOTES) ?>"
                            data-genre="<?= htmlspecialchars($film['genre'] ?? '', ENT_QUOTES) ?>"
                            data-rating="<?= htmlspecialchars($film['rating'] ?? '', ENT_QUOTES) ?>"
                            data-director="<?= htmlspecialchars($film['director'] ?? '', ENT_QUOTES) ?>"
                            data-actors="<?= htmlspecialchars($film['actors'] ?? '', ENT_QUOTES) ?>"
                            data-synopsis="<?= htmlspecialchars($film['synopsis'] ?? '', ENT_QUOTES) ?>"
                            data-poster="<?= htmlspecialchars($film['poster_url'] ?? '', ENT_QUOTES) ?>">
                            <div class="relative overflow-hidden rounded-xl">
                                <img src="<?= htmlspecialchars($film['poster_url'] ?? '', ENT_QUOTES) ?>"
                                     alt="<?= htmlspecialchars($film['title'], ENT_QUOTES) ?>"
                                     class="aspect-[2/3] w-full object-cover">
                                <span class="absolute right-2 top-2 rounded-full bg-slate-900/80 px-2.5 py-1 text-xs font-bold text-cyan-300 shadow-sm backdrop-blur-md">★ <?= htmlspecialchars($film['rating'] ?? '-', ENT_QUOTES) ?></span>
                            </div>
                            <h3 class="mt-3 truncate text-sm font-bold text-slate-900"><?= htmlspecialchars($film['title'], ENT_QUOTES) ?></h3>
                            <p class="mt-1 text-xs text-slate-500"><?= (int)$film['year'] ?> <span class="px-1 text-slate-300">•</span> <?= htmlspecialchars($film['duration'] ?? '-', ENT_QUOTES) ?></p>
                            <span class="mt-2 inline-flex rounded-full border border-cyan-200/60 bg-cyan-50 px-2.5 py-0.5 text-[10px] font-semibold text-cyan-700"><?= htmlspecialchars($genre1, ENT_QUOTES) ?></span>
                            <p class="mt-2 text-xs tracking-wide text-amber-400">★★★★★</p>
                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <!-- MODAL EDIT PROFIL -->
    <div id="editModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4 backdrop-blur-sm">
        <form id="editForm" enctype="multipart/form-data" class="w-full max-w-md space-y-4 rounded-3xl border border-slate-200 bg-white p-6 shadow-2xl sm:p-8">
            <div>
                <p class="mb-1 text-xs font-bold uppercase tracking-[.16em] text-indigo-600">Pengaturan akun</p>
                <h2 class="text-xl font-extrabold text-slate-900">Edit profil</h2>
            </div>

            <!-- Pratinjau foto profil di dalam modal -->
            <div class="flex items-center gap-4">
                <div id="previewAvatarWrap" class="relative flex h-16 w-16 shrink-0 items-center justify-center rounded-full bg-slate-200 text-xl font-extrabold text-slate-700 overflow-hidden">
                    <?php if ($userPhoto): ?>
                        <img id="previewAvatarImg" src="<?= htmlspecialchars($userPhoto) ?>" alt="Pratinjau foto" class="h-full w-full object-cover rounded-full">
                    <?php else: ?>
                        <span id="previewAvatarInitial"><?= $userInitial ?></span>
                    <?php endif; ?>
                </div>
                <p class="text-xs text-slate-500">Foto profil saat ini</p>
            </div>

            <label class="block text-sm font-medium text-slate-700" for="nameInput">
                Nama lengkap
                <input type="text" id="nameInput" name="name" value="<?= $userName ?>" class="mt-1 w-full rounded-2xl border border-slate-200 px-4 py-2.5 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-cyan-200">
            </label>

            <label class="block text-sm font-medium text-slate-700">
                Foto profil
                <input type="file" id="photoInput" name="photo" accept="image/*" class="mt-1 w-full text-xs text-slate-500 file:mr-3 file:rounded-full file:border-0 file:bg-slate-100 file:px-3 file:py-1.5 file:font-semibold file:text-slate-700">
            </label>

            <!-- Pesan status -->
            <p id="editStatus" class="hidden text-xs font-semibold"></p>

            <div class="flex justify-end gap-2 pt-2">
                <button type="button" id="cancelEdit" class="rounded-full px-4 py-2 text-sm font-semibold text-slate-500 transition-colors hover:text-slate-900">Batal</button>
                <button type="submit" id="saveBtn" class="rounded-full bg-[#ccff00] px-5 py-2 text-sm font-extrabold text-slate-900 transition-all duration-200 hover:-translate-y-0.5 hover:bg-[#b8e600] hover:shadow-md">Simpan</button>
            </div>
        </form>
    </div>

    <!-- MODAL DETAIL FILM (dengan tombol "Hapus dari daftar") -->
    <div id="movieModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4 backdrop-blur-sm sm:p-6">
        <div class="relative max-h-[90vh] w-full max-w-4xl overflow-y-auto rounded-3xl border border-slate-200 bg-white p-5 shadow-2xl sm:p-8">
            <button id="closeModal" aria-label="Tutup" class="absolute right-5 top-4 text-2xl text-slate-400 transition-colors hover:text-slate-900">&times;</button>
            <div class="flex flex-col gap-6 sm:flex-row sm:gap-8">
                <div class="w-full shrink-0 space-y-4 sm:w-52">
                    <img id="modalPoster" src="" alt="Poster film" class="aspect-[2/3] w-full rounded-2xl object-cover">
                    <!-- Tombol HAPUS DARI DAFTAR -->
                    <button id="removeFromWatchlistBtn" type="button"
                        class="w-full rounded-full bg-rose-500 px-3 py-2.5 text-xs font-extrabold text-white transition-all duration-200 hover:-translate-y-0.5 hover:bg-rose-600 hover:shadow-md">
                        🗑 Hapus dari daftar
                    </button>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-3 text-xs">
                        <p class="mb-2 font-bold text-slate-900">Tempat menonton</p>
                        <p class="flex items-center gap-2 text-slate-500"><span class="h-3 w-4 rounded-sm bg-indigo-500"></span>Bioskop &amp; Streaming</p>
                    </div>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="mb-2 text-xs font-bold uppercase tracking-[.16em] text-indigo-600">Detail film</p>
                    <h2 id="modalTitle" class="text-3xl font-extrabold text-slate-900"></h2>
                    <p id="modalMeta" class="mt-2 text-xs text-slate-500"></p>
                    <h3 class="mt-6 text-sm font-bold text-slate-900">Aktor</h3>
                    <p id="modalActors" class="mt-1 text-sm leading-6 text-slate-600"></p>
                    <h3 class="mt-5 text-sm font-bold text-slate-900">Sinopsis</h3>
                    <p id="modalSynopsis" class="mt-1 text-sm leading-6 text-slate-600"></p>
                    <h3 class="mt-5 text-sm font-bold text-slate-900">Genre</h3>
                    <p id="modalGenre" class="mt-1 text-sm leading-6 text-slate-600"></p>
                </div>
            </div>
        </div>
    </div>

    <script>
    // ============================================================
    // STATE
    // ============================================================
    const modalDetailFilm = document.getElementById("movieModal");
    const modalEditProfil = document.getElementById("editModal");
    let activeMovieId = null; // ID film yang sedang dibuka di modal

    // ============================================================
    // MODAL DETAIL FILM
    // ============================================================
    function bukaDetailFilm(event) {
        const kartu = event.currentTarget;
        if (!kartu || !kartu.dataset.title) return;

        activeMovieId = kartu.dataset.id;

        document.getElementById("modalTitle").textContent   = kartu.dataset.title;
        document.getElementById("modalMeta").innerHTML      =
            `${kartu.dataset.year || ""} <span class="px-1 text-slate-300">•</span>
             ${kartu.dataset.genre || ""} <span class="px-1 text-slate-300">•</span>
             ${kartu.dataset.duration || ""} <span class="px-1 text-slate-300">•</span>
             <span class="text-amber-500">★ ${kartu.dataset.rating || ""}</span>`;
        document.getElementById("modalActors").textContent  = kartu.dataset.actors   || "Tidak tersedia";
        document.getElementById("modalSynopsis").textContent= kartu.dataset.synopsis || "Sinopsis belum tersedia.";
        document.getElementById("modalGenre").textContent   = kartu.dataset.genre    || "-";

        const posterEl = document.getElementById("modalPoster");
        posterEl.src   = kartu.dataset.poster || "";
        posterEl.alt   = `Poster ${kartu.dataset.title}`;

        modalDetailFilm.classList.remove("hidden");
        document.body.style.overflow = "hidden";
    }

    function tutupDetailFilm() {
        modalDetailFilm.classList.add("hidden");
        document.body.style.overflow = "";
        activeMovieId = null;
    }

    // ============================================================
    // HAPUS DARI WATCHLIST
    // ============================================================
    document.getElementById("removeFromWatchlistBtn").addEventListener("click", async () => {
        if (!activeMovieId) return;

        const btn = document.getElementById("removeFromWatchlistBtn");
        btn.disabled   = true;
        btn.textContent = "Menghapus...";

        try {
            const res = await fetch("watchlist.php", {
                method : "POST",
                headers: { "Content-Type": "application/json" },
                body   : JSON.stringify({ action: "remove", movie_id: activeMovieId }),
            });
            const data = await res.json();

            if (data.success) {
                // Hapus kartu dari UI
                const kartu = document.querySelector(`.kartu-film[data-id="${activeMovieId}"]`);
                if (kartu) kartu.remove();

                // Perbarui counter
                const grid  = document.getElementById("watchlistGrid");
                const count = grid.querySelectorAll(".kartu-film").length;
                document.getElementById("watchlistCountBadge").textContent = count;
                document.getElementById("watchlistCountLabel").textContent  = `${count} film tersimpan`;

                // Tampilkan pesan kosong jika tidak ada lagi
                if (count === 0 && !document.getElementById("emptyWatchlist")) {
                    const msg    = document.createElement("p");
                    msg.id       = "emptyWatchlist";
                    msg.className= "col-span-full py-12 text-center text-sm text-slate-400";
                    msg.textContent = "Belum ada film di daftar menontonmu.";
                    grid.appendChild(msg);
                }

                tutupDetailFilm();
            } else {
                alert("Gagal menghapus film: " + (data.error || ""));
                btn.disabled    = false;
                btn.textContent = "🗑 Hapus dari daftar";
            }
        } catch (err) {
            console.error(err);
            alert("Terjadi kesalahan saat menghapus.");
            btn.disabled    = false;
            btn.textContent = "🗑 Hapus dari daftar";
        }
    });

    // ============================================================
    // MODAL EDIT PROFIL
    // ============================================================
    function bukaEditProfil() {
        modalEditProfil.classList.remove("hidden");
        document.body.style.overflow = "hidden";
    }

    function tutupEditProfil() {
        modalEditProfil.classList.add("hidden");
        document.body.style.overflow = "";
        document.getElementById("editStatus").classList.add("hidden");
    }

    // Pratinjau foto sebelum disimpan
    document.getElementById("photoInput").addEventListener("change", (e) => {
        const file = e.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = (ev) => {
            const wrap = document.getElementById("previewAvatarWrap");
            // Hapus initial, tampilkan gambar
            const initEl = document.getElementById("previewAvatarInitial");
            if (initEl) initEl.remove();
            let img = document.getElementById("previewAvatarImg");
            if (!img) {
                img      = document.createElement("img");
                img.id   = "previewAvatarImg";
                img.className = "h-full w-full object-cover rounded-full";
                img.alt  = "Pratinjau foto";
                wrap.prepend(img);
            }
            img.src = ev.target.result;
        };
        reader.readAsDataURL(file);
    });

    // Simpan profil via AJAX → update_profile.php
    document.getElementById("editForm").addEventListener("submit", async (e) => {
        e.preventDefault();

        const statusEl  = document.getElementById("editStatus");
        const saveBtn   = document.getElementById("saveBtn");
        saveBtn.disabled = true;
        saveBtn.textContent = "Menyimpan...";
        statusEl.classList.add("hidden");

        const formData = new FormData(e.target);

        try {
            const res  = await fetch("update_profile.php", { method: "POST", body: formData });
            const data = await res.json();

            if (data.success) {
                const namaBaruSafe = data.username;

                // ── Perbarui semua tampilan nama di halaman ──
                document.getElementById("profileName").textContent  = namaBaruSafe;
                document.getElementById("navUserName").textContent  = namaBaruSafe;
                document.getElementById("nameInput").value          = namaBaruSafe;

                const initial = namaBaruSafe.charAt(0).toUpperCase();

                // ── Perbarui avatar jika ada foto baru ───────
                if (data.photo_path) {
                    const photoUrl = data.photo_path;

                    // Profil besar
                    _updateAvatar("profileAvatarWrap",  "profileAvatarImg",  "profileAvatarInitial",  photoUrl, initial);
                    // Navbar
                    _updateAvatar("navAvatarWrap",       "navAvatarImg",       "navAvatarInitial",       photoUrl, initial);
                    // Pratinjau modal
                    _updateAvatar("previewAvatarWrap",   "previewAvatarImg",   "previewAvatarInitial",   photoUrl, initial);
                } else {
                    // Hanya perbarui initial jika tidak ada foto
                    ["profileAvatarInitial","navAvatarInitial","previewAvatarInitial"].forEach(id => {
                        const el = document.getElementById(id);
                        if (el) el.textContent = initial;
                    });
                }

                statusEl.textContent = "Profil berhasil diperbarui!";
                statusEl.className   = "text-xs font-semibold text-emerald-600";
                statusEl.classList.remove("hidden");

                setTimeout(tutupEditProfil, 1200);
            } else {
                statusEl.textContent = "Gagal: " + (data.error || "Terjadi kesalahan.");
                statusEl.className   = "text-xs font-semibold text-rose-600";
                statusEl.classList.remove("hidden");
            }
        } catch (err) {
            statusEl.textContent = "Terjadi kesalahan koneksi.";
            statusEl.className   = "text-xs font-semibold text-rose-600";
            statusEl.classList.remove("hidden");
        } finally {
            saveBtn.disabled    = false;
            saveBtn.textContent = "Simpan";
        }
    });

    /**
     * Helper: Perbarui elemen avatar (gambar atau initial).
     */
    function _updateAvatar(wrapId, imgId, initialId, photoUrl, initial) {
        const wrap = document.getElementById(wrapId);
        if (!wrap) return;

        const initEl = document.getElementById(initialId);
        if (initEl) initEl.remove();

        let img = document.getElementById(imgId);
        if (!img) {
            img           = document.createElement("img");
            img.id        = imgId;
            img.className = "h-full w-full object-cover rounded-full";
            img.alt       = "Foto profil";
            wrap.prepend(img);
        }
        img.src = photoUrl + "?t=" + Date.now(); // cache-bust
    }

    // ============================================================
    // EVENT LISTENERS
    // ============================================================
    document.querySelectorAll(".kartu-film").forEach((kartu) => {
        kartu.addEventListener("click", bukaDetailFilm);
    });

    document.getElementById("closeModal").addEventListener("click", tutupDetailFilm);
    modalDetailFilm.addEventListener("click", (e) => {
        if (e.target === modalDetailFilm) tutupDetailFilm();
    });

    document.getElementById("editBtn").addEventListener("click", bukaEditProfil);
    document.getElementById("cancelEdit").addEventListener("click", tutupEditProfil);
    modalEditProfil.addEventListener("click", (e) => {
        if (e.target === modalEditProfil) tutupEditProfil();
    });

    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape") { tutupDetailFilm(); tutupEditProfil(); }
    });
    </script>
</body>
</html>
