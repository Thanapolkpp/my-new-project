<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>จัดการรีวิว - Admin</title>
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
        <h2 class="fw-bold mb-4"><i class="bi bi-chat-quote text-success"></i> รีวิวจากลูกค้า</h2>

        <?php if (isset($_GET['msg']) && $_GET['msg'] === 'deleted'): ?>
            <div class="alert alert-danger border-0 shadow-sm">
                ลบรีวิวที่ไม่เหมาะสมเรียบร้อยแล้ว
            </div>
        <?php endif; ?>

        <div class="row">
            <?php foreach ($reviews as $r): ?>
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <img src="<?= htmlspecialchars($r['img'] ?: 'https://via.placeholder.com/60') ?>"
                                    class="rounded me-3 border" style="width: 60px; height: 60px; object-fit: cover;">
                                <div>
                                    <h6 class="fw-bold mb-0 text-truncate" style="max-width:250px;">
                                        <?= htmlspecialchars($r['product_name']) ?>
                                    </h6>
                                    <div class="text-warning small mt-1">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <i class="bi bi-star-<?= $i <= $r['rating'] ? 'fill' : 'break' ?>"></i>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <div class="ms-auto">
                                    <a href="<?= BASE_URL ?>/admin/reviews/delete/<?= $r['id'] ?>"
                                        class="btn btn-outline-danger btn-sm"
                                        onclick="confirmAction(event, this.href, 'ยืนยันคุณต้องการลบรีวิวนี้ใช่หรือไม่?', 'ใช่, ลบเลย!');" title="ลบรีวิวนี้">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </div>

                            <div class="bg-light p-3 rounded mb-3 border position-relative" style="font-size: 0.95rem;">
                                <!-- Quotation mark icon background -->
                                <i class="bi bi-quote position-absolute top-0 start-0 text-black-50"
                                    style="font-size: 3rem; opacity: 0.1; transform: translate(-5px, -15px);"></i>
                                <p class="mb-0 text-secondary" style="position: relative; z-index: 1;">
                                    "
                                    <?= nl2br(htmlspecialchars($r['comment'] ?: 'ไม่มีความคิดเห็น')) ?>"
                                </p>
                            </div>

                            <div
                                class="d-flex justify-content-between align-items-center text-muted small mt-auto border-top pt-2">
                                <div><i class="bi bi-person-circle"></i>
                                    <?= htmlspecialchars($r['username'] ?? 'ไม่ทราบชื่อ') ?>
                                </div>
                                <div><i class="bi bi-clock"></i>
                                    <?= date('d/m/Y H:i', strtotime($r['created_at'])) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php if (empty($reviews)): ?>
                <div class="col-12 text-center text-muted py-5">
                    <i class="bi bi-chat-square-text fs-1"></i>
                    <p class="mt-3">ยังไม่มีรีวิวจากลูกค้าในขณะนี้</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>