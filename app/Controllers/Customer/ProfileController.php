<?php
namespace App\Controllers\Customer;

use App\Models\UserOld;
use App\Services\Auth;

class ProfileController
{
    private $db;
    private $userModel;

    public function __construct()
    {
        require_once ROOT_PATH . '/app/Legacy/LegacyDatabase.php';
        $this->db = (new \LegacyDatabase())->getConnection();
        $this->userModel = new UserOld($this->db);
    }

    public function index()
    {
        if (!Auth::check()) {
            header("Location: " . BASE_URL . "/login");
            exit();
        }

        $userId = $_SESSION['user_id'];
        $user = $this->userModel->findById($userId);

        // We will adapt the view to work for both admin and customer to avoid duplication
        // If the user is admin, maybe we show a slightly different layout, but for now a simple layout works.
        require_once APP_PATH . '/Views/customer/profile/index.php';
    }

    public function update()
    {
        if (!Auth::check()) {
            header("Location: " . BASE_URL . "/login");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user_id'];

            $data = [
                'name' => trim($_POST['name'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'phone' => trim($_POST['phone'] ?? ''),
                'address' => trim($_POST['address'] ?? ''),
            ];

            if (!empty($_POST['password'])) {
                if ($_POST['password'] === $_POST['confirm_password']) {
                    $data['password'] = $_POST['password'];
                } else {
                    header("Location: " . BASE_URL . "/profile?msg=password_mismatch");
                    exit();
                }
            }

            if ($this->userModel->updateProfile($userId, $data)) {
                // อัปเดตชื่อใน session ด้วยเผื่อเปลี่ยนชื่อ
                if (!empty($data['name'])) {
                    $_SESSION['username'] = $data['name'];
                }
                header("Location: " . BASE_URL . "/profile?msg=success");
                exit();
            } else {
                header("Location: " . BASE_URL . "/profile?msg=error");
                exit();
            }
        }
    }
}
