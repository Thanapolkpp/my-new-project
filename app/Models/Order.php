<?php
namespace App\Models;
use PDO;

class Order
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create($user_id, $total, $shipping, $payment_method)
    {
        $sql = "INSERT INTO orders (user_id, total_price, shipping_method, payment_status, status, created_at, updated_at) 
                VALUES (:user_id, :total, :shipping, :payment_status, 'pending', NOW(), NOW())";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':user_id' => $user_id,
            ':total' => $total,
            ':shipping' => $shipping,
            ':payment_status' => $payment_method
        ]);
        return $this->conn->lastInsertId();
    }

    public function addItem($order_id, $product_id, $quantity, $price)
    {
        $sql = "INSERT INTO order_items (order_id, product_id, quantity, price, created_at, updated_at) 
                VALUES (:order_id, :product_id, :quantity, :price, NOW(), NOW())";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':order_id' => $order_id,
            ':product_id' => $product_id,
            ':quantity' => $quantity,
            ':price' => $price
        ]);
    }

    public function getUserOrders($user_id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM orders WHERE user_id = :user_id ORDER BY created_at DESC");
        $stmt->execute([':user_id' => $user_id]);
        return $stmt->fetchAll();
    }

    public function getOrderItems($order_id)
    {
        $sql = "SELECT oi.*, p.product_name, p.img 
                FROM order_items oi 
                JOIN products p ON oi.product_id = p.id 
                WHERE oi.order_id = :order_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':order_id' => $order_id]);
        return $stmt->fetchAll();
    }

    // --- สำหรับ Admin ---
    public function getAllOrders($params = [])
    {
        $sql = "SELECT o.*, u.name as username, u.email 
                FROM orders o 
                LEFT JOIN users u ON o.user_id = u.id 
                WHERE 1=1";
        $binds = [];

        if (!empty($params['search'])) {
            $sql .= " AND (u.name LIKE :search OR u.email LIKE :search OR o.id LIKE :search)";
            $binds[':search'] = "%{$params['search']}%";
        }

        if (!empty($params['start_date'])) {
            $sql .= " AND o.created_at >= :start_date";
            $binds[':start_date'] = $params['start_date'] . ' 00:00:00';
        }

        if (!empty($params['end_date'])) {
            $sql .= " AND o.created_at <= :end_date";
            $binds[':end_date'] = $params['end_date'] . ' 23:59:59';
        }

        $sql .= " ORDER BY o.created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($binds);
        return $stmt->fetchAll();
    }

    public function getOrderById($id)
    {
        $sql = "SELECT o.*, u.name as username, u.email 
                FROM orders o 
                LEFT JOIN users u ON o.user_id = u.id 
                WHERE o.id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function updateStatus($id, $status)
    {
        $sql = "UPDATE orders SET status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':status' => $status, ':id' => $id]);
    }

    // --- Dashboard Stats ---
    public function getDashboardStats()
    {
        // ยอดขายรวม (Completed)
        $stmt = $this->conn->query("SELECT SUM(total_price) as total_revenue FROM orders WHERE status = 'completed'");
        $revenue = $stmt->fetch()['total_revenue'] ?? 0;

        // จำนวน order ทั้งหมด
        $stmt = $this->conn->query("SELECT COUNT(*) as total_orders FROM orders");
        $totalOrders = $stmt->fetch()['total_orders'] ?? 0;

        // จำนวน order ยกเลิก
        $stmt = $this->conn->query("SELECT COUNT(*) as cancelled_orders FROM orders WHERE status = 'cancelled'");
        $cancelledOrders = $stmt->fetch()['cancelled_orders'] ?? 0;

        // จำนวน order สำเร็จ
        $stmt = $this->conn->query("SELECT COUNT(*) as completed_orders FROM orders WHERE status = 'completed'");
        $completedOrders = $stmt->fetch()['completed_orders'] ?? 0;

        return [
            'revenue' => $revenue,
            'total_orders' => $totalOrders,
            'cancelled_orders' => $cancelledOrders,
            'completed_orders' => $completedOrders
        ];
    }

    public function getDailySales($days = 7)
    {
        // ยอดขายรายวัน (7 วันย้อนหลัง)
        $sql = "SELECT DATE(created_at) as sale_date, SUM(total_price) as daily_revenue, COUNT(id) as daily_orders 
                FROM orders 
                WHERE status = 'completed' AND created_at >= DATE_SUB(CURDATE(), INTERVAL :days DAY)
                GROUP BY DATE(created_at) 
                ORDER BY sale_date ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':days', (int) $days, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getTopSellingProducts($limit = 5)
    {
        // สินค้าขายดี (อ้างอิงจาก order ที่เสร็จสิ้น หรือทั้งหมดก็ได้ ในที่นี้เอาจาก order)
        $sql = "SELECT p.product_name, p.img, SUM(oi.quantity) as total_sold
                FROM order_items oi
                JOIN orders o ON oi.order_id = o.id
                JOIN products p ON oi.product_id = p.id
                WHERE o.status != 'cancelled'
                GROUP BY p.id
                ORDER BY total_sold DESC
                LIMIT :limit";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
