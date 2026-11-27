<?php // Membuka blok kode PHP
interface KontrakPresenter { // Mendefinisikan interface kontrak method untuk layer Presenter modul kandidat
    public function tampilkanKandidat(): string; // Kontrak method untuk menyiapkan dan mengembalikan HTML halaman daftar kandidat
    public function tampilkanFormKandidat(?int $id = null): string; // Kontrak method untuk menampilkan form tambah (id null) atau form edit (id terisi)
    public function tambahKandidat(string $nama, string $posisi, string $domisili, int $skorTes, int $pengalamanTahun): void; // Kontrak method untuk memproses penambahan data kandidat baru
    public function ubahKandidat(int $id, string $nama, string $posisi, string $domisili, int $skorTes, int $pengalamanTahun): void; // Kontrak method untuk memproses pembaruan data kandidat berdasarkan ID
    public function hapusKandidat(int $id): void; // Kontrak method untuk memproses penghapusan data kandidat berdasarkan ID
} // Menutup definisi interface KontrakPresenter
// Akhir file KontrakPresenter.php (tanpa tag penutup PHP agar aman dari whitespace sebelum header redirect)