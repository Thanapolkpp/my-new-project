<?php
namespace App\Controllers\Admin;

use App\Services\Auth;
use LegacyDatabase;
use App\Models\Banner;

class BannerController
{
    private $db;
    private $bannerModel;

    public function __construct()
    {
        if (!Auth::isAdmin()) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
        require_once ROOT_PATH . '/app/Legacy/LegacyDatabase.php';
        $database = new \LegacyDatabase();
        $this->db = $database->getConnection();
        $this->bannerModel = new Banner($this->db);
    }

    public function index()
    {
        $banners = $this->bannerModel->all();
        require_once APP_PATH . '/Views/admin/banner/index.php';
    }

    public function create()
    {
        require_once APP_PATH . '/Views/admin/banner/form.php';
    }

    public function edit($id)
    {
        $banner = $this->bannerModel->find($id);
        if (!$banner) {
            header("Location: " . BASE_URL . "/admin/banners");
            exit;
        }
        require_once APP_PATH . '/Views/admin/banner/form.php';
    }

    public function save()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $data = [
                    'title' => $_POST['title'] ?? '',
                    'subtitle' => $_POST['subtitle'] ?? '',
                    'link' => $_POST['link'] ?? '',
                    'is_active' => isset($_POST['is_active']) ? 1 : 0,
                    'sort_order' => empty($_POST['sort_order']) ? 0 : $_POST['sort_order']
                ];

                if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                    // ปรับเป็น path ที่ถูกต้องสำหรับ public
                    $target_dir = ROOT_PATH . "/public/assets/images/banners/";
                    if (!is_dir($target_dir)) {
                        if (!mkdir($target_dir, 0777, true)) {
                            die("สร้างโฟลเดอร์สำหรับแบนเนอร์ไม่ได้ โปรดตรวจสอบ Permission (โฟลเดอร์ public/assets/images/banners)");
                        }
                    }

                    $file_extension = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
                    $new_filename = time() . '_' . rand(1000, 9999) . '.' . $file_extension;
                    $target_file = $target_dir . $new_filename;

                    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                        $data['image'] = $new_filename;
                    } else {
                        die("อัปโหลดไฟล์ล้มเหลว ย้ายไฟล์ไม่ได้ โปรดตรวจสอบ Permission ของโฟลเดอร์ public/assets");
                    }
                } elseif (isset($_FILES['image']) && $_FILES['image']['error'] != 4) { // ไม่ได้อัปโหลด Error=4
                    die("เกิดข้อผิดพลาดในการอัปโหลดไฟล์รหัส: " . $_FILES['image']['error'] . " (1 = ไฟล์ใหญ่เกินไป)");
                }

                if (!empty($_POST['id'])) {
                    $this->bannerModel->update($_POST['id'], $data);
                } else {
                    if (isset($data['image'])) {
                        $this->bannerModel->create($data);
                    } else {
                        die("ต้องอัปโหลดรูปภาพเพื่อสร้างแบนเนอร์ใหม่");
                    }
                }

                header("Location: " . BASE_URL . "/admin/banners");
                exit;
            } catch (\Exception $e) {
                die("เกิดข้อผิดพลาดฐานข้อมูล: " . $e->getMessage());
            }
        }
    }

    public function delete($id)
    {
        $banner = $this->bannerModel->find($id);
        if ($banner) {
            if ($banner['image']) {
                $image_path = ROOT_PATH . "/public/assets/images/banners/" . $banner['image'];
                if (file_exists($image_path)) {
                    unlink($image_path);
                }
            }
            $this->bannerModel->delete($id);
        }
        header("Location: " . BASE_URL . "/admin/banners");
        exit;
    }
}
