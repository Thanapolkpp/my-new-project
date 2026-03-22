<?php use App\Services\Auth; ?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ประวัติคำสั่งซื้อ - TECH GPU STORE</title>
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
        <h2 class="fw-bold mb-4"><i class="bi bi-list-check"></i> ประวัติคำสั่งซื้อของคุณ</h2>

        <?php if (isset($_GET['msg'])): ?>
            <?php 
                $msg_type = 'info';
                $msg_text = '';
                $msg_icon = 'bi-info-circle';

                if ($_GET['msg'] == 'success') {
                    $msg_type = 'success';
                    $msg_text = 'สั่งซื้อสินค้าสำเร็จ! แจ้งเตือนสถานะจะปรากฎในหน้านี้';
                    $msg_icon = 'bi-check-circle-fill';
                } elseif ($_GET['msg'] == 'cancelled' || $_GET['msg'] == 'order_cancelled') {
                    $msg_type = 'warning';
                    $msg_text = 'ยกเลิกคำสั่งซื้อเรียบร้อยแล้ว';
                    $msg_icon = 'bi-x-circle-fill';
                } elseif ($_GET['msg'] == 'payment_success') {
                    $msg_type = 'success';
                    $msg_text = 'แจ้งชำระเงินสำเร็จ! ระบบกำลังตรวจสอบหลักฐานของคุณ';
                    $msg_icon = 'bi-shield-check';
                } elseif ($_GET['msg'] == 'error') {
                    $msg_type = 'danger';
                    $msg_text = 'เกิดข้อผิดพลาดในการดำเนินการ กรุณาลองใหม่อีกครั้ง';
                    $msg_icon = 'bi-exclamation-triangle-fill';
                }
            ?>
            <?php if ($msg_text): ?>
                <div class="alert alert-<?= $msg_type ?> alert-dismissible fade show border-0 shadow-sm d-flex align-items-center" role="alert">
                    <i class="bi <?= $msg_icon ?> fs-4 me-3"></i>
                    <div><?= $msg_text ?></div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <?php if (empty($orders)): ?>
            <div class="alert border-0 bg-white py-5 shadow-sm text-center">
                <i class="bi bi-clipboard-x display-1 text-muted"></i>
                <h4 class="mt-3">คุณยังไม่มีคำสั่งซื้อ</h4>
                <p>เลือกหาสินค้าดีๆ เพื่อเริ่มต้นกันเลย!</p>
                <a href="<?= BASE_URL ?>/home" class="btn btn-outline-primary px-4 py-2 mt-3 fw-bold">ไปหน้าร้านค้า</a>
            </div>
        <?php else: ?>
            <div class="row row-cols-1 g-4">
                <?php foreach ($orders as $order): ?>
                    <div class="col">
                        <div class="card border-0 shadow-sm">
                            <div
                                class="card-header bg-white border-bottom-0 pt-4 pb-0 d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="badge bg-secondary mb-2">Order #
                                        <?= str_pad($order['id'], 6, '0', STR_PAD_LEFT) ?>
                                    </span>
                                    <h6 class="text-muted small mb-0"><i class="bi bi-calendar-event"></i>
                                        <?= date('d M Y H:i', strtotime($order['created_at'])) ?>
                                    </h6>
                                </div>
                                <?php
                                $statusBadge = 'bg-secondary';
                                $statusText = 'รอชำระเงิน';
                                if ($order['status'] == 'paid') {
                                    $statusBadge = 'bg-info text-dark';
                                    $statusText = 'ชำระแล้ว';
                                }
                                if ($order['status'] == 'shipped') {
                                    $statusBadge = 'bg-warning text-dark';
                                    $statusText = 'กำลังจัดส่ง';
                                }
                                if ($order['status'] == 'completed') {
                                    $statusBadge = 'bg-success';
                                    $statusText = 'สำเร็จ';
                                }
                                if ($order['status'] == 'cancelled') {
                                    $statusBadge = 'bg-danger';
                                    $statusText = 'ยกเลิกคำสั่งซื้อแล้ว';
                                }
                                ?>
                                <div class="text-end">
                                     <span class="badge <?= $statusBadge ?> fs-6 rounded-pill px-3 py-2 mb-2 d-inline-block">
                                         <?= $statusText ?>
                                     </span>
                                     <?php if ($order['status'] == 'pending' && $order['payment_status'] != 'COD'): ?>
                                         <br>
                                         <a href="<?= BASE_URL ?>/payment/view/<?= $order['id'] ?>"
                                             class="btn btn-sm btn-primary px-3 rounded-pill mt-1 fw-bold">
                                             <i class="bi bi-wallet2"></i> ชำระเงินที่นี่
                                         </a>
                                     <?php endif; ?>
                                     <?php if(!empty($order['payment_slip'])): ?>
                                         <br>
                                         <span class="badge bg-success mt-1 small">ส่งหลักฐานแล้ว</span>
                                     <?php endif; ?>
                                    <?php if (in_array($order['status'], ['pending', 'paid'])): ?>
                                        <br>
                                        <a href="<?= BASE_URL ?>/orders/cancel?id=<?= $order['id'] ?>"
                                            class="btn btn-sm btn-outline-danger px-3 rounded-pill mt-1"
                                            onclick="confirmAction(event, this.href, 'คุณแน่ใจหรือไม่ว่าต้องการยกเลิกคำสั่งซื้อนี้?', 'ใช่, ยกเลิกคำสั่งซื้อ');">ยกเลิกคำสั่งซื้อ</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="card-body">

                                <!-- Timeline / Status steps -->
                                <div class="position-relative m-4 px-2">
                                    <div class="progress" style="height: 4px;">
                                        <?php
                                        // Determine progress
                                        $progress = 0;
                                        if ($order['status'] == 'pending')
                                            $progress = 25;
                                        if ($order['status'] == 'paid')
                                            $progress = 50;
                                        if ($order['status'] == 'shipped')
                                            $progress = 75;
                                        if ($order['status'] == 'completed')
                                            $progress = 100;
                                        if ($order['status'] == 'cancelled')
                                            $progress = 0;
                                        ?>
                                        <div class="progress-bar <?= $order['status'] == 'cancelled' ? 'bg-danger' : 'bg-primary' ?>"
                                            role="progressbar" style="width: <?= $progress ?>%;"
                                            aria-valuenow="<?= $progress ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <?php if ($order['status'] != 'cancelled'): ?>
                                        <button type="button"
                                            class="position-absolute top-0 start-0 translate-middle btn btn-sm <?= $progress >= 25 ? 'btn-primary' : 'btn-secondary' ?> rounded-circle"
                                            style="width: 2rem; height:2rem; line-height: 1;">1</button>
                                        <button type="button"
                                            class="position-absolute top-0 start-50 translate-middle btn btn-sm <?= $progress >= 50 ? 'btn-primary' : 'btn-secondary' ?> rounded-circle"
                                            style="width: 2rem; height:2rem; line-height: 1;">2</button>
                                        <button type="button"
                                            class="position-absolute top-0 start-100 translate-middle btn btn-sm <?= $progress >= 100 ? 'btn-primary' : 'btn-secondary' ?> rounded-circle"
                                            style="width: 2rem; height:2rem; line-height: 1;">3</button>

                                        <div class="d-flex justify-content-between mt-2 small text-muted">
                                            <span style="margin-left:-1.5rem;">รอชำระเงิน</span>
                                            <span class="ps-2 text-center">กำลังจัดเตรียม/จัดส่ง</span>
                                            <span style="margin-right:-1.5rem;">สำเร็จ</span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <hr>
                                <?php
                                // Fetch items for this order directly via model instance created in controller
                                $items = $this->orderModel->getOrderItems($order['id']);
                                ?>
                                <div class="table-responsive">
                                    <table class="table table-borderless table-sm mb-0 align-middle">
                                        <tbody>
                                            <?php foreach ($items as $item): ?>
                                                <tr>
                                                    <td style="width:50px;">
                                                        <img src="<?= htmlspecialchars($item['img'] ?: 'https://via.placeholder.com/40') ?>"
                                                            class="rounded" style="width:40px;height:40px;object-fit:cover;">
                                                    </td>
                                                    <td>
                                                        <span class="text-truncate d-block" style="max-width: 200px;"
                                                            title="<?= htmlspecialchars($item['product_name']) ?>">
                                                            <?= htmlspecialchars($item['product_name']) ?>
                                                        </span>
                                                    </td>
                                                    <td class="text-muted small">x
                                                        <?= $item['quantity'] ?>
                                                    </td>
                                                    <td class="text-end fw-bold">฿
                                                        <?= number_format($item['price'], 2) ?>
                                                        <br>
                                                        <a href="<?= BASE_URL ?>/product/view/<?= $item['product_id'] ?>#reviewForm" class="btn btn-sm btn-outline-primary mt-2 rounded-pill shadow-sm" style="font-size: 0.75rem;">
                                                            <i class="bi bi-star"></i> รีวิวสินค้า
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div
                                class="card-footer bg-light border-top-0 d-flex justify-content-between align-items-center rounded-bottom p-3">
                                <span class="small text-muted"><i class="bi bi-truck"></i> จัดส่งโดย:
                                    <?= htmlspecialchars($order['shipping_method']) ?>
                                </span>
                                <span class="fw-bold fs-5">ยอดสุทธิ <span class="text-danger">฿
                                        <?= number_format($order['total_price'], 2) ?>
                                    </span></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>