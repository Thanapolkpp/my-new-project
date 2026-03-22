<?php
namespace App\Controllers\Customer;

use App\Models\Order;
use App\Services\Auth;

class PaymentController
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

    public function show($id)
    {
        if (!Auth::check()) {
            header("Location: " . BASE_URL . "/login");
            exit();
        }

        $order = $this->orderModel->getOrderById($id);

        if (!$order || $order['user_id'] != $_SESSION['user_id']) {
            header("Location: " . BASE_URL . "/orders");
            exit();
        }

        if ($order['status'] == 'cancelled') {
            header("Location: " . BASE_URL . "/orders?msg=order_cancelled");
            exit();
        }

        // หากเป็น COD หรือยกเลิกเเล้ว ให้ข้ามไปหน้ารายการสั่งซื้อ
        // หมายเหตุ: เปิดให้สถานะ 'paid' สามารถดูหน้าเพจได้ เพื่อดูหลักฐานหรือรายละเอียด (จะโชว์ว่าชำระแล้ว)
        if ($order['payment_status'] == 'COD' || $order['status'] == 'cancelled' || $order['status'] == 'completed') {
            header("Location: " . BASE_URL . "/orders");
            exit();
        }

        require_once APP_PATH . '/Views/customer/shop/payment.php';
    }

    public function uploadSlip($id)
    {
        if (!Auth::check() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . BASE_URL . "/login");
            exit();
        }

        $order = $this->orderModel->getOrderById($id);
        if (!$order || $order['user_id'] != $_SESSION['user_id']) {
            header("Location: " . BASE_URL . "/orders");
            exit();
        }

        if (isset($_FILES['slip']) && $_FILES['slip']['error'] == 0) {
            $target_dir = ROOT_PATH . "/public/uploads/slips/";
            if (!is_dir($target_dir)) {
                if (!mkdir($target_dir, 0777, true)) {
                    die("ไม่สามารถสร้างโฟลเดอร์สำหรับเก็บหลักฐานได้: " . $target_dir);
                }
            }

            if (!is_writable($target_dir)) {
                die("โฟลเดอร์สำหรับเก็บหลักฐานไม่สามารถเขียนข้อมูลได้: " . $target_dir);
            }

            $file_extension = strtolower(pathinfo($_FILES["slip"]["name"], PATHINFO_EXTENSION));
            $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
            
            if (!in_array($file_extension, $allowed_extensions)) {
                die("อนุญาตเฉพาะไฟล์รูปภาพ (jpg, jpeg, png, gif) เท่านั้น");
            }

            $new_filename = 'slip_' . $id . '_' . time() . '.' . $file_extension;
            $target_file = $target_dir . $new_filename;

            if (move_uploaded_file($_FILES["slip"]["tmp_name"], $target_file)) {
                // Update Order Status and Slip
                $stmt = $this->db->prepare("UPDATE orders SET payment_slip = :slip, payment_date = NOW(), status = 'paid' WHERE id = :id");
                $stmt->execute([
                    ':slip' => $new_filename,
                    ':id' => $id
                ]);

                header("Location: " . BASE_URL . "/orders?msg=payment_success");
                exit();
            } else {
                $error = error_get_last();
                die("อัปโหลดหลักฐานการชำระเงินไม่สำเร็จ (Internal Error: " . ($error['message'] ?? 'Unknown') . ")");
            }
        } else {
            $upload_error = $_FILES['slip']['error'] ?? 'No file';
            die("กรุณาแนบไฟล์หลักฐานการชำระเงิน (Error Code: " . $upload_error . ")");
        }
    }
}
