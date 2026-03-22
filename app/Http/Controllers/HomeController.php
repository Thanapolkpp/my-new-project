<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\PromoCode;
use App\Models\Banner;
// Use global LegacyDatabase via require_once below

class HomeController extends Controller
{
    public function index()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        // Define constants if they don't exist
        if (!defined('ROOT_PATH')) define('ROOT_PATH', base_path());
        if (!defined('APP_PATH')) define('APP_PATH', base_path('app'));
        if (!defined('BASE_URL')) define('BASE_URL', url('/') . '/index.php');

        require_once ROOT_PATH . '/app/Legacy/LegacyDatabase.php';
        $db = (new \LegacyDatabase())->getConnection();
        
        $productModel = new \App\Models\Product($db);
        $bannerModel = new \App\Models\Banner($db);

        $banners = $bannerModel->getActiveBanners();

        $keyword = request('search', '');
        
        $hasFilters = request()->hasAny(['search', 'min_price', 'max_price', 'warranty', 'certification', 'in_stock', 'sort_price']);

        if ($hasFilters) {
            $products = $productModel->filter(request()->all());
        } else {
            $products = $productModel->all();
        }

        $bestSellers = $productModel->getBestSellers(4);

        // Fetch active promo codes
        $stmt = $db->prepare("SELECT * FROM promo_codes WHERE is_active = 1 AND show_on_home = 1 AND (expiry_date IS NULL OR expiry_date >= NOW()) AND (usage_limit = 0 OR used_count < usage_limit) LIMIT 3");
        $stmt->execute();
        $activePromos = $stmt->fetchAll();

        // Instead of require_once, we can use include or even better, move it to a proper blade
        // But for now, we follow the user's hybrid approach
        ob_start();
        include APP_PATH . '/Views/customer/shop/index.php';
        return ob_get_clean();
    }
}
