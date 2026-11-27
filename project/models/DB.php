<?php // Membuka blok kode PHP
class DB { // Mendefinisikan kelas induk DB untuk manajemen koneksi dan eksekusi query database
    private string $host = "localhost"; // Mendeklarasikan properti privat alamat host server MySQL dengan nilai bawaan localhost
    private string $db_name = "mvp_kandidat_db"; // Mendeklarasikan properti privat nama database yang digunakan
    private string $username = "root"; // Mendeklarasikan properti privat username koneksi MySQL
    private string $password = ""; // Mendeklarasikan properti privat password koneksi MySQL
    private ?PDO $conn = null; // Mendeklarasikan properti privat penyimpan objek koneksi PDO (bernilai awal null)
    private ?PDOStatement $result = null; // Mendeklarasikan properti privat penyimpan hasil statement query terakhir (bernilai awal null)

    public function __construct( // Mendefinisikan konstruktor kelas DB dengan parameter konfigurasi database
        string $host = "localhost", // Parameter alamat host database dengan nilai bawaan localhost
        string $db_name = "mvp_kandidat_db", // Parameter nama database dengan nilai bawaan mvp_kandidat_db
        string $username = "root", // Parameter username database dengan nilai bawaan root
        string $password = "" // Parameter password database dengan nilai bawaan string kosong
    ) { // Membuka badan konstruktor
        $this->host = $host; // Menyimpan parameter host ke properti $this->host
        $this->db_name = $db_name; // Menyimpan parameter nama database ke properti $this->db_name
        $this->username = $username; // Menyimpan parameter username ke properti $this->username
        $this->password = $password; // Menyimpan parameter password ke properti $this->password
        $this->conn = $this->connect(); // Memanggil method connect() untuk membuka koneksi PDO lalu menyimpannya ke $this->conn
    } // Menutup badan konstruktor

    public function connect(): PDO { // Mendefinisikan method untuk membuat dan mengembalikan instance koneksi PDO
        try { // Memulai blok try untuk menangkap potensi error saat menghubungkan ke database
            $dsn = "mysql:host={$this->host};dbname={$this->db_name};charset=utf8mb4"; // Menyusun Data Source Name (DSN) MySQL beserta pengkodean utf8mb4
            $options = [ // Menyiapkan array opsi konfigurasi koneksi PDO
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Mengatur agar PDO melempar exception setiap kali terjadi error SQL
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Mengatur mode pengambilan data bawaan menjadi array asosiatif
                PDO::ATTR_EMULATE_PREPARES => false, // Menonaktifkan emulasi prepared statement agar menggunakan native prepared statement MySQL
            ]; // Menutup array konfigurasi $options
            return new PDO($dsn, $this->username, $this->password, $options); // Membuat dan mengembalikan objek koneksi PDO baru
        } catch (PDOException $e) { // Menangkap PDOException apabila koneksi ke server MySQL gagal
            throw new RuntimeException("Koneksi Database Gagal: " . $e->getMessage(), 0, $e); // Melempar ulang error sebagai RuntimeException dengan pesan yang jelas
        } // Menutup blok try-catch
    } // Menutup method connect

    public function executeQuery(string $query, array $params = []): PDOStatement { // Mendefinisikan method untuk menyiapkan dan mengeksekusi query berparameter
        if ($this->conn === null) { // Mengecek apakah koneksi database sedang tidak aktif atau sudah ditutup
            throw new RuntimeException("Tidak ada koneksi database aktif."); // Melempar RuntimeException jika koneksi tidak tersedia
        } // Menutup pengecekan koneksi null
        try { // Memulai blok try untuk menangani potensi kesalahan sintaks atau eksekusi SQL
            $stmt = $this->conn->prepare($query); // Menyiapkan query sebagai PDO prepared statement untuk mencegah SQL Injection
            $stmt->execute($params); // Mengeksekusi prepared statement sambil mengikat seluruh nilai parameter $params
            $this->result = $stmt; // Menyimpan objek PDOStatement hasil eksekusi ke properti $this->result
            return $stmt; // Mengembalikan objek PDOStatement ke pemanggil
        } catch (PDOException $e) { // Menangkap PDOException apabila proses prepare atau execute gagal
            throw new RuntimeException("Query Gagal: " . $e->getMessage(), 0, $e); // Melempar ulang error sebagai RuntimeException beserta detail pesan PDO
        } // Menutup blok try-catch
    } // Menutup method executeQuery

    public function getAllResult(): array { // Mendefinisikan method untuk mengambil seluruh baris hasil dari query terakhir
        if ($this->result === null) { // Mengecek apakah belum ada statement hasil query yang tersimpan
            return []; // Mengembalikan array kosong jika belum ada hasil query
        } // Menutup pengecekan $this->result null
        return $this->result->fetchAll(PDO::FETCH_ASSOC); // Mengambil seluruh baris hasil query sebagai array asosiatif dan mengembalikannya
    } // Menutup method getAllResult

    public function close(): void { // Mendefinisikan method untuk menutup dan membersihkan referensi koneksi database
        $this->result = null; // Menghapus referensi statement hasil query dari memori
        $this->conn = null; // Memutus referensi koneksi PDO agar koneksi ke MySQL ditutup
    } // Menutup method close
} // Menutup definisi kelas DB
// Akhir file DB.php (tanpa tag penutup PHP agar aman dari whitespace sebelum header redirect)