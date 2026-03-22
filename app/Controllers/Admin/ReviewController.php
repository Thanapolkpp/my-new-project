<?php
namespace App\Controllers\Admin;

use App\Models\Review;

class ReviewController
{
    private $db;
    private $reviewModel;

    public function __construct()
    {
        require_once ROOT_PATH . '/app/Legacy/LegacyDatabase.php';
        $this->db = (new \LegacyDatabase())->getConnection();
        $this->reviewModel = new Review($this->db);
    }

    public function index()
    {
        $reviews = $this->reviewModel->getAllReviews();
        require_once APP_PATH . '/Views/admin/review/index.php';
    }

    public function delete($id)
    {
        if ($this->reviewModel->delete($id)) {
            header("Location: " . BASE_URL . "/admin/reviews?msg=deleted");
            exit();
        } else {
            echo "เกิดข้อผิดพลาดในการลบรีวิว";
        }
    }
}
