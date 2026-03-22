<?php
namespace App\Controllers\Admin;

use App\Models\Order;

class OrderController
{
    private $db;
    private $orderModel;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        require_once ROOT_PATH . '/app/Legacy/LegacyDatabase.php';
        $this->db = (new \LegacyDatabase())->getConnection();
        $this->orderModel = new Order($this->db);
    }

    public function index()
    {
        $orders = $this->orderModel->getAllOrders($_GET);
        require_once APP_PATH . '/Views/admin/order/index.php';
    }

    public function updateStatus($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $status = $_POST['status'] ?? 'pending';
            if ($this->orderModel->updateStatus($id, $status)) {
                header("Location: " . BASE_URL . "/admin/orders?msg=status_updated");
                exit();
            } else {
                echo "เกิดข้อผิดพลาดในการอัปเดตสถานะ";
            }
        }
    }

    public function approvePayment($id)
    {
        if ($this->orderModel->updateStatus($id, 'paid')) {
            header("Location: " . $_SERVER['HTTP_REFERER'] . "?msg=payment_approved");
            exit();
        }
    }

    public function rejectPayment($id)
    {
        // เมื่อปฏิเสธ ให้ตีกลับเป็น pending และอาจจะล้างค่าสลิป (เลือกไม่ล้างเพื่อให้ดูประวัติได้ แต่เปลี่ยนสถานะเป็น pending)
        if ($this->orderModel->updateStatus($id, 'pending')) {
            header("Location: " . $_SERVER['HTTP_REFERER'] . "?msg=payment_rejected");
            exit();
        }
    }

    public function view($id)
    {
        $order = $this->orderModel->getOrderById($id);
        if (!$order) {
            die("Order not found");
        }
        $items = $this->orderModel->getOrderItems($id);
        require_once APP_PATH . '/Views/admin/order/detail.php';
    }
}
