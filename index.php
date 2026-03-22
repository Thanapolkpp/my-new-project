<?php
// เริ่มต้น Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. กำหนด Path พื้นฐาน
define('ROOT_PATH', __DIR__);
define('APP_PATH', ROOT_PATH . '/app');
// คำนวณหา BASE_URL แบบอัตโนมัติ ไม่ว่าจะอยู่โฟลเดอร์ไหน
$script_dirname = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$base_url = ($script_dirname === '/' || $script_dirname === '\\') ? '' : $script_dirname;
define('BASE_URL', $base_url . '/index.php');
// 2. Autoloader (ปรับปรุงใหม่: แปลง App ตัวใหญ่ ให้หาในโฟลเดอร์ app ตัวเล็ก)
spl_autoload_register(function ($class_name) {
    $path = str_replace('\\', '/', $class_name);
    // ถ้าขึ้นต้นด้วย App/ ให้เปลี่ยนเป็น app/ (แก้ปัญหาหา Class ไม่เจอ)
    if (strpos($path, 'App/') === 0) {
        $path = 'app/' . substr($path, 4);
    }

    $file = ROOT_PATH . '/' . $path . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// 3. Routing (Force using index.php in URLs for environments without URL Rewriting)
$request_uri = $_SERVER['REQUEST_URI'];

// ตัด parameter ? ออกก่อนเพื่อเอาแค่พาร์ทของ URL
$parsed_url = parse_url($request_uri);
$path = $parsed_url['path'] ?? '';

// เราจะหาค่าที่อยู่หลัง index.php
$index_pos = strpos($path, 'index.php');
if ($index_pos !== false) {
    // ถ้ามีส่วน index.php ให้ตัดเอาเฉพาะข้อความหลังจากนั้นมาเป็น request_uri
    $request_uri = substr($path, $index_pos + 9); // 9 คือความยาวคำว่า index.php
} else {
    // ถ้าไม่มีให้ถือว่าเป็น root
    $request_uri = '';
}

// เอา / ที่ต่อท้ายสุดออกและเพิ่ม / ข้างหน้าเสมอ (ถ้าไม่ว่าง)
$request_uri = '/' . trim($request_uri, '/');

// ถ้า URL ว่าง ให้ไปหน้า home
if ($request_uri == '' || $request_uri == '/') {
    $request_uri = '/home';
}

$segments = explode('/', trim($request_uri, '/'));

// 4. เส้นทาง (Routes) ที่รองรับฟีเจอร์ทั้งหมด
switch ($request_uri) {
    // ==========================================
    // ฝั่งลูกค้า (หน้าแรก และ สินค้า)
    // ==========================================
    case '/home':
    case '/products':
        require_once ROOT_PATH . '/app/Legacy/LegacyDatabase.php';
        $db = (new \LegacyDatabase())->getConnection();
        $productModel = new \App\Models\Product($db);
        $promoModel = new \App\Models\PromoCode($db);
        $bannerModel = new \App\Models\Banner($db);

        $banners = $bannerModel->getActiveBanners();

        $keyword = isset($_GET['search']) ? $_GET['search'] : '';
        // ตรวจสอบว่ามีการส่งพารามิเตอร์ใดๆ สำหรับค้นหาหรือฟิลเตอร์มาหรือไม่
        $hasFilters = isset($_GET['search']) || isset($_GET['min_price']) || isset($_GET['max_price']) ||
            isset($_GET['warranty']) || isset($_GET['certification']) ||
            isset($_GET['in_stock']) || isset($_GET['sort_price']);

        if ($hasFilters) {
            $products = $productModel->filter($_GET);
        } else {
            $products = $productModel->all();
        }

        $bestSellers = $productModel->getBestSellers(4);

        // Fetch active promo codes
        $stmt = $db->prepare("SELECT * FROM promo_codes WHERE is_active = 1 AND show_on_home = 1 AND (expiry_date IS NULL OR expiry_date >= NOW()) AND (usage_limit = 0 OR used_count < usage_limit) LIMIT 3");
        $stmt->execute();
        $activePromos = $stmt->fetchAll();

        require_once APP_PATH . '/Views/customer/shop/index.php';
        break;

    // ==========================================
    // ระบบ Auth (Login / Register / Logout)
    // ==========================================
    case '/login':
        $ctrl = new \App\Controllers\AuthController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ctrl->login();
        } else {
            $ctrl->showLogin();
        }
        break;

    case '/register':
        $ctrl = new \App\Controllers\AuthController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ctrl->register();
        } else {
            $ctrl->showRegister();
        }
        break;

    case '/logout':
        $ctrl = new \App\Controllers\AuthController();
        $ctrl->logout();
        break;

    case '/profile':
        (new \App\Controllers\Customer\ProfileController())->index();
        break;
    case '/profile/update':
        (new \App\Controllers\Customer\ProfileController())->update();
        break;

    // ==========================================
    // ระบบตะกร้าสินค้า (Feature 2 & 3)
    // ==========================================
    case '/cart':
        (new \App\Controllers\Customer\CartController())->index();
        break;
    case '/cart/add':
        (new \App\Controllers\Customer\CartController())->add();
        break;
    case '/cart/remove':
        (new \App\Controllers\Customer\CartController())->remove();
        break;
    case '/cart/update':
        (new \App\Controllers\Customer\CartController())->update();
        break;
    case '/cart/apply_promo':
        (new \App\Controllers\Customer\CartController())->applyPromo();
        break;
    case '/cart/remove_promo':
        (new \App\Controllers\Customer\CartController())->removePromo();
        break;
    case '/checkout':
        (new \App\Controllers\Customer\CartController())->checkout();
        break;
    case '/checkout/process':
        (new \App\Controllers\Customer\CartController())->processCheckout();
        break;
    case '/orders':
        (new \App\Controllers\Customer\CartController())->orders();
        break;
    case '/orders/cancel':
        if (isset($_GET['id'])) {
            (new \App\Controllers\Customer\CartController())->cancelOrder($_GET['id']);
        }
        break;

    // ==========================================
    // ฝั่ง Admin (Feature 7, 8, 9)
    // ==========================================
    case '/admin':
        (new \App\Controllers\Admin\DashboardController())->index();
        break;

    case '/admin/products':
        (new \App\Controllers\Admin\ProductController())->index();
        break;
    case '/admin/products/add':
        (new \App\Controllers\Admin\ProductController())->add();
        break;
    case '/admin/products/save':
        (new \App\Controllers\Admin\ProductController())->save();
        break;

    // Promo Codes Management
    case '/admin/promocodes':
        (new \App\Controllers\Admin\PromoCodeController())->index();
        break;
    case '/admin/promocodes/add':
        (new \App\Controllers\Admin\PromoCodeController())->add();
        break;
    case '/admin/promocodes/save':
        (new \App\Controllers\Admin\PromoCodeController())->save();
        break;

    // Orders Management
    case '/admin/orders':
        (new \App\Controllers\Admin\OrderController())->index();
        break;

    // Reviews Management
    case '/admin/reviews':
        (new \App\Controllers\Admin\ReviewController())->index();
        break;

    // Banners Management
    case '/admin/banners':
        (new \App\Controllers\Admin\BannerController())->index();
        break;
    case '/admin/banners/add':
        (new \App\Controllers\Admin\BannerController())->create();
        break;
    case '/admin/banners/save':
        (new \App\Controllers\Admin\BannerController())->save();
        break;


    // ==========================================
    // API สำหรับ AI (Feature 4 & 12)
    // ==========================================
    case '/api/chat':
        (new \App\Controllers\Customer\ChatbotController())->chat();
        exit();

    // ==========================================
    // การจัดการ Route ที่มี Parameter (เช่นแก้ไข, ลบ) และ 404
    // ==========================================
    default:
        // ตรวจสอบรูปแบบ URL ของ Admin
        if (count($segments) >= 3 && $segments[0] === 'admin') {
            $section = $segments[1];

            // --- จัดการ Products ---
            if ($section === 'products' && isset($segments[2])) {
                $ctrl = new \App\Controllers\Admin\ProductController();
                $action = $segments[2];
                $id = $segments[3] ?? null;

                if ($action === 'edit' && $id !== null) {
                    $ctrl->edit($id);
                    exit();
                }
                if ($action === 'update' && $id !== null) {
                    $ctrl->update($id);
                    exit();
                }
                if ($action === 'delete' && $id !== null) {
                    $ctrl->delete($id);
                    exit();
                }
            }

            // --- จัดการ Promo Codes ---
            if ($section === 'promocodes' && isset($segments[2])) {
                $ctrl = new \App\Controllers\Admin\PromoCodeController();
                $action = $segments[2];
                $id = $segments[3] ?? null;

                if ($action === 'edit' && $id !== null) {
                    $ctrl->edit($id);
                    exit();
                }
                if ($action === 'update' && $id !== null) {
                    $ctrl->update($id);
                    exit();
                }
                if ($action === 'delete' && $id !== null) {
                    $ctrl->delete($id);
                    exit();
                }
            }

            // --- จัดการ Orders ---
            if ($section === 'orders' && isset($segments[2])) {
                $ctrl = new \App\Controllers\Admin\OrderController();
                $action = $segments[2];
                $id = $segments[3] ?? null;

                if ($action === 'update_status' && $id !== null) {
                    $ctrl->updateStatus($id);
                    exit();
                }
                if ($action === 'view' && $id !== null) {
                    $ctrl->view($id);
                    exit();
                }
            }

            // --- จัดการ Reviews ---
            if ($section === 'reviews' && isset($segments[2])) {
                $ctrl = new \App\Controllers\Admin\ReviewController();
                $action = $segments[2];
                $id = $segments[3] ?? null;
                if ($action === 'delete' && $id !== null) {
                    $ctrl->delete($id);
                    exit();
                }
            }

            // --- จัดการ Banners ---
            if ($section === 'banners' && isset($segments[2])) {
                $ctrl = new \App\Controllers\Admin\BannerController();
                $action = $segments[2];
                $id = $segments[3] ?? null;

                if ($action === 'edit' && $id !== null) {
                    $ctrl->edit($id);
                    exit();
                }
                if ($action === 'delete' && $id !== null) {
                    $ctrl->delete($id);
                    exit();
                }
            }
        }

        // การจัดการ Route ที่เป็น Dynamic ของ Customer
        if ($segments[0] === 'product' && isset($segments[1])) {
            $ctrl = new \App\Controllers\Customer\ProductController();
            $action = $segments[1];
            $id = $segments[2] ?? null;

            if ($action === 'view' && $id !== null) {
                $ctrl->view($id);
                exit();
            }
            if ($action === 'review' && $id !== null) {
                $ctrl->addReview($id);
                exit();
            }
            if ($action === 'react' && $id !== null) {
                $ctrl->reactReview($id);
                exit();
            }
            if ($action === 'reply' && $id !== null) {
                $ctrl->replyReview($id);
                exit();
            }
        }
        
        // --- จัดการ Payment ---
        if ($segments[0] === 'payment' && isset($segments[1])) {
            $ctrl = new \App\Controllers\Customer\PaymentController();
            $action = $segments[1];
            $id = $segments[2] ?? null;

            if ($action === 'view' && $id !== null) {
                $ctrl->show($id);
                exit();
            }
            if ($action === 'upload' && $id !== null) {
                $ctrl->uploadSlip($id);
                exit();
            }
        }

        // หน้าต่าง 404 แบบสวยงาม
        http_response_code(404);
        echo "<!DOCTYPE html><html lang='th'><head><meta charset='UTF-8'><title>404 Not Found</title></head>";
        echo "<body style='display:flex; flex-direction:column; align-items:center; justify-content:center; height:100vh; background:#f8f9fa; font-family:sans-serif; margin:0;'>";
        echo "<h1 style='font-size:4rem; color:#dc3545; margin-bottom:10px;'>404</h1>";
        echo "<h2 style='color:#343a40; margin-bottom:30px;'>ไม่พบหน้าที่คุณต้องการ</h2>";
        echo "<a href='" . BASE_URL . "/home' style='padding:12px 24px; background:#0d6efd; color:white; text-decoration:none; border-radius:8px; font-weight:bold;'>กลับสู่หน้าหลัก</a>";
        echo "</body></html>";
        break;
}
