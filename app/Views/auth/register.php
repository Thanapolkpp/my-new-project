<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ลงทะเบียนบัญชีใหม่ - TECH GPU STORE</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Outfit:wght@500;700;800&family=Kanit:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #f59e0b;
            --secondary: #64748b;
            --glass-bg: rgba(255, 255, 255, 0.7);
            --glass-border: rgba(255, 255, 255, 0.3);
        }

        body {
            font-family: 'Inter', 'Kanit', sans-serif;
            background: #0f0e0dff;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            overflow: hidden;
            color: #f8fafc;
        }

        /* Animated Background (Same as Login) */
        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            z-index: 1;
            opacity: 0.5;
            animation: move 20s infinite alternate;
        }

        .orb-1 { width: 400px; height: 400px; background: #f59e0b; top: -100px; left: -100px; }
        .orb-2 { width: 300px; height: 300px; background: #ea580c; bottom: -50px; right: -50px; animation-delay: -5s; }
        .orb-3 { width: 250px; height: 250px; background: #fbbf24; top: 20%; right: 10%; animation-delay: -10s; }

        @keyframes move {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(50px, 50px) scale(1.1); }
        }

        .auth-container {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 460px;
            padding: 20px;
        }

        .auth-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 32px;
            padding: 40px;
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
            background: linear-gradient(135deg, #f59e0b, #9a3412);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .auth-subtitle {
            text-align: center;
            color: #64748b;
            font-size: 0.95rem;
            margin-bottom: 30px;
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
            margin-bottom: 20px;
        }

        .input-group-custom input {
            width: 100%;
            padding: 12px 16px 12px 48px;
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
            box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.1);
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
            background: linear-gradient(135deg, #f59e0b, #ea580c);
            color: white;
            border: none;
            border-radius: 16px;
            padding: 16px;
            width: 100%;
            font-weight: 700;
            font-size: 1rem;
            box-shadow: 0 10px 15px -3px rgba(245, 158, 11, 0.3);
            transition: all 0.3s;
            margin-top: 10px;
            margin-bottom: 16px;
        }

        .btn-primary-val:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(245, 158, 11, 0.4);
            filter: brightness(1.1);
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
    </style>
</head>

<body>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>

    <a href="<?php echo BASE_URL; ?>/home" class="back-home"><i class="bi bi-arrow-left me-2"></i> กลับสู่ร้านค้า</a>

    <div class="auth-container">
        <div class="auth-card">
            <h1 class="auth-title">Join Us</h1>
            <p class="auth-subtitle">สมัครสมาชิกเพื่อรับสิทธิพิเศษและโปรโมชั่น</p>
            
            <form action="<?php echo BASE_URL; ?>/register" method="POST">
                <label class="form-label">Username</label>
                <div class="input-group-custom">
                    <i class="bi bi-person"></i>
                    <input type="text" name="username" placeholder="Your name" required>
                </div>

                <label class="form-label">Email Address</label>
                <div class="input-group-custom">
                    <i class="bi bi-envelope"></i>
                    <input type="email" name="email" placeholder="name@example.com" required>
                </div>

                <label class="form-label">Password</label>
                <div class="input-group-custom">
                    <i class="bi bi-lock"></i>
                    <input type="password" name="password" placeholder="••••••••" required>
                </div>

                <button type="submit" class="btn-primary-val">Create Account</button>
            </form>

            <div class="text-center mt-4 pt-3 border-top">
                <p class="text-secondary small mb-2">เป็นสมาชิกอยู่แล้ว?</p>
                <a href="<?php echo BASE_URL; ?>/login" class="text-primary fw-bold text-decoration-none">เข้าสู่ระบบที่นี่</a>
            </div>
        </div>
    </div>
</body>

</html>