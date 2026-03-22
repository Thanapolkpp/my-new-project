<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>
        <?= isset($promo) ? 'แก้ไขโค้ดส่วนลด' : 'เพิ่มโค้ดส่วนลดใหม่' ?> - Admin
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
        <div class="row justify-content-center">
            <div class="col-md-8">
                <a href="<?= BASE_URL ?>/admin/promocodes" class="btn btn-secondary mb-3"><i
                        class="bi bi-arrow-left"></i> กลับไปหน้ารวม</a>

                <div class="card shadow-sm border-0 rounded-4 p-4">
                    <h3 class="fw-bold mb-4 text-center">
                        <i class="bi bi-tag-fill text-warning"></i>
                        <?= isset($promo) ? 'แก้ไขโค้ดส่วนลด' : 'สร้างโค้ดส่วนลดใหม่' ?>
                    </h3>

                    <form
                        action="<?= BASE_URL ?>/admin/promocodes/<?= isset($promo) ? 'update/' . $promo['id'] : 'save' ?>"
                        method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold">รหัสโค้ด (Promo Code) <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="code" class="form-control text-uppercase font-monospace"
                                placeholder="e.g. SUMMER2026" required
                                value="<?= htmlspecialchars($promo['code'] ?? '') ?>">
                            <small class="text-muted">ตัวอักษรภาษาอังกฤษหรือตัวเลข (A-Z, 0-9)</small>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">มูลค่าส่วนลด <span
                                        class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="discount_amount" class="form-control"
                                    placeholder="100.00" required
                                    value="<?= htmlspecialchars($promo['discount_amount'] ?? '') ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">ประเภทส่วนลด</label>
                                <select name="is_percentage" class="form-select">
                                    <option value="0" <?= (isset($promo) && !$promo['is_percentage']) ? 'selected' : '' ?>>ลดเป็นจำนวนเงิน (บาท)</option>
                                    <option value="1" <?= (isset($promo) && $promo['is_percentage']) ? 'selected' : '' ?>
                                        >ลดเป็นเปอร์เซ็นต์ (%)</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">จำกัดจำนวนครั้งรวม (0 = ไม่จำกัด)</label>
                                <input type="number" name="usage_limit" class="form-control" placeholder="0"
                                    value="<?= htmlspecialchars($promo['usage_limit'] ?? '0') ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">ผู้ใช้ 1 คนใช้ได้ (ครั้ง)</label>
                                <input type="number" name="max_uses_per_user" class="form-control" placeholder="1"
                                    value="<?= htmlspecialchars($promo['max_uses_per_user'] ?? '1') ?>">
                            </div>
                        </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <label class="form-label fw-bold">วันหมดอายุ (ถ้ามี)</label>
            <?php
            $expiry_formatted = '';
            if (isset($promo['expiry_date']) && $promo['expiry_date']) {
                $expiry_formatted = date('Y-m-d\TH:i', strtotime($promo['expiry_date']));
            }
            ?>
            <input type="datetime-local" name="expiry_date" class="form-control"
                value="<?= $expiry_formatted ?>">
        </div>
    </div>

                        <div class="d-flex mb-4 pt-2 pb-2 gap-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="isActive" name="is_active"
                                    value="1" <?= (!isset($promo) || $promo['is_active']) ? 'checked' : '' ?>
                                style="transform: scale(1.3); margin-right: 10px;">
                                <label class="form-check-label fw-bold text-success" for="isActive">เปิดใช้งานระบบ</label>
                            </div>
                            
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="showOnHome" name="show_on_home"
                                    value="1" <?= (isset($promo) && $promo['show_on_home']) ? 'checked' : '' ?>
                                style="transform: scale(1.3); margin-right: 10px;">
                                <label class="form-check-label fw-bold text-primary" for="showOnHome">โชว์หน้าแรก (แบนเนอร์)</label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-3 fs-5 fw-bold rounded-3">
                            <i class="bi bi-save me-2"></i>
                            <?= isset($promo) ? 'บันทึกการปรับปรุงข้อมูล' : 'สร้างโค้ดส่วนลด' ?>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>