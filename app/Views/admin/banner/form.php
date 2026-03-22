<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>เพิ่ม/แก้ไขแบนเนอร์ - Admin</title>
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
    <?php
    require_once APP_PATH . '/Views/admin/nav.php';

    $isEdit = isset($banner);
    $action_url = BASE_URL . "/admin/banners/save";
    ?>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>
                <?= $isEdit ? 'แก้ไขแบนเนอร์' : 'เพิ่มแบนเนอร์ใหม่' ?>
            </h2>
            <a href="<?= BASE_URL ?>/admin/banners" class="btn btn-secondary"><i class="bi bi-arrow-left"></i>
                กลับไปหน้าจัดการ</a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <form action="<?= $action_url ?>" method="POST" enctype="multipart/form-data">
                    <?php if ($isEdit): ?>
                        <input type="hidden" name="id" value="<?= $banner['id'] ?>">
                    <?php endif; ?>

                    <div class="mb-3">
                        <label class="form-label">รูปภาพแบนเนอร์
                            <?= !$isEdit ? '<span class="text-danger">*</span>' : '' ?>
                        </label>
                        <input type="file" class="form-control" name="image" accept="image/*" <?= !$isEdit ? 'required' : '' ?>>
                        <div class="form-text">แนะนำขนาดภาพ 1920x1080 หรืออัตราส่วน 16:9 เพื่อความสวยงามสูงสุด</div>
                        <?php if ($isEdit && $banner['image']): ?>
                            <div class="mt-2">
                                <span class="d-block mb-1 text-muted">รูปภาพปัจจุบัน:</span>
                                <img src="<?= str_replace('index.php', '', BASE_URL) ?>public/assets/images/banners/<?= htmlspecialchars($banner['image']) ?>"
                                    alt="Current Banner" class="img-thumbnail" style="max-height: 150px;">
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">หัวข้อ (Title) [ไม่บังคับ]</label>
                        <input type="text" class="form-control" name="title"
                            value="<?= $isEdit ? htmlspecialchars($banner['title']) : '' ?>"
                            placeholder="เช่น โปรโมชั่นแห่งปี...">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">คำอธิบายย่อย (Subtitle) หรือรายละเอียด [ไม่บังคับ]</label>
                        <textarea class="form-control" name="subtitle" rows="3"
                            placeholder="รายละเอียดโปรโมชั่น..."><?= $isEdit ? htmlspecialchars($banner['subtitle']) : '' ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">ลิงก์ (URL) หากต้องการให้คลิกที่แบนเนอร์แล้วไปอีกหน้า
                            [ไม่บังคับ]</label>
                        <input type="text" class="form-control" name="link"
                            value="<?= $isEdit ? htmlspecialchars($banner['link']) : '' ?>"
                            placeholder="<?= BASE_URL ?>/products">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">ลำดับการแสดงผล (ยิ่งน้อยยิ่งแสดงก่อน)</label>
                            <input type="number" class="form-control" name="sort_order" min="0"
                                value="<?= $isEdit ? htmlspecialchars($banner['sort_order']) : '0' ?>">
                        </div>
                        <div class="col-md-6 mb-3 d-flex align-items-end">
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" role="switch" id="is_active"
                                    name="is_active" <?= (!$isEdit || $banner['is_active']) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="is_active">เปิดใช้งาน (แสดงที่หน้าหลัก)</label>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">
                    <button type="submit" class="btn btn-primary btn-lg w-100"><i class="bi bi-save"></i>
                        บันทึกข้อมูลแบนเนอร์</button>
                </form>
            </div>
        </div>
    </div>

    </div>
</body>

</html>