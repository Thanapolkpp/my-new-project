<?php
namespace App\Models;
use PDO;

class Product
{
    private $conn;
    private $table = "products";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function all($params = [])
    {
        $sql = "SELECT * FROM " . $this->table . " WHERE 1=1";
        $binds = [];

        if (!empty($params['search'])) {
            $sql .= " AND (product_name LIKE :search OR serial_number LIKE :search OR certifications LIKE :search)";
            $binds[':search'] = "%{$params['search']}%";
        }

        if (!empty($params['start_date'])) {
            $sql .= " AND created_at >= :start_date";
            $binds[':start_date'] = $params['start_date'] . ' 00:00:00';
        }

        if (!empty($params['end_date'])) {
            $sql .= " AND created_at <= :end_date";
            $binds[':end_date'] = $params['end_date'] . ' 23:59:59';
        }

        $sql .= " ORDER BY id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($binds);
        return $stmt->fetchAll();
    }

    public function search($keyword)
    {
        $keyword = "%{$keyword}%";
        $stmt = $this->conn->prepare("SELECT * FROM " . $this->table . " WHERE product_name LIKE :keyword OR detail LIKE :keyword ORDER BY id DESC");
        $stmt->execute([':keyword' => $keyword]);
        return $stmt->fetchAll();
    }

    public function filter($params)
    {
        $sql = "SELECT * FROM " . $this->table . " WHERE 1=1";
        $binds = [];

        // ค้นหาชื่อหรือรุ่น
        if (!empty($params['search'])) {
            $sql .= " AND (product_name LIKE :search OR serial_number LIKE :search)";
            $binds[':search'] = "%{$params['search']}%";
        }

        // ช่วงราคา
        if (!empty($params['min_price'])) {
            $sql .= " AND price >= :min_price";
            $binds[':min_price'] = (float) $params['min_price'];
        }
        if (!empty($params['max_price'])) {
            $sql .= " AND price <= :max_price";
            $binds[':max_price'] = (float) $params['max_price'];
        }

        // การรับประกัน
        if (!empty($params['warranty'])) {
            $sql .= " AND warranty_value > 0";
        }

        // มาตรฐาน (Certifications)
        if (!empty($params['certification'])) {
            $sql .= " AND certifications LIKE :cert";
            $binds[':cert'] = "%{$params['certification']}%";
        }

        // มีสินค้าไหม (มีสต็อก)
        if (!empty($params['in_stock']) && $params['in_stock'] == '1') {
            $sql .= " AND stock > 0";
        }

        // การเรียงลำดับราคา
        if (!empty($params['sort_price'])) {
            if ($params['sort_price'] === 'asc') {
                $sql .= " ORDER BY price ASC";
            } elseif ($params['sort_price'] === 'desc') {
                $sql .= " ORDER BY price DESC";
            } else {
                $sql .= " ORDER BY id DESC";
            }
        } else {
            $sql .= " ORDER BY id DESC";
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($binds);
        return $stmt->fetchAll();
    }

    public function getBestSellers($limit = 4)
    {
        // คำนวณยอดขายจากตาราง order_items ที่สถานะออเดอร์ไม่ใช่ cancelled แบบ Real-time
        $sql = "SELECT p.*, COALESCE(v.total_sold, 0) as total_sold
                FROM " . $this->table . " p
                LEFT JOIN (
                    SELECT product_id, SUM(quantity) as total_sold
                    FROM order_items oi
                    JOIN orders o ON oi.order_id = o.id
                    WHERE o.status != 'cancelled'
                    GROUP BY product_id
                ) v ON p.id = v.product_id
                ORDER BY total_sold DESC, p.id DESC
                LIMIT :limit";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
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
        $sql = "INSERT INTO " . $this->table . " 
                (product_name, serial_number, detail, price, discount, stock, img, warranty_value, warranty_unit, certifications) 
                VALUES (:name, :sn, :detail, :price, :discount, :stock, :img, :w_val, :w_unit, :cert)";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':name' => $data['product_name'],
            ':sn' => $data['serial_number'],
            ':detail' => $data['detail'] ?? '',
            ':price' => $data['price'],
            ':discount' => $data['discount'] ?? 0,
            ':stock' => $data['stock'] ?? 0,
            ':img' => $data['img'] ?? '',
            ':w_val' => $data['warranty_value'] ?? 0,
            ':w_unit' => $data['warranty_unit'] ?? 'year',
            ':cert' => $data['certifications'] ?? ''
        ]);
    }

    public function update($id, $data)
    {
        $sql = "UPDATE " . $this->table . " SET 
                product_name = :name, 
                serial_number = :sn, 
                detail = :detail, 
                price = :price, 
                discount = :discount, 
                stock = :stock, 
                img = :img, 
                warranty_value = :w_val, 
                warranty_unit = :w_unit, 
                certifications = :cert 
                WHERE id = :id";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':name' => $data['product_name'],
            ':sn' => $data['serial_number'],
            ':detail' => $data['detail'],
            ':price' => $data['price'],
            ':discount' => $data['discount'],
            ':stock' => $data['stock'],
            ':img' => $data['img'],
            ':w_val' => $data['warranty_value'],
            ':w_unit' => $data['warranty_unit'],
            ':cert' => $data['certifications'],
            ':id' => $id
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM " . $this->table . " WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}