<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>จัดการแบนเนอร์โฆษณา - Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&family=Kanit:wght@300;400;600&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', 'Kanit', sans-serif;
        }
    </style>
</head>

<body class="bg-light">
    <?php require_once APP_PATH . '/Views/admin/nav.php'; ?>


    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>จัดการแบนเนอร์โฆษณา (Banners)</h2>
            <a href="<?= BASE_URL ?>/admin/banners/add" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> เพิ่มแบนเนอร์ใหม่
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>ลำดับบนเว็บ</th>
                                <th>รูปภาพ</th>
                                <th>หัวข้อ (Title)</th>
                                <th>สถานะ</th>
                                <th>จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($banners)): ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted">ยังไม่มีข้อมูลแบนเนอร์</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($banners as $banner): ?>
                                    <tr>
                                        <td>
                                            <?= htmlspecialchars($banner['sort_order']) ?>
                                        </td>
                                        <td>
                                            <img src="<?= str_replace('index.php', '', BASE_URL) ?>public/assets/images/banners/<?= htmlspecialchars($banner['image']) ?>"
                                                alt="Banner" style="max-height: 80px; max-width: 150px; object-fit: cover;"
                                                class="rounded border">
                                        </td>
                                        <td>
                                            <?= htmlspecialchars($banner['title'] ?: 'ไม่มีหัวข้อ') ?>
                                        </td>
                                        <td>
                                            <?php if ($banner['is_active']): ?>
                                                <span class="badge bg-success">เปิดใช้งาน</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">ปิดใช้งาน</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="<?= BASE_URL ?>/admin/banners/edit/<?= $banner['id'] ?>"
                                                class="btn btn-warning btn-sm me-2">แก้ไข</a>
                                            <a href="<?= BASE_URL ?>/admin/banners/delete/<?= $banner['id'] ?>"
                                                class="btn btn-danger btn-sm"
                                                onclick="confirmAction(event, this.href, 'ยืนยันประสงค์ที่จะลบแบนเนอร์นี้?', 'ใช่, ลบเลย!');">ลบ</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    </div>
</body>

</html>