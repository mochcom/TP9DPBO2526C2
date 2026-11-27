<?php // Membuka blok kode PHP
interface KontrakModelLowongan { // Mendefinisikan interface kontrak method CRUD untuk layer Model lowongan
    public function getAllLowongan(): array; // Kontrak method untuk mengambil seluruh data lowongan sebagai array objek Lowongan
    public function getLowonganById(int $id): ?Lowongan; // Kontrak method untuk mengambil satu objek Lowongan berdasarkan ID (atau null jika tidak ada)
    public function addLowongan( // Memulai deklarasi kontrak method untuk menambah data lowongan baru
        string $judulLowongan, // Parameter judul posisi lowongan yang ditambahkan
        string $perusahaan, // Parameter nama perusahaan penyelenggara lowongan
        string $picRekruter, // Parameter nama PIC rekruter penanggung jawab
        int $gajiJuta, // Parameter estimasi gaji dalam satuan juta rupiah
        string $status, // Parameter status lowongan (Dibuka/Ditutup)
        ?int $idKandidat // Parameter ID kandidat unggulan yang boleh bernilai null
    ): void; // Menutup deklarasi kontrak addLowongan tanpa nilai kembalian
    public function updateLowongan( // Memulai deklarasi kontrak method untuk memperbarui data lowongan yang sudah ada
        int $id, // Parameter ID lowongan target yang akan diubah
        string $judulLowongan, // Parameter judul lowongan baru
        string $perusahaan, // Parameter nama perusahaan baru
        string $picRekruter, // Parameter nama PIC rekruter baru
        int $gajiJuta, // Parameter estimasi gaji baru dalam satuan juta
        string $status, // Parameter status lowongan baru
        ?int $idKandidat // Parameter ID kandidat unggulan baru (boleh null)
    ): void; // Menutup deklarasi kontrak updateLowongan tanpa nilai kembalian
    public function deleteLowongan(int $id): void; // Kontrak method untuk menghapus satu data lowongan berdasarkan ID
} // Menutup definisi interface KontrakModelLowongan
// Akhir file KontrakModelLowongan.php (tanpa tag penutup PHP agar aman dari whitespace sebelum header redirect)