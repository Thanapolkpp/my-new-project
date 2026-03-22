<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>ระบบจัดการโค้ดส่วนลด - Admin</title>
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
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold"><i class="bi bi-ticket-perforated"></i> จัดการโค้ดส่วนลด (กิจกรรม)</h2>
            <a href="<?= BASE_URL ?>/admin/promocodes/add" class="btn btn-success"><i class="bi bi-plus-lg"></i>
                เพิ่มโค้ดใหม่</a>
        </div>

        <?php if (isset($_GET['msg'])): ?>
            <div class="alert alert-info border-0 shadow-sm">
                <?php
                if ($_GET['msg'] === 'success')
                    echo "เพิ่มโค้ดส่วนลดเรียบร้อยแล้ว!";
                if ($_GET['msg'] === 'updated')
                    echo "อัปเดตข้อมูลโค้ดเรียบร้อยแล้ว!";
                if ($_GET['msg'] === 'deleted')
                    echo "ลบโค้ดส่วนลดเรียบร้อยแล้ว!";
                ?>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>รหัสโค้ด</th>
                                <th>ส่วนลด</th>
                                <th>การใช้งาน (จำนวน/จำกัด)</th>
                                <th>วันหมดอายุ</th>
                                <th>สถานะ</th>
                                <th class="text-center">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($promocodes as $code): ?>
                                <tr>
                                    <td><span class="badge bg-primary fs-6"><?= htmlspecialchars($code['code']) ?></span>
                                    </td>
                                    <td class="fw-bold text-danger">
                                        <?= number_format($code['discount_amount'], 2) ?>
                                        <?= $code['is_percentage'] ? '%' : '฿' ?>
                                    </td>
                                    <td>
                                        รวม: <?= $code['used_count'] ?> / <?= $code['usage_limit'] ?: 'ไม่จำกัด' ?><br>
                                        <small class="text-muted">ผู้ใช้ 1 คน: <?= $code['max_uses_per_user'] ?: '1' ?>
                                            สิทธิ์</small>
                                    </td>
                                    <td>
                                        <?= $code['expiry_date'] ? date('d/m/Y H:i', strtotime($code['expiry_date'])) : '<span class="text-muted">ไม่มีวันหมดอายุ</span>' ?>
                                    </td>
                                    <td>
                                        <?php if ($code['is_active']): ?>
                                            <span class="badge bg-success">เปิดใช้งาน</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">ปิดใช้งาน</span>
                                        <?php endif; ?>
                                        <br>
                                        <?php if ($code['show_on_home']): ?>
                                            <span class="badge bg-primary mt-1"><i class="bi bi-house-door"></i>
                                                โชว์หน้าแรก</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?= BASE_URL ?>/admin/promocodes/edit/<?= $code['id'] ?>"
                                            class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil-square"></i>
                                            แก้ไข</a>
                                        <a href="<?= BASE_URL ?>/admin/promocodes/delete/<?= $code['id'] ?>"
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="confirmAction(event, this.href, 'ยืนยันคุณต้องการลบโค้ดนี้ใช่หรือไม่?', 'ใช่, ลบเลย!');"><i
                                                class="bi bi-trash"></i> ลบ</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (empty($promocodes)): ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">ยังไม่มีข้อมูลโค้ดส่วนลด</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>

</html>