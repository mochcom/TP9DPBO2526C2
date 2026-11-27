<?php // Membuka blok kode PHP
interface KontrakModel { // Mendefinisikan interface kontrak method CRUD untuk layer Model modul kandidat
    public function getAllKandidat(): array; // Kontrak method untuk mengambil seluruh data kandidat sebagai array objek Kandidat
    public function getKandidatById(int $id): ?Kandidat; // Kontrak method untuk mengambil satu objek Kandidat berdasarkan ID (atau null jika tidak ditemukan)
    public function addKandidat(string $nama, string $posisi, string $domisili, int $skorTes, int $pengalamanTahun): void; // Kontrak method untuk menambahkan data kandidat baru ke database
    public function updateKandidat(int $id, string $nama, string $posisi, string $domisili, int $skorTes, int $pengalamanTahun): void; // Kontrak method untuk memperbarui data kandidat berdasarkan ID
    public function deleteKandidat(int $id): void; // Kontrak method untuk menghapus satu data kandidat berdasarkan ID
} // Menutup definisi interface KontrakModel
// Akhir file KontrakModel.php (tanpa tag penutup PHP agar aman dari whitespace sebelum header redirect)