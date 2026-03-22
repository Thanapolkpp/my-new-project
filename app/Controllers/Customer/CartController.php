<?php
namespace App\Controllers\Customer;

use App\Models\Product;
use App\Models\Order;
use App\Services\Auth;

class CartController
{
    private $db;
    private $productModel;
    private $orderModel;

    public function __construct()
    {
        require_once ROOT_PATH . '/app/Legacy/LegacyDatabase.php';
        $this->db = (new \LegacyDatabase())->getConnection();
        $this->productModel = new Product($this->db);
        $this->orderModel = new Order($this->db);

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    public function index()
    {
        $cartItems = $_SESSION['cart'];
        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        require_once APP_PATH . '/Views/customer/shop/cart.php';
    }

    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = $_POST['product_id'];
            $product = $this->productModel->find($productId);

            if ($product) {
                // Check if already in cart
                $found = false;
                foreach ($_SESSION['cart'] as &$item) {
                    if ($item['id'] == $productId) {
                        $item['quantity'] += 1;
                        $found = true;
                        break;
                    }
                }

                if (!$found) {
                    $price = $product['price'] - ($product['discount'] ?? 0);
                    $_SESSION['cart'][] = [
                        'id' => $product['id'],
                        'product_name' => $product['product_name'],
                        'price' => $price,
                        'img' => $product['img'],
                        'quantity' => 1
                    ];
                }

                if (isset($_POST['ajax']) && $_POST['ajax'] == 1) {
                    header('Content-Type: application/json');
                    echo json_encode([
                        'success' => true,
                        'message' => 'เพิ่มสินค้าลงตะกร้าเรียบร้อย!',
                        'cartCount' => count($_SESSION['cart'])
                    ]);
                    exit();
                }

                header("Location: " . BASE_URL . "/cart");
                exit();
            }
        }
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = $_POST['product_id'];
            $action = $_POST['action']; // 'increase' or 'decrease'

            foreach ($_SESSION['cart'] as $key => &$item) {
                if ($item['id'] == $productId) {
                    if ($action == 'increase') {
                        $item['quantity']++;
                    } elseif ($action == 'decrease') {
                        $item['quantity']--;
                        if ($item['quantity'] <= 0) {
                            unset($_SESSION['cart'][$key]);
                        }
                    }
                    break;
                }
            }
            $_SESSION['cart'] = array_values($_SESSION['cart']); // reindex
        }
        header("Location: " . BASE_URL . "/cart");
        exit();
    }

    public function remove()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = $_POST['product_id'];
            foreach ($_SESSION['cart'] as $key => $item) {
                if ($item['id'] == $productId) {
                    unset($_SESSION['cart'][$key]);
                    break;
                }
            }
            $_SESSION['cart'] = array_values($_SESSION['cart']);
        }
        header("Location: " . BASE_URL . "/cart");
        exit();
    }

    public function checkout()
    {
        if (!Auth::check()) {
            $_SESSION['redirect_after_login'] = BASE_URL . '/checkout';
            header("Location: " . BASE_URL . "/login");
            exit();
        }

        if (empty($_SESSION['cart'])) {
            header("Location: " . BASE_URL . "/home");
            exit();
        }

        $cartItems = $_SESSION['cart'];
        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        $userModel = new \App\Models\UserOld($this->db);
        $user = $userModel->findById($_SESSION['user_id']);

        require_once APP_PATH . '/Views/customer/shop/checkout.php';
    }

    public function applyPromo()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $code = $_POST['promo_code'] ?? '';
            $stmt = $this->db->prepare("SELECT * FROM promo_codes WHERE code = :code AND is_active = 1");
            $stmt->execute([':code' => $code]);
            $promo = $stmt->fetch();

            if ($promo) {
                if ($promo['expiry_date'] && strtotime($promo['expiry_date']) < time()) {
                    header("Location: " . BASE_URL . "/cart?err_promo=1");
                    exit();
                }
                if ($promo['usage_limit'] > 0 && $promo['used_count'] >= $promo['usage_limit']) {
                    header("Location: " . BASE_URL . "/cart?err_promo=1");
                    exit();
                }

                $_SESSION['promo_code'] = $promo['code'];
                $_SESSION['promo_id'] = $promo['id'];

                $total = 0;
                foreach ($_SESSION['cart'] as $item) {
                    $total += $item['price'] * $item['quantity'];
                }

                $discount = $promo['discount_amount'];
                if ($promo['is_percentage']) {
                    $discount = ($total * $discount) / 100;
                }
                $_SESSION['promo_discount'] = $discount;
                header("Location: " . BASE_URL . "/cart");
                exit();
            } else {
                header("Location: " . BASE_URL . "/cart?err_promo=1");
                exit();
            }
        }
    }

    public function removePromo()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            unset($_SESSION['promo_code']);
            unset($_SESSION['promo_id']);
            unset($_SESSION['promo_discount']);
            header("Location: " . BASE_URL . "/cart");
            exit();
        }
    }

    public function processCheckout()
    {
        if (!Auth::check()) {
            header("Location: " . BASE_URL . "/login");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_SESSION['cart'])) {
            $shippingMethod = $_POST['shipping_method'];
            $paymentMethod = $_POST['payment_method'];

            $total = 0;
            foreach ($_SESSION['cart'] as $item) {
                $total += $item['price'] * $item['quantity'];
            }

            $discount_total = isset($_SESSION['promo_discount']) ? $_SESSION['promo_discount'] : 0;
            $promo_code = isset($_SESSION['promo_code']) ? $_SESSION['promo_code'] : null;
            $net_total = $total - $discount_total;
            if ($net_total < 0)
                $net_total = 0;

            // Fetch user info for address and phone
            $userModel = new \App\Models\UserOld($this->db);
            $user = $userModel->findById($_SESSION['user_id']);
            $userAddress = $user['address'] ?? null;
            $userPhone = $user['phone'] ?? null;

            // Create Order
            try {
                // start transaction
                $this->db->beginTransaction();

                // update orderModel logic or just use direct query since we changed db structure 
                $sql = "INSERT INTO orders (user_id, total_price, shipping_method, payment_status, status, promo_code, discount_total, address, phone, created_at, updated_at) 
                        VALUES (:user_id, :total, :shipping, :payment_status, 'pending', :promo_code, :discount_total, :address, :phone, NOW(), NOW())";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([
                    ':user_id' => $_SESSION['user_id'],
                    ':total' => $net_total,
                    ':shipping' => $shippingMethod,
                    ':payment_status' => $paymentMethod,
                    ':promo_code' => $promo_code,
                    ':discount_total' => $discount_total,
                    ':address' => $userAddress,
                    ':phone' => $userPhone
                ]);
                $orderId = $this->db->lastInsertId();

                foreach ($_SESSION['cart'] as $item) {
                    $this->orderModel->addItem($orderId, $item['id'], $item['quantity'], $item['price']);

                    // Deduct stock only, since sales_count column is removed
                    $stmt = $this->db->prepare("UPDATE products SET stock = stock - :quantity WHERE id = :id");
                    $stmt->execute([
                        ':quantity' => $item['quantity'],
                        ':id' => $item['id']
                    ]);
                }

                // If promo code used, increase usage count
                if (isset($_SESSION['promo_id'])) {
                    $stmt = $this->db->prepare("UPDATE promo_codes SET used_count = used_count + 1 WHERE id = :id");
                    $stmt->execute([':id' => $_SESSION['promo_id']]);
                }

                $this->db->commit();

                // Clear cart & promo code
                $_SESSION['cart'] = [];
                unset($_SESSION['promo_code']);
                unset($_SESSION['promo_id']);
                unset($_SESSION['promo_discount']);

                if ($paymentMethod === 'COD') {
                    header("Location: " . BASE_URL . "/orders?msg=success");
                } else {
                    header("Location: " . BASE_URL . "/payment/view/" . $orderId);
                }
                exit();
            } catch (\Exception $e) {
                $this->db->rollBack();
                // Error handling
                die("เกิดข้อผิดพลาดในการสร้างคำสั่งซื้อ: " . $e->getMessage());
            }
        }
    }

    public function orders()
    {
        if (!Auth::check()) {
            header("Location: " . BASE_URL . "/login");
            exit();
        }

        $orders = $this->orderModel->getUserOrders($_SESSION['user_id']);
        require_once APP_PATH . '/Views/customer/shop/orders.php';
    }

    public function cancelOrder($id)
    {
        if (!Auth::check()) {
            header("Location: " . BASE_URL . "/login");
            exit();
        }

        $order = $this->orderModel->getOrderById($id);
        if ($order && $order['user_id'] == $_SESSION['user_id'] && in_array($order['status'], ['pending', 'paid'])) {
            $this->orderModel->updateStatus($id, 'cancelled');
            header("Location: " . BASE_URL . "/orders?msg=cancelled");
            exit();
        }
        header("Location: " . BASE_URL . "/orders?msg=error");
        exit();
    }
}
