<?php // Membuka blok kode PHP
require_once __DIR__ . '/models/TabelKandidat.php'; // Memuat kelas Model TabelKandidat untuk modul kandidat dan opsi relasi lowongan
require_once __DIR__ . '/views/ViewKandidat.php'; // Memuat kelas View pasif untuk modul kandidat
require_once __DIR__ . '/presenters/PresenterKandidat.php'; // Memuat kelas Presenter untuk modul kandidat
require_once __DIR__ . '/models/TabelLowongan.php'; // Memuat kelas Model TabelLowongan untuk modul lowongan
require_once __DIR__ . '/views/ViewLowongan.php'; // Memuat kelas View pasif untuk modul lowongan
require_once __DIR__ . '/presenters/PresenterLowongan.php'; // Memuat kelas Presenter untuk modul lowongan

$host = 'localhost'; // Menyimpan nama host MySQL Laragon dalam satu variabel terpusat
$dbName = 'mvp_kandidat_db'; // Menyimpan nama database aktif dalam satu variabel terpusat
$username = 'root'; // Menyimpan username MySQL bawaan Laragon
$password = ''; // Menyimpan password MySQL bawaan Laragon (kosong)

$modul = $_POST['modul'] ?? $_GET['modul'] ?? 'kandidat'; // Menentukan modul aktif dari POST, GET, atau default ke 'kandidat'

if ($modul === 'lowongan') { // Mengecek apakah permintaan ditujukan untuk modul lowongan
    $modelLowongan = new TabelLowongan($host, $dbName, $username, $password); // Membuat instance Model untuk tabel lowongan
    $modelKandidat = new TabelKandidat($host, $dbName, $username, $password); // Membuat instance Model kandidat untuk kebutuhan dropdown relasi
    $viewLowongan = new ViewLowongan(); // Membuat instance View pasif untuk modul lowongan
    $presenterLowongan = new PresenterLowongan($modelLowongan, $modelKandidat, $viewLowongan); // Membuat instance PresenterLowongan dengan menyuntikkan tiga kontrak

    if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Mengecek apakah permintaan ke modul lowongan menggunakan metode POST
        $action = $_POST['action'] ?? ''; // Mengambil jenis aksi POST ('tambah', 'ubah', atau 'hapus')

        if ($action === 'tambah') { // Mengecek apakah aksi POST adalah penambahan lowongan baru
            $judulLowongan = trim($_POST['judulLowongan'] ?? ''); // Mengambil dan membersihkan spasi pada input judul lowongan
            $perusahaan = trim($_POST['perusahaan'] ?? ''); // Mengambil dan membersihkan spasi pada input nama perusahaan
            $picRekruter = trim($_POST['picRekruter'] ?? ''); // Mengambil dan membersihkan spasi pada input nama PIC rekruter
            $gajiJuta = (int)($_POST['gajiJuta'] ?? 0); // Mengambil input estimasi gaji dan mengonversinya ke integer
            $status = trim($_POST['status'] ?? 'Dibuka'); // Mengambil pilihan status lowongan dengan bawaan 'Dibuka'
            $rawIdKandidat = trim($_POST['idKandidat'] ?? ''); // Mengambil nilai mentah dropdown kandidat terpilih
            $idKandidat = ($rawIdKandidat !== '' && (int)$rawIdKandidat > 0) ? (int)$rawIdKandidat : null; // Mengubah string kosong menjadi null atau angka ID valid menjadi integer
            $presenterLowongan->tambahLowongan($judulLowongan, $perusahaan, $picRekruter, $gajiJuta, $status, $idKandidat); // Meneruskan data ke PresenterLowongan untuk disimpan ke database
        } elseif ($action === 'ubah') { // Mengecek apakah aksi POST adalah pembaruan data lowongan
            $id = (int)($_POST['id'] ?? 0); // Mengambil ID lowongan target dan mengonversinya ke integer
            $judulLowongan = trim($_POST['judulLowongan'] ?? ''); // Mengambil dan membersihkan spasi pada input judul lowongan baru
            $perusahaan = trim($_POST['perusahaan'] ?? ''); // Mengambil dan membersihkan spasi pada input perusahaan baru
            $picRekruter = trim($_POST['picRekruter'] ?? ''); // Mengambil dan membersihkan spasi pada input PIC rekruter baru
            $gajiJuta = (int)($_POST['gajiJuta'] ?? 0); // Mengambil input gaji baru sebagai integer
            $status = trim($_POST['status'] ?? 'Dibuka'); // Mengambil pilihan status lowongan baru
            $rawIdKandidat = trim($_POST['idKandidat'] ?? ''); // Mengambil nilai mentah dropdown kandidat baru
            $idKandidat = ($rawIdKandidat !== '' && (int)$rawIdKandidat > 0) ? (int)$rawIdKandidat : null; // Mengubah string kosong menjadi null atau angka ID valid menjadi integer
            $presenterLowongan->ubahLowongan($id, $judulLowongan, $perusahaan, $picRekruter, $gajiJuta, $status, $idKandidat); // Meneruskan data perubahan ke PresenterLowongan
        } elseif ($action === 'hapus') { // Mengecek apakah aksi POST adalah penghapusan lowongan
            $id = (int)($_POST['id'] ?? 0); // Mengambil ID lowongan yang akan dihapus sebagai integer
            $presenterLowongan->hapusLowongan($id); // Meneruskan ID ke PresenterLowongan untuk dihapus
        } // Menutup percabangan aksi POST modul lowongan

        header("Location: index.php?modul=lowongan"); // Mengarahkan ulang ke daftar lowongan jika nilai aksi tidak dikenali
        exit(); // Menghentikan eksekusi skrip setelah redirect
    } else { // Cabang permintaan GET untuk modul lowongan
        $screen = $_GET['screen'] ?? 'list'; // Mengambil parameter halaman tampilan (default 'list')
        if ($screen === 'add') { // Mengecek apakah pengguna membuka form tambah lowongan
            echo $presenterLowongan->tampilkanFormLowongan(); // Menampilkan HTML form tambah lowongan dari PresenterLowongan
        } elseif ($screen === 'edit' && isset($_GET['id'])) { // Mengecek apakah pengguna membuka form edit dengan parameter ID
            echo $presenterLowongan->tampilkanFormLowongan((int)$_GET['id']); // Menampilkan HTML form edit dengan data prefill berdasarkan ID
        } else { // Cabang bawaan untuk halaman daftar lowongan
            echo $presenterLowongan->tampilkanLowongan(); // Menampilkan HTML tabel daftar lowongan dari PresenterLowongan
        } // Menutup percabangan tampilan GET modul lowongan
    } // Menutup percabangan metode HTTP modul lowongan
} else { // Cabang default untuk modul kandidat
    $modelKandidat = new TabelKandidat($host, $dbName, $username, $password); // Membuat instance Model TabelKandidat
    $viewKandidat = new ViewKandidat(); // Membuat instance View pasif ViewKandidat
    $presenterKandidat = new PresenterKandidat($modelKandidat, $viewKandidat); // Membuat instance PresenterKandidat dengan injeksi Model dan View

    if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Mengecek apakah permintaan ke modul kandidat menggunakan metode POST
        $action = $_POST['action'] ?? ''; // Mengambil jenis aksi POST ('tambah', 'ubah', atau 'hapus')

        if ($action === 'tambah') { // Mengecek apakah aksi POST adalah penambahan kandidat baru
            $nama = trim($_POST['nama'] ?? ''); // Mengambil dan membersihkan spasi berlebih pada input nama
            $posisi = trim($_POST['posisi'] ?? ''); // Mengambil dan membersihkan spasi berlebih pada input posisi
            $domisili = trim($_POST['domisili'] ?? ''); // Mengambil dan membersihkan spasi berlebih pada input domisili
            $skorTes = (int)($_POST['skorTes'] ?? 0); // Mengambil input skor tes sebagai integer
            $pengalamanTahun = (int)($_POST['pengalamanTahun'] ?? 0); // Mengambil input pengalaman kerja sebagai integer
            $presenterKandidat->tambahKandidat($nama, $posisi, $domisili, $skorTes, $pengalamanTahun); // Meneruskan data ke PresenterKandidat untuk disimpan
        } elseif ($action === 'ubah') { // Mengecek apakah aksi POST adalah pembaruan data kandidat
            $id = (int)($_POST['id'] ?? 0); // Mengambil ID kandidat target sebagai integer
            $nama = trim($_POST['nama'] ?? ''); // Mengambil dan membersihkan spasi pada input nama baru
            $posisi = trim($_POST['posisi'] ?? ''); // Mengambil dan membersihkan spasi pada input posisi baru
            $domisili = trim($_POST['domisili'] ?? ''); // Mengambil dan membersihkan spasi pada input domisili baru
            $skorTes = (int)($_POST['skorTes'] ?? 0); // Mengambil input skor tes baru sebagai integer
            $pengalamanTahun = (int)($_POST['pengalamanTahun'] ?? 0); // Mengambil input pengalaman kerja baru sebagai integer
            $presenterKandidat->ubahKandidat($id, $nama, $posisi, $domisili, $skorTes, $pengalamanTahun); // Meneruskan perubahan ke PresenterKandidat
        } elseif ($action === 'hapus') { // Mengecek apakah aksi POST adalah penghapusan kandidat
            $id = (int)($_POST['id'] ?? 0); // Mengambil ID kandidat yang akan dihapus sebagai integer
            $presenterKandidat->hapusKandidat($id); // Meneruskan ID ke PresenterKandidat untuk dihapus
        } // Menutup percabangan aksi POST modul kandidat

        header("Location: index.php?modul=kandidat"); // Mengarahkan ulang ke daftar kandidat jika nilai aksi tidak dikenali
        exit(); // Menghentikan eksekusi skrip setelah redirect
    } else { // Cabang permintaan GET untuk modul kandidat
        $screen = $_GET['screen'] ?? 'list'; // Mengambil parameter tampilan GET (default 'list')
        if ($screen === 'add') { // Mengecek apakah pengguna membuka form tambah kandidat
            echo $presenterKandidat->tampilkanFormKandidat(); // Menampilkan HTML form tambah kandidat kosong
        } elseif ($screen === 'edit' && isset($_GET['id'])) { // Mengecek apakah pengguna membuka form edit kandidat disertai ID
            echo $presenterKandidat->tampilkanFormKandidat((int)$_GET['id']); // Menampilkan HTML form edit kandidat dengan data prefill
        } else { // Cabang bawaan untuk daftar kandidat
            echo $presenterKandidat->tampilkanKandidat(); // Menampilkan HTML tabel daftar kandidat
        } // Menutup percabangan tampilan GET modul kandidat
    } // Menutup percabangan metode HTTP modul kandidat
} // Menutup percabangan pemilihan modul aktif
// Akhir file index.php (tanpa tag penutup PHP agar aman dari whitespace sebelum header redirect)