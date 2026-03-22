<?php
namespace App\Models;

class Review
{
    private $conn;
    private $table = 'reviews';

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // สำหรับ Admin: ดึงรีวิวพร้อมข้อมูลสินค้าและช่องทางยูเซอร์
    public function getAllReviews()
    {
        $sql = "SELECT r.*, p.product_name, p.img, u.name as username, u.email 
                FROM " . $this->table . " r 
                LEFT JOIN products p ON r.product_id = p.id 
                LEFT JOIN users u ON r.user_id = u.id 
                ORDER BY r.created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM " . $this->table . " WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
