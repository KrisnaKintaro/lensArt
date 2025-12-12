<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register - Lensart</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <style>
        /* Style SAMA dengan Login (hanya class-nya yang digunakan) */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #2e2829; 
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: #ccc;
        }

        .register-card {
            width: 100%;
            max-width: 400px;
            padding: 40px;
            background-color: transparent; 
            border-radius: 20px;
            border: 2px solid #4d4444; 
            box-shadow: none; 
            text-align: center;
        }

        .logo-area {
            text-align: left;
            margin-bottom: 25px;
        }

        .logo-area img {
            width: 40px;
            height: auto;
        }

        .form-title {
            color: #f0f0f0;
            margin-bottom: 30px;
            font-weight: bold;
            font-size: 1.8rem;
        }

        /* Style untuk Input Field (Warna gelap) */
        .input-group-custom {
            background-color: #4d4444; 
            border: 1px solid #5d5354;
            height: 55px;
            border-radius: 8px;
            margin-bottom: 25px;
            overflow: hidden;
        }
        
        .input-group-custom .form-control {
            background-color: transparent;
            color: white;
            border: none;
            box-shadow: none !important;
            padding-left: 5px;
            height: 100%;
        }
        
        .input-group-custom .form-control:focus {
             background-color: #5d5354;
        }
        
        .input-group-custom .input-group-text {
            background-color: #5d5354; 
            border: none;
            color: #f0f0f0;
            width: 60px; 
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 1.1rem;
        }

        /* Style untuk Tombol Register */
        .btn-register-custom {
            background-color: #4d4444; 
            color: white;
            border: none;
            padding: 10px 40px;
            font-weight: bold;
            border-radius: 8px;
            transition: background-color 0.3s;
        }
        
        .btn-register-custom:hover {
            background-color: #5d5354;
            color: white;
        }
        
        /* Link Login */
        .login-link-button {
            color: #ccc;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s;
        }
        
        .login-link-button:hover {
            color: white;
        }
    </style>
</head>

<body>

    <div class="register-card">
        <div class="logo-area">
             <img src="{{ asset('assetslensart/logo/Logo Lensart Putih.png') }}" alt="Lensart Logo">
        </div>
        
        <h3 class="form-title">Form Register</h3>

        <form action="{{ route('register.post') ?? url('/register') }}" method="POST">
            @csrf

            <div class="input-group input-group-custom">
                <span class="input-group-text"><i class="fas fa-user-circle"></i></span>
                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" placeholder="Nama Lengkap" required autofocus>
            </div>
            @error('name')
                <div class="text-danger small mb-3 text-start">{{ $message }}</div>
            @enderror

            <div class="input-group input-group-custom">
                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                <input type="text" class="form-control @error('noTelp') is-invalid @enderror" name="noTelp" value="{{ old('noTelp') }}" placeholder="Nomor Telepon (misal: 0812xxxx)" required>
            </div>
            @error('noTelp')
                <div class="text-danger small mb-3 text-start">{{ $message }}</div>
            @enderror
            <div class="input-group input-group-custom">
                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Email" required>
            </div>
            @error('email')
                <div class="text-danger small mb-3 text-start">{{ $message }}</div>
            @enderror

            <div class="input-group input-group-custom">
                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Password" required>
            </div>
            @error('password')
                <div class="text-danger small mb-3 text-start">{{ $message }}</div>
            @enderror
            
            <div class="input-group input-group-custom">
                <span class="input-group-text"><i class="fas fa-lock-open"></i></span>
                <input type="password" class="form-control" name="password_confirmation" placeholder="Konfirmasi Password" required>
            </div>

            <div class="d-grid mb-5 mt-4">
                <button type="submit" class="btn btn-register-custom">Daftar Sekarang</button>
            </div>

            <p class="text-center text-muted">
                Sudah punya akun? 
            </p>
            <a href="{{ route('login') }}" class="login-link-button">
                Login
            </a>

        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>