<?php
namespace App\Controllers;
use App\Models\UserOld;
use App\Services\Auth;

class AuthController
{
    private $db;
    private $userModel;

    public function __construct()
    {
        require_once ROOT_PATH . '/app/Legacy/LegacyDatabase.php';
        $this->db = (new \LegacyDatabase())->getConnection();
        $this->userModel = new UserOld($this->db);
    }

    public function showLogin()
    {
        require_once APP_PATH . '/Views/auth/login.php';
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $login = $_POST['email'] ?? $_POST['login_id']; // Accept both for compatibility
            $user = $this->userModel->findByLogin($login);

            if ($user && password_verify($_POST['password'], $user['password'])) {
                Auth::login($user);
                if ($user['is_admin'] == 1) {
                    header("Location: " . BASE_URL . "/admin/products");
                    exit();
                } else {
                    header("Location: " . BASE_URL . "/home");
                    exit();
                }
            }
            echo "<script>alert('ข้อมูลประจำตัวหรือรหัสผ่านไม่ถูกต้อง'); window.history.back();</script>";
        }
    }

    public function showRegister()
    {
        require_once APP_PATH . '/Views/auth/register.php';
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->userModel->findByEmail($_POST['email'])) {
                echo "<script>alert('อีเมลนี้ถูกใช้งานแล้ว'); window.history.back();</script>";
                return;
            }

            if ($this->userModel->create($_POST)) {
                echo "<script>alert('สมัครสมาชิกสำเร็จ!'); window.location='" . BASE_URL . "/login';</script>";
            } else {
                echo "<script>alert('เกิดข้อผิดพลาดในการสมัครสมาชิก'); window.history.back();</script>";
            }
        }
    }

    public function logout()
    {
        Auth::logout();
        header("Location: " . BASE_URL . "/login");
        exit();
    }

    public function requestOtp()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $user = $this->userModel->findByEmail($email);

            if (!$user) {
                echo json_encode(['success' => false, 'message' => 'ไม่พบอีเมลนี้ในระบบ']);
                return;
            }

            $otp = rand(100000, 999999);
            $this->userModel->setOtp($email, $otp);

            try {
                \Illuminate\Support\Facades\Mail::raw("สวัสดีครับ!\n\nรหัส OTP สำหรับเข้าสู่ระบบร้าน Cute boy IT shop คือ: $otp\n\nกรุณากรอกรหัสนี้ภายใน 10 นาทีนะครับ", function ($message) use ($email) {
    $message->to($email)
        ->subject('รหัสยืนยันตัวตน (OTP) - Cute boy IT shop');
});

                echo json_encode(['success' => true, 'message' => 'ส่งรหัส OTP เรียบร้อยแล้ว โปรดตรวจสอบที่อีเมลของคุณ']);
            } catch (\Exception $e) {
                echo json_encode(['success' => false, 'message' => 'เกิดข้อผิดพลาดในการส่งอีเมล: ' . $e->getMessage()]);
            }
        }
    }

    public function verifyOtpLogin()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $otp = $_POST['otp'];

            error_log("Attempting OTP verification for: $email with OTP: $otp");

            $user = $this->userModel->verifyOtp($email, $otp);

            if ($user) {
                error_log("OTP verified successfully for: $email");
                $this->userModel->clearOtp($email);
                Auth::login($user);
                
                if ($user['is_admin'] == 1) {
                    echo json_encode(['success' => true, 'redirect' => BASE_URL . "/admin/products"]);
                } else {
                    echo json_encode(['success' => true, 'redirect' => BASE_URL . "/home"]);
                }
                return;
            }

            error_log("OTP verification failed for: $email");
            echo json_encode(['success' => false, 'message' => 'รหัส OTP ไม่ถูกต้องหรือหมดอายุ']);
        }
    }
}
