<?php
use App\Services\Auth;
$isAdmin = Auth::isAdmin();
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>โปรไฟล์ของฉัน - TECH GPU STORE</title>
    <?php if ($isAdmin): ?>
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
        <link
            href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Kanit:wght@300;400;500;600&display=swap"
            rel="stylesheet">
    <?php else: ?>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
        <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap" rel="stylesheet">
        <style>
            body {
                font-family: 'Kanit', sans-serif;
                background-color: #f8f9fa;
            }
        </style>
    <?php endif; ?>
</head>

<body <?php if ($isAdmin)
    echo 'class="bg-slate-50"'; ?>>

    <!-- Navbar -->
    <?php
    if ($isAdmin) {
        require_once APP_PATH . '/Views/admin/nav.php';
    } else {
        require_once APP_PATH . '/Views/customer/nav.php';
    }
    ?>

    <?php if ($isAdmin): ?>
        <!-- Admin Profile View (Tailwind) -->
        <div class="container admin-main-content">
            <div class="max-w-4xl mx-auto">
                <div class="mb-8">
                    <h1 class="text-2xl sm:text-3xl font-bold text-slate-800 tracking-tight">Profile Settings</h1>
                    <p class="text-sm text-slate-500 mt-1">Manage your account information and preferences.</p>
                </div>

                <?php if (isset($_GET['msg'])): ?>
                    <?php if ($_GET['msg'] === 'success'): ?>
                        <div class="bg-green-50 text-green-700 border border-green-200 rounded-xl p-4 mb-6 flex items-center gap-3">
                            <i class="bi bi-check-circle-fill text-xl"></i> บันทึกข้อมูลโปรไฟล์สำเร็จ!
                        </div>
                    <?php elseif ($_GET['msg'] === 'password_mismatch'): ?>
                        <div class="bg-red-50 text-red-700 border border-red-200 rounded-xl p-4 mb-6 flex items-center gap-3">
                            <i class="bi bi-exclamation-circle-fill text-xl"></i> รหัสผ่านและการยืนยันรหัสไม่ตรงกัน!
                        </div>
                    <?php elseif ($_GET['msg'] === 'error'): ?>
                        <div class="bg-red-50 text-red-700 border border-red-200 rounded-xl p-4 mb-6 flex items-center gap-3">
                            <i class="bi bi-x-circle-fill text-xl"></i> เกิดข้อผิดพลาดในการบันทึกข้อมูล
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
                    <form action="<?= BASE_URL ?>/profile/update" method="POST" class="p-6 sm:p-8 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">ชื่อ - นามสกุล</label>
                                <input type="text" name="name" value="<?= htmlspecialchars($user['name'] ?? '') ?>" required
                                    class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors text-slate-700">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">อีเมล</label>
                                <input type="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>"
                                    required
                                    class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors text-slate-700">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">เบอร์โทรศัพท์</label>
                                <input type="text" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>"
                                    placeholder="08X-XXX-XXXX"
                                    class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors text-slate-700">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">ที่อยู่สำหรับการจัดส่ง</label>
                            <textarea name="address" rows="3"
                                class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors text-slate-700"
                                placeholder="บ้านเลขที่, ถนน, ซอย, เขต, จังหวัด, รหัสไปรษณีย์"><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
                        </div>

                        <div class="pt-6 border-t border-slate-100">
                            <h3 class="text-lg font-bold text-slate-800 mb-4">เปลี่ยนรหัสผ่าน <span
                                    class="text-sm font-normal text-slate-500">(หากไม่ต้องการเปลี่ยนโปรดเว้นว่าง)</span>
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">รหัสผ่านใหม่</label>
                                    <input type="password" name="password" minlength="4"
                                        class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors text-slate-700">
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-semibold text-slate-700 mb-2">ยืนยันรหัสผ่านใหม่</label>
                                    <input type="password" name="confirm_password" minlength="4"
                                        class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors text-slate-700">
                                </div>
                            </div>
                        </div>

                        <div class="pt-6 border-t border-slate-100 flex justify-end gap-3">
                            <button type="submit"
                                class="px-6 py-2.5 bg-blue-600 text-white rounded-xl text-sm font-bold shadow-sm shadow-blue-600/30 hover:bg-blue-700 hover:-translate-y-0.5 transition-all">
                                บันทึกการเปลี่ยนแปลง
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php else: ?>
        <!-- Customer Profile View (Bootstrap 5) -->
        <div class="container mt-5 mb-5 pb-5">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <h2 class="fw-bold mb-4 text-primary"><i class="bi bi-person-lines-fill"></i> จัดการโปรไฟล์ของฉัน
                    </h2>

                    <?php if (isset($_GET['msg'])): ?>
                        <?php if ($_GET['msg'] === 'success'): ?>
                            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm">
                                <i class="bi bi-check-circle-fill me-2"></i> บันทึกข้อมูลโปรไฟล์สำเร็จ!
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php elseif ($_GET['msg'] === 'password_mismatch'): ?>
                            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm">
                                <i class="bi bi-exclamation-circle-fill me-2"></i> รหัสผ่านและการยืนยันรหัสไม่ตรงกัน!
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php elseif ($_GET['msg'] === 'error'): ?>
                            <div class="alert alert-warning alert-dismissible fade show border-0 shadow-sm">
                                <i class="bi bi-x-circle-fill me-2"></i> เกิดข้อผิดพลาดโปรดลองอีกครั้ง
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4 p-md-5">
                            <form action="<?= BASE_URL ?>/profile/update" method="POST">
                                <div class="row g-4 mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">ชื่อ - นามสกุล <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white"><i
                                                    class="bi bi-person text-muted"></i></span>
                                            <input type="text" name="name" class="form-control"
                                                value="<?= htmlspecialchars($user['name'] ?? '') ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">อีเมล <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white"><i
                                                    class="bi bi-envelope text-muted"></i></span>
                                            <input type="email" name="email" class="form-control"
                                                value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">เบอร์โทรศัพท์</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white"><i
                                                    class="bi bi-telephone text-muted"></i></span>
                                            <input type="text" name="phone" placeholder="08X-XXX-XXXX" class="form-control"
                                                value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-5">
                                    <label class="form-label fw-bold">ที่อยู่สำหรับการจัดส่งครบถ้วน</label>
                                    <textarea name="address" class="form-control" rows="3"
                                        placeholder="ตัวอย่าง: 123/45 หมู่ 6 ถ.สุขุมวิท ซอย 7 แขวงคลองเตย เขตคลองเตย กรุงเทพฯ 10110"><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
                                </div>

                                <h5 class="fw-bold mb-3 border-bottom pb-2">เปลี่ยนรหัสผ่าน <small
                                        class="text-muted fs-6 fw-normal">(เว้นว่างหากไม่ต้องการเปลี่ยน)</small></h5>
                                <div class="row g-4 mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label">รหัสผ่านใหม่</label>
                                        <input type="password" name="password" minlength="4" class="form-control"
                                            placeholder="••••••••">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">ยืนยันรหัสผ่านใหม่</label>
                                        <input type="password" name="confirm_password" minlength="4" class="form-control"
                                            placeholder="••••••••">
                                    </div>
                                </div>

                                <div class="text-end">
                                    <button type="submit"
                                        class="btn btn-primary px-5 py-2 fw-bold rounded-pill shadow-sm">บันทึกการเปลี่ยนแปลง
                                        <i class="bi bi-arrow-right-circle"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?php endif; ?>
</body>

</html>