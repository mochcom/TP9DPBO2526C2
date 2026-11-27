<?php // Membuka blok kode PHP
class Kandidat { // Mendefinisikan kelas entitas Kandidat untuk merepresentasikan satu data pelamar
    private ?int $id; // Mendeklarasikan properti privat ID kandidat bertipe integer atau null
    private string $nama; // Mendeklarasikan properti privat nama lengkap kandidat
    private string $posisi; // Mendeklarasikan properti privat posisi yang dilamar kandidat
    private string $domisili; // Mendeklarasikan properti privat kota domisili kandidat
    private int $skorTes; // Mendeklarasikan properti privat nilai skor tes kandidat
    private int $pengalamanTahun; // Mendeklarasikan properti privat lama pengalaman kerja dalam satuan tahun

    public function __construct( // Mendefinisikan konstruktor untuk menginisialisasi seluruh properti objek Kandidat
        ?int $id, // Parameter ID kandidat bertipe nullable integer
        string $nama, // Parameter nama lengkap kandidat bertipe string
        string $posisi, // Parameter posisi dilamar bertipe string
        string $domisili, // Parameter kota domisili bertipe string
        int $skorTes, // Parameter nilai skor tes bertipe integer
        int $pengalamanTahun // Parameter lama pengalaman kerja bertipe integer
    ) { // Membuka badan konstruktor
        $this->id = $id; // Menyimpan nilai parameter ID ke properti $this->id
        $this->nama = $nama; // Menyimpan nilai parameter nama ke properti $this->nama
        $this->posisi = $posisi; // Menyimpan nilai parameter posisi ke properti $this->posisi
        $this->domisili = $domisili; // Menyimpan nilai parameter domisili ke properti $this->domisili
        $this->skorTes = $skorTes; // Menyimpan nilai parameter skor tes ke properti $this->skorTes
        $this->pengalamanTahun = $pengalamanTahun; // Menyimpan nilai parameter pengalaman kerja ke properti $this->pengalamanTahun
    } // Menutup badan konstruktor

    public function getId(): ?int { return $this->id; } // Mendefinisikan getter untuk mengembalikan nilai ID kandidat
    public function getNama(): string { return $this->nama; } // Mendefinisikan getter untuk mengembalikan nama kandidat
    public function getPosisi(): string { return $this->posisi; } // Mendefinisikan getter untuk mengembalikan posisi dilamar
    public function getDomisili(): string { return $this->domisili; } // Mendefinisikan getter untuk mengembalikan domisili kandidat
    public function getSkorTes(): int { return $this->skorTes; } // Mendefinisikan getter untuk mengembalikan nilai skor tes
    public function getPengalamanTahun(): int { return $this->pengalamanTahun; } // Mendefinisikan getter untuk mengembalikan lama pengalaman kerja

    public function setNama(string $nama): void { $this->nama = $nama; } // Mendefinisikan setter untuk mengubah nilai properti nama
    public function setPosisi(string $posisi): void { $this->posisi = $posisi; } // Mendefinisikan setter untuk mengubah nilai properti posisi
    public function setDomisili(string $domisili): void { $this->domisili = $domisili; } // Mendefinisikan setter untuk mengubah nilai properti domisili
    public function setSkorTes(int $skorTes): void { $this->skorTes = $skorTes; } // Mendefinisikan setter untuk mengubah nilai properti skorTes
    public function setPengalamanTahun(int $pengalamanTahun): void { $this->pengalamanTahun = $pengalamanTahun; } // Mendefinisikan setter untuk mengubah nilai properti pengalamanTahun
} // Menutup definisi kelas Kandidat
// Akhir file Kandidat.php (tanpa tag penutup PHP agar aman dari whitespace sebelum header redirect)