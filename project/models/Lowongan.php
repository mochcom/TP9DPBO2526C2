<?php // Membuka blok kode PHP
class Lowongan { // Mendefinisikan kelas entitas Lowongan untuk membungkus data satu baris tabel lowongan
    private ?int $id; // Mendeklarasikan properti privat ID lowongan yang boleh bernilai integer atau null
    private string $judulLowongan; // Mendeklarasikan properti privat untuk judul posisi lowongan
    private string $perusahaan; // Mendeklarasikan properti privat untuk nama perusahaan atau studio
    private string $picRekruter; // Mendeklarasikan properti privat untuk nama PIC rekruter (artis kelas dunia)
    private int $gajiJuta; // Mendeklarasikan properti privat untuk nominal gaji dalam satuan juta rupiah
    private string $status; // Mendeklarasikan properti privat untuk status lowongan (Dibuka atau Ditutup)
    private ?int $idKandidat; // Mendeklarasikan properti privat Foreign Key ID kandidat terpilih (boleh null)
    private ?string $namaKandidat; // Mendeklarasikan properti privat nama kandidat hasil LEFT JOIN (boleh null)

    public function __construct( // Mendefinisikan konstruktor untuk menginisialisasi seluruh properti objek Lowongan
        ?int $id, // Parameter ID lowongan bertipe nullable integer
        string $judulLowongan, // Parameter judul posisi lowongan bertipe string
        string $perusahaan, // Parameter nama perusahaan bertipe string
        string $picRekruter, // Parameter nama PIC rekruter bertipe string
        int $gajiJuta, // Parameter besaran gaji dalam juta bertipe integer
        string $status, // Parameter status lowongan bertipe string
        ?int $idKandidat = null, // Parameter ID kandidat unggulan bertipe nullable integer dengan nilai bawaan null
        ?string $namaKandidat = null // Parameter nama kandidat hasil JOIN bertipe nullable string dengan nilai bawaan null
    ) { // Membuka badan konstruktor
        $this->id = $id; // Menyimpan parameter ID ke properti $this->id
        $this->judulLowongan = $judulLowongan; // Menyimpan parameter judul lowongan ke properti $this->judulLowongan
        $this->perusahaan = $perusahaan; // Menyimpan parameter nama perusahaan ke properti $this->perusahaan
        $this->picRekruter = $picRekruter; // Menyimpan parameter PIC rekruter ke properti $this->picRekruter
        $this->gajiJuta = $gajiJuta; // Menyimpan parameter gaji ke properti $this->gajiJuta
        $this->status = $status; // Menyimpan parameter status ke properti $this->status
        $this->idKandidat = $idKandidat; // Menyimpan parameter ID kandidat ke properti $this->idKandidat
        $this->namaKandidat = $namaKandidat; // Menyimpan parameter nama kandidat ke properti $this->namaKandidat
    } // Menutup badan konstruktor

    public function getId(): ?int { // Mendefinisikan getter untuk mengambil nilai ID lowongan
        return $this->id; // Mengembalikan nilai properti $this->id
    } // Menutup getter getId

    public function getJudulLowongan(): string { // Mendefinisikan getter untuk mengambil judul lowongan
        return $this->judulLowongan; // Mengembalikan nilai properti $this->judulLowongan
    } // Menutup getter getJudulLowongan

    public function getPerusahaan(): string { // Mendefinisikan getter untuk mengambil nama perusahaan
        return $this->perusahaan; // Mengembalikan nilai properti $this->perusahaan
    } // Menutup getter getPerusahaan

    public function getPicRekruter(): string { // Mendefinisikan getter untuk mengambil nama PIC rekruter
        return $this->picRekruter; // Mengembalikan nilai properti $this->picRekruter
    } // Menutup getter getPicRekruter

    public function getGajiJuta(): int { // Mendefinisikan getter untuk mengambil besaran gaji dalam juta
        return $this->gajiJuta; // Mengembalikan nilai properti $this->gajiJuta
    } // Menutup getter getGajiJuta

    public function getStatus(): string { // Mendefinisikan getter untuk mengambil status lowongan
        return $this->status; // Mengembalikan nilai properti $this->status
    } // Menutup getter getStatus

    public function getIdKandidat(): ?int { // Mendefinisikan getter untuk mengambil Foreign Key ID kandidat
        return $this->idKandidat; // Mengembalikan nilai properti $this->idKandidat
    } // Menutup getter getIdKandidat

    public function getNamaKandidat(): ?string { // Mendefinisikan getter untuk mengambil nama kandidat hasil JOIN
        return $this->namaKandidat; // Mengembalikan nilai properti $this->namaKandidat
    } // Menutup getter getNamaKandidat

    public function setJudulLowongan(string $judulLowongan): void { // Mendefinisikan setter untuk mengubah judul lowongan
        $this->judulLowongan = $judulLowongan; // Menetapkan nilai baru ke properti $this->judulLowongan
    } // Menutup setter setJudulLowongan

    public function setPerusahaan(string $perusahaan): void { // Mendefinisikan setter untuk mengubah nama perusahaan
        $this->perusahaan = $perusahaan; // Menetapkan nilai baru ke properti $this->perusahaan
    } // Menutup setter setPerusahaan

    public function setPicRekruter(string $picRekruter): void { // Mendefinisikan setter untuk mengubah nama PIC rekruter
        $this->picRekruter = $picRekruter; // Menetapkan nilai baru ke properti $this->picRekruter
    } // Menutup setter setPicRekruter

    public function setGajiJuta(int $gajiJuta): void { // Mendefinisikan setter untuk mengubah besaran gaji
        $this->gajiJuta = $gajiJuta; // Menetapkan nilai baru ke properti $this->gajiJuta
    } // Menutup setter setGajiJuta

    public function setStatus(string $status): void { // Mendefinisikan setter untuk mengubah status lowongan
        $this->status = $status; // Menetapkan nilai baru ke properti $this->status
    } // Menutup setter setStatus

    public function setIdKandidat(?int $idKandidat): void { // Mendefinisikan setter untuk mengubah ID kandidat terpilih
        $this->idKandidat = $idKandidat; // Menetapkan nilai baru ke properti $this->idKandidat
    } // Menutup setter setIdKandidat

    public function setNamaKandidat(?string $namaKandidat): void { // Mendefinisikan setter untuk mengubah nama kandidat
        $this->namaKandidat = $namaKandidat; // Menetapkan nilai baru ke properti $this->namaKandidat
    } // Menutup setter setNamaKandidat
} // Menutup definisi kelas Lowongan
// Akhir file Lowongan.php (tanpa tag penutup PHP agar aman dari whitespace sebelum header redirect)