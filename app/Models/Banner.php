<?php
namespace App\Models;

use PDO;

class Banner
{
    private $conn;
    private $table = "banners";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function all()
    {
        $query = "SELECT * FROM " . $this->table . " ORDER BY sort_order ASC, id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getActiveBanners()
    {
        $query = "SELECT * FROM " . $this->table . " WHERE is_active = 1 ORDER BY sort_order ASC, id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function find($id)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $query = "INSERT INTO " . $this->table . " (image, title, subtitle, link, is_active, sort_order) 
                  VALUES (:image, :title, :subtitle, :link, :is_active, :sort_order)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':image', $data['image']);
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':subtitle', $data['subtitle']);
        $stmt->bindParam(':link', $data['link']);
        $stmt->bindParam(':is_active', $data['is_active'], PDO::PARAM_INT);
        $stmt->bindParam(':sort_order', $data['sort_order'], PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function update($id, $data)
    {
        $query = "UPDATE " . $this->table . " 
                  SET title = :title, 
                      subtitle = :subtitle, 
                      link = :link, 
                      is_active = :is_active, 
                      sort_order = :sort_order" .
            (isset($data['image']) ? ", image = :image" : "") . " 
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':subtitle', $data['subtitle']);
        $stmt->bindParam(':link', $data['link']);
        $stmt->bindParam(':is_active', $data['is_active'], PDO::PARAM_INT);
        $stmt->bindParam(':sort_order', $data['sort_order'], PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if (isset($data['image'])) {
            $stmt->bindParam(':image', $data['image']);
        }

        return $stmt->execute();
    }

    public function delete($id)
    {
        $query = "DELETE FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }
}
