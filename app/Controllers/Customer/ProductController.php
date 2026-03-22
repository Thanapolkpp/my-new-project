<?php
namespace App\Controllers\Customer;

use App\Models\Product;
use App\Models\Review;
use App\Models\Order;
use App\Services\Auth;

class ProductController
{
    private $db;
    private $productModel;
    private $reviewModel;

    public function __construct()
    {
        require_once ROOT_PATH . '/app/Legacy/LegacyDatabase.php';
        $this->db = (new \LegacyDatabase())->getConnection();
        $this->productModel = new Product($this->db);
        $this->reviewModel = new Review($this->db);
    }

    public function view($id)
    {
        $product = $this->productModel->find($id);

        if (!$product) {
            header("Location: " . BASE_URL . "/home");
            exit();
        }

        // ดึงรีวิวของสินค้านี้
        $stmt = $this->db->prepare("SELECT r.*, u.name as username FROM reviews r JOIN users u ON r.user_id = u.id WHERE r.product_id = :product_id ORDER BY r.created_at DESC");
        $stmt->execute([':product_id' => $id]);
        $reviews = $stmt->fetchAll();

        foreach ($reviews as &$r) {
            $stmt = $this->db->prepare("SELECT type, COUNT(*) as count FROM review_reactions WHERE review_id = ? GROUP BY type");
            $stmt->execute([$r['id']]);
            $r['reactions'] = $stmt->fetchAll(\PDO::FETCH_KEY_PAIR);

            $r['user_reaction'] = null;
            if (Auth::check()) {
                $stmt = $this->db->prepare("SELECT type FROM review_reactions WHERE review_id = ? AND user_id = ?");
                $stmt->execute([$r['id'], $_SESSION['user_id']]);
                $r['user_reaction'] = $stmt->fetchColumn();
            }

            $stmt = $this->db->prepare("
    SELECT rr.*, u.name as username, u.is_admin 
    FROM review_replies rr 
    JOIN users u ON rr.user_id = u.id 
    WHERE rr.review_id = ? 
    ORDER BY rr.created_at ASC
");
            $stmt->execute([$r['id']]);
            $r['replies'] = $stmt->fetchAll();
        }

        // ตรวจสอบว่าเคยซื้อไหมจะได้ให้รีวิวได้
        $hasPurchased = false;
        if (Auth::check()) {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM order_items oi JOIN orders o ON oi.order_id = o.id WHERE o.user_id = :user_id AND oi.product_id = :product_id");
            $stmt->execute([':user_id' => $_SESSION['user_id'], ':product_id' => $id]);
            $hasPurchased = $stmt->fetchColumn() > 0;
        }

        require_once APP_PATH . '/Views/customer/shop/detail.php';
    }

    public function addReview($id)
    {
        if (!Auth::check()) {
            header("Location: " . BASE_URL . "/login");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $rating = $_POST['rating'] ?? 5;
            $comment = $_POST['comment'] ?? '';

            $stmt = $this->db->prepare("INSERT INTO reviews (product_id, user_id, rating, comment, created_at, updated_at) VALUES (:product_id, :user_id, :rating, :comment, NOW(), NOW())");
            $stmt->execute([
                ':product_id' => $id,
                ':user_id' => $_SESSION['user_id'],
                ':rating' => $rating,
                ':comment' => $comment
            ]);

            header("Location: " . BASE_URL . "/product/view/" . $id . "?msg=review_added");
            exit();
        }
    }

    public function reactReview($review_id)
    {
        if (!Auth::check()) {
            header("Location: " . BASE_URL . "/login");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $type = $_POST['type'] ?? 'like';
            $user_id = $_SESSION['user_id'];

            $stmt = $this->db->prepare("SELECT * FROM review_reactions WHERE review_id = ? AND user_id = ?");
            $stmt->execute([$review_id, $user_id]);
            $existing = $stmt->fetch();

            if ($existing) {
                if ($existing['type'] === $type) {
                    $stmt = $this->db->prepare("DELETE FROM review_reactions WHERE id = ?");
                    $stmt->execute([$existing['id']]);
                } else {
                    $stmt = $this->db->prepare("UPDATE review_reactions SET type = ? WHERE id = ?");
                    $stmt->execute([$type, $existing['id']]);
                }
            } else {
                $stmt = $this->db->prepare("INSERT INTO review_reactions (review_id, user_id, type) VALUES (?, ?, ?)");
                $stmt->execute([$review_id, $user_id, $type]);
            }

            $stmt = $this->db->prepare("SELECT product_id FROM reviews WHERE id = ?");
            $stmt->execute([$review_id]);
            $rev = $stmt->fetch();
            if ($rev) {
                header("Location: " . BASE_URL . "/product/view/" . $rev['product_id']);
                exit();
            }
        }
    }

    public function replyReview($review_id)
    {
        if (!Auth::check()) {
            header("Location: " . BASE_URL . "/login");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $comment = trim($_POST['comment'] ?? '');
            $user_id = $_SESSION['user_id'];

            if (!empty($comment)) {
                $stmt = $this->db->prepare("INSERT INTO review_replies (review_id, user_id, comment) VALUES (?, ?, ?)");
                $stmt->execute([$review_id, $user_id, $comment]);
            }

            $stmt = $this->db->prepare("SELECT product_id FROM reviews WHERE id = ?");
            $stmt->execute([$review_id]);
            $rev = $stmt->fetch();
            if ($rev) {
                header("Location: " . BASE_URL . "/product/view/" . $rev['product_id']);
                exit();
            }
        }
    }
}
