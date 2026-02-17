<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Stok Tas Fasasa</title>
    <link rel="icon" href="{{ asset('logo_fasasa.ico') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            background-color: #fafafa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            font-size: 13px;
            padding: 16px;
        }
        .login-container {
            width: 100%;
            max-width: 380px;
        }
        .login-card {
            background: #ffffff;
            border-radius: 12px;
            padding: 30px 24px;
            border: 1px solid #e5e5e5;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            animation: cardEntrance 0.6s cubic-bezier(0.4, 0, 0.2, 1) forwards;
            opacity: 0;
            transform: translateY(20px);
        }
        @keyframes cardEntrance {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .logo-section {
            text-align: center;
            margin-bottom: 28px;
        }
        .logo-section img {
            height: 50px;
            width: auto;
            margin-bottom: 12px;
            transition: transform 0.3s ease;
        }
        .logo-section h1 {
            font-size: 18px;
            font-weight: 600;
            color: #000;
            margin-bottom: 4px;
            letter-spacing: -0.5px;
            animation: fadeInUp 0.5s ease 0.1s forwards;
            opacity: 0;
        }
        .logo-section p {
            font-size: 12px;
            color: #666;
            margin: 0;
            animation: fadeInUp 0.5s ease 0.2s forwards;
            opacity: 0;
        }
        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
            from {
                opacity: 0;
                transform: translateY(10px);
            }
        }
        .form-group {
            margin-bottom: 16px;
        }
        .form-label {
            display: block;
            font-size: 12px;
            font-weight: 500;
            color: #000;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .form-control {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            background-color: #fafafa;
            transition: all 0.2s ease;
        }
        .form-control:focus {
            border-color: #000;
            background-color: #fff;
            box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.05);
            outline: none;
        }
        .form-check {
            margin-bottom: 24px;
            display: flex;
            align-items: center;
        }
        .form-check-input {
            width: 18px;
            height: 18px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-right: 10px;
            cursor: pointer;
            transition: all 0.2s ease;
            flex-shrink: 0;
        }
        .form-check-input:checked {
            background-color: #000;
            border-color: #000;
        }
        .form-check-label {
            font-size: 13px;
            color: #666;
            cursor: pointer;
            margin-bottom: 0;
            line-height: 1.4;
        }
        .btn-login {
            width: 100%;
            padding: 12px;
            background-color: #000;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-transform: uppercase;
            letter-spacing: 0.3px;
            position: relative;
            overflow: hidden;
            animation: gentleBounce 2s ease-in-out infinite;
            animation-delay: 1s;
        }
        @keyframes gentleBounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-3px); }
        }
        .btn-login:hover {
            background-color: #222;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }
        .btn-login i {
            transition: transform 0.3s ease;
        }
        .btn-login:hover i {
            transform: translateX(3px);
        }
        .alert {
            font-size: 12px;
            padding: 12px 14px;
            margin-bottom: 20px;
            border-radius: 8px;
            border: 1px solid;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            animation: slideIn 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .alert i {
            flex-shrink: 0;
            margin-top: 1px;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        .alert-success {
            background-color: #f0f9ff;
            border-color: #cce5ff;
            color: #003d99;
        }
        .alert-danger {
            background-color: #fff5f5;
            border-color: #ffcccc;
            color: #cc0000;
        }
        .login-info {
            margin-top: 28px;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #e5e5e5;
            transition: all 0.3s ease;
        }
        .login-info:hover {
            border-color: #ccc;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        }
        .login-info-header {
            background-color: #f5f5f5;
            padding: 12px 16px;
            border-bottom: 1px solid #e5e5e5;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            user-select: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .login-info-header:hover {
            background-color: #eee;
        }
        .login-info-title {
            font-size: 13px;
            font-weight: 600;
            color: #000;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
        }
        .login-info-title i {
            font-size: 15px;
            color: #666;
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .login-info-header:hover .login-info-title i {
            transform: rotate(15deg);
        }
        .login-info-toggle {
            background: none;
            border: none;
            color: #666;
            font-size: 16px;
            cursor: pointer;
            padding: 6px;
            border-radius: 6px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-info-toggle:hover {
            color: #000;
            background-color: rgba(0,0,0,0.05);
            transform: scale(1.1);
        }
        .login-info-content {
            background-color: #fafafa;
            padding: 0;
            max-height: 0;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            opacity: 0;
        }
        .login-info-content.show {
            max-height: 200px;
            opacity: 1;
            padding: 16px;
        }
        .login-info-grid {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 10px 16px;
            font-size: 13px;
            animation: fadeIn 0.5s ease forwards;
            opacity: 0;
        }
        @keyframes fadeIn {
            to { opacity: 1; transform: translateY(0); }
            from { opacity: 0; transform: translateY(-5px); }
        }
        .login-info-content.show .login-info-grid {
            animation: fadeIn 0.5s ease 0.2s forwards;
        }
        .login-info-label {
            font-weight: 600;
            color: #333;
            white-space: nowrap;
            transition: color 0.2s ease;
        }
        .login-info:hover .login-info-label {
            color: #000;
        }
        .login-info-value {
            color: #333;
            transition: color 0.2s ease;
            word-break: break-all;
        }
        .login-info-value code {
            background-color: #e5e5e5;
            padding: 6px 10px;
            border-radius: 6px;
            font-family: 'Monaco', 'Courier New', monospace;
            font-size: 12px;
            color: #000;
            display: inline-block;
            margin-top: -1px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid transparent;
            word-break: break-all;
        }
        .login-info-value code:hover {
            background-color: #d5d5d5;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .login-info-note {
            grid-column: 1 / -1;
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px dashed #ddd;
            font-size: 12px;
            color: #666;
            display: flex;
            align-items: flex-start;
            gap: 8px;
            transition: all 0.3s ease;
        }
        .login-info-note i {
            flex-shrink: 0;
            margin-top: 2px;
            color: #f5a623;
            animation: glow 2s ease-in-out infinite alternate;
        }
        @keyframes glow {
            from { filter: drop-shadow(0 0 2px rgba(245, 166, 35, 0.3)); }
            to { filter: drop-shadow(0 0 4px rgba(245, 166, 35, 0.6)); }
        }
        .footer {
            text-align: center;
            margin-top: 28px;
            font-size: 12px;
            color: #999;
            transition: color 0.3s ease;
            padding: 0 8px;
        }
        .password-field {
            position: relative;
        }
        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #666;
            font-size: 16px;
            cursor: pointer;
            padding: 6px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2;
        }
        .password-toggle:hover {
            color: #000;
            transform: translateY(-50%) scale(1.1);
        }
        
        /* Responsive Styles */
        @media (max-width: 768px) {
            body {
                padding: 12px;
                align-items: flex-start;
                padding-top: 40px;
            }
            
            .login-container {
                max-width: 100%;
            }
            
            .login-card {
                padding: 24px 20px;
                border-radius: 10px;
            }
            
            .logo-section {
                margin-bottom: 24px;
            }
            
            .logo-section img {
                height: 45px;
            }
            
            .logo-section h1 {
                font-size: 16px;
            }
            
            .logo-section p {
                font-size: 11px;
            }
            
            .form-control {
                padding: 14px 16px;
                font-size: 16px; /* Larger for mobile */
            }
            
            .form-label {
                font-size: 13px;
            }
            
            .btn-login {
                padding: 14px;
                font-size: 15px;
            }
            
            .login-info-header {
                padding: 14px 16px;
            }
            
            .login-info-title {
                font-size: 14px;
            }
            
            .login-info-grid {
                grid-template-columns: 1fr;
                gap: 8px;
            }
            
            .login-info-label,
            .login-info-value {
                font-size: 14px;
            }
            
            .login-info-value code {
                font-size: 13px;
                padding: 8px 12px;
                width: 100%;
                display: block;
                text-align: center;
                margin-top: 4px;
            }
            
            .login-info-note {
                font-size: 13px;
                flex-direction: column;
                align-items: flex-start;
                gap: 6px;
            }
            
            .footer {
                font-size: 11px;
                margin-top: 24px;
            }
            
            .password-toggle {
                right: 14px;
                font-size: 18px;
            }
            
            /* Adjust form check for mobile */
            .form-check {
                margin-bottom: 20px;
            }
            
            .form-check-label {
                font-size: 14px;
            }
        }
        
        @media (max-width: 480px) {
            body {
                padding: 8px;
                padding-top: 30px;
            }
            
            .login-card {
                padding: 20px 16px;
                border-radius: 8px;
            }
            
            .logo-section img {
                height: 40px;
            }
            
            .logo-section h1 {
                font-size: 15px;
            }
            
            .form-control {
                padding: 12px 14px;
            }
            
            .btn-login {
                padding: 13px;
                font-size: 14px;
            }
            
            .alert {
                padding: 10px 12px;
                font-size: 13px;
            }
            
            .login-info-header {
                padding: 12px 14px;
            }
            
            .login-info-title {
                font-size: 13px;
            }
            
            .login-info-toggle {
                font-size: 15px;
                padding: 5px;
            }
            
            .footer {
                font-size: 10px;
            }
        }
        
        @media (max-width: 320px) {
            .login-card {
                padding: 16px 12px;
            }
            
            .logo-section h1 {
                font-size: 14px;
            }
            
            .form-control {
                font-size: 15px;
            }
            
            .btn-login {
                font-size: 13px;
            }
        }
        
        /* Landscape mode optimization */
        @media (max-height: 600px) and (orientation: landscape) {
            body {
                align-items: flex-start;
                padding-top: 20px;
                padding-bottom: 20px;
            }
            
            .login-card {
                padding: 20px;
                max-height: 90vh;
                overflow-y: auto;
            }
            
            .logo-section {
                margin-bottom: 20px;
            }
            
            .form-group {
                margin-bottom: 12px;
            }
            
            .login-info {
                margin-top: 20px;
            }
        }
        
        /* Dark mode support */
        /* @media (prefers-color-scheme: dark) {
            body {
                background-color: #121212;
            }
            
            .login-card {
                background-color: #1e1e1e;
                border-color: #333;
                color: #e0e0e0;
            }
            
            .logo-section h1,
            .form-label {
                color: #e0e0e0;
            }
            
            .form-control {
                background-color: #2d2d2d;
                border-color: #444;
                color: #e0e0e0;
            }
            
            .form-control:focus {
                border-color: #666;
                background-color: #333;
            }
            
            .login-info {
                border-color: #333;
            }
            
            .login-info-header {
                background-color: #2d2d2d;
                border-color: #333;
            }
            
            .login-info-content {
                background-color: #252525;
            }
            
            .login-info-label,
            .login-info-value {
                color: #e0e0e0;
            }
            
            .login-info-value code {
                background-color: #333;
                color: #e0e0e0;
                border-color: #444;
            }
            
            .footer {
                color: #888;
            }
        } */
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="logo-section">
                <img src="{{ asset('logofasasa_black.png') }}" alt="Fasasa" 
                     onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHJ4PSI4IiBmaWxsPSIjMDAwMDAwIi8+PHBhdGggZD0iTTE1IDIwTDM1IDIwTDM4IDI4TDM1IDM2TDE1IDM2TDEyIDI4WiIgZmlsbD0iI2ZmZmZmZiIgc3Ryb2tlPSIjZmZmZmZmIiBzdHJva2Utd2lkdGg9IjIiLz48L3N2Zz4='">
                <h1>Stok Tas Fasasa</h1>
                <p>Admin Dashboard</p>
            </div>

            @if(session('status'))
                <div class="alert alert-success">
                    <i class="bi bi-check-circle"></i>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <div>
                        <i class="bi bi-exclamation-circle" style="margin-top: 0;"></i>
                    </div>
                    <div>
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="bi bi-x-circle"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" style="animation: fadeInUp 0.5s ease 0.3s forwards; opacity: 0;">
                @csrf

                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input id="email" type="email" class="form-control" name="email" 
                           value="{{ old('email') }}" required autofocus>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="password-field">
                        <input id="password" type="password" class="form-control" 
                               name="password" required>
                        <button type="button" class="password-toggle" id="togglePassword">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">
                        Ingat saya
                    </label>
                </div>

                <button type="submit" class="btn-login">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Login
                </button>
            </form>

            <!-- Demo Credentials dengan Toggle -->
            <div class="login-info" style="animation: fadeInUp 0.5s ease 0.4s forwards; opacity: 0;">
                <div class="login-info-header" id="toggleDemoInfo">
                    <div class="login-info-title">
                        <i class="bi bi-key"></i>
                        <span>MAU NGAPAIN LU MONYETT</span>
                    </div>
                    <button class="login-info-toggle" type="button" aria-label="Toggle demo credentials">
                        <i class="bi bi-eye-slash" id="toggleIcon"></i>
                    </button>
                </div>
                <div class="login-info-content" id="demoInfoContent">
                    <div class="login-info-grid">
                        <div class="login-info-label">Email:</div>
                        <div class="login-info-value">
                            <code>admin1@fasasa.com</code>
                        </div>
                        
                        <div class="login-info-label">Password:</div>
                        <div class="login-info-value">
                            <code>admin123</code>
                        </div>
                        
                        <div class="login-info-note">
                            <i class="bi bi-lightbulb"></i>
                            <span>Gunakan otak anda untuk mencoba sistem</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="footer" style="animation: fadeInUp 0.5s ease 0.5s forwards; opacity: 0;">
                <i class="bi bi-c-circle"></i> {{ date('Y') }} Fasasa. All rights reserved.
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Toggle Password Visibility dengan animasi smooth
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            
            // Smooth transition untuk icon
            const eyeIcon = this.querySelector('i');
            eyeIcon.style.transform = 'scale(0.8)';
            
            setTimeout(() => {
                passwordInput.setAttribute('type', type);
                
                if (type === 'text') {
                    eyeIcon.classList.remove('bi-eye');
                    eyeIcon.classList.add('bi-eye-slash');
                } else {
                    eyeIcon.classList.remove('bi-eye-slash');
                    eyeIcon.classList.add('bi-eye');
                }
                
                eyeIcon.style.transform = 'scale(1.1)';
                
                setTimeout(() => {
                    eyeIcon.style.transform = 'scale(1)';
                }, 150);
            }, 100);
        });

        // Toggle Demo Credentials Visibility dengan animasi lebih smooth
        const toggleDemoInfo = document.getElementById('toggleDemoInfo');
        const demoInfoContent = document.getElementById('demoInfoContent');
        const toggleIcon = document.getElementById('toggleIcon');
        
        // STATE: Default hidden saat pertama load
        let isDemoInfoVisible = false;
        
        // Fungsi untuk toggle visibility dengan animasi
        function toggleDemoInfoVisibility() {
            isDemoInfoVisible = !isDemoInfoVisible;
            
            // Animasi untuk icon
            toggleIcon.style.transform = 'scale(0.8)';
            
            setTimeout(() => {
                if (isDemoInfoVisible) {
                    demoInfoContent.classList.add('show');
                    toggleIcon.classList.remove('bi-eye-slash');
                    toggleIcon.classList.add('bi-eye');
                } else {
                    demoInfoContent.classList.remove('show');
                    toggleIcon.classList.remove('bi-eye');
                    toggleIcon.classList.add('bi-eye-slash');
                }
                
                toggleIcon.style.transform = 'scale(1.1)';
                
                setTimeout(() => {
                    toggleIcon.style.transform = 'scale(1)';
                }, 150);
            }, 100);
        }
        
        // Event listener untuk klik header
        toggleDemoInfo.addEventListener('click', toggleDemoInfoVisibility);
        
        // Event listener untuk touch devices
        toggleDemoInfo.addEventListener('touchstart', function(e) {
            e.preventDefault();
            toggleDemoInfoVisibility();
        }, { passive: false });
        
        // Optimasi untuk mobile
        document.addEventListener('DOMContentLoaded', function() {
            // Adjust viewport untuk mobile
            if ('ontouchstart' in window) {
                document.documentElement.style.setProperty('--vh', `${window.innerHeight * 0.01}px`);
                
                // Prevent zoom on input focus
                const inputs = document.querySelectorAll('input, select, textarea');
                inputs.forEach(input => {
                    input.addEventListener('focus', function() {
                        if (window.innerWidth < 768) {
                            this.style.fontSize = '16px'; // Prevent zoom on iOS
                        }
                    });
                });
            }
            
            // Handle keyboard visibility on mobile
            window.addEventListener('resize', function() {
                if (window.innerWidth < 768) {
                    const vh = window.innerHeight * 0.01;
                    document.documentElement.style.setProperty('--vh', `${vh}px`);
                }
            });
        });
        
        // Auto-focus email field on mobile for better UX
        if (window.innerWidth < 768) {
            setTimeout(() => {
                document.getElementById('email').focus();
            }, 300);
        }
    </script>
</body>
</html>