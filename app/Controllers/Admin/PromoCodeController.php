<?php
namespace App\Controllers\Admin;

use App\Models\PromoCode;
use App\Services\Security;

class PromoCodeController
{
    private $db;
    private $promoModel;

    public function __construct()
    {
        require_once ROOT_PATH . '/app/Legacy/LegacyDatabase.php';
        $this->db = (new \LegacyDatabase())->getConnection();
        $this->promoModel = new PromoCode($this->db);
    }

    public function index()
    {
        $promocodes = $this->promoModel->all();
        require_once APP_PATH . '/Views/admin/promocode/index.php';
    }

    public function add()
    {
        require_once APP_PATH . '/Views/admin/promocode/add_edit.php';
    }

    public function save()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->promoModel->create($_POST)) {
                header("Location: " . BASE_URL . "/admin/promocodes?msg=success");
                exit();
            } else {
                echo "เกิดข้อผิดพลาดในการบันทึกข้อมูล";
            }
        }
    }

    public function edit($id)
    {
        $promo = $this->promoModel->find($id);
        if (!$promo) {
            die("Promo code not found");
        }
        require_once APP_PATH . '/Views/admin/promocode/add_edit.php';
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->promoModel->update($id, $_POST)) {
                header("Location: " . BASE_URL . "/admin/promocodes?msg=updated");
                exit();
            } else {
                echo "เกิดข้อผิดพลาดในการอัปเดตข้อมูล";
            }
        }
    }

    public function delete($id)
    {
        if ($this->promoModel->delete($id)) {
            header("Location: " . BASE_URL . "/admin/promocodes?msg=deleted");
            exit();
        } else {
            echo "เกิดข้อผิดพลาดในการลบข้อมูล";
        }
    }
}
