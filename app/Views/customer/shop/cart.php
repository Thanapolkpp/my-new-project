<?php use App\Services\Auth; ?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ตะกร้าสินค้า - TECH GPU STORE</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Kanit:wght@300;400;500;600&family=Outfit:wght@500;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #2563eb;
            --surface-bg: #f8fafc;
        }
        body {
            font-family: 'Inter', 'Kanit', sans-serif;
            background-color: var(--surface-bg);
            color: #1e293b;
        }
        .cart-title {
            font-size: 2rem;
            font-weight: 800;
            letter-spacing: -1px;
            color: #0f172a;
        }
        .cart-card {
            background: #ffffff;
            border-radius: 28px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(226, 232, 240, 0.8);
            overflow: hidden;
        }
        .cart-item {
            border-bottom: 1px solid #f1f5f9;
            transition: background 0.2s;
        }
        .cart-item:last-child { border-bottom: none; }
        .cart-item:hover { background: #fbfcfe; }
        
        .quantity-control {
            background: #f1f5f9;
            border-radius: 12px;
            padding: 4px;
            display: inline-flex;
            align-items: center;
        }
        .btn-qty {
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            border: none;
            background: white;
            color: #475569;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
            transition: all 0.2s;
        }
        .btn-qty:hover { background: var(--primary-blue); color: white; }
        
        .summary-card {
            background: #ffffff;
            border-radius: 28px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(226, 232, 240, 0.8);
        }
        .price-total {
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 1.5rem;
            color: #0f172a;
        }
        .btn-checkout {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            border-radius: 16px;
            padding: 16px;
            font-weight: 800;
            box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.3);
            transition: all 0.3s;
        }
        .btn-checkout:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(37, 99, 235, 0.4);
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <?php require_once APP_PATH . '/Views/customer/nav.php'; ?>

    <div class="container py-5">
        <h2 class="cart-title mb-5 d-flex align-items-center">
            <i class="bi bi-bag-check-fill me-3 text-primary"></i>ตะกร้าสินค้าของคุณ
        </h2>

        <?php if (empty($_SESSION['cart'])): ?>
            <div class="cart-card text-center py-5 px-4 shadow-sm">
                <div class="mb-4">
                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 120px; height: 120px;">
                        <i class="bi bi-cart-x text-muted" style="font-size: 4rem;"></i>
                    </div>
                </div>
                <h4 class="fw-bold text-dark">ว้า... ตะกร้าคุณยังว่างเปล่าอยู่เลย</h4>
                <p class="text-muted mb-4 pb-2">ไปเลือกชมสินค้าเทคโนโลยีสุดเจ๋งของเรา แล้วเพิ่มลงตะกร้าสิ!</p>
                <a href="<?= BASE_URL ?>/home" class="btn btn-primary px-5 py-3 rounded-pill fw-bold shadow-sm">
                    กลับไปช้อปเลย <i class="bi bi-arrow-right ms-2"></i>
                </a>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="cart-card">
                        <?php foreach ($_SESSION['cart'] as $item): ?>
                            <div class="cart-item p-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                                <div class="d-flex align-items-center">
                                    <div class="position-relative">
                                        <img src="<?= htmlspecialchars($item['img'] ?: 'https://via.placeholder.com/100') ?>"
                                            class="rounded-4 me-4 border bg-light shadow-sm" style="width: 100px; height: 100px; object-fit: cover;">
                                    </div>
                                    <div style="max-width: 300px;">
                                        <h6 class="fw-bold text-dark mb-1 text-truncate" title="<?= htmlspecialchars($item['product_name']) ?>">
                                            <?= htmlspecialchars($item['product_name']) ?>
                                        </h6>
                                        <div class="text-primary fw-bold small">฿<?= number_format($item['price']) ?> / ชิ้น</div>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center justify-content-between flex-grow-1 ps-md-5">
                                    <div class="quantity-control shadow-sm border bg-white">
                                        <form action="<?= BASE_URL ?>/cart/update" method="POST" class="m-0">
                                            <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                                            <input type="hidden" name="action" value="decrease">
                                            <button type="submit" class="btn-qty"><i class="bi bi-dash"></i></button>
                                        </form>
                                        <span class="mx-3 fw-bold fs-6"><?= $item['quantity'] ?></span>
                                        <form action="<?= BASE_URL ?>/cart/update" method="POST" class="m-0">
                                            <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                                            <input type="hidden" name="action" value="increase">
                                            <button type="submit" class="btn-qty"><i class="bi bi-plus"></i></button>
                                        </form>
                                    </div>

                                    <div class="text-end">
                                        <div class="text-muted small mb-1">ยอดรวม</div>
                                        <div class="fw-bold text-dark fs-5">฿<?= number_format($item['price'] * $item['quantity']) ?></div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="summary-card p-4 sticky-top" style="top: 100px;">
                        <h5 class="fw-bold mb-4 pb-2 border-bottom">สรุปคำสั่งซื้อ</h5>
                        <div class="d-flex justify-content-between mb-3 text-secondary">
                            <span>ยอดรวมทั้งหมด</span>
                            <span class="fw-bold">฿<?= number_format($total) ?></span>
                        </div>
                        
                        <?php
                        $discount = $_SESSION['promo_discount'] ?? 0;
                        $net_total = max(0, $total - $discount);
                        ?>

                        <div class="d-flex justify-content-between mb-4 text-success">
                            <span>ส่วนลดพิเศษ</span>
                            <span class="fw-bold">- ฿<?= number_format($discount) ?></span>
                        </div>

                        <div class="bg-light p-3 rounded-4 mb-4">
                            <?php if (isset($_SESSION['promo_code'])): ?>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <small class="text-muted text-uppercase fw-bold ls-wide" style="font-size: 0.65rem;">ใช้โค้ดแล้ว</small>
                                        <div class="fw-bold text-primary">“<?= htmlspecialchars($_SESSION['promo_code']) ?>”</div>
                                    </div>
                                    <form action="<?= BASE_URL ?>/cart/remove_promo" method="POST" class="m-0">
                                        <button type="submit" class="btn btn-sm btn-outline-danger border-0"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            <?php else: ?>
                                <form action="<?= BASE_URL ?>/cart/apply_promo" method="POST">
                                    <div class="input-group">
                                        <input type="text" name="promo_code" class="form-control border-0 bg-transparent" placeholder="กรอกโค้ดส่วนลด" required>
                                        <button class="btn btn-primary rounded-3 px-3" type="submit">ใช้โค้ด</button>
                                    </div>
                                </form>
                            <?php endif; ?>
                            <?php if (isset($_GET['err_promo'])): ?>
                                <div class="text-danger x-small mt-2"><i class="bi bi-info-circle me-1"></i>โค้ดไม่ถูกต้องหรือหมดอายุ</div>
                            <?php endif; ?>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-5">
                            <span class="fw-bold text-dark">ยอดที่ต้องจ่ายจริง</span>
                            <span class="price-total text-primary text-gradient">฿<?= number_format($net_total) ?></span>
                        </div>

                        <a href="<?= BASE_URL ?>/checkout" class="btn btn-primary w-100 btn-checkout py-3 mb-3">
                            ดำเนินการชำระเงิน <i class="bi bi-shield-lock-fill ms-2"></i>
                        </a>
                        <a href="<?= BASE_URL ?>/home" class="btn btn-link w-100 text-decoration-none text-muted small">
                            <i class="bi bi-arrow-left me-1"></i> เลือกช้อปสินค้าต่อ
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>