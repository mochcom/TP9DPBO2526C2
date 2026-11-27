<?php // Membuka blok kode PHP
require_once __DIR__ . '/DB.php'; // Memuat kelas induk DB untuk manajemen koneksi dan eksekusi PDO
require_once __DIR__ . '/KontrakModelLowongan.php'; // Memuat interface KontrakModelLowongan sebagai kontrak Model
require_once __DIR__ . '/Lowongan.php'; // Memuat kelas entitas Lowongan untuk pemetaan hasil query

class TabelLowongan extends DB implements KontrakModelLowongan { // Mendefinisikan kelas TabelLowongan turunan DB yang mengimplementasikan KontrakModelLowongan

    public function __construct( // Mendefinisikan konstruktor dengan parameter koneksi database MySQL
        string $host = "localhost", // Menentukan host server database bawaan localhost
        string $db_name = "mvp_kandidat_db", // Menentukan nama database yang digunakan
        string $username = "root", // Menentukan username MySQL bawaan Laragon
        string $password = "" // Menentukan password MySQL bawaan Laragon (kosong)
    ) { // Membuka badan konstruktor
        parent::__construct($host, $db_name, $username, $password); // Memanggil konstruktor induk DB untuk membuka koneksi PDO
    } // Menutup badan konstruktor

    private function mapRowToLowongan(array $row): Lowongan { // Membuat helper privat untuk memetakan 1 baris array hasil query menjadi objek Lowongan
        $idKandidat = $row['idKandidat'] !== null ? (int)$row['idKandidat'] : null; // Mengonversi idKandidat ke integer jika tidak null, atau tetap null jika kosong
        $namaKandidat = $row['namaKandidat'] ?? null; // Mengambil nama kandidat hasil LEFT JOIN atau null jika lowongan belum memiliki kandidat terpilih

        return new Lowongan( // Membuat dan mengembalikan objek entitas Lowongan baru
            (int)$row['id'], // Mengisi ID lowongan sebagai integer
            $row['judulLowongan'], // Mengisi judul lowongan dari hasil query
            $row['perusahaan'], // Mengisi nama perusahaan dari hasil query
            $row['picRekruter'], // Mengisi nama PIC rekruter dari hasil query
            (int)$row['gajiJuta'], // Mengisi nominal gaji (juta) sebagai integer
            $row['status'], // Mengisi status lowongan dari hasil query
            $idKandidat, // Mengisi ID kandidat (integer atau null)
            $namaKandidat // Mengisi nama kandidat hasil JOIN (string atau null)
        ); // Menutup pemanggilan konstruktor Lowongan
    } // Menutup helper mapRowToLowongan

    public function getAllLowongan(): array { // Mendefinisikan method untuk mengambil semua lowongan beserta nama kandidatnya
        $query = "SELECT l.id, l.judulLowongan, l.perusahaan, l.picRekruter, l.gajiJuta, l.status, l.idKandidat, k.nama AS namaKandidat " // Memilih seluruh kolom lowongan dan kolom nama dari tabel kandidat dengan alias namaKandidat
            . "FROM lowongan l " // Menentukan tabel utama lowongan dengan alias l
            . "LEFT JOIN kandidat k ON l.idKandidat = k.id " // Menggabungkan tabel kandidat (alias k) dengan LEFT JOIN agar lowongan ber-idKandidat NULL tetap tampil
            . "ORDER BY l.gajiJuta DESC, l.id ASC"; // Mengurutkan hasil dari gaji tertinggi lalu ID terkecil
        $this->executeQuery($query); // Menjalankan query melalui method executeQuery milik kelas induk DB
        $rows = $this->getAllResult(); // Mengambil seluruh baris hasil query dalam bentuk array asosiatif

        $list = []; // Menginisialisasi array kosong penampung kumpulan objek Lowongan
        foreach ($rows as $row) { // Melakukan perulangan untuk setiap baris hasil query
            $list[] = $this->mapRowToLowongan($row); // Memetakan baris array menjadi objek Lowongan lalu menambahkannya ke array $list
        } // Menutup perulangan foreach
        return $list; // Mengembalikan daftar objek Lowongan ke Presenter
    } // Menutup method getAllLowongan

    public function getLowonganById(int $id): ?Lowongan { // Mendefinisikan method untuk mencari satu lowongan berdasarkan ID
        $query = "SELECT l.id, l.judulLowongan, l.perusahaan, l.picRekruter, l.gajiJuta, l.status, l.idKandidat, k.nama AS namaKandidat " // Memilih kolom lowongan beserta nama kandidat hasil JOIN
            . "FROM lowongan l " // Menentukan tabel utama lowongan dengan alias l
            . "LEFT JOIN kandidat k ON l.idKandidat = k.id " // Melakukan LEFT JOIN ke tabel kandidat berdasarkan kecocokan ID kandidat
            . "WHERE l.id = :id LIMIT 1"; // Memfilter tepat satu baris lowongan berdasarkan placeholder :id
        $this->executeQuery($query, [':id' => $id]); // Menjalankan prepared statement dengan mengikat nilai $id ke :id
        $rows = $this->getAllResult(); // Mengambil hasil query dalam bentuk array asosiatif

        if (empty($rows)) { // Mengecek apakah data lowongan tidak ditemukan
            return null; // Mengembalikan null jika ID tidak cocok dengan baris mana pun
        } // Menutup percabangan if
        return $this->mapRowToLowongan($rows[0]); // Mengonversi baris pertama hasil query menjadi objek Lowongan dan mengembalikannya
    } // Menutup method getLowonganById

    public function addLowongan( // Memulai definisi method untuk menambahkan data lowongan baru ke database
        string $judulLowongan, // Parameter judul lowongan
        string $perusahaan, // Parameter nama perusahaan
        string $picRekruter, // Parameter nama PIC rekruter
        int $gajiJuta, // Parameter nominal gaji dalam satuan juta
        string $status, // Parameter status lowongan
        ?int $idKandidat // Parameter ID kandidat unggulan (boleh null)
    ): void { // Membuka badan method addLowongan
        $query = "INSERT INTO lowongan (judulLowongan, perusahaan, picRekruter, gajiJuta, status, idKandidat) " // Memulai query INSERT dengan daftar kolom tabel lowongan
            . "VALUES (:judulLowongan, :perusahaan, :picRekruter, :gajiJuta, :status, :idKandidat)"; // Menentukan named placeholder PDO untuk seluruh kolom nilai
        $params = [ // Menyiapkan array asosiatif nilai parameter untuk prepared statement
            ':judulLowongan' => $judulLowongan, // Mengikat nilai judul lowongan ke placeholder :judulLowongan
            ':perusahaan'    => $perusahaan, // Mengikat nilai perusahaan ke placeholder :perusahaan
            ':picRekruter'   => $picRekruter, // Mengikat nilai PIC rekruter ke placeholder :picRekruter
            ':gajiJuta'      => $gajiJuta, // Mengikat nilai gaji ke placeholder :gajiJuta
            ':status'        => $status, // Mengikat nilai status ke placeholder :status
            ':idKandidat'    => $idKandidat, // Mengikat nilai idKandidat (integer atau NULL) ke placeholder :idKandidat
        ]; // Menutup array $params
        $this->executeQuery($query, $params); // Mengeksekusi query INSERT secara aman menggunakan prepared statement PDO
    } // Menutup method addLowongan

    public function updateLowongan( // Memulai definisi method untuk memperbarui data lowongan berdasarkan ID
        int $id, // Parameter ID lowongan yang akan diperbarui
        string $judulLowongan, // Parameter judul lowongan baru
        string $perusahaan, // Parameter nama perusahaan baru
        string $picRekruter, // Parameter nama PIC rekruter baru
        int $gajiJuta, // Parameter nominal gaji baru dalam satuan juta
        string $status, // Parameter status lowongan baru
        ?int $idKandidat // Parameter ID kandidat unggulan baru (boleh null)
    ): void { // Membuka badan method updateLowongan
        $query = "UPDATE lowongan " // Memulai query UPDATE untuk tabel lowongan
            . "SET judulLowongan = :judulLowongan, perusahaan = :perusahaan, picRekruter = :picRekruter, " // Menetapkan pembaruan kolom teks utama memakai placeholder PDO
            . "gajiJuta = :gajiJuta, status = :status, idKandidat = :idKandidat " // Menetapkan pembaruan kolom gaji, status, dan Foreign Key kandidat
            . "WHERE id = :id"; // Membatasi pembaruan hanya pada baris dengan ID yang sesuai
        $params = [ // Menyiapkan array pasangan placeholder dan nilai baru
            ':id'            => $id, // Mengikat ID lowongan target ke placeholder :id
            ':judulLowongan' => $judulLowongan, // Mengikat judul lowongan baru ke placeholder :judulLowongan
            ':perusahaan'    => $perusahaan, // Mengikat nama perusahaan baru ke placeholder :perusahaan
            ':picRekruter'   => $picRekruter, // Mengikat nama PIC rekruter baru ke placeholder :picRekruter
            ':gajiJuta'      => $gajiJuta, // Mengikat nominal gaji baru ke placeholder :gajiJuta
            ':status'        => $status, // Mengikat status lowongan baru ke placeholder :status
            ':idKandidat'    => $idKandidat, // Mengikat ID kandidat baru (integer atau NULL) ke placeholder :idKandidat
        ]; // Menutup array $params
        $this->executeQuery($query, $params); // Mengeksekusi query UPDATE menggunakan prepared statement PDO
    } // Menutup method updateLowongan

    public function deleteLowongan(int $id): void { // Mendefinisikan method untuk menghapus data lowongan berdasarkan ID
        $query = "DELETE FROM lowongan WHERE id = :id"; // Menyiapkan query DELETE dengan filter placeholder :id
        $this->executeQuery($query, [':id' => $id]); // Mengeksekusi penghapusan data secara aman dengan prepared statement PDO
    } // Menutup method deleteLowongan
} // Menutup definisi kelas TabelLowongan
// Akhir file TabelLowongan.php (tanpa tag penutup PHP agar aman dari whitespace sebelum header redirect)