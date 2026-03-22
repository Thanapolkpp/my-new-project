<?php use App\Services\Auth; ?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ชำระเงิน - TECH GPU STORE</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;800&family=Kanit:wght@300;400;600&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', 'Kanit', sans-serif;
            background-color: #f8f9fa;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <?php require_once APP_PATH . '/Views/customer/nav.php'; ?>

    <div class="container mt-5 mb-5 pb-5">
        <form action="<?= BASE_URL ?>/checkout/process" method="POST">
            <div class="row g-5">
                <!-- Payment & Shipping details -->
                <div class="col-md-7">
                    <h3 class="fw-bold mb-4"><i class="bi bi-geo-alt"></i> ที่อยู่และการจัดส่ง</h3>
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body p-4">
                            <h5 class="fw-bold text-muted mb-3">ชื่อ-นามสกุล</h5>
                            <p class="fs-5 fw-bold mb-1">
                                <?= htmlspecialchars($_SESSION['username']) ?>
                            </p>
                            <h5>เบอร์โทรศัพท์</h5>
                            <p class="fs-5 fw-bold mb-1">
                                <?= htmlspecialchars($user['phone']) ?>
                            </p>
                            <h5 class="fw-bold text-muted mb-2 mt-4 small uppercase tracking-wide">ที่อยู่จัดส่ง</h5>
                            <p class="mb-3 text-dark bg-light p-3 border rounded">
                                <?php if (!empty($user['address'])): ?>
                                    <?= nl2br(htmlspecialchars($user['address'])) ?>
                                <?php else: ?>
                                    <span class="text-danger italic">ยังไม่มีข้อมูลที่อยู่จัดส่ง</span><br>
                                    <a href="<?= BASE_URL ?>/profile" class="btn btn-sm btn-outline-primary mt-2">
                                        <i class="bi bi-pencil-square"></i> เพิ่มที่อยู่ตอนนี้
                                    </a>
                                <?php endif; ?>
                            </p>
                            
                            <p class="text-muted small italic">
                                <i class="bi bi-info-circle"></i> ข้อมูลที่อยู่ถูกดึงมาจากโปรไฟล์ของท่าน 
                                <a href="<?= BASE_URL ?>/profile" class="text-primary text-decoration-none">แก้ไขโปรไฟล์ที่นี่</a>
                            </p>

                            <hr class="my-4">
                            <h5 class="fw-bold text-muted mb-3 mt-4">เลือกวิธีการจัดส่ง</h5>

                            <div class="form-check p-3 border rounded mb-2 bg-light">
                                <input class="form-check-input ms-1 me-3 fs-5" type="radio" name="shipping_method"
                                    id="ship1" value="KERRY" required>
                                <label class="form-check-label w-100" for="ship1">
                                    <span class="fw-bold d-block">Thanapol Express (จัดส่งด่วน)</span>
                                    <span class="text-muted small">ส่งถึงภายใน 1-2 วันทำการ</span>
                                </label>
                            </div>

                            <div class="form-check p-3 border rounded mb-2 bg-light">
                                <input class="form-check-input ms-1 me-3 fs-5" type="radio" name="shipping_method"
                                    id="ship2" value="EMS" required>
                                <label class="form-check-label w-100" for="ship2">
                                    <span class="fw-bold d-block">ไปรษณีย์ไทย Fast8</span>
                                    <span class="text-muted small">ส่งถึงภายใน 2-3 วันทำการ</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <h3 class="fw-bold mb-4 mt-5"><i class="bi bi-credit-card"></i> วิธีการชำระเงิน</h3>
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body p-4">
                            <select class="form-select form-select-lg p-3 bg-light border" name="payment_method"
                                required>
                                <option value="" selected disabled>-- เลือกวิธีการชำระเงิน --</option>
                                <option value="PROMPTPAY">สแกน QR Code (PromptPay)</option>
                                <option value="CREDIT_CARD">จ่ายผ่านกระเเสจิต</option>
                                <option value="COD">ชำระเงินปลายทาง (Cash on Delivery)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Order summary sidebar -->
                <div class="col-md-5">
                    <div class="card border-0 shadow-sm bg-white p-4 sticky-top" style="top: 80px;">
                        <h4 class="fw-bold mb-4">สรุปการสั่งซื้อ <span class="badge bg-primary rounded-pill fs-6">
                                <?= count($_SESSION['cart']) ?>
                            </span></h4>

                        <div class="order-items mb-4" style="max-height: 300px; overflow-y: auto;">
                            <?php foreach ($cartItems as $item): ?>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="d-flex align-items-center w-75">
                                        <div class="position-relative me-3">
                                            <img src="<?= htmlspecialchars($item['img'] ?: 'https://via.placeholder.com/60') ?>"
                                                class="rounded border"
                                                style="width: 60px; height: 60px; object-fit: cover;">
                                            <span
                                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-secondary border border-light">
                                                <?= $item['quantity'] ?>
                                            </span>
                                        </div>
                                        <div class="text-truncate">
                                            <span class="fw-bold small d-block text-truncate"
                                                title="<?= htmlspecialchars($item['product_name']) ?>">
                                                <?= htmlspecialchars($item['product_name']) ?>
                                            </span>
                                        </div>
                                    </div>
                                    <span class="fw-bold text-end">฿
                                        <?= number_format($item['price'] * $item['quantity'], 2) ?>
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <hr class="border-secondary">

                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">ยอดรวมสินค้า</span>
                            <span class="fw-bold">฿
                                <?php
                                $discount = isset($_SESSION['promo_discount']) ? $_SESSION['promo_discount'] : 0;
                                $net_total = $total - $discount;
                                if ($net_total < 0)
                                    $net_total = 0;
                                ?>
                                <?= number_format($total, 2) ?>
                            </span>
                        </div>
                        <?php if ($discount > 0): ?>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">ส่วนลดพิเศษ (ชิ้น/โค้ด)</span>
                                <span class="fw-bold text-success">- ฿<?= number_format($discount, 2) ?></span>
                            </div>
                        <?php endif; ?>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">ค่าจัดส่ง</span>
                            <span class="fw-bold text-success">ส่งฟรี!</span>
                        </div>
                        <div class="d-flex justify-content-between mt-4">
                            <span class="fw-bold fs-5">ยอดที่ต้องชำระทั้งสิ้น</span>
                            <span class="fw-bold text-danger fs-3">฿
                                <?= number_format($net_total, 2) ?>
                            </span>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-3 mt-4 fw-bold fs-5 shadow-sm">
                            <i class="bi bi-check-circle"></i> ยืนยันคำสั่งซื้อ
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>