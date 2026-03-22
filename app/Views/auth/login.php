<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ - TECH GPU STORE</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Outfit:wght@500;700;800&family=Kanit:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #2563eb;
            --secondary: #64748b;
            --glass-bg: rgba(255, 255, 255, 0.7);
            --glass-border: rgba(255, 255, 255, 0.3);
        }

        body {
            font-family: 'Inter', 'Kanit', sans-serif;
            background: #0f172a;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            overflow: hidden;
            color: #f8fafc;
        }

        /* Animated Background */
        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            z-index: 1;
            opacity: 0.5;
            animation: move 20s infinite alternate;
        }

        .orb-1 { width: 400px; height: 400px; background: #1d4ed8; top: -100px; left: -100px; }
        .orb-2 { width: 300px; height: 300px; background: #7e22ce; bottom: -50px; right: -50px; animation-delay: -5s; }
        .orb-3 { width: 250px; height: 250px; background: #0ea5e9; top: 20%; right: 10%; animation-delay: -10s; }

        @keyframes move {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(50px, 50px) scale(1.1); }
        }

        .auth-container {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 440px;
            padding: 20px;
        }

        .auth-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 32px;
            padding: 48px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            color: #1e293b;
        }

        .auth-title {
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 2.25rem;
            letter-spacing: -1px;
            margin-bottom: 8px;
            text-align: center;
            background: linear-gradient(135deg, #1e293b, #334155);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .auth-subtitle {
            text-align: center;
            color: #64748b;
            font-size: 0.95rem;
            margin-bottom: 40px;
        }

        .form-label {
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #475569;
            margin-bottom: 8px;
        }

        .input-group-custom {
            position: relative;
            margin-bottom: 24px;
        }

        .input-group-custom input {
            width: 100%;
            padding: 14px 16px 14px 48px;
            background: rgba(255, 255, 255, 0.5);
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            color: #1e293b;
            transition: all 0.3s;
            font-size: 1rem;
        }

        .input-group-custom input:focus {
            outline: none;
            border-color: var(--primary);
            background: #fff;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }

        .input-group-custom i {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 1.2rem;
        }

        .btn-primary-val {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white;
            border: none;
            border-radius: 16px;
            padding: 16px;
            width: 100%;
            font-weight: 700;
            font-size: 1rem;
            box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.3);
            transition: all 0.3s;
            margin-bottom: 16px;
        }

        .btn-primary-val:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(37, 99, 235, 0.4);
            filter: brightness(1.1);
        }

        .btn-outline-val {
            background: transparent;
            color: #475569;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 12px;
            width: 100%;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.2s;
        }

        .btn-outline-val:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
        }

        .back-home {
            position: fixed;
            top: 24px;
            left: 24px;
            z-index: 100;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            padding: 10px 20px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s;
        }

        .back-home:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateX(-5px);
            color: white;
        }

        .otp-badge {
            background: #dcfce7;
            color: #15803d;
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 700;
            margin-bottom: 16px;
            display: inline-block;
        }
    </style>
</head>

<body>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>

    <a href="<?php echo BASE_URL; ?>/home" class="back-home"><i class="bi bi-arrow-left me-2"></i> กลับสู่ร้านค้า</a>

    <div class="auth-container">
        <div class="auth-card">
            <div id="password-login">
                <h1 class="auth-title">Welcome Back</h1>
                <p class="auth-subtitle">เข้าสู่ระบบเพื่อจัดการคลัง GPU ของคุณ</p>
                
                <form action="<?php echo BASE_URL; ?>/login" method="POST">
                    <label class="form-label">Email or Username</label>
                    <div class="input-group-custom">
                        <i class="bi bi-person-circle"></i>
                        <input type="text" name="login_id" placeholder="Email or Username" required>
                    </div>

                    <label class="form-label">Password</label>
                    <div class="input-group-custom">
                        <i class="bi bi-lock"></i>
                        <input type="password" name="password" placeholder="••••••••" required>
                    </div>

                    <button type="submit" class="btn-primary-val">Sign In</button>
                    
                    <button type="button" class="btn-outline-val" onclick="toggleOtpMode(true)">
                        <i class="bi bi-shield-lock me-2"></i> เข้าด้วยรหัส OTP ทางอีเมล
                    </button>
                </form>
            </div>

        <!-- OTP Login Section -->
        <div id="otp-login" style="display: none;">
            <div id="step-request-otp">
                <h1 class="auth-title">Verify with OTP</h1>
                <p class="auth-subtitle">เราจะส่งรหัส 6 หลักไปที่อีเมลของคุณ</p>
                
                <label class="form-label">Email Address</label>
                <div class="input-group-custom">
                    <i class="bi bi-envelope"></i>
                    <input type="email" id="otp-email" placeholder="name@example.com">
                </div>

                <button type="button" onclick="requestOtp()" class="btn-primary-val" id="btn-send-otp">
                    Send OTP Code
                </button>
            </div>

            <div id="step-verify-otp" style="display: none;">
                <h1 class="auth-title">Check Your Inbox</h1>
                <div class="text-center">
                    <span class="otp-badge"><i class="bi bi-send-fill me-1"></i> Sent to <span id="display-email"></span></span>
                </div>
                
                <label class="form-label text-center d-block mt-2">Enter 6-Digit Code</label>
                <div class="input-group-custom">
                    <i class="bi bi-key-fill"></i>
                    <input type="text" id="otp-code" class="text-center fw-bold fs-4" placeholder="000000" maxlength="6" style="letter-spacing: 8px; padding-left: 16px;">
                </div>

                <button type="button" onclick="verifyOtp()" class="btn-primary-val" id="btn-verify-otp">
                    Verify & Sign In
                </button>
                
                <div class="text-center">
                    <button type="button" class="btn btn-link text-decoration-none text-muted small" onclick="requestOtp()">
                        ไม่ได้รับรหัส? <span class="text-primary fw-bold">ส่งอีกครั้ง</span>
                    </button>
                </div>
            </div>

            <div class="text-center mt-4">
                <button type="button" class="btn btn-link text-decoration-none text-secondary small" onclick="toggleOtpMode(false)">
                    <i class="bi bi-arrow-left me-1"></i> กลับไปใช้อีเมลและรหัสผ่าน
                </button>
            </div>
        </div>

        <div class="text-center mt-5 pt-4 border-top">
            <p class="text-secondary small mb-2">ยังไม่มีบัญชีสมาชิก?</p>
            <a href="<?php echo BASE_URL; ?>/register" class="text-primary fw-bold text-decoration-none">สมัครสมาชิกใหม่ที่นี่</a>
        </div>

        <script>
            function toggleOtpMode(isOtp) {
                document.getElementById('password-login').style.display = isOtp ? 'none' : 'block';
                document.getElementById('otp-login').style.display = isOtp ? 'block' : 'none';
                document.getElementById('step-request-otp').style.display = 'block';
                document.getElementById('step-verify-otp').style.display = 'none';
            }

            async function requestOtp() {
                const email = document.getElementById('otp-email').value;
                if (!email) {
                    alert('กรุณากรอกอีเมลของคุณ');
                    return;
                }

                const btn = document.getElementById('btn-send-otp');
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Sending...';

                try {
                    const response = await fetch('<?php echo BASE_URL; ?>/otp/request', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: `email=${encodeURIComponent(email)}`
                    });
                    const data = await response.json();
                    
                    if (data.success) {
                        document.getElementById('display-email').innerText = email;
                        document.getElementById('step-request-otp').style.display = 'none';
                        document.getElementById('step-verify-otp').style.display = 'block';
                    } else {
                        alert(data.message);
                    }
                } catch (error) {
                    alert('เกิดข้อผิดพลาดในการเชื่อมต่อ กรุณาลองใหม่');
                } finally {
                    btn.disabled = false;
                    btn.innerText = 'Send OTP Code';
                }
            }

            async function verifyOtp() {
                const email = document.getElementById('otp-email').value;
                const otp = document.getElementById('otp-code').value;
                
                if (!otp || otp.length < 6) {
                    alert('กรุณากรอกรหัส OTP 6 หลัก');
                    return;
                }

                const btn = document.getElementById('btn-verify-otp');
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Verifying...';

                try {
                    const response = await fetch('<?php echo BASE_URL; ?>/otp/login', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: `email=${encodeURIComponent(email)}&otp=${encodeURIComponent(otp)}`
                    });
                    const data = await response.json();
                    
                    if (data.success) {
                        window.location.href = data.redirect;
                    } else {
                        alert(data.message);
                    }
                } catch (error) {
                    alert('เกิดข้อผิดพลาดในการตรวจสอบรหัส');
                } finally {
                    btn.disabled = false;
                    btn.innerText = 'Verify & Sign In';
                }
            }
        </script>
    </div>
</body>

</html>