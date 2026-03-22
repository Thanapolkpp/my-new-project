<?php
// คำนวณจำนวน "ชิ้น" รวมทั้งหมดในตะกร้า
$totalCartQty = 0;
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        if (is_array($item) && isset($item['qty'])) {
            $totalCartQty += (int)$item['qty'];
        } elseif (is_array($item) && isset($item['quantity'])) {
            $totalCartQty += (int)$item['quantity'];
        } elseif (is_numeric($item)) {
            // กรณีเก็บแบบ $_SESSION['cart'][$product_id] = จำนวนชิ้น
            $totalCartQty += (int)$item;
        } else {
            $totalCartQty++;
        }
    }
}
?>

<nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top shadow-sm border-bottom border-primary border-2">
    <div class="container">
        <a class="navbar-brand fw-bold fs-4" href="<?= BASE_URL ?>/home">
            <i class="bi bi-motherboard text-primary me-1"></i>
            <span class="text-primary d-inline-block align-middle">Cute boy</span> 
            <span class="text-secondary fs-6 d-inline-block align-middle mt-1">ITshop</span>
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <form class="d-flex mx-auto w-50" action="<?= BASE_URL ?>/products" method="GET">
                <input class="form-control me-2 rounded-pill px-4 bg-light border-0 shadow-sm" type="search" name="search"
                    placeholder="ค้นหาสินค้า การ์ดจอ ซีพียู..."
                    value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                <button class="btn btn-primary rounded-pill px-4 shadow-sm" type="submit"><i class="bi bi-search"></i>
                    ค้นหา</button>
            </form>
            <ul class="navbar-nav ms-auto align-items-center mt-3 mt-lg-0">
                
                <li class="nav-item me-3">
                    <a class="nav-link text-dark fw-semibold" href="<?= BASE_URL ?>/cart">
                        <i class="bi bi-cart3 fs-5 text-primary"></i>
                        <span id="cart-badge" class="badge bg-danger rounded-pill shadow-sm">
                            <?= $totalCartQty ?> 
                        </span>
                    </a>
                </li>
                
                <?php if (App\Services\Auth::check()): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-dark fw-bold" href="#" id="userDropdown" role="button"
                            data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle fs-5 text-primary align-middle"></i>
                            <span class="align-middle"><?= htmlspecialchars($_SESSION['username']) ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2 rounded-3">
                            <li><a class="dropdown-item py-2" href="<?= BASE_URL ?>/home"><i class="bi bi-shop text-primary me-2"></i>
                                    หน้าแรก</a></li>
                            <li><a class="dropdown-item py-2" href="<?= BASE_URL ?>/orders"><i
                                        class="bi bi-box-seam text-primary me-2"></i> ประวัติคำสั่งซื้อรับประกัน</a></li>
                            <li><a class="dropdown-item py-2" href="<?= BASE_URL ?>/profile"><i
                                        class="bi bi-person-bounding-box text-primary me-2"></i> ข้อมูลโปรไฟล์ของฉัน</a></li>
                            <?php if (App\Services\Auth::isAdmin()): ?>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item py-2 text-primary fw-bold bg-light rounded mx-2" style="width: 90%;"
                                        href="<?= BASE_URL ?>/admin/products"><i class="bi bi-gear-fill me-2"></i>
                                        ระบบจัดการหลังบ้าน</a></li>
                            <?php endif; ?>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item text-danger py-2 fw-semibold" href="<?= BASE_URL ?>/logout"><i
                                        class="bi bi-box-arrow-right me-2"></i> ออกจากระบบ</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item mt-2 mt-lg-0">
                        <a class="btn btn-outline-primary rounded-pill px-4 fw-semibold me-2" href="<?= BASE_URL ?>/login">เข้าสู่ระบบ</a>
                    </li>
                    <li class="nav-item mt-2 mt-lg-0">
                        <a class="btn btn-primary rounded-pill px-4 shadow fw-semibold" href="<?= BASE_URL ?>/register">สมัครสมาชิก</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<script src="<?= str_replace('index.php', '', BASE_URL) ?>assets/js/cart-toast.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Global Confirm Action with SweetAlert2
    function confirmAction(e, url, message = "คุณแน่ใจหรือไม่?", confirmText = "ยืนยัน", isDanger = true) {
        if (e) e.preventDefault();
        Swal.fire({
            title: 'โปรดยืนยัน',
            text: message,
            icon: isDanger ? 'warning' : 'question',
            showCancelButton: true,
            confirmButtonColor: isDanger ? '#dc3545' : '#0d6efd',
            cancelButtonColor: '#6c757d',
            confirmButtonText: confirmText,
            cancelButtonText: 'ยกเลิก',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-4 shadow-sm'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    }
</script>