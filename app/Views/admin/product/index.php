<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>ระบบจัดการสินค้า - Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&family=Kanit:wght@300;400;600&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --admin-primary: #3b82f6;
            --admin-bg: #f8fafc;
        }
        body {
            font-family: 'Inter', 'Kanit', sans-serif;
            background-color: var(--admin-bg);
            color: #1e293b;
        }
        .page-title {
            font-size: 1.75rem;
            font-weight: 800;
            letter-spacing: -0.5px;
            color: #0f172a;
        }
        .admin-card {
            background: #ffffff;
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 20px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }
        .admin-card:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        .table {
            border-collapse: separate;
            border-spacing: 0 8px;
        }
        .table thead th {
            border: none;
            text-transform: uppercase;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            color: #64748b;
            padding: 1rem 1.5rem;
        }
        .table tbody tr {
            background: #ffffff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
            transition: transform 0.2s;
        }
        .table tbody tr:hover {
            transform: scale(1.005);
            background: #f1f5f9 !important;
        }
        .table tbody td {
            border: none;
            padding: 1rem 1.5rem;
            vertical-align: middle;
        }
        .table tbody td:first-child { border-radius: 12px 0 0 12px; }
        .table tbody td:last-child { border-radius: 0 12px 12px 0; }
        
        .product-img {
            width: 56px;
            height: 56px;
            object-fit: cover;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .badge-soft-success { background: #dcfce7; color: #166534; }
        .badge-soft-danger { background: #fee2e2; color: #991b1b; }
        .badge-soft-secondary { background: #f1f5f9; color: #475569; }
        
        .btn-action {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            transition: all 0.2s;
        }
        .search-input {
            border-radius: 12px;
            border: 1px solid #e2e3e5;
            padding-left: 2.5rem;
        }
        .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }
    </style>
</head>

<body class="bg-light">
    <!-- Navbar -->
    <?php require_once APP_PATH . '/Views/admin/nav.php'; ?>

    <div class="container py-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-5">
            <div>
                <h2 class="page-title mb-1">จัดการสินค้า</h2>
                <p class="text-muted small mb-0">จัดการรายการสินค้า สต็อก และราคาทั้งหมดในระบบ</p>
            </div>
            <a href="<?= BASE_URL ?>/admin/products/add" class="btn btn-primary px-4 py-2 rounded-3 fw-bold shadow-sm">
                <i class="bi bi-plus-lg me-2"></i>เพิ่มสินค้าใหม่
            </a>
        </div>

        <!-- Filter Card -->
        <div class="admin-card p-4 mb-5 border-0">
            <form action="<?= BASE_URL ?>/admin/products" method="GET" class="row g-3 align-items-end">
                <div class="col-lg-4">
                    <label class="form-label font-bold text-xs text-muted">ค้นหาแบบละเอียด</label>
                    <div class="position-relative">
                        <i class="bi bi-search search-icon"></i>
                        <input type="text" name="search" class="form-control bg-light border-0 py-2 ps-5 search-input"
                            placeholder="รุ่น, รหัส, แบรนด์..."
                            value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <label class="form-label font-bold text-xs text-muted">เริ่มวันที่</label>
                    <input type="date" name="start_date" class="form-control bg-light border-0 py-2 rounded-3"
                        value="<?= htmlspecialchars($_GET['start_date'] ?? '') ?>">
                </div>
                <div class="col-6 col-lg-3">
                    <label class="form-label font-bold text-xs text-muted">ถึงวันที่</label>
                    <input type="date" name="end_date" class="form-control bg-light border-0 py-2 rounded-3"
                        value="<?= htmlspecialchars($_GET['end_date'] ?? '') ?>">
                </div>
                <div class="col-lg-2">
                    <button type="submit" class="btn btn-dark w-100 py-2 rounded-3 fw-bold">กรองข้อมูล</button>
                </div>
            </form>
        </div>

        <?php if (isset($_GET['msg'])): ?>
            <div class="alert alert-primary border-0 shadow-sm rounded-4 px-4 py-3 mb-4 d-flex align-items-center">
                <i class="bi bi-info-circle-fill fs-5 me-3"></i>
                <div class="fw-bold">
                    <?php
                    if ($_GET['msg'] === 'success') echo "เพิ่มสินค้าเรียบร้อยแล้ว!";
                    if ($_GET['msg'] === 'updated') echo "อัปเดตข้อมูลสินค้าเรียบร้อยแล้ว!";
                    if ($_GET['msg'] === 'deleted') echo "ลบสินค้าเรียบร้อยแล้ว!";
                    ?>
                </div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Table View -->
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th style="width: 80px;">สินค้า</th>
                        <th>ข้อมูลสินค้า</th>
                        <th>รหัสซีเรียล</th>
                        <th>ราคาขาย</th>
                        <th>ส่วนลดคงเหลือ</th>
                        <th>ความพร้อม</th>
                        <th class="text-end px-4">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $item): ?>
                        <tr>
                            <td>
                                <img src="<?= htmlspecialchars($item['img'] ?: 'https://via.placeholder.com/60') ?>"
                                    class="product-img">
                            </td>
                            <td>
                                <div class="fw-bold text-slate-800"><?= htmlspecialchars($item['product_name']) ?></div>
                                <div class="text-xs text-muted">อัปเดตล่าสุด: <?= date('d/m/Y') ?></div>
                            </td>
                            <td>
                                <span class="badge badge-soft-secondary px-3 py-2 rounded-pill font-medium">
                                    <?= htmlspecialchars($item['serial_number'] ?? '-') ?>
                                </span>
                            </td>
                            <td>
                                <div class="fw-bold text-primary">฿ <?= number_format($item['price'], 2) ?></div>
                            </td>
                            <td>
                                <?php if (($item['discount'] ?? 0) > 0): ?>
                                    <div class="text-danger font-bold">- ฿ <?= number_format($item['discount'], 2) ?></div>
                                <?php else: ?>
                                    <span class="text-muted opacity-50 small">ไม่มีส่วนลด</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($item['stock'] > 0): ?>
                                    <span class="badge badge-soft-success px-3 py-2 rounded-pill">
                                        มีสินค้าในคลัง <?= $item['stock'] ?>
                                    </span>
                                <?php else: ?>
                                    <span class="badge badge-soft-danger px-3 py-2 rounded-pill">สินค้าหมดคลัง</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end px-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="<?= BASE_URL ?>/admin/products/edit/<?= $item['id'] ?>"
                                        class="btn btn-action btn-outline-primary" title="แก้ไข">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    <a href="<?= BASE_URL ?>/admin/products/delete/<?= $item['id'] ?>"
                                        class="btn btn-action btn-outline-danger" title="ลบ"
                                        onclick="confirmAction(event, this.href, 'ยืนยันคุณต้องการลบสินค้านี้ใช่หรือไม่?', 'ใช่, ลบเลย!');">
                                        <i class="bi bi-trash3-fill"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>