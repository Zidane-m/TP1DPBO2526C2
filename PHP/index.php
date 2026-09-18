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

// Inisialisasi data sampel awal jika session masih kosong
if (!isset($_SESSION['daftar_film'])) {
    $_SESSION['daftar_film'] = [
        new Film(1, "Inception", "Sci-Fi", 148, "images/inception.jpg"),
        new Film(2, "The Dark Knight", "Action", 152, "images/dark_knight.jpg"),
        new Film(3, "Interstellar", "Sci-Fi", 169, "images/interstellar.jpg")
    ];
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
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-smooth: #f4f6f9;
            --card-bg: #ffffff;
            --card-header-bg: #2563eb;
            --card-header-text: #ffffff;
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --accent: #d97706;
            --danger: #dc2626;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border: #e2e8f0;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Outfit', sans-serif;
        }

        body {
            background: var(--bg-smooth);
            color: var(--text-main);
            min-height: 100vh;
            padding: 2rem 1rem;
        }

        .container {
            max-width: 1100px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .header h1 {
            font-size: 2.2rem;
            font-weight: 700;
            color: #1e3a8a;
            margin-bottom: 0.4rem;
        }

        .header p {
            color: var(--text-muted);
            font-size: 1rem;
        }

        .alert {
            padding: 0.85rem 1.25rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .alert-success { background: #dcfce7; border: 1px solid #86efac; color: #166534; }
        .alert-danger { background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; }
        .alert-warning { background: #fef3c7; border: 1px solid #fde047; color: #854d0e; }
        .alert-info { background: #dbeafe; border: 1px solid #93c5fd; color: #1e40af; }

        .layout-grid {
            display: grid;
            grid-template-columns: 340px 1fr;
            gap: 1.5rem;
        }

        @media (max-width: 850px) {
            .layout-grid {
                grid-template-columns: 1fr;
            }
        }

        .card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .card-header {
            background: var(--card-header-bg);
            color: var(--card-header-text);
            padding: 0.9rem 1.25rem;
            font-size: 1.1rem;
            font-weight: 600;
        }

        .card-body {
            padding: 1.25rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-main);
            margin-bottom: 0.35rem;
        }

        .form-control {
            width: 100%;
            padding: 0.65rem 0.85rem;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            color: var(--text-main);
            font-size: 0.9rem;
            transition: border-color 0.2s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        }

        .btn {
            display: inline-block;
            width: 100%;
            padding: 0.75rem 1rem;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s;
            text-align: center;
            text-decoration: none;
            font-size: 0.9rem;
        }

        .btn-primary {
            background: var(--primary);
            color: #ffffff;
        }

        .btn-primary:hover {
            background: var(--primary-hover);
        }

        .btn-warning {
            background: #f59e0b;
            color: #ffffff;
        }

        .btn-warning:hover {
            background: #d97706;
        }

        .btn-secondary {
            background: #e2e8f0;
            color: var(--text-main);
            margin-top: 0.5rem;
        }

        .btn-secondary:hover {
            background: #cbd5e1;
        }

        /* Search Section */
        .search-box {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1.25rem;
        }

        .search-box .form-control {
            margin-bottom: 0;
        }

        .search-box .btn {
            width: auto;
            white-space: nowrap;
        }

        /* Table Styling */
        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        th {
            background: #f1f5f9;
            padding: 0.75rem 1rem;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
            border-bottom: 1px solid var(--border);
        }

        td {
            padding: 0.85rem 1rem;
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
            font-size: 0.9rem;
        }

        tr:hover td {
            background: #f8fafc;
        }

        .badge {
            display: inline-block;
            padding: 0.2rem 0.5rem;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
            background: #dbeafe;
            color: #1e40af;
        }

        .img-thumb {
            width: 45px;
            height: 60px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid var(--border);
            background: #f1f5f9;
        }

        .img-placeholder {
            width: 45px;
            height: 60px;
            border-radius: 4px;
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.65rem;
            color: var(--text-muted);
            text-align: center;
            padding: 2px;
        }

        .action-btns {
            display: flex;
            gap: 0.35rem;
        }

        .btn-sm {
            padding: 0.35rem 0.6rem;
            font-size: 0.75rem;
            border-radius: 4px;
            width: auto;
        }

        .btn-danger {
            background: #fee2e2;
            color: #dc2626;
            border: 1px solid #fca5a5;
        }

        .btn-danger:hover {
            background: var(--danger);
            color: #ffffff;
        }

        .btn-edit {
            background: #fef3c7;
            color: #d97706;
            border: 1px solid #fde047;
        }

        .btn-edit:hover {
            background: #f59e0b;
            color: #ffffff;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>🎬 Manajemen Data Bioskop</h1>
        <p>Aplikasi Pengelolaan Data Film Bioskop berbasis PHP (Object-Oriented Programming)</p>
    </div>

    <?php if ($pesan): ?>
        <div class="alert alert-<?= $tipePesan ?>">
            <span><?= htmlspecialchars($pesan) ?></span>
        </div>
    <?php endif; ?>

    <div class="layout-grid">
        <!-- Form Section (Tambah / Edit) -->
        <div class="card">
            <div class="card-header">
                <?= $editFilm ? "✏️ Edit Data Film" : "➕ Tambah Data Film" ?>
            </div>
            <div class="card-body">
                <form action="index.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="<?= $editFilm ? 'update' : 'tambah' ?>">

                    <div class="form-group">
                        <label>ID Film</label>
                        <input type="number" name="id" class="form-control" placeholder="Contoh: 1" value="<?= $editFilm ? $editFilm->getId() : '' ?>" <?= $editFilm ? 'readonly' : 'required' ?>>
                        <?php if ($editFilm): ?>
                            <small style="color: var(--text-muted); font-size:0.75rem;">ID tidak dapat diubah saat mode edit.</small>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label>Judul Film</label>
                        <input type="text" name="judul" class="form-control" placeholder="Judul film..." value="<?= $editFilm ? htmlspecialchars($editFilm->getJudul()) : '' ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Genre</label>
                        <input type="text" name="genre" class="form-control" placeholder="Genre film (misal: Action, Sci-Fi)..." value="<?= $editFilm ? htmlspecialchars($editFilm->getGenre()) : '' ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Durasi (Menit)</label>
                        <input type="number" name="durasi" class="form-control" placeholder="Contoh: 120" value="<?= $editFilm ? $editFilm->getDurasi() : '' ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Path Gambar / Upload</label>
                        <input type="text" name="gambar_text" class="form-control" placeholder="images/foto.jpg" value="<?= $editFilm ? htmlspecialchars($editFilm->getGambar()) : '' ?>" style="margin-bottom:0.5rem;">
                        <input type="file" name="gambar_file" class="form-control" accept="image/*">
                    </div>

                    <button type="submit" class="btn <?= $editFilm ? 'btn-warning' : 'btn-primary' ?>">
                        <?= $editFilm ? 'Simpan Perubahan' : 'Tambah Film' ?>
                    </button>

                    <?php if ($editFilm): ?>
                        <a href="index.php" class="btn btn-secondary">Batal Edit</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>

        <!-- Table & Search Section -->
        <div class="card">
            <div class="card-header">
                📋 Daftar Film Bioskop
            </div>
            <div class="card-body">
                <!-- Search Bar -->
                <form action="index.php" method="GET" class="search-box">
                    <input type="text" name="search" class="form-control" placeholder="Cari berdasarkan ID, Judul, atau Genre..." value="<?= htmlspecialchars($searchKeyword) ?>">
                    <button type="submit" class="btn btn-primary">Cari</button>
                    <?php if ($searchKeyword !== ""): ?>
                        <a href="index.php" class="btn btn-secondary" style="margin-top:0;">Reset</a>
                    <?php endif; ?>
                </form>

                <div class="table-responsive">
                    <table>
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
                                    <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 2rem;">
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
                                        <td><span class="badge"><?= htmlspecialchars($film->getGenre()) ?></span></td>
                                        <td><?= $film->getDurasi() ?> menit</td>
                                        <td style="font-size: 0.8rem; color: var(--text-muted);"><?= htmlspecialchars($film->getGambar()) ?></td>
                                        <td>
                                            <div class="action-btns">
                                                <a href="index.php?edit=<?= $film->getId() ?>" class="btn btn-sm btn-edit">Edit</a>
                                                <a href="index.php?hapus=<?= $film->getId() ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus film ini?')">Hapus</a>
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

</body>
</html>
