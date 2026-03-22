<?php
class LegacyDatabase
{
    private $host;
    private $db_name;
    private $username;
    private $password;
    public $conn;

    public function __construct()
    {
        // โหลดไฟล์ .env ถ้าเป็นโปรเจกต์แบบ Hybrid ที่ไม่ได้รันผ่าน Laravel boot ให้ใช้ Dotenv
        // แต่ถ้าเรียกผ่าน Controller ของ Laravel มันจะรู้จัก getenv() อยู่แล้ว
        $this->host = getenv('DB_HOST') ?: "localhost";
        $this->db_name = getenv('DB_DATABASE') ?: "it_std6730202181";
        $this->username = getenv('DB_USERNAME') ?: "std6730202181";
        $this->password = getenv('DB_PASSWORD') ?: "t8Lp^2Ya";
    }

    public function getConnection()
    {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Connection Error: " . $e->getMessage());
        }
        return $this->conn;
    }
}