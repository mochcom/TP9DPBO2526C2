<?php // Membuka blok kode PHP
require_once __DIR__ . '/DB.php'; // Memuat file kelas induk DB untuk koneksi dan eksekusi query PDO
require_once __DIR__ . '/KontrakModel.php'; // Memuat interface KontrakModel sebagai kontrak method layer Model
require_once __DIR__ . '/Kandidat.php'; // Memuat kelas entitas Kandidat untuk representasi objek data

class TabelKandidat extends DB implements KontrakModel { // Mendefinisikan kelas TabelKandidat turunan DB yang mengimplementasikan KontrakModel

    public function __construct( // Mendefinisikan konstruktor kelas dengan parameter konfigurasi database
        string $host = "localhost", // Menentukan nama host server MySQL dengan nilai bawaan localhost
        string $db_name = "mvp_kandidat_db", // Menentukan nama database yang digunakan
        string $username = "root", // Menentukan username database MySQL
        string $password = "" // Menentukan password database MySQL (kosong untuk default Laragon)
    ) { // Membuka badan konstruktor
        parent::__construct($host, $db_name, $username, $password); // Memanggil konstruktor kelas induk DB untuk membuka koneksi PDO
    } // Menutup badan konstruktor

    private function mapRowToKandidat(array $row): Kandidat { // Membuat fungsi helper privat untuk mengubah 1 baris array hasil query menjadi objek Kandidat
        return new Kandidat( // Membuat dan mengembalikan instance baru kelas Kandidat
            (int)$row['id'], // Mengisi properti id dengan konversi nilai ke tipe integer
            $row['nama'], // Mengisi properti nama dari kolom nama pada hasil query
            $row['posisi'], // Mengisi properti posisi dari kolom posisi pada hasil query
            $row['domisili'], // Mengisi properti domisili dari kolom domisili pada hasil query
            (int)$row['skorTes'], // Mengisi properti skorTes dengan konversi nilai ke tipe integer
            (int)$row['pengalamanTahun'] // Mengisi properti pengalamanTahun dengan konversi nilai ke tipe integer
        ); // Menutup pemanggilan konstruktor Kandidat
    } // Menutup method mapRowToKandidat

    public function getAllKandidat(): array { // Mendefinisikan method untuk mengambil seluruh data kandidat sebagai array objek
        $query = "SELECT * FROM kandidat ORDER BY skorTes DESC, id ASC"; // Menyiapkan query SQL untuk mengambil semua kandidat urut skor tertinggi lalu ID terkecil
        $this->executeQuery($query); // Menjalankan query melalui method executeQuery milik kelas induk DB
        $rows = $this->getAllResult(); // Mengambil seluruh baris hasil query dalam bentuk array asosiatif

        $list = []; // Menginisialisasi array kosong untuk menampung kumpulan objek Kandidat
        foreach ($rows as $row) { // Melakukan perulangan untuk setiap baris data yang diambil dari database
            $list[] = $this->mapRowToKandidat($row); // Mengonversi baris array menjadi objek Kandidat lalu memasukkannya ke array $list
        } // Menutup blok perulangan foreach
        return $list; // Mengembalikan daftar objek Kandidat ke pemanggil (Presenter)
    } // Menutup method getAllKandidat

    public function getKandidatById(int $id): ?Kandidat { // Mendefinisikan method untuk mengambil satu data kandidat berdasarkan ID (atau null jika tidak ditemukan)
        $query = "SELECT * FROM kandidat WHERE id = :id LIMIT 1"; // Menyiapkan query SQL berparameter :id untuk mencari tepat satu baris kandidat
        $this->executeQuery($query, [':id' => $id]); // Menjalankan prepared statement dengan mengikat nilai $id ke placeholder :id
        $rows = $this->getAllResult(); // Mengambil hasil query dalam bentuk array asosiatif
        if (empty($rows)) { // Mengecek apakah hasil pencarian kosong (ID tidak ditemukan)
            return null; // Mengembalikan null jika data kandidat tidak ada di database
        } // Menutup blok percabangan if
        return $this->mapRowToKandidat($rows[0]); // Mengonversi baris pertama hasil query menjadi objek Kandidat dan mengembalikannya
    } // Menutup method getKandidatById

    public function addKandidat(string $nama, string $posisi, string $domisili, int $skorTes, int $pengalamanTahun): void { // Mendefinisikan method untuk menambah data kandidat baru ke database
        $query = "INSERT INTO kandidat (nama, posisi, domisili, skorTes, pengalamanTahun) " // Memulai penyusunan query SQL INSERT dengan daftar kolom tujuan
            . "VALUES (:nama, :posisi, :domisili, :skorTes, :pengalamanTahun)"; // Menyambungkan klausa VALUES berisi named placeholder PDO untuk mencegah SQL Injection
        $params = [ // Menyiapkan array asosiatif pasangan placeholder dan nilainya
            ':nama'            => $nama, // Mengikat nilai nama kandidat ke placeholder :nama
            ':posisi'          => $posisi, // Mengikat nilai posisi dilamar ke placeholder :posisi
            ':domisili'        => $domisili, // Mengikat nilai domisili ke placeholder :domisili
            ':skorTes'         => $skorTes, // Mengikat nilai skor tes ke placeholder :skorTes
            ':pengalamanTahun' => $pengalamanTahun, // Mengikat nilai pengalaman kerja ke placeholder :pengalamanTahun
        ]; // Menutup array parameter $params
        $this->executeQuery($query, $params); // Mengeksekusi query INSERT menggunakan prepared statement PDO
    } // Menutup method addKandidat

    public function updateKandidat(int $id, string $nama, string $posisi, string $domisili, int $skorTes, int $pengalamanTahun): void { // Mendefinisikan method untuk memperbarui data kandidat berdasarkan ID
        $query = "UPDATE kandidat " // Memulai penyusunan query SQL UPDATE pada tabel kandidat
            . "SET nama = :nama, posisi = :posisi, domisili = :domisili, skorTes = :skorTes, pengalamanTahun = :pengalamanTahun " // Menentukan kolom-kolom yang diperbarui memakai placeholder PDO
            . "WHERE id = :id"; // Membatasi pembaruan hanya pada baris dengan ID yang sesuai
        $params = [ // Menyiapkan array parameter untuk diikat ke prepared statement
            ':id'              => $id, // Mengikat nilai ID kandidat target ke placeholder :id
            ':nama'            => $nama, // Mengikat nilai nama baru ke placeholder :nama
            ':posisi'          => $posisi, // Mengikat nilai posisi baru ke placeholder :posisi
            ':domisili'        => $domisili, // Mengikat nilai domisili baru ke placeholder :domisili
            ':skorTes'         => $skorTes, // Mengikat nilai skor tes baru ke placeholder :skorTes
            ':pengalamanTahun' => $pengalamanTahun, // Mengikat nilai pengalaman kerja baru ke placeholder :pengalamanTahun
        ]; // Menutup array parameter $params
        $this->executeQuery($query, $params); // Mengeksekusi query UPDATE menggunakan prepared statement PDO
    } // Menutup method updateKandidat

    public function deleteKandidat(int $id): void { // Mendefinisikan method untuk menghapus data kandidat berdasarkan ID
        $query = "DELETE FROM kandidat WHERE id = :id"; // Menyiapkan query SQL DELETE berparameter :id
        $this->executeQuery($query, [':id' => $id]); // Mengeksekusi query penghapusan dengan mengikat nilai $id secara aman
    } // Menutup method deleteKandidat
} // Menutup definisi kelas TabelKandidat
// Akhir file TabelKandidat.php (tanpa tag penutup PHP agar aman dari whitespace sebelum header redirect)