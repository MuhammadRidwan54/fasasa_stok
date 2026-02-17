<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Stok Tas Fasasa</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
            padding: 40px;
        }
        .logo {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo h2 {
            color: #2c3e50;
            font-weight: bold;
        }
        .logo p {
            color: #7f8c8d;
            margin: 0;
        }
        .form-control {
            padding: 12px 15px;
            border-radius: 8px;
            border: 1px solid #ddd;
            margin-bottom: 20px;
            font-size: 16px;
        }
        .btn-login {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s;
        }
        .login-info {
            background-color: #e8f4fc;
            border-radius: 8px;
            padding: 15px;
            margin-top: 20px;
            border-left: 4px solid #3498db;
        }
        .fasasa-logo {
            font-size: 2.5rem;
            margin-bottom: 10px;
            color: #3498db;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="logo">
            <div class="fasasa-logo">
                <i class="bi bi-bag-fill"></i>
            </div>
            <h2>Stok Tas Fasasa</h2>
            <p><i class="bi bi-person-badge"></i> Login sebagai Administrator</p>
        </div>

        @if(session('status'))
            <div class="alert alert-success">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('status') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-circle-fill me-2"></i>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                <i class="bi bi-x-circle-fill me-2"></i> {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="form-label">
                    <i class="bi bi-envelope me-1"></i> Email
                </label>
                <input id="email" type="email" class="form-control" name="email" 
                       value="{{ old('email') }}" required autofocus>
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label for="password" class="form-label">
                    <i class="bi bi-lock me-1"></i> Password
                </label>
                <input id="password" type="password" class="form-control" 
                       name="password" required>
            </div>

            <!-- Remember Me -->
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                <label class="form-check-label" for="remember">
                    <i class="bi bi-check-square me-1"></i> Ingat saya
                </label>
            </div>

            <button type="submit" class="btn btn-login">
                <i class="bi bi-box-arrow-in-right me-2"></i> Login
            </button>
        </form>

        <!-- Login Info -->
        <div class="login-info">
            <p class="mb-1"><strong><i class="bi bi-info-circle me-1"></i> Login Default:</strong></p>
            <p class="mb-1">
                <i class="bi bi-envelope me-1"></i> Email: <code>admin@fasasa.com</code>
            </p>
            <p class="mb-0">
                <i class="bi bi-key me-1"></i> Password: <code>admin123</code>
            </p>
        </div>

        <!-- Footer -->
        <div class="text-center mt-4 text-muted">
            <small>
                <i class="bi bi-c-circle"></i> {{ date('Y') }} Fasasa. All rights reserved.
            </small>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>