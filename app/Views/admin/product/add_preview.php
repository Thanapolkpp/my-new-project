<?php use App\Services\Security; ?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title><?= isset($product) ? 'แก้ไขข้อมูลสินค้า' : 'เพิ่มข้อมูลสินค้า' ?> - Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Kanit', sans-serif;
            background-color: #f4f6f9;
        }

        .card-custom {
            border-radius: 12px;
            border: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .form-label {
            font-size: 0.9rem;
            font-weight: 500;
            color: #333;
        }

        .form-control,
        .form-select {
            border-radius: 8px;
            border: 1px solid #ced4da;
            padding: 0.6rem 1rem;
        }

        .form-control:focus {
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, .1);
        }

        .req-star {
            color: red;
        }

        .btn-update {
            background-color: #dca800;
            color: white;
            border-radius: 8px;
            font-weight: 600;
        }

        .btn-update:hover {
            background-color: #c49600;
            color: white;
        }

        .preview-img-container {
            border: 2px dashed #ccc;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
            background-color: #fff;
        }

        .preview-img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            max-height: 250px;
            object-fit: contain;
        }

        .header-title-container {
            border-bottom: 3px solid #dca800;
            display: inline-block;
            padding-bottom: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="container py-4">
        <!-- Header -->
        <div class="mb-4 d-flex align-items-center">
            <a href="<?= BASE_URL ?>/admin/products"
                class="btn btn-light rounded-circle shadow-sm me-3 d-flex align-items-center justify-content-center"
                style="width: 40px; height: 40px; border: 1px solid #ddd;">
                <i class="bi bi-arrow-left text-primary fs-5"></i>
            </a>
            <div class="header-title-container">
                <h3 class="mb-0 fw-bold" style="color: #333;">
                    <i
                        class="bi bi-tools text-secondary me-2"></i><?= isset($product) ? 'แก้ไขข้อมูลสินค้า' : 'เพิ่มข้อมูลสินค้า' ?>
                </h3>
            </div>
        </div>

        <form id="productForm"
            action="<?= BASE_URL ?>/admin/products/<?= isset($product) ? 'update/' . $product['id'] : 'save' ?>"
            method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">

            <div class="row g-4">
                <!-- Left Form Side -->
                <div class="col-lg-7">
                    <div class="card card-custom p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">ชื่อสินค้า <span class="req-star">*</span></label>
                                <input type="text" class="form-control" name="product_name" id="nameInput"
                                    value="<?= htmlspecialchars($product['product_name'] ?? '') ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">รหัสซีเรียล <span class="req-star">*</span></label>
                                <input type="text" class="form-control" name="serial_number"
                                    value="<?= htmlspecialchars($product['serial_number'] ?? '') ?>" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">ราคา (บาท) <span class="req-star">*</span></label>
                                <input type="number" class="form-control" name="price" id="priceInput"
                                    value="<?= htmlspecialchars($product['price'] ?? '0') ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold"><i class="bi bi-patch-check"></i>
                                    มาตรฐานไฟฟ้า</label>
                                <div class="input-group input-group-sm shadow-sm rounded">
                                    <span class="input-group-text bg-light border-end-0"><i
                                            class="bi bi-lightning"></i></span>
                                    <input type="text" name="certifications" id="certInput"
                                        class="form-control border-start-0 bg-light"
                                        placeholder="เช่น 80+ Gold, Bronze..."
                                        value="<?= htmlspecialchars($product['certifications'] ?? '') ?>">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">ส่วนลด (บาท)</label>
                                <input type="number" class="form-control" name="discount" id="discountInput"
                                    value="<?= htmlspecialchars($product['discount'] ?? '0') ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">จำนวนสินค้าในคลัง <span class="req-star">*</span></label>
                                <input type="number" class="form-control" name="stock" id="stockInput"
                                    value="<?= htmlspecialchars($product['stock'] ?? '0') ?>" required>
                            </div>

                            <div class="col-12 mt-4">
                                <label class="form-label">รายละเอียดสินค้า</label>
                                <textarea class="form-control" name="detail" id="detailInput"
                                    rows="4"><?= htmlspecialchars($product['detail'] ?? '') ?></textarea>
                            </div>

                            <!-- Using Image URL block looking similar to file upload -->
                            <div class="col-12 mt-4">
                                <div class="p-3 border rounded bg-light" style="border-radius: 8px;">
                                    <label class="form-label fw-bold mb-3 d-block text-secondary">รูปภาพสินค้า <span
                                            class="fw-normal">(กรอก URL รูปภาพ)</span></label>

                                    <div class="d-flex align-items-center mb-2">
                                        <div class="form-check me-4">
                                            <input class="form-check-input" type="radio" name="uploadType"
                                                id="uploadFile" value="file" onchange="toggleUploadType()">
                                            <label class="form-check-label text-muted"
                                                for="uploadFile">อัปโหลดไฟล์ใหม่</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="uploadType"
                                                id="uploadUrl" value="url" onchange="toggleUploadType()" checked>
                                            <label class="form-check-label" for="uploadUrl">ใช้ลิงก์ URL ใหม่</label>
                                        </div>
                                    </div>
                                    <div id="urlInputGroup">
                                        <input type="url" class="form-control mt-2" name="img" id="imgInput"
                                            value="<?= htmlspecialchars($product['img'] ?? '') ?>"
                                            placeholder="https://เว็บไซต์.com/รูปภาพ.jpg">
                                    </div>
                                    <div id="fileInputGroup" style="display: none;">
                                        <input type="file" class="form-control mt-2" name="img_file" id="imgFileInput"
                                            accept="image/*">
                                        <small class="text-muted d-block mt-1">ขนาดแนะนำ 800x600 px</small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mt-4">
                                <label class="form-label">ระยะเวลารับประกัน</label>
                                <div class="d-flex gap-2">
                                    <input type="number" class="form-control" name="warranty_value"
                                        value="<?= htmlspecialchars($product['warranty_value'] ?? '0') ?>"
                                        style="max-width: 150px;">
                                    <select class="form-select" name="warranty_unit" style="max-width: 120px;">
                                        <option value="month" <?= (isset($product) && ($product['warranty_unit'] == 'month' || $product['warranty_unit'] == 'เดือน')) ? 'selected' : '' ?>>เดือน</option>
                                        <option value="year" <?= (isset($product) && ($product['warranty_unit'] == 'year' || $product['warranty_unit'] == 'ปี' || $product['warranty_unit'] == '')) ? 'selected' : '' ?>>ปี</option>
                                        <option value="day" <?= (isset($product) && ($product['warranty_unit'] == 'day' || $product['warranty_unit'] == 'วัน')) ? 'selected' : '' ?>>วัน</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-update w-100 py-3 fs-5">
                                    <i
                                        class="bi bi-save me-2"></i><?= isset($product) ? 'อัปเดตข้อมูลสินค้า' : 'บันทึกข้อมูลสินค้า' ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Preview Side -->
                <div class="col-lg-5">
                    <div class="card card-custom p-4 sticky-top" style="top: 20px;">
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-eye text-danger me-2" style="font-size: 1.2rem;"></i>
                            <h5 class="mb-0 fw-bold text-secondary" style="font-size: 1.1rem; font-style: italic;">
                                พรีวิวหลังแก้ไข</h5>
                        </div>

                        <div class="preview-img-container">
                            <img id="previewImg"
                                src="<?= htmlspecialchars($product['img'] ?? 'https://via.placeholder.com/400x300?text=No+Image') ?>"
                                class="preview-img" alt="Preview Image">
                        </div>

                        <div class="text-center px-3">
                            <h4 id="previewTitle" class="fw-bold mb-3" style="color: #2c3e50; min-height: 28px;">
                                <?= htmlspecialchars($product['product_name'] ?? 'N/A') ?>
                            </h4>

                            <div class="mb-1" style="min-height: 20px;">
                                <span id="previewOriginalPrice" class="text-muted text-decoration-line-through me-2"
                                    style="display:none; font-size: 0.95rem;"></span>
                            </div>
                            <h3 id="previewNetPrice" class="text-danger fw-bold mb-3">
                                ฿<?= number_format($product['price'] ?? 0, 2) ?>
                            </h3>

                            <div class="mb-3 fw-bold" style="color: #28a745; font-size: 0.9rem;" id="previewStock">IN
                                STOCK: <?= htmlspecialchars($product['stock'] ?? '0') ?></div>

                            <div class="mb-4" style="min-height: 24px;">
                                <span id="previewCert"
                                    class="badge rounded-pill bg-success bg-opacity-10 text-success border border-success px-3 py-2"
                                    style="display:none; font-size: 0.8rem;"></span>
                            </div>

                            <hr style="border-color: #eee; margin-top: 25px; margin-bottom: 25px;">

                            <p id="previewDetail" class="text-muted small px-3 text-start" style="line-height: 1.6;">
                                <?= htmlspecialchars($product['detail'] ?? '') ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        const els = {
            name: document.getElementById('nameInput'),
            price: document.getElementById('priceInput'),
            discount: document.getElementById('discountInput'),
            stock: document.getElementById('stockInput'),
            img: document.getElementById('imgInput'),
            imgFile: document.getElementById('imgFileInput'),
            detail: document.getElementById('detailInput'),
            cert: document.getElementById('certInput'),

            pTitle: document.getElementById('previewTitle'),
            pDetail: document.getElementById('previewDetail'),
            pImg: document.getElementById('previewImg'),
            pNetPrice: document.getElementById('previewNetPrice'),
            pOriginalPrice: document.getElementById('previewOriginalPrice'),
            pStock: document.getElementById('previewStock'),
            pCert: document.getElementById('previewCert'),
        };

        function toggleUploadType() {
            const isFile = document.getElementById('uploadFile').checked;
            document.getElementById('fileInputGroup').style.display = isFile ? 'block' : 'none';
            document.getElementById('urlInputGroup').style.display = isFile ? 'none' : 'block';

            if (isFile) {
                els.img.value = ''; // clear url
            } else {
                els.imgFile.value = ''; // clear file
            }
            updatePreview();
        }

        function updatePreview() {
            els.pTitle.textContent = els.name.value || 'N/A';
            els.pDetail.textContent = els.detail.value || '';

            if (document.getElementById('uploadFile').checked && els.imgFile.files && els.imgFile.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    els.pImg.src = e.target.result;
                }
                reader.readAsDataURL(els.imgFile.files[0]);
            } else {
                els.pImg.src = els.img.value || 'https://via.placeholder.com/400x300?text=No+Image';
            }

            els.pStock.textContent = 'IN STOCK: ' + (els.stock.value || '0');

            if (els.cert.value) {
                els.pCert.innerHTML = '<i class="bi bi-shield-check me-1"></i> ' + els.cert.value;
                els.pCert.style.display = 'inline-block';
            } else {
                els.pCert.style.display = 'none';
            }

            let price = parseFloat(els.price.value) || 0;
            let discount = parseFloat(els.discount.value) || 0;

            if (discount > 0) {
                let net = price - discount;
                els.pNetPrice.textContent = '฿' + net.toLocaleString('th-TH', { minimumFractionDigits: 2 });
                els.pOriginalPrice.textContent = '฿' + price.toLocaleString('th-TH', { minimumFractionDigits: 2 });
                els.pOriginalPrice.style.display = 'inline';
            } else {
                els.pNetPrice.textContent = '฿' + price.toLocaleString('th-TH', { minimumFractionDigits: 2 });
                els.pOriginalPrice.style.display = 'none';
            }
        }

        Object.keys(els).forEach(key => {
            if (els[key] && (els[key].tagName === 'INPUT' || els[key].tagName === 'TEXTAREA')) {
                els[key].addEventListener('input', updatePreview);
            }
        });

        updatePreview();
    </script>
</body>

</html>