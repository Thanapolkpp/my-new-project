<?php
namespace App\Controllers\Admin;

use App\Models\Order;
use App\Models\Product;

class DashboardController
{
    private $db;
    private $orderModel;
    private $productModel;

    public function __construct()
    {
        require_once ROOT_PATH . '/app/Legacy/LegacyDatabase.php';
        $this->db = (new \LegacyDatabase())->getConnection();
        $this->orderModel = new Order($this->db);
        $this->productModel = new Product($this->db);
    }

    public function index()
    {
        $stats = $this->orderModel->getDashboardStats();
        $dailySales = $this->orderModel->getDailySales();
        $topProducts = $this->orderModel->getTopSellingProducts(5);

        require_once APP_PATH . '/Views/admin/dashboard/index.php';
    }
}
