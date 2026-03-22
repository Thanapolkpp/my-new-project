<?php
namespace App\Controllers\Admin;
use App\Models\Product;
use App\Services\Security;

class ProductController
{
    private $db;
    private $productModel;

    public function __construct()
    {
        require_once ROOT_PATH . '/app/Legacy/LegacyDatabase.php';
        $this->db = (new \LegacyDatabase())->getConnection();
        $this->productModel = new Product($this->db);
    }

    public function index()
    {
        $products = $this->productModel->all($_GET);
        require_once APP_PATH . '/Views/admin/product/index.php';
    }

    public function add()
    {
        require_once APP_PATH . '/Views/admin/product/add_preview.php';
    }

    private function handleImageUpload()
    {
        // 1. ถ้าระบุลิงก์ URL มา ให้ใช้ลิงก์นั้นเป็นหลัก (ถ้าเลือกใช้ URL รึป่าว?)
        // จริงๆ ใน form มี uploadType -> 'file' หรือ 'url'
        $uploadType = $_POST['uploadType'] ?? 'url';

        if ($uploadType === 'url') {
            return $_POST['img'] ?? '';
        }

        // 2. ถ้าเลือกอัปโหลดไฟล์
        if (isset($_FILES['img_file']) && $_FILES['img_file']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = ROOT_PATH . '/uploads/products/';

            // สร้างโฟลเดอร์ถ้ายังไม่มี
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $fileName = time() . '_' . basename($_FILES['img_file']['name']);
            $targetPath = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['img_file']['tmp_name'], $targetPath)) {
                // ตัดคำว่า /index.php ออกจาก BASE_URL เพื่อให้ path รูปถูกต้อง
                $asset_url = str_replace('/index.php', '', BASE_URL);
                return $asset_url . '/uploads/products/' . $fileName;
            }


        }
        return $_POST['img'] ?? ''; // fallback
    }

    public function save()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                die("Invalid Security Token");
            }

            $_POST['img'] = $this->handleImageUpload();

            if ($this->productModel->create($_POST)) {
                header("Location: " . BASE_URL . "/admin/products?msg=success");
                exit();
            } else {
                echo "เกิดข้อผิดพลาดในการบันทึกข้อมูล";
            }
        }
    }

    public function edit($id)
    {
        $product = $this->productModel->find($id);
        if (!$product) {
            die("Product not found");
        }
        require_once APP_PATH . '/Views/admin/product/edit.php';
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                die("Invalid Security Token");
            }

            // ถ้าไม่ได้อัปโหลดรูปใหม่ (img_file ว่าง และ uploadType = file) ระบบจะใช้รูปเดิมที่มีอยู่ถ้า fallback ตัวเก่า
            $newImg = $this->handleImageUpload();
            if (!empty($newImg)) {
                $_POST['img'] = $newImg;
            }

            if ($this->productModel->update($id, $_POST)) {
                header("Location: " . BASE_URL . "/admin/products?msg=updated");
                exit();
            } else {
                echo "เกิดข้อผิดพลาดในการอัปเดตข้อมูล";
            }
        }
    }

    public function delete($id)
    {
        try {
            if ($this->productModel->delete($id)) {
                header("Location: " . BASE_URL . "/admin/products?msg=deleted");
                exit();
            } else {
                echo "<script>alert('เกิดข้อผิดพลาดในการลบข้อมูล'); window.history.back();</script>";
            }
        } catch (\PDOException $e) {
            // Check for foreign key constraint violation
            if ($e->getCode() == '23000') {
                echo "<script>alert('ไม่สามารถลบสินค้านี้ได้ เนื่องจากมีข้อมูลการสั่งซื้อหรือข้อมูลอื่นที่เกี่ยวข้องอยู่'); window.history.back();</script>";
            } else {
                echo "<script>alert('Error: " . addslashes($e->getMessage()) . "'); window.history.back();</script>";
            }
        }
    }
}
