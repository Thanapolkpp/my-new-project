<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>จัดการคำสั่งซื้อ - Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap" rel="stylesheet">
    <style> body { font-family: 'Kanit', sans-serif; } </style>
</head>
<body class="bg-light">
    <?php require_once APP_PATH . '/Views/admin/nav.php'; ?>

    <div class="container mt-5">
        <h2 class="fw-bold mb-4"><i class="bi bi-cart-check"></i> คำสั่งซื้อ & การจัดส่ง</h2>

        <!-- Filter Section -->
        <div class="card shadow-sm border-0 mb-4 bg-white rounded-3">
            <div class="card-body p-4">
                <form action="<?= BASE_URL ?>/admin/orders" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label fw-bold small text-muted">ค้นหา (รหัสบิล, ชื่อลูกค้า, อีเมล)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" class="form-control border-start-0 bg-light" 
                                placeholder="ค้นหาตามข้อมูลลูกค้า..." 
                                value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold small text-muted">ตั้งแต่วันที่</label>
                        <input type="date" name="start_date" class="form-control bg-light" 
                            value="<?= htmlspecialchars($_GET['start_date'] ?? '') ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold small text-muted">ถึงวันที่</label>
                        <input type="date" name="end_date" class="form-control bg-light" 
                            value="<?= htmlspecialchars($_GET['end_date'] ?? '') ?>">
                    </div>
                    <div class="col-md-2">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary fw-bold">
                                <i class="bi bi-funnel"></i> กรอง
                            </button>
                            <a href="<?= BASE_URL ?>/admin/orders" class="btn btn-link btn-sm text-decoration-none text-muted">ล้างตัวกรอง</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <?php if (isset($_GET['msg'])): ?>
            <?php if ($_GET['msg'] === 'status_updated'): ?>
                <div class="alert alert-success border-0 shadow-sm">อัปเดตสถานะคำสั่งซื้อเรียบร้อยแล้ว!</div>
            <?php elseif ($_GET['msg'] === 'payment_approved'): ?>
                <div class="alert alert-success border-0 shadow-sm"><i class="bi bi-patch-check-fill"></i> ยืนยันการชำระเงินสำเร็จ!</div>
            <?php elseif ($_GET['msg'] === 'payment_rejected'): ?>
                <div class="alert alert-warning border-0 shadow-sm"><i class="bi bi-exclamation-triangle"></i> ปฏิเสธการชำระเงิน (ตีกลับไปรอชำระใหม่)</div>
            <?php endif; ?>
        <?php endif; ?>

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>รหัสบิล</th>
                                <th>ผู้สั่งซื้อ</th>
                                <th>ยอดรวม (฿)</th>
                                <th>รูปแบบการส่ง</th>
                                <th>สลิป / การตรวจสอบ</th>
                                <th>สถานะปัจจุบัน</th>
                                <th>อัปเดตสถานะ</th>
                                <th class="text-center">รายละเอียด</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $o): ?>
                                <tr>
                                    <td class="fw-bold text-primary">#<?= str_pad($o['id'], 5, '0', STR_PAD_LEFT) ?></td>
                                    <td>
                                        <?= htmlspecialchars($o['username']) ?><br>
                                        <small class="text-muted"><?= htmlspecialchars($o['email']) ?></small>
                                    </td>
                                    <td class="fw-bold text-danger"><?= number_format($o['total_price'] ?? $o['total_amount'] ?? 0, 2) ?></td>
                                    <td><span class="badge bg-secondary"><?= htmlspecialchars($o['shipping_method'] ?? 'N/A') ?></span></td>
                                    <td>
                                        <?php if (!empty($o['payment_slip'])): ?>
                                            <div class="btn-group">
                                                <a href="<?= str_replace('/index.php', '', BASE_URL) ?>/public/uploads/slips/<?= $o['payment_slip'] ?>" target="_blank" class="btn btn-sm btn-dark">
                                                    <i class="bi bi-image"></i> สลิป
                                                </a>
                                                <?php if ($o['status'] === 'pending'): ?>
                                                    <a href="<?= BASE_URL ?>/admin/orders/approve_payment/<?= $o['id'] ?>" class="btn btn-sm btn-success" onclick="return confirm('ยืนยันว่าสลิปนี้ถูกต้อง?')">
                                                        <i class="bi bi-check-lg"></i> อนุมัติ
                                                    </a>
                                                    <a href="<?= BASE_URL ?>/admin/orders/reject_payment/<?= $o['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('สลิปนี้ไม่ถูกต้อง จะตีกลับไปให้ลูกค้าโหลดใหม่?')">
                                                        <i class="bi bi-x-lg"></i> ไม่ผ่าน
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-muted small">ยังไม่แนบสลิป</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                            $badge_class = 'bg-warning text-dark';
                                            $status_text = 'รอดำเนินการ';
                                            if($o['status'] === 'paid') { $badge_class = 'bg-info text-dark'; $status_text = 'ชำระเงินแล้ว'; }
                                            if($o['status'] === 'shipping' || $o['status'] === 'shipped') { $badge_class = 'bg-primary text-white'; $status_text = 'กำลังจัดส่ง'; }
                                            if($o['status'] === 'completed') { $badge_class = 'bg-success'; $status_text = 'จัดส่งสำเร็จ'; }
                                            if($o['status'] === 'cancelled') { $badge_class = 'bg-danger'; $status_text = 'ยกเลิกคำสั่งซื้อ'; }
                                        ?>
                                        <span class="badge <?= $badge_class ?>"><?= $status_text ?></span>
                                    </td>
                                    <td>
                                        <form action="<?= BASE_URL ?>/admin/orders/update_status/<?= $o['id'] ?>" method="POST" class="d-flex">
                                            <select name="status" class="form-select form-select-sm me-2" style="width: 140px;">
                                                <option value="pending" <?= $o['status'] === 'pending' ? 'selected' : '' ?>>รอดำเนินการ</option>
                                                <option value="paid" <?= $o['status'] === 'paid' ? 'selected' : '' ?>>ชำระเงินแล้ว</option>
                                                <option value="shipping" <?= $o['status'] === 'shipping' || $o['status'] === 'shipped' ? 'selected' : '' ?>>กำลังจัดส่ง</option>
                                                <option value="completed" <?= $o['status'] === 'completed' ? 'selected' : '' ?>>จัดส่งสำเร็จ</option>
                                                <option value="cancelled" <?= $o['status'] === 'cancelled' ? 'selected' : '' ?>>ยกเลิกคำสั่งซื้อ</option>
                                            </select>
                                            <button type="submit" class="btn btn-sm btn-outline-primary">อัปเดต</button>
                                        </form>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?= BASE_URL ?>/admin/orders/view/<?= $o['id'] ?>" class="btn btn-sm btn-outline-dark">
                                            <i class="bi bi-eye"></i> ดูใบเสร็จ
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if(empty($orders)): ?>
                                <tr><td colspan="7" class="text-center text-muted py-4">ยังไม่มีประวัติคำสั่งซื้อ</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
