<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ชำระเงิน - TECH GPU STORE</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Kanit:wght@300;400;500;600&family=Outfit:wght@500;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
    <style>
        :root {
            --primary-bg: #030712;
            --accent-color: #3b82f6;
            --secondary-accent: #8b5cf6;
            --card-bg: rgba(17, 24, 39, 0.8);
            --glass-border: rgba(255, 255, 255, 0.08);
        }
        body {
            font-family: 'Inter', 'Kanit', sans-serif;
            background-color: var(--primary-bg);
            color: #f8fafc;
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }
        /* Animated Orbs */
        .orb {
            position: fixed;
            width: 500px;
            height: 500px;
            border-radius: 50%;
            filter: blur(120px);
            z-index: -1;
            opacity: 0.15;
            pointer-events: none;
        }
        .orb-1 { top: -10%; left: -10%; background: var(--accent-color); animation: move 20s infinite alternate; }
        .orb-2 { bottom: -10%; right: -10%; background: var(--secondary-accent); animation: move 25s infinite alternate-reverse; }
        
        @keyframes move {
            from { transform: translate(0, 0) scale(1); }
            to { transform: translate(100px, 50px) scale(1.1); }
        }

        .payment-card {
            background: var(--card-bg);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border-radius: 32px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7), inset 0 1px 1px var(--glass-border);
            border: 1px solid var(--glass-border);
            animation: slideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .price-display {
            font-family: 'Outfit', sans-serif;
            font-size: 3.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, #60a5fa 0%, #3b82f6 50%, #2563eb 100%);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -2px;
        }
        .btn-payment {
            padding: 1.25rem;
            border-radius: 20px;
            font-weight: 700;
            font-size: 1.1rem;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            border: none;
            box-shadow: 0 10px 25px -5px rgba(59, 130, 246, 0.5);
        }
        .btn-payment:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 20px 30px -5px rgba(59, 130, 246, 0.6);
            filter: brightness(1.1);
        }
        .file-upload-wrapper {
            position: relative;
            background: rgba(255, 255, 255, 0.02);
            border: 2px dashed rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            padding: 3rem;
            transition: all 0.4s ease;
        }
        .file-upload-wrapper:hover {
            background: rgba(255, 255, 255, 0.05);
            border-color: var(--accent-color);
            box-shadow: 0 0 30px rgba(59, 130, 246, 0.1);
        }
        .qr-container {
            position: relative;
            display: inline-block;
            padding: 15px;
            background: #fff;
            border-radius: 24px;
            box-shadow: 0 0 40px rgba(255, 255, 255, 0.1);
        }
        .qr-scanner-line {
            position: absolute;
            width: calc(100% - 30px);
            height: 4px;
            background: var(--accent-color);
            top: 15px;
            left: 15px;
            opacity: 0.8;
            box-shadow: 0 0 20px var(--accent-color);
            animation: scan 3s infinite ease-in-out;
            border-radius: 10px;
        }
        @keyframes scan {
            0%, 100% { top: 15px; }
            50% { top: calc(100% - 19px); }
        }
        .copy-badge {
            cursor: pointer;
            transition: all 0.2s;
            background: rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(59, 130, 246, 0.2);
            padding: 4px 12px;
            border-radius: 8px;
            font-size: 0.75rem;
        }
        .copy-badge:hover {
            background: var(--accent-color);
            color: white;
            transform: translateY(-2px);
        }
        .bank-info-box {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 24px;
            padding: 1.5rem;
            transition: all 0.3s;
        }
        .bank-info-box:hover {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.2);
        }
        .toast-copy {
            position: fixed;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            background: #10b981;
            color: white;
            padding: 12px 24px;
            border-radius: 50px;
            font-weight: 600;
            box-shadow: 0 10px 30px rgba(16, 185, 129, 0.4);
            z-index: 1000;
            animation: bounceIn 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        @keyframes bounceIn {
            from { opacity: 0; transform: translate(-50%, 50px); }
            to { opacity: 1; transform: translate(-50%, 0); }
        }
    </style>
    </style>
</head>
<body>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>

    <!-- Navbar -->
    <?php require_once APP_PATH . '/Views/customer/nav.php'; ?>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <!-- Header Section -->
                <div class="text-center mb-5">
                    <?php if ($order['status'] == 'paid'): ?>
                        <div class="status-badge mb-3 bg-success bg-opacity-25 text-success">
                            <i class="bi bi-check-circle-fill me-2"></i>แจ้งชำระเงินแล้ว
                        </div>
                    <?php else: ?>
                        <div class="status-badge mb-3">
                            <i class="bi bi-clock-history me-2"></i>รอการชำระเงิน
                        </div>
                    <?php endif; ?>
                    <h2 class="fw-bold mb-1" style="font-size: 2.2rem;">รายละเอียดการชำระเงิน</h2>
                    <p class="text-white-50">เลขที่ใบสั่งซื้อ: <span class="text-white">#<?= str_pad($order['id'], 6, '0', STR_PAD_LEFT) ?></span></p>
                </div>

                <div class="payment-card p-4 p-md-5">
                    <!-- Order Total -->
                    <div class="text-center mb-5">
                        <p class="text-white-50 mb-1 text-uppercase small ls-wide fw-bold">ยอดที่ต้องชำระทั้งสิ้น</p>
                        <h1 class="price-display">฿ <?= number_format($order['total_price'], 2) ?></h1>
                    </div>

                    <!-- Dynamic Payment UI based on Method -->
                    <div class="payment-method-ui mb-5">
                        <?php if ($order['payment_status'] === 'CREDIT_CARD'): ?>
                            <div class="card bg-white text-dark rounded-4 shadow-sm border-0 p-4 border-top border-primary border-5">
                                <h5 class="fw-bold mb-3"><i class="bi bi-credit-card-2-front me-2 text-primary"></i>ชำระผ่านบัตรเครดิต/เดบิต (Mock)</h5>
                                <div class="mb-3">
                                    <label class="form-label small text-muted">หมายเลขบัตร</label>
                                    <input type="text" class="form-control bg-light" placeholder="xxxx-xxxx-xxxx-4444" value="4444-5555-6666-7777" readonly>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <label class="form-label small text-muted">วันหมดอายุ</label>
                                        <input type="text" class="form-control bg-light" placeholder="MM/YY" value="12/28" readonly>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small text-muted">CVV</label>
                                        <input type="text" class="form-control bg-light" placeholder="***" value="***" readonly>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-primary w-100 py-3 mt-4 rounded-3 fw-bold" onclick="alert('ระบบจำลองการชำระเงินสำเร็จ! กรุณาแคปหน้าจอนี้ไว้อัปโหลดเป็นสลิปด้านล่าง')">
                                    <i class="bi bi-shield-lock-fill me-2"></i>จำลองการชำระเงิน (Secure Pay)
                                </button>
                            </div>
                        <?php else: ?>
                            <!-- Action Button to show QR -->
                            <div class="text-center">
                                <button type="button" class="btn btn-primary btn-payment w-100 fs-5" data-bs-toggle="modal" data-bs-target="#qrModal">
                                    <i class="bi bi-qr-code-scan me-2"></i>คลิกเพื่อชำระเงิน (QR PromptPay)
                                </button>
                                <p class="text-white-50 mt-3 small">
                                    <i class="bi bi-shield-check text-success me-1"></i> ระบบรองรับการสแกนผ่านทุกแอปธนาคาร
                                </p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <hr class="border-white-10 mb-5">

                    <!-- Bank Details (Always show as alternative) -->
                    <div class="bank-info-box mb-5">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 bg-primary bg-opacity-10 p-3 rounded-4 me-3">
                                    <i class="bi bi-bank fs-3 text-primary"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold">ธนาคารกสิกรไทย (K-Bank)</h6>
                                    <p class="mb-0 text-white-50 small">ชื่อบัญชี: นายธนพล ผู้ดูแลระบบ</p>
                                    <p class="mb-0 fw-bold text-accent fs-5" id="accNumber">123-4-56789-0</p>
                                </div>
                            </div>
                            <button type="button" class="btn copy-badge text-primary" onclick="copyAcc()">
                                <i class="bi bi-files me-1"></i>คัดลอก
                            </button>
                        </div>
                    </div>

                    <!-- Form to upload slip -->
                    <form action="<?= BASE_URL ?>/payment/upload/<?= $order['id'] ?>" method="POST" enctype="multipart/form-data" id="uploadForm">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold mb-0">แจ้งโอนเงิน</h5>
                            <span class="text-white-50 small">*รับไฟล์ PNG, JPG, JPEG</span>
                        </div>
                        
                        <div class="file-upload-wrapper mb-4" id="dropZone">
                            <div id="upload-preview" class="d-none">
                                <img src="" id="preview-img" class="img-fluid rounded-3 mb-3" style="max-height: 200px;">
                                <h6 id="file-name" class="text-white fw-bold mb-0"></h6>
                                <button type="button" class="btn btn-sm btn-outline-danger mt-2" onclick="resetUpload()">
                                    เปลี่ยนรูปภาพ <i class="bi bi-arrow-repeat ms-1"></i>
                                </button>
                            </div>
                            <div id="upload-instruction">
                                <div class="bg-primary bg-opacity-10 d-inline-block p-4 rounded-circle mb-3">
                                    <i class="bi bi-file-earmark-image fs-1 text-primary"></i>
                                </div>
                                <h6 class="fw-bold mb-1">อัปโหลดสลิปการโอนเงิน</h6>
                                <p id="file-chosen" class="text-white-50 small mb-0">ลากไฟล์มาวางที่นี่ หรือ คลิกเพื่อเลือกไฟล์</p>
                            </div>
                            <input type="file" name="slip" id="slip" accept="image/*" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-3 rounded-4 fw-bold mb-3" id="submitBtn">
                            <i class="bi bi-<?= $order['status'] == 'paid' ? 'arrow-repeat' : 'check-circle-fill' ?> me-2"></i>
                            <?= $order['status'] == 'paid' ? 'อัปโหลดสลิปใหม่ (แก้ไข)' : 'ยืนยันและส่งหลักฐานหน้าจอนี้' ?>
                        </button>
                        
                        <a href="<?= BASE_URL ?>/orders" class="btn btn-link w-100 text-white-50 text-decoration-none small">
                            ยังไม่ชำระตอนนี้ กลับไปยังหน้ารายการสั่งซื้อ
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- QR Code Modal -->
    <div class="modal fade" id="qrModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg p-2" style="border-radius: 35px; overflow: hidden;">
                <div class="modal-header border-0 pb-0 justify-content-end">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center pt-0 pb-5 px-4 px-md-5">
                    <img src="https://img5.pic.in.th/file/secure-sv1/PromptPay-logo89211fb50c05761a.png" 
                         alt="PromptPay Logo" style="height: 50px;" class="mb-4">
                    
                    <div class="qr-container mb-4">
                        <img src="<?= str_replace('index.php', '', BASE_URL) ?>public/assets/images/payments/my_qr_code.jpg" 
     alt="QR Code" class="img-fluid rounded-4 shadow-sm" 
     style="width: 280px; height: 280px; border: 12px solid #fff; background: #fff;">
                        <div class="qr-scanner-line"></div>
                    </div>

                    <div class="mb-4">
                        <p class="text-muted small mb-0 text-uppercase tracking-wider">จำนวนเงินที่ต้องชำระ</p>
                        <h3 class="fw-bold text-dark mb-1">฿ <?= number_format($order['total_price'], 2) ?></h3>
                    </div>
                    
                    <div class="alert alert-primary border-0 rounded-4 py-3 mb-4 text-start">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-lightbulb-fill fs-4 me-3 text-primary"></i>
                            <div>
                                <p class="mb-0 small fw-bold text-primary">แนะนำการใช้งาน</p>
                                <p class="mb-0 small text-dark opacity-75">สแกนชำระเงินผ่านแอปธนาคาร แล้วแคปหน้าจอเพื่ออัปโหลดสลิปแจ้งโอนภายหลัง</p>
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn btn-dark w-100 py-3 rounded-4 fw-bold" data-bs-dismiss="modal">
                        บันทึกเรียบร้อย
                    </button>
                    <p class="text-muted mt-3 small mb-0">ปิดหน้านี้เพื่ออัปโหลดหลักฐานแจ้งโอนด้านล่าง</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const slipInput = document.getElementById('slip');
        const fileChosen = document.getElementById('file-chosen');
        const uploadInstruction = document.getElementById('upload-instruction');
        const uploadPreview = document.getElementById('upload-preview');
        const previewImg = document.getElementById('preview-img');
        const fileName = document.getElementById('file-name');
        const dropZone = document.getElementById('dropZone');
        const submitBtn = document.getElementById('submitBtn');

        slipInput.addEventListener('change', function(){
            handleFile(this.files[0]);
        });

        function copyAcc() {
            const acc = document.getElementById('accNumber').innerText;
            navigator.clipboard.writeText(acc);
            
            const toast = document.createElement('div');
            toast.className = 'toast-copy';
            toast.innerHTML = '<i class="bi bi-check-circle-fill me-2"></i>คัดลอกเลขบัญชีสำเร็จ!';
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transition = '0.5s';
                setTimeout(() => toast.remove(), 500);
            }, 2000);
        }

        function handleFile(file) {
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    fileName.innerText = file.name;
                    uploadInstruction.classList.add('d-none');
                    uploadPreview.classList.remove('d-none');
                    dropZone.style.borderColor = '#3b82f6';
                    dropZone.style.background = 'rgba(59, 130, 246, 0.05)';
                    
                    // Style submit button when file is ready
                    submitBtn.classList.remove('btn-outline-primary');
                    submitBtn.classList.add('btn-primary');
                    
                    confetti({
                        particleCount: 100,
                        spread: 70,
                        origin: { y: 0.6 },
                        colors: ['#3b82f6', '#ffffff']
                    });
                }
                reader.readAsDataURL(file);
            }
        }

        function resetUpload() {
            slipInput.value = '';
            uploadInstruction.classList.remove('d-none');
            uploadPreview.classList.add('d-none');
            dropZone.style.borderColor = 'rgba(255, 255, 255, 0.2)';
            dropZone.style.background = 'rgba(255, 255, 255, 0.03)';
            submitBtn.classList.add('btn-outline-primary');
            submitBtn.classList.remove('btn-primary');
        }

        // Drag and Drop functionality
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults (e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => {
                dropZone.classList.add('bg-primary', 'bg-opacity-10');
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => {
                dropZone.classList.remove('bg-primary', 'bg-opacity-10');
            }, false);
        });

        dropZone.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;
            slipInput.files = files;
            handleFile(files[0]);
        }, false);
    </script>
</body>
</html>
