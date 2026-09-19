<?php
require_once __DIR__ . '/Film.php';
session_start();

// Reset session jika terdapat objek incomplete class dari eksekusi sebelumnya
if (isset($_SESSION['daftar_film']) && is_array($_SESSION['daftar_film'])) {
    foreach ($_SESSION['daftar_film'] as $film) {
        if ($film instanceof __PHP_Incomplete_Class) {
            unset($_SESSION['daftar_film']);
            break;
        }
    }
}

$defaultFilms = [
    new Film(1, "Inception", "Sci-Fi", 148, "images/inception.webp"),
    new Film(2, "Agak Laen 2", "Comedy", 96, "images/AgakLaen.webp"),
    new Film(3, "Interstellar", "Sci-Fi", 169, "images/interstellar.webp"),
    new Film(4, "Avengers: Doomsday", "Action", 180, "images/Avengers_Doomsday.webp"),
    new Film(5, "Merah Putih One For All", "Action", 120, "images/merah_putih_ofa.webp")
];

// Fitur Reset Data Sampel
if (isset($_GET['reset_data'])) {
    $_SESSION['daftar_film'] = $defaultFilms;
    header('Location: index.php');
    exit;
}

// Inisialisasi data sampel awal jika session masih kosong
if (!isset($_SESSION['daftar_film'])) {
    $_SESSION['daftar_film'] = $defaultFilms;
} else {
    // Sinkronisasi data sampel default jika belum ada di session (misal terhapus / baru ditambahkan)
    $existingIds = array_map(function($f) { return $f->getId(); }, $_SESSION['daftar_film']);
    foreach ($defaultFilms as $defFilm) {
        if (!in_array($defFilm->getId(), $existingIds)) {
            $_SESSION['daftar_film'][] = $defFilm;
        }
    }
    // Otomatis memperbarui path pada session jika file .jpg/.png dikonversi ke .webp
    foreach ($_SESSION['daftar_film'] as $film) {
        $gambarSaatIni = $film->getGambar();
        if (!file_exists(__DIR__ . '/' . $gambarSaatIni)) {
            $webpPath = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $gambarSaatIni);
            if (file_exists(__DIR__ . '/' . $webpPath)) {
                $film->setGambar($webpPath);
            }
        }
    }
}

$pesan = "";
$tipePesan = "";
$editFilm = null;
$searchKeyword = isset($_GET['search']) ? trim($_GET['search']) : "";

// Direktori untuk menyimpan upload gambar lokal
$uploadDir = __DIR__ . '/images/';
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// 1. PROSES TAMBAH & UPDATE DATA
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'tambah') {
        $id = (int)($_POST['id'] ?? 0);
        $judul = trim($_POST['judul'] ?? '');
        $genre = trim($_POST['genre'] ?? '');
        $durasi = (int)($_POST['durasi'] ?? 0);
        $gambarPath = trim($_POST['gambar_text'] ?? '');

        // Cek jika ada file gambar di-upload
        if (isset($_FILES['gambar_file']) && $_FILES['gambar_file']['error'] === UPLOAD_ERR_OK) {
            $fileName = time() . '_' . basename($_FILES['gambar_file']['name']);
            $targetPath = $uploadDir . $fileName;
            if (move_uploaded_file($_FILES['gambar_file']['tmp_name'], $targetPath)) {
                $gambarPath = "images/" . $fileName;
            }
        }

        // Validasi ID Unik
        $idSudahAda = false;
        foreach ($_SESSION['daftar_film'] as $film) {
            if ($film->getId() === $id) {
                $idSudahAda = true;
            }
        }

        if ($id <= 0) {
            $pesan = "ID harus lebih dari 0!";
            $tipePesan = "danger";
        } elseif ($idSudahAda) {
            $pesan = "ID $id sudah digunakan!";
            $tipePesan = "danger";
        } elseif (empty($judul) || empty($genre) || $durasi <= 0 || empty($gambarPath)) {
            $pesan = "Semua field (Judul, Genre, Durasi > 0, Path Gambar) wajib diisi!";
            $tipePesan = "warning";
        } else {
            $_SESSION['daftar_film'][] = new Film($id, $judul, $genre, $durasi, $gambarPath);
            $pesan = "Data film berhasil ditambahkan!";
            $tipePesan = "success";
        }
    } elseif ($action === 'update') {
        $id = (int)($_POST['id'] ?? 0);
        $judul = trim($_POST['judul'] ?? '');
        $genre = trim($_POST['genre'] ?? '');
        $durasi = (int)($_POST['durasi'] ?? 0);
        $gambarPath = trim($_POST['gambar_text'] ?? '');

        if (isset($_FILES['gambar_file']) && $_FILES['gambar_file']['error'] === UPLOAD_ERR_OK) {
            $fileName = time() . '_' . basename($_FILES['gambar_file']['name']);
            $targetPath = $uploadDir . $fileName;
            if (move_uploaded_file($_FILES['gambar_file']['tmp_name'], $targetPath)) {
                $gambarPath = "images/" . $fileName;
            }
        }

        $posisi = -1;
        foreach ($_SESSION['daftar_film'] as $index => $film) {
            if ($film->getId() === $id) {
                $posisi = $index;
            }
        }

        if ($posisi === -1) {
            $pesan = "Data film tidak ditemukan!";
            $tipePesan = "danger";
        } elseif (empty($judul) || empty($genre) || $durasi <= 0 || empty($gambarPath)) {
            $pesan = "Semua field wajib diisi!";
            $tipePesan = "warning";
        } else {
            $_SESSION['daftar_film'][$posisi]->setJudul($judul);
            $_SESSION['daftar_film'][$posisi]->setGenre($genre);
            $_SESSION['daftar_film'][$posisi]->setDurasi($durasi);
            $_SESSION['daftar_film'][$posisi]->setGambar($gambarPath);
            $pesan = "Data film berhasil diperbarui!";
            $tipePesan = "success";
        }
    }
}

// 2. PROSES HAPUS DATA
if (isset($_GET['hapus'])) {
    $idHapus = (int)$_GET['hapus'];
    $posisiHapus = -1;
    foreach ($_SESSION['daftar_film'] as $index => $film) {
        if ($film->getId() === $idHapus) {
            $posisiHapus = $index;
        }
    }
    if ($posisiHapus !== -1) {
        array_splice($_SESSION['daftar_film'], $posisiHapus, 1);
        $pesan = "Data film dengan ID $idHapus berhasil dihapus!";
        $tipePesan = "info";
    }
}

// PROSES PRE-FILL FORM EDIT
if (isset($_GET['edit'])) {
    $idEdit = (int)$_GET['edit'];
    foreach ($_SESSION['daftar_film'] as $film) {
        if ($film->getId() === $idEdit) {
            $editFilm = $film;
        }
    }
}

// 3. PROSES CARI / FILTER DATA
$daftarTampil = [];
foreach ($_SESSION['daftar_film'] as $film) {
    if ($searchKeyword !== "") {
        if ((string)$film->getId() === $searchKeyword || stripos($film->getJudul(), $searchKeyword) !== false || stripos($film->getGenre(), $searchKeyword) !== false) {
            $daftarTampil[] = $film;
        }
    } else {
        $daftarTampil[] = $film;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Manajemen Data Bioskop</title>
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #ffffff;
            color: #1e293b;
        }

        .custom-header h1 {
            font-weight: 700;
            color: rgb(0, 120, 200);
        }

        .custom-card {
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .custom-card-header {
            background-color: rgb(0, 158, 255);
            color: #ffffff;
            font-weight: 600;
            padding: 0.9rem 1.25rem;
            font-size: 1.1rem;
        }

        .btn-custom-primary {
            background-color: rgb(0, 158, 255);
            color: #ffffff;
            border: none;
            font-weight: 600;
        }

        .btn-custom-primary:hover {
            background-color: rgb(0, 130, 215);
            color: #ffffff;
        }

        .table-custom {
            margin-bottom: 0;
        }

        .table-custom th {
            background-color: rgb(224, 242, 254);
            color: rgb(3, 105, 161);
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 2px solid rgb(186, 230, 253);
            padding: 0.75rem 1rem;
        }

        .table-custom td {
            padding: 0.85rem 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #e2e8f0;
            font-size: 0.9rem;
        }

        .table-custom tbody tr:nth-child(odd) {
            background-color: #ffffff;
        }

        .table-custom tbody tr:nth-child(even) {
            background-color: rgb(240, 249, 255);
        }

        .table-custom tbody tr:hover {
            background-color: rgb(224, 242, 254);
        }

        .badge-genre {
            background-color: rgb(224, 242, 254);
            color: rgb(3, 105, 161);
            font-weight: 600;
            font-size: 0.75rem;
            padding: 0.3em 0.6em;
            border-radius: 4px;
        }

        .img-thumb {
            width: 45px;
            height: 60px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid #e2e8f0;
        }

        .img-placeholder {
            width: 45px;
            height: 60px;
            border-radius: 4px;
            background-color: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.65rem;
            color: #64748b;
            text-align: center;
            padding: 2px;
        }
    </style>
</head>
<body>

<div class="container py-4">
    <div class="custom-header text-center mb-4">
        <h1>Manajemen Data Bioskop</h1>
        <p class="text-muted">Web Pengelolaan Data Film Bioskop berbasis PHP (Object-Oriented Programming)</p>
    </div>

    <?php if ($pesan): ?>
        <div class="alert alert-<?= $tipePesan ?> alert-dismissible fade show mb-4" role="alert">
            <?= htmlspecialchars($pesan) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <!-- Form Section (Tambah / Edit) -->
        <div class="col-lg-4">
            <div class="card custom-card">
                <div class="custom-card-header">
                    <?= $editFilm ? "✏️ Edit Data Film" : "➕ Tambah Data Film" ?>
                </div>
                <div class="card-body">
                    <form action="index.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="<?= $editFilm ? 'update' : 'tambah' ?>">

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">ID Film</label>
                            <input type="number" name="id" class="form-control" placeholder="Contoh: 1" value="<?= $editFilm ? $editFilm->getId() : '' ?>" <?= $editFilm ? 'readonly' : 'required' ?>>
                            <?php if ($editFilm): ?>
                                <div class="form-text text-muted small">ID tidak dapat diubah saat mode edit.</div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Judul Film</label>
                            <input type="text" name="judul" class="form-control" placeholder="Judul film..." value="<?= $editFilm ? htmlspecialchars($editFilm->getJudul()) : '' ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Genre</label>
                            <input type="text" name="genre" class="form-control" placeholder="Genre film (misal: Action, Sci-Fi)..." value="<?= $editFilm ? htmlspecialchars($editFilm->getGenre()) : '' ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Durasi (Menit)</label>
                            <input type="number" name="durasi" class="form-control" placeholder="Contoh: 120" value="<?= $editFilm ? $editFilm->getDurasi() : '' ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Path Gambar / Upload</label>
                            <input type="text" name="gambar_text" class="form-control mb-2" placeholder="images/foto.webp" value="<?= $editFilm ? htmlspecialchars($editFilm->getGambar()) : '' ?>">
                            <input type="file" name="gambar_file" class="form-control" accept="image/*">
                        </div>

                        <button type="submit" class="btn <?= $editFilm ? 'btn-warning text-white' : 'btn-custom-primary' ?> w-100 fw-semibold">
                            <?= $editFilm ? 'Simpan Perubahan' : 'Tambah Film' ?>
                        </button>

                        <?php if ($editFilm): ?>
                            <a href="index.php" class="btn btn-secondary w-100 fw-semibold mt-2">Batal Edit</a>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>

        <!-- Table & Search Section -->
        <div class="col-lg-8">
            <div class="card custom-card">
                <div class="custom-card-header">
                    📋 Daftar Film Bioskop
                </div>
                <div class="card-body">
                    <!-- Search Bar -->
                    <form action="index.php" method="GET" class="row g-2 mb-3">
                        <div class="col">
                            <input type="text" name="search" class="form-control" placeholder="Cari berdasarkan ID, Judul, atau Genre..." value="<?= htmlspecialchars($searchKeyword) ?>">
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-custom-primary">Cari</button>
                        </div>
                        <?php if ($searchKeyword !== ""): ?>
                            <div class="col-auto">
                                <a href="index.php" class="btn btn-secondary">Reset Search</a>
                            </div>
                        <?php endif; ?>
                        <div class="col-auto">
                            <a href="index.php?reset_data=1" class="btn btn-outline-secondary" onclick="return confirm('Apakah Anda yakin ingin mengembalikan daftar film ke data sampel awal?')">Reset Sampel</a>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-custom">
                            <thead>
                                <tr>
                                    <th>Cover</th>
                                    <th>ID</th>
                                    <th>Judul</th>
                                    <th>Genre</th>
                                    <th>Durasi</th>
                                    <th>Path Gambar</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($daftarTampil)): ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            Belum ada data film yang tersimpan.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($daftarTampil as $film): ?>
                                        <tr>
                                            <td>
                                                <?php if (file_exists(__DIR__ . '/' . $film->getGambar()) && !is_dir(__DIR__ . '/' . $film->getGambar())): ?>
                                                    <img src="<?= htmlspecialchars($film->getGambar()) ?>" alt="<?= htmlspecialchars($film->getJudul()) ?>" class="img-thumb">
                                                <?php else: ?>
                                                    <div class="img-placeholder"><?= htmlspecialchars($film->getGambar() ?: 'No Image') ?></div>
                                                <?php endif; ?>
                                            </td>
                                            <td><strong>#<?= $film->getId() ?></strong></td>
                                            <td><strong><?= htmlspecialchars($film->getJudul()) ?></strong></td>
                                            <td><span class="badge-genre"><?= htmlspecialchars($film->getGenre()) ?></span></td>
                                            <td><?= $film->getDurasi() ?> menit</td>
                                            <td class="small text-muted"><?= htmlspecialchars($film->getGambar()) ?></td>
                                            <td>
                                                <div class="d-flex gap-1">
                                                    <a href="index.php?edit=<?= $film->getId() ?>" class="btn btn-sm btn-warning text-white fw-semibold">Edit</a>
                                                    <a href="index.php?hapus=<?= $film->getId() ?>" class="btn btn-sm btn-danger fw-semibold" onclick="return confirm('Apakah Anda yakin ingin menghapus film ini?')">Hapus</a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap 5 JS Bundle CDN -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
