<?php use App\Services\Auth; ?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= htmlspecialchars($product['product_name']) ?> - TECH GPU STORE
    </title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;800&family=Kanit:wght@300;400;600&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Kanit:wght@300;400;500;600&family=Outfit:wght@500;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #2563eb;
            --surface-bg: #f8fafc;
            --premium-gray: #64748b;
        }
        body {
            font-family: 'Inter', 'Kanit', sans-serif;
            background-color: var(--surface-bg);
            color: #1e293b;
        }
        .breadcrumb-item a {
            color: var(--premium-gray);
            font-weight: 500;
            font-size: 0.9rem;
        }
        .breadcrumb-item.active {
            color: var(--primary-blue);
            font-weight: 600;
        }
        .product-main-card {
            background: #ffffff;
            border-radius: 32px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 10px 15px -3px rgba(0, 0, 0, 0.03);
            border: 1px solid rgba(226, 232, 240, 0.8);
            overflow: hidden;
            transition: transform 0.3s ease;
        }
        .product-img-container {
            background: #fbfcfe;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem;
            min-height: 500px;
        }
        .product-img-container img {
            transition: all 0.5s ease;
            filter: drop-shadow(0 20px 30px rgba(0,0,0,0.1));
        }
        .product-img-container:hover img {
            transform: scale(1.05) rotate(2deg);
        }
        #price-val {
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 2.75rem;
            color: #0f172a;
            letter-spacing: -1px;
        }
        .discount-label {
            background: #fee2e2;
            color: #ef4444;
            padding: 4px 12px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.9rem;
        }
        .btn-premium-cart {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            border: none;
            padding: 18px 40px;
            border-radius: 20px;
            font-weight: 800;
            font-size: 1.1rem;
            box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.4);
            transition: all 0.3s;
        }
        .btn-premium-cart:hover {
            transform: translateY(-4px) scale(1.02);
            box-shadow: 0 20px 25px -5px rgba(37, 99, 235, 0.5);
            filter: brightness(1.1);
        }
        .review-card {
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 24px;
            background: white;
            transition: all 0.3s ease;
        }
        .review-card:hover {
            border-color: var(--primary-blue);
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }
        .status-pill {
            display: inline-flex;
            align-items: center;
            padding: 6px 16px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.85rem;
        }
        .status-in-stock { background: #dcfce7; color: #15803d; }
        .status-out-stock { background: #f1f5f9; color: #64748b; }
    </style>
</head>

<body>
    <!-- Navbar -->
    <?php require_once APP_PATH . '/Views/customer/nav.php'; ?>

    <div class="container mt-5 mb-5 pb-5">
        <div class="row g-5">
            <!-- ภาพสินค้า -->
            <div class="col-lg-6">
                <div class="product-main-card">
                    <div class="product-img-container">
                        <img src="<?= htmlspecialchars($product['img'] ?: 'https://via.placeholder.com/600x400') ?>"
                            class="img-fluid" alt="<?= htmlspecialchars($product['product_name']) ?>">
                    </div>
                </div>
            </div>

            <!-- ข้อมูลสินค้า -->
            <div class="col-lg-6">
                <div class="ps-lg-4">
                    <nav aria-label="breadcrumb" class="mb-4">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/home">หน้าร้านค้า</a></li>
                            <li class="breadcrumb-item active"><?= htmlspecialchars($product['product_name']) ?></li>
                        </ol>
                    </nav>

                    <h1 class="fw-bold mb-2" style="font-size: 2.5rem; letter-spacing: -1px; color: #0f172a;">
                        <?= htmlspecialchars($product['product_name']) ?>
                    </h1>
                    
                    <div class="d-flex align-items-center mb-4 pb-2">
                        <div class="text-warning me-2 fs-6">
                            <?php for($i=1; $i<=5; $i++): ?>
                                <i class="bi bi-star-<?= $i <= 4 ? 'fill' : ($i == 5 ? 'half' : 'outline') ?>"></i>
                            <?php endfor; ?>
                        </div>
                        <span class="text-muted small fw-medium">(<?= count($reviews) ?> รีวิวจากลูกค้า)</span>
                    </div>

                    <div class="mb-4 pb-2">
                        <?php if ($product['discount'] > 0): ?>
                            <div class="d-flex align-items-center gap-3 mb-1">
                                <span id="price-val">฿<?= number_format($product['price'] - $product['discount']) ?></span>
                                <span class="discount-label">ประหยัด ฿<?= number_format($product['discount']) ?></span>
                            </div>
                            <div class="text-muted text-decoration-line-through">฿<?= number_format($product['price']) ?></div>
                        <?php else: ?>
                            <span id="price-val">฿<?= number_format($product['price']) ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="mb-5">
                        <p class="text-secondary lh-lg fs-6" style="max-height: 200px; overflow-y: auto;">
                            <?= nl2br(htmlspecialchars($product['detail'])) ?>
                        </p>
                    </div>

                    <div class="mb-5 d-flex align-items-center gap-3">
                        <span class="text-muted small fw-bold text-uppercase tracking-wider">Availability:</span>
                        <?php if ($product['stock'] > 0): ?>
                            <span class="status-pill status-in-stock">
                                <i class="bi bi-check-circle-fill me-2"></i>ในคลังสินค้า <?= $product['stock'] ?> ชิ้น
                            </span>
                        <?php else: ?>
                            <span class="status-pill status-out-stock">
                                <i class="bi bi-x-circle-fill me-2"></i>สินค้าหมดแล้ว
                            </span>
                        <?php endif; ?>
                    </div>

                    <form action="<?= BASE_URL ?>/cart/add" method="POST">
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                        <?php if ($product['stock'] > 0): ?>
                            <button type="submit" class="btn btn-premium-cart w-100 d-flex align-items-center justify-content-center">
                                <i class="bi bi-cart-plus-fill me-3 fs-4"></i>เพิ่มลงในตะกร้าสินค้า
                            </button>
                        <?php else: ?>
                            <button type="button" class="btn btn-secondary w-100 py-3 rounded-4 fw-bold" disabled>สินค้าหมด</button>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>

        <!-- แท็บรายละเอียด / รีวิว -->
        <div class="row mt-5 pt-4 border-top">
            <div class="col-12">
                <h4 class="fw-bold mb-4"><i class="bi bi-chat-right-quote-fill text-primary"></i> รีวิวจากผู้ใช้งานจริง
                </h4>

                <?php if (isset($_GET['msg']) && $_GET['msg'] === 'review_added'): ?>
                    <div class="alert alert-success">ขอบคุณสำหรับความคิดเห็นของคุณ!</div>
                <?php endif; ?>

                <div class="row">
                    <div class="col-md-8">
                        <?php if (empty($reviews)): ?>
                            <div class="alert bg-white border shadow-sm text-center py-5 rounded-4">
                                <i class="bi bi-chat-square-text text-muted fs-1 mb-3 d-block"></i>
                                <p class="text-muted">ยังไม่มีรีวิวสำหรับสินค้านี้ เป็นคนแรกที่รีวิวสิ!</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($reviews as $r): ?>
                                <div class="review-card p-4 mb-4">
                                    <div class="d-flex justify-content-between mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                                                <i class="bi bi-person-fill text-secondary fs-5"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">
                                                    <?php
                                                    $uName = $r['username'];
                                                    $len = mb_strlen($uName, 'UTF-8');
                                                    if ($len > 3) {
                                                        $maskedName = mb_substr($uName, 0, 1, 'UTF-8') . str_repeat('*', $len - 2) . mb_substr($uName, -1, 1, 'UTF-8');
                                                    } else {
                                                        $maskedName = mb_substr($uName, 0, 1, 'UTF-8') . str_repeat('*', max(1, $len - 1));
                                                    }
                                                    echo htmlspecialchars($maskedName);
                                                    ?>
                                                </div>
                                                <div class="text-warning small" style="letter-spacing: 2px;">
                                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                                        <i class="bi bi-star-<?= $i <= $r['rating'] ? 'fill' : 'break' ?>"></i>
                                                    <?php endfor; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-muted small fw-medium">
                                            <?= date('d/m/Y H:i', strtotime($r['created_at'])) ?>
                                        </div>
                                    </div>
                                    <p class="mb-4 text-secondary lh-lg" style="font-size: 0.95rem;">
                                        "<?= nl2br(htmlspecialchars($r['comment'])) ?>"
                                    </p>

                                    <!-- Reactions -->
                                    <div class="d-flex align-items-center gap-2">
                                        <form action="<?= BASE_URL ?>/product/react/<?= $r['id'] ?>" method="POST" class="m-0">
                                            <input type="hidden" name="type" value="heart">
                                            <button type="submit" class="btn btn-sm <?= ($r['user_reaction'] === 'heart') ? 'btn-danger text-white' : 'btn-outline-danger' ?> rounded-pill px-3 py-1">
                                                <i class="bi bi-heart<?= ($r['user_reaction'] === 'heart') ? '-fill' : '' ?> me-1"></i>
                                                <?= $r['reactions']['heart'] ?? 0 ?>
                                            </button>
                                        </form>

                                        <form action="<?= BASE_URL ?>/product/react/<?= $r['id'] ?>" method="POST" class="m-0">
                                            <input type="hidden" name="type" value="dislike">
                                            <button type="submit" class="btn btn-sm <?= ($r['user_reaction'] === 'dislike') ? 'btn-secondary text-white' : 'btn-outline-secondary' ?> rounded-pill px-3 py-1">
                                                <i class="bi bi-hand-thumbs-down<?= ($r['user_reaction'] === 'dislike') ? '-fill' : '' ?> me-1"></i>
                                                <?= $r['reactions']['dislike'] ?? 0 ?>
                                            </button>
                                        </form>

                                        <?php if (Auth::check()): ?>
                                            <button class="btn btn-sm btn-light text-muted border rounded-pill px-3 py-1" type="button" data-bs-toggle="collapse" data-bs-target="#replyForm<?= $r['id'] ?>">
                                                <i class="bi bi-reply-fill"></i> ตอบกลับ
                                            </button>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Replies Section -->
                                    <?php if (!empty($r['replies'])): ?>
                                        <div class="mt-4 pt-3 border-top border-light">
                                            <?php foreach ($r['replies'] as $reply): ?>
                                                <div class="mb-3 ps-3 border-start border-3 border-primary border-opacity-25">
                                                    <div class="d-flex justify-content-between mb-1">
                                                        <div class="fw-bold small d-flex align-items-center">
                                                            <?php if (isset($reply['is_admin']) && $reply['is_admin'] == 1): ?>
                                                                <span class="badge bg-primary-subtle text-primary me-2" style="font-size: 0.65rem;">ADMIN</span>
                                                            <?php endif; ?>
                                                            <?= htmlspecialchars($reply['username']) ?>
                                                        </div>
                                                        <div class="text-muted" style="font-size: 0.7rem;">
                                                            <?= date('d/m/Y H:i', strtotime($reply['created_at'])) ?>
                                                        </div>
                                                    </div>
                                                    <p class="mb-0 text-secondary small bg-light p-2 rounded-3">
                                                        <?= nl2br(htmlspecialchars($reply['comment'])) ?>
                                                    </p>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Reply Form -->
                                    <?php if (Auth::check()): ?>
                                        <div class="collapse mt-3" id="replyForm<?= $r['id'] ?>">
                                            <form action="<?= BASE_URL ?>/product/reply/<?= $r['id'] ?>" method="POST" class="d-flex gap-2">
                                                <input type="text" name="comment" class="form-control form-control-sm border-0 bg-light rounded-3" placeholder="เขียนการตอบกลับ..." required>
                                                <button type="submit" class="btn btn-sm btn-primary px-3 rounded-3"><i class="bi bi-send"></i></button>
                                            </form>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-4">
                        <div class="review-card p-4 sticky-top" style="top: 100px; z-index: 1;">
                            <h5 class="fw-bold mb-4">เขียนรีวิวของคุณ</h5>
                            <?php if ($hasPurchased): ?>
                                <form action="<?= BASE_URL ?>/product/review/<?= $product['id'] ?>" method="POST">
                                    <div class="mb-3">
                                        <label class="form-label font-bold text-xs text-muted text-uppercase">ระดับความพึงพอใจ</label>
                                        <select name="rating" class="form-select border-0 bg-light py-2 rounded-3" required>
                                            <option value="5">⭐⭐⭐⭐⭐ ยอดเยี่ยมที่สุด</option>
                                            <option value="4">⭐⭐⭐⭐ ดีมาก</option>
                                            <option value="3">⭐⭐⭐ พอใช้</option>
                                            <option value="2">⭐⭐ ควรปรับปรุง</option>
                                            <option value="1">⭐ ไม่ประทับใจ</option>
                                        </select>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label font-bold text-xs text-muted text-uppercase">ความคิดเห็นของคุณ</label>
                                        <textarea name="comment" class="form-control border-0 bg-light p-3 rounded-3" rows="4" placeholder="แชร์ความรู้สึกของคุณหลังการใช้งาน..." required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100 py-3 rounded-3 fw-bold shadow-sm">ส่งรีวิวสินค้า</button>
                                </form>
                            <?php else: ?>
                                <div class="text-center py-4 px-3 bg-light rounded-4">
                                    <i class="bi bi-lock-fill text-muted fs-2 mb-3 d-block"></i>
                                    <p class="small text-muted mb-0">คุณสามารถเขียนรีวิวได้หลังจากสั่งซื้อและได้รับสินค้าชิ้นนี้แล้วเท่านั้น</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>