<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>รายละเอียดคำสั่งซื้อ #
        <?= $order['id'] ?> - Admin
    </title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Kanit', sans-serif;
        }
    </style>
</head>

<body class="bg-light">
    <?php require_once APP_PATH . '/Views/admin/nav.php'; ?>

    <div class="container mt-5">
        <a href="<?= BASE_URL ?>/admin/orders" class="btn btn-secondary mb-3"><i class="bi bi-arrow-left"></i>
            กลับไปหน้ารวมคำสั่งซื้อ</a>

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body p-4 bg-white rounded">
                <div class="d-flex justify-content-between border-bottom pb-3 mb-3">
                    <h4 class="fw-bold"><i class="bi bi-receipt"></i> ใบเสร็จรับเงิน / คำสั่งซื้อ #
                        <?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?>
                    </h4>
                    <div>
                        <span class="badge bg-secondary fs-6">
                            <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?>
                        </span>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-sm-6">
                        <h6 class="fw-bold mb-3 text-muted">ข้อมูลลูกค้า:</h6>
                        <div><strong>
                                <?= htmlspecialchars($order['username']) ?>
                            </strong></div>
                        <div>
                            <?= htmlspecialchars($order['email']) ?>
                        </div>
                        <div>ที่อยู่จัดส่ง:
                            <?= nl2br(htmlspecialchars($order['address'] ?? 'ไม่มีข้อมูลที่อยู่')) ?>
                        </div>
                    </div>
                    <div class="col-sm-6 text-sm-end">
                        <h6 class="fw-bold mb-3 text-muted">รายละเอียดการส่ง & ชำระเงิน:</h6>
                        <div>สถานะ: <strong>
                                <?= htmlspecialchars($order['status']) ?>
                            </strong></div>
                        <div>การจัดส่ง: <strong>
                                <?= htmlspecialchars($order['shipping_method'] ?? 'N/A') ?>
                            </strong></div>
                        <div>การชำระเงิน: <strong>
                                <?= htmlspecialchars($order['payment_status'] ?? 'N/A') ?>
                            </strong></div>
                        <?php if (!empty($order['promo_code'])): ?>
                            <div class="text-success mt-2">
                                <i class="bi bi-tag-fill"></i> ใช้โค้ด: <strong>
                                    <?= htmlspecialchars($order['promo_code']) ?>
                                </strong>
                                (-฿
                                <?= number_format($order['discount_total'], 2) ?>)
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>สินค้า</th>
                                <th class="text-center">ราคาต่อหน่วย</th>
                                <th class="text-center">จำนวน</th>
                                <th class="text-end">รวมทั้งหมด (฿)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $item): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="<?= htmlspecialchars($item['img'] ?: 'https://via.placeholder.com/50') ?>"
                                                class="rounded me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                            <div>
                                                <?= htmlspecialchars($item['product_name']) ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <?= number_format($item['price'], 2) ?>
                                    </td>
                                    <td class="text-center">
                                        <?= $item['quantity'] ?>
                                    </td>
                                    <td class="text-end fw-bold">
                                        <?= number_format($item['price'] * $item['quantity'], 2) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-end text-danger fs-5">ยอดชำระสุทธิ (Net Total):</th>
                                <td class="text-end text-danger fw-bold fs-5">฿
                                    <?= number_format($order['total_price'] ?? $order['total_amount'] ?? 0, 2) ?>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>

</html>