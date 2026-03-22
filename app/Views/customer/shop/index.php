<?php use App\Services\Auth; ?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cute boy ITshop</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Kanit:wght@300;400;500;600&family=Outfit:wght@500;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #2563eb;
            --accent-cyan: #06b6d4;
            --surface-bg: #f8fafc;
            --card-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.04), 0 4px 6px -2px rgba(0, 0, 0, 0.02);
            --card-hover-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        body {
            font-family: 'Inter', 'Kanit', sans-serif;
            background-color: var(--surface-bg);
            color: #1e293b;
        }

        .hero-section {
            background: radial-gradient(circle at top right, #3b82f6, #1d4ed8);
            color: white;
            padding: 80px 0;
            position: relative;
            overflow: hidden;
            border-radius: 0 0 50px 50px;
        }
        .hero-section::after {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: url('https://www.transparenttextures.com/patterns/cubes.png');
            opacity: 0.1;
            pointer-events: none;
        }

        .product-card {
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 20px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            background: #ffffff;
            overflow: hidden;
            box-shadow: var(--card-shadow);
        }

        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--card-hover-shadow);
            border-color: rgba(59, 130, 246, 0.3);
        }

        .product-card img {
            transition: transform 0.6s ease;
        }
        .product-card:hover img {
            transform: scale(1.08);
        }

        .price-tag {
            font-family: 'Outfit', sans-serif;
            color: var(--primary-blue);
            font-weight: 800;
            font-size: 1.4rem;
            letter-spacing: -0.5px;
        }
        .old-price {
            font-size: 0.9rem;
            text-decoration: line-through;
            color: #94a3b8;
            margin-right: 8px;
        }

        .discount-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: #ef4444;
            color: white;
            padding: 6px 14px;
            border-radius: 12px;
            font-weight: 800;
            font-size: 0.75rem;
            z-index: 2;
            box-shadow: 0 4px 10px rgba(239, 68, 68, 0.4);
            font-family: 'Outfit', sans-serif;
        }

        .carousel-item {
            height: 480px;
        }
        .carousel-item img {
            object-fit: cover;
            height: 100%;
            filter: brightness(0.7);
        }
        .carousel-caption {
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-radius: 30px;
            padding: 40px;
            bottom: 12%;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
            max-width: 600px;
            margin: 0 auto;
        }

        .btn-add-cart {
            border-radius: 14px;
            padding: 10px 20px;
            font-weight: 700;
            transition: all 0.3s;
            background: #f1f5f9;
            color: #475569;
            border: none;
        }
        .btn-add-cart:hover {
            background: var(--primary-blue);
            color: white;
            transform: scale(1.05);
        }

        .filter-sidebar {
            background: #ffffff;
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 28px;
            padding: 2rem;
            box-shadow: var(--card-shadow);
        }

        .filter-label {
            font-size: 0.85rem;
            color: #495057;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .filter-label i {
            color: #0d6efd;
        }

        .price-input-group .input-group-text {
            background-color: transparent;
            border-right: none;
            color: #6c757d;
        }

        .price-input-group .form-control {
            border-left: none;
            font-weight: 600;
        }

        .custom-check .form-check-input {
            width: 1.2em;
            height: 1.2em;
            margin-top: 0.2em;
            cursor: pointer;
        }

        .custom-check .form-check-label {
            cursor: pointer;
            padding-left: 5px;
            font-weight: 500;
        }

        .btn-apply-filter {
            background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);
            border: none;
            padding: 12px;
            border-radius: 12px;
            font-weight: 700;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }

        .btn-apply-filter:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3);
        }

        .sticky-filter {
            top: 100px;
            z-index: 100;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <?php require_once APP_PATH . '/Views/customer/nav.php'; ?>

    <!-- Banner Carousel -->
    <div id="homeCarousel" class="carousel slide carousel-fade mb-5 shadow-sm" data-bs-ride="carousel">
        <?php if (!empty($banners)): ?>
            <div class="carousel-indicators">
                <?php foreach ($banners as $index => $banner): ?>
                    <button type="button" data-bs-target="#homeCarousel" data-bs-slide-to="<?= $index ?>"
                        class="<?= $index === 0 ? 'active' : '' ?>" aria-label="Slide <?= $index + 1 ?>"></button>
                <?php endforeach; ?>
            </div>
            <div class="carousel-inner rounded-bottom-4 overflow-hidden">
                <?php foreach ($banners as $index => $banner): ?>
                    <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>" data-bs-interval="5000">
                        <?php if (!empty($banner['link'])): ?>
                            <a href="<?= htmlspecialchars($banner['link']) ?>">
                            <?php endif; ?>
                            <img src="<?= str_replace('index.php', '', BASE_URL) ?>public/assets/images/banners/<?= htmlspecialchars($banner['image']) ?>"
                                class="d-block w-100" alt="<?= htmlspecialchars($banner['title'] ?? 'Banner') ?>"
                                style="aspect-ratio: 1920/1080; object-fit: cover; max-height: 80vh;">
                            <?php if (!empty($banner['link'])): ?>
                            </a>
                        <?php endif; ?>

                        <?php if (!empty($banner['title']) || !empty($banner['subtitle'])): ?>
                            <div class="carousel-caption d-none d-md-block text-start mb-5 pb-4 px-5">
                                <?php if (!empty($banner['title'])): ?>
                                    <h2 class="display-4 fw-bold text-white mb-3"><?= htmlspecialchars($banner['title']) ?></h2>
                                <?php endif; ?>
                                <?php if (!empty($banner['subtitle'])): ?>
                                    <p class="lead text-white"><?= htmlspecialchars($banner['subtitle']) ?></p>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php if (count($banners) > 1): ?>
                <button class="carousel-control-prev" type="button" data-bs-target="#homeCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#homeCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            <?php endif; ?>
        <?php else: ?>
            <!-- Default Placeholder Banner if no banners are in DB -->
            <div class="carousel-inner rounded-bottom-4 overflow-hidden">
                <div class="carousel-item active" data-bs-interval="5000">
                    <div class="d-flex align-items-center justify-content-center bg-secondary"
                        style="height: 400px; color: #fff;">
                        <h3>ยินดีต้อนรับสู่ cute boy ITshop</h3>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Hero / Campaign Section -->
    <div id="promo-section" class="hero-section mb-5">
        <div class="container">
            <h1 class="display-4 fw-bold">Upgrade Your Power</h1>
            <p class="lead">ค้นพบการ์ดจอและอุปกรณ์คอมพิวเตอร์ที่ดีที่สุดในราคาพิเศษ</p>
        </div>
    </div>

    <div class="container mb-5">
        <!-- Promo Codes Section (Moved out of the hero box) -->
        <?php if (!empty($activePromos)): ?>
            <h3 class="mb-4 fw-bold text-primary"><i class="bi bi-ticket-perforated"></i> โค้ดส่วนลดพิเศษ</h3>
            <div class="row justify-content-center mb-5">
                <?php foreach ($activePromos as $promo): ?>
                    <div class="col-md-4 mb-3">
                        <div class="card shadow-sm border-0 border-start border-warning border-4">
                            <div class="card-body py-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="mb-0 text-dark fw-bold"><i class="bi bi-tag-fill text-warning"></i>
                                            <?= htmlspecialchars($promo['code']) ?></h5>
                                        <small class="text-muted">
                                            ลดทันที
                                            <span class="text-danger fw-bold fs-6">
                                                <?= number_format($promo['discount_amount']) ?>
                                                <?= $promo['is_percentage'] ? '%' : '฿' ?>
                                            </span>
                                        </small>
                                    </div>
                                    <button class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-bold"
                                        onclick="navigator.clipboard.writeText('<?= $promo['code'] ?>'); alert('คัดลอกโค้ดแล้ว!');">
                                        เก็บโค้ด
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Best Sellers -->
        <?php if (!empty($bestSellers)): ?>
            <h3 class="mb-4 fw-bold"><i class="bi bi-fire text-danger"></i> สินค้าขายดี (Best Sellers)</h3>
            <div class="row g-4 mb-5">
                <?php foreach ($bestSellers as $item): ?>
                    <div class="col-md-3">
                        <div class="card h-100 product-card relative">
                            <?php if ($item['discount'] > 0): ?>
                                <span class="discount-badge">-
                                    <?= number_format($item['discount']) ?> ฿
                                </span>
                            <?php endif; ?>
                            <a href="<?= BASE_URL ?>/product/view/<?= $item['id'] ?>" class="text-decoration-none">
                                <img src="<?= htmlspecialchars($item['img'] ?: 'https://via.placeholder.com/300x200?text=No+Image') ?>"
                                    class="card-img-top" alt="<?= htmlspecialchars($item['product_name']) ?>"
                                    style="height: 200px; object-fit: cover;">
                            </a>
                            <div class="card-body d-flex flex-column">
                                <a href="<?= BASE_URL ?>/product/view/<?= $item['id'] ?>" class="text-decoration-none">
                                    <h5 class="card-title text-truncate fw-bold text-dark"
                                        title="<?= htmlspecialchars($item['product_name']) ?>">
                                        <?= htmlspecialchars($item['product_name']) ?>
                                    </h5>
                                </a>
                                <p class="card-text text-muted small text-truncate">
                                    <?= htmlspecialchars($item['detail']) ?>
                                </p>
                                <div class="mt-auto">
                                    <?php if ($item['discount'] > 0): ?>
                                        <span class="text-muted text-decoration-line-through small">
                                            <?= number_format($item['price'], 2) ?> ฿
                                        </span>
                                        <p class="price-tag mb-2">
                                            <?= number_format($item['price'] - $item['discount'], 2) ?> ฿
                                        </p>
                                    <?php else: ?>
                                        <p class="price-tag mb-2">
                                            <?= number_format($item['price'], 2) ?> ฿
                                        </p>
                                    <?php endif; ?>
                                    <form action="<?= BASE_URL ?>/cart/add" method="POST" class="d-inline">
                                        <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                                        <?php if ($item['stock'] > 0): ?>
                                            <button type="submit" class="btn btn-outline-primary w-100"><i
                                                    class="bi bi-cart-plus"></i> เพิ่มลงตะกร้า</button>
                                        <?php else: ?>
                                            <button type="button" class="btn btn-outline-secondary w-100" disabled><i
                                                    class="bi bi-cart-x"></i> สินค้าหมด</button>
                                        <?php endif; ?>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <hr>
        <?php endif; ?>

        <!-- All Products with Filters -->
        <h3 class="fw-bold mb-4">
            <?= $keyword ? 'ผลการค้นหา: ' . htmlspecialchars($keyword) : 'สินค้าทั้งหมด' ?>
        </h3>

        <div class="row">
            <!-- Filter Sidebar -->
            <div class="col-md-3 mb-4">
                <div class="card border-0 shadow-sm sticky-top sticky-filter filter-sidebar">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-4 pb-2 border-bottom">
                            <h5 class="fw-bold mb-0"><i class="bi bi-sliders2-vertical text-primary me-2"></i>
                                ตัวกรองอัจฉริยะ</h5>
                        </div>

                        <form action="<?= BASE_URL ?>/products" method="GET">
                            <!-- ถ้ามีการค้นหาค้างไว้ -->
                            <?php if (isset($_GET['search']) && $_GET['search'] !== ''): ?>
                                <input type="hidden" name="search" value="<?= htmlspecialchars($_GET['search']) ?>">
                            <?php endif; ?>

                            <!-- ช่วงราคา -->
                            <div class="mb-4">
                                <label class="filter-label fw-bold"><i class="bi bi-wallet2"></i> ช่วงราคา (บาท)</label>
                                <div class="price-input-group">
                                    <div class="input-group input-group-sm mb-2 shadow-sm rounded">
                                        <span class="input-group-text bg-light border-end-0">฿</span>
                                        <input type="number" name="min_price"
                                            class="form-control border-start-0 bg-light" placeholder="ราคาต่ำสุด"
                                            value="<?= htmlspecialchars($_GET['min_price'] ?? '') ?>">
                                    </div>
                                    <div class="input-group input-group-sm shadow-sm rounded">
                                        <span class="input-group-text bg-light border-end-0">฿</span>
                                        <input type="number" name="max_price"
                                            class="form-control border-start-0 bg-light" placeholder="ราคาสูงสุด"
                                            value="<?= htmlspecialchars($_GET['max_price'] ?? '') ?>">
                                    </div>
                                </div>
                            </div>

                            <!-- การรับประกัน -->
                            <div class="mb-4">
                                <label class="filter-label fw-bold"><i class="bi bi-shield-check"></i>
                                    บริการเสริม</label>
                                <div class="form-check custom-check mb-2">
                                    <input class="form-check-input border-primary" type="checkbox" name="warranty"
                                        value="1" id="filterWarranty" <?= isset($_GET['warranty']) ? 'checked' : '' ?>>
                                    <label class="form-check-label small text-dark" for="filterWarranty">
                                        มีรับประกันคุณภาพสินค้า
                                    </label>
                                </div>
                                <div class="form-check custom-check">
                                    <input class="form-check-input border-primary" type="checkbox" name="in_stock"
                                        value="1" id="filterStock" <?= isset($_GET['in_stock']) ? 'checked' : '' ?>>
                                    <label class="form-check-label small text-dark" for="filterStock">
                                        พร้อมส่งเดี๋ยวนี้ (In Stock)
                                    </label>
                                </div>
                            </div>

                            <!-- มาตรฐาน -->
                            <div class="mb-4">
                                <label class="filter-label fw-bold"><i class="bi bi-patch-check"></i>
                                    มาตรฐานไฟฟ้า</label>
                                <div class="input-group input-group-sm shadow-sm rounded">
                                    <span class="input-group-text bg-light border-end-0"><i
                                            class="bi bi-lightning"></i></span>
                                    <input type="text" name="certification" class="form-control border-start-0 bg-light"
                                        placeholder="เช่น 80+ Gold, Bronze..."
                                        value="<?= htmlspecialchars($_GET['certification'] ?? '') ?>">
                                </div>
                            </div>

                            <!-- การจัดเรียง -->
                            <div class="mb-4">
                                <label class="filter-label fw-bold"><i class="bi bi-sort-down"></i>
                                    จัดเรียงสินค้า</label>
                                <select name="sort_price"
                                    class="form-select form-select-sm shadow-sm bg-light border-0">
                                    <option value="">ความนิยมล่าสุด</option>
                                    <option value="asc" <?= (isset($_GET['sort_price']) && $_GET['sort_price'] == 'asc') ? 'selected' : '' ?>>
                                        ราคา: น้อย 👉 มาก
                                    </option>
                                    <option value="desc" <?= (isset($_GET['sort_price']) && $_GET['sort_price'] == 'desc') ? 'selected' : '' ?>>
                                        ราคา: มาก 👉 น้อย
                                    </option>
                                </select>
                            </div>

                            <div class="d-grid gap-2 pt-2">
                                <button type="submit" class="btn btn-primary btn-apply-filter text-white shadow">
                                    <i class="bi bi-funnel-fill me-2"></i> ค้นหาแบบละเอียด
                                </button>
                                <a href="<?= BASE_URL ?>/products"
                                    class="btn btn-link btn-sm text-decoration-none text-muted text-center mt-1">
                                    <i class="bi bi-arrow-counterclockwise"></i> ล้างการตั้งค่าทั้งหมด
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Product Grid -->
            <div class="col-md-9">
                <?php if (empty($products)): ?>
                    <div class="alert alert-warning text-center">ไม่พบสินค้าที่คุณค้นหา หรือลองเปลี่ยนตัวกรองดูใหม่ครับ
                    </div>
                <?php else: ?>
                    <div class="row g-4">
                        <?php foreach ($products as $item): ?>
                            <div class="col-md-4">
                                <div class="card h-100 product-card relative">
                                    <?php if ($item['discount'] > 0): ?>
                                        <span class="discount-badge">-
                                            <?= number_format($item['discount']) ?> ฿
                                        </span>
                                    <?php endif; ?>
                                    <a href="<?= BASE_URL ?>/product/view/<?= $item['id'] ?>" class="text-decoration-none">
                                        <img src="<?= htmlspecialchars($item['img'] ?: 'https://via.placeholder.com/300x200?text=No+Image') ?>"
                                            class="card-img-top" alt="<?= htmlspecialchars($item['product_name']) ?>"
                                            style="height: 200px; object-fit: cover;">
                                    </a>
                                    <div class="card-body d-flex flex-column p-4">
                                        <a href="<?= BASE_URL ?>/product/view/<?= $item['id'] ?>" class="text-decoration-none">
                                            <h6 class="card-title text-truncate fw-bold text-dark mb-2"
                                                title="<?= htmlspecialchars($item['product_name']) ?>" style="font-size: 1.05rem;">
                                                <?= htmlspecialchars($item['product_name']) ?>
                                            </h6>
                                        </a>
                                        <div class="mt-auto">
                                            <div class="d-flex align-items-center mb-3">
                                                <?php if ($item['discount'] > 0): ?>
                                                    <span class="old-price">฿<?= number_format($item['price']) ?></span>
                                                    <span class="price-tag">฿<?= number_format($item['price'] - $item['discount']) ?></span>
                                                <?php else: ?>
                                                    <span class="price-tag">฿<?= number_format($item['price']) ?></span>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <form action="<?= BASE_URL ?>/cart/add" method="POST" class="d-flex gap-2">
                                                <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                                                <?php if ($item['stock'] > 0): ?>
                                                    <button type="submit" class="btn btn-add-cart flex-grow-1 shadow-sm"><i
                                                            class="bi bi-cart3 me-1"></i> ใส่ตะกร้า</button>
                                                <?php else: ?>
                                                    <button type="button" class="btn btn-outline-secondary flex-grow-1" disabled><i
                                                            class="bi bi-cart-x"></i> หมด</button>
                                                <?php endif; ?>
                                                <a href="<?= BASE_URL ?>/product/view/<?= $item['id'] ?>" class="btn btn-light rounded-3 px-3 border border-1 border-opacity-10">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Chatbot Floating Widget -->
    <div id="chat-widget" class="position-fixed bottom-0 end-0 m-4"
        style="z-index: 9999; display: flex; flex-direction: column; align-items: flex-end;">
        <div id="chat-window" class="card shadow-lg border-primary mb-2"
            style="width: 450px; display: none; border-radius: 12px; overflow: hidden; background-color: #f8f9fa;">
            <div class="card-header bg-primary text-white p-3 d-flex justify-content-between align-items-center cursor-pointer"
                onclick="toggleChat()">
                <div class="d-flex align-items-center">
                    <i class="bi bi-robot fs-4 me-2"></i>
                    <h5 class="mb-0 fw-bold">cute boy ITshop Assistant</h5>
                </div>
                <button type="button" class="btn-close btn-close-white"></button>
            </div>
            <div id="chat-messages" class="card-body" style="height: 400px; overflow-y: auto; background-color: #fff;">
                <div class="d-flex mb-3">
                    <div class="bg-light p-3 rounded-4 shadow-sm text-dark" style="max-width: 85%; font-size: 0.95rem;">
                        สวัสดีครับ! ผมคือผู้ช่วย AI ของ cute boy ITshop
                        มีสินค้าชิ้นไหนหรือสเปคแบบไหนให้ผมช่วยแนะนำไหมครับ?
                    </div>
                </div>
            </div>
            <div class="card-footer bg-white p-3 border-top pb-3">
                <form id="chat-form" onsubmit="sendMessage(event)">
                    <div class="input-group">
                        <input type="text" id="chat-input" class="form-control" placeholder="พิมพ์ข้อความที่นี่..."
                            required autocomplete="off">
                        <button class="btn btn-primary px-3" type="submit" id="btn-send"><i
                                class="bi bi-send-fill"></i></button>
                    </div>
                </form>
            </div>
        </div>
        <button id="chat-toggle" class="btn btn-primary rounded-circle shadow-lg"
            style="width: 65px; height: 65px; display: flex; align-items: center; justify-content: center; font-size: 2rem; transition: transform 0.3s;"
            onclick="toggleChat()">
            <i class="bi bi-chat-dots-fill"></i>
        </button>
    </div>

    <!-- Script สำหรับแชทบอท AI -->
    <script>
        function toggleChat() {
            const chatWindow = document.getElementById('chat-window');
            const chatToggle = document.getElementById('chat-toggle');
            if (chatWindow.style.display === 'none') {
                chatWindow.style.display = 'block';
                chatToggle.style.transform = 'scale(0)';
                setTimeout(() => chatToggle.style.display = 'none', 300);
            } else {
                chatWindow.style.display = 'none';
                chatToggle.style.display = 'flex';
                setTimeout(() => chatToggle.style.transform = 'scale(1)', 10);
            }
        }

        async function sendMessage(e) {
            e.preventDefault();
            const inputField = document.getElementById('chat-input');
            const message = inputField.value.trim();
            const btnSend = document.getElementById('btn-send');

            if (!message) return;

            appendMessage('user', message);
            inputField.value = '';
            inputField.disabled = true;
            btnSend.disabled = true;

            const loadingId = appendLoading();

            try {
                const response = await fetch('<?= BASE_URL ?>/api/chat', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ message: message })
                });

                removeElement(loadingId);

                if (response.ok) {
                    const data = await response.json();
                    if (data.reply) {
                        appendMessage('bot', data.reply);
                    } else if (data.error) {
                        appendMessage('bot', `<span class="text-danger">Error: ${data.error}</span>`);
                    }
                } else {
                    appendMessage('bot', '<span class="text-danger">ขออภัย ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ AI ได้ในขณะนี้</span>');
                }
            } catch (error) {
                removeElement(loadingId);
                appendMessage('bot', '<span class="text-danger">เกิดข้อผิดพลาดในการส่งข้อมูล</span>');
                console.error(error);
            } finally {
                inputField.disabled = false;
                btnSend.disabled = false;
                inputField.focus();
            }
        }

        function appendMessage(sender, htmlContent) {
            const chatBox = document.getElementById('chat-messages');
            const wrapper = document.createElement('div');

            if (sender === 'user') {
                wrapper.className = 'd-flex mb-3 flex-row-reverse';
                wrapper.innerHTML = `<div class="bg-primary text-white p-3 rounded-4 shadow-sm" style="max-width: 85%; font-size: 0.95rem;">${escapeHtml(htmlContent)}</div>`;
            } else {
                wrapper.className = 'd-flex mb-3';
                // ถ้าเป็น bot จะแสดงผลด้วย HTML เผื่อส่งตารางมา
                wrapper.innerHTML = `
                    <div class="d-flex align-items-start">
                        <div class="bg-light p-3 rounded-4 shadow-sm" style="max-width: 100%; font-size: 0.95rem; overflow-x: auto;">
                            ${htmlContent}
                        </div>
                    </div>`;
            }

            chatBox.appendChild(wrapper);
            chatBox.scrollTop = chatBox.scrollHeight;
        }

        function appendLoading() {
            const id = 'loading-' + Date.now();
            const chatBox = document.getElementById('chat-messages');
            const wrapper = document.createElement('div');
            wrapper.id = id;
            wrapper.className = 'd-flex mb-3';
            wrapper.innerHTML = `
                <div class="bg-light p-3 rounded-4 shadow-sm text-secondary" style="font-size: 0.95rem;">
                    <div class="spinner-grow spinner-grow-sm text-primary" role="status"></div>
                    <div class="spinner-grow spinner-grow-sm text-primary" role="status"></div>
                    <div class="spinner-grow spinner-grow-sm text-primary" role="status"></div>
                </div>`;
            chatBox.appendChild(wrapper);
            chatBox.scrollTop = chatBox.scrollHeight;
            return id;
        }

        function removeElement(id) {
            const el = document.getElementById(id);
            if (el) el.remove();
        }

        function escapeHtml(unsafe) {
            return unsafe
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>