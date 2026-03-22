<?php
namespace App\Models;
use PDO;

class PromoCode
{
    private $conn;
    private $table = 'promo_codes';

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function all()
    {
        $stmt = $this->conn->prepare("SELECT * FROM " . $this->table . " ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function find($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM " . $this->table . " WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $sql = "INSERT INTO " . $this->table . " (code, discount_amount, is_percentage, usage_limit, max_uses_per_user, used_count, expiry_date, is_active, show_on_home) 
                VALUES (:code, :discount_amount, :is_percentage, :usage_limit, :max_uses_per_user, 0, :expiry_date, :is_active, :show_on_home)";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':code' => $data['code'],
            ':discount_amount' => $data['discount_amount'],
            ':is_percentage' => $data['is_percentage'] ?? 0,
            ':usage_limit' => $data['usage_limit'] ?: 0,
            ':max_uses_per_user' => $data['max_uses_per_user'] ?: 1, // Default to 1 use per user
            ':expiry_date' => $data['expiry_date'] ?: null,
            ':is_active' => $data['is_active'] ?? 1,
            ':show_on_home' => $data['show_on_home'] ?? 0
        ]);
    }

    public function update($id, $data)
    {
        $sql = "UPDATE " . $this->table . " 
                SET code=:code, discount_amount=:discount_amount, is_percentage=:is_percentage, 
                    usage_limit=:usage_limit, max_uses_per_user=:max_uses_per_user, expiry_date=:expiry_date, 
                    is_active=:is_active, show_on_home=:show_on_home 
                WHERE id=:id";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':code' => $data['code'],
            ':discount_amount' => $data['discount_amount'],
            ':is_percentage' => $data['is_percentage'] ?? 0,
            ':usage_limit' => $data['usage_limit'] ?: 0,
            ':max_uses_per_user' => $data['max_uses_per_user'] ?: 1,
            ':expiry_date' => $data['expiry_date'] ?: null,
            ':is_active' => $data['is_active'] ?? 1,
            ':show_on_home' => $data['show_on_home'] ?? 0,
            ':id' => $id
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM " . $this->table . " WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}