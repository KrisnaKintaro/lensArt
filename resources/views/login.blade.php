<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login User - Lensart</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('adminLte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    
    <style>
        /* CSS Sesuai Desain Hitam/Cokelat Gelap Anda */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            /* Warna latar belakang cokelat gelap dari gambar Anda */
            background-color: #2e2829; 
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: #ccc;
        }

        .login-card {
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
            color: white; /* Pastikan teks input selalu putih */
            border: none;
            box-shadow: none !important;
            padding-left: 5px;
            height: 100%;
        }
        
        /* FIX: Warna fokus input field */
        .input-group-custom .form-control:focus {
             background-color: #5d5354; /* Gunakan warna yang lebih gelap dari teks, misalnya warna latar belakang icon */
             color: white; /* Jaga teks tetap putih */
             border-color: #f0f0f0; /* Tambahkan border terang saat fokus */
             box-shadow: 0 0 0 0.25rem rgba(240, 240, 240, 0.25); /* Shadow putih tipis */
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

        /* Style untuk Tombol Login */
        .btn-login-custom {
            background-color: #4d4444; 
            color: white;
            border: none;
            padding: 10px 40px;
            font-weight: bold;
            border-radius: 8px;
            transition: background-color 0.3s;
        }
        
        .btn-login-custom:hover {
            background-color: #5d5354;
            color: white;
        }
        
        /* Link Register */
        .register-link-button {
            background-color: transparent;
            color: #f0f0f0;
            border: 1px solid #f0f0f0;
            padding: 8px 25px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s; /* Ubah transisi agar lebih halus */
        }
        
        .register-link-button:hover {
            background-color: #f0f0f0;
            color: #2e2829;
            box-shadow: 0 0 10px rgba(240, 240, 240, 0.5); /* Tambah shadow saat hover */
        }
        
        .link-text {
            color: #ccc;
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .link-text:hover {
            color: white;
        }
        
    </style>
</head>

<body>
    
    {{-- Notifikasi Error/Gagal dari sesi PHP (Jika tidak menggunakan AJAX) --}}
    @if (Session::get('failed'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Akses Gagal!',
                    text: '{{ Session::get('failed') }}',
                    showConfirmButton: true
                });
            });
        </script>
    @endif
    
    <div class="login-card">
        <div class="logo-area">
             <img src="{{ asset('assetslensart/logo/Logo Lensart Putih.png') }}" alt="Lensart Logo">
        </div>
        
        <h3 class="form-title">Form Login</h3>

        <form id="form-login" action="{{ route('prosesLogin') }}" method="POST">
            @csrf

            <div class="input-group input-group-custom">
                <span class="input-group-text"><i class="fas fa-user"></i></span>
                <input type="text" name="identity" class="form-control" value="{{ old('identity') }}" placeholder="Email atau Nama Lengkap" required>
            </div>

            <div class="input-group input-group-custom">
                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>

           <div class="d-flex justify-content-between align-items-center mb-5 small">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label **text-white**" for="remember">
                        Remember Me?
                    </label>
                </div>
                <a href="#" class="**text-white** text-decoration-none small">Forgot Password</a>
            </div>
            
            <div class="d-grid mb-4">
                <button type="submit" class="btn btn-login-custom" id="btn-submit">Login</button>
            </div>
            
            <p class="text-center text-muted mt-5">
                Don't have an account? 
            </p>
            <a href="{{ route('register') }}" class="register-link-button mt-2 d-inline-block">
                Register Here
            </a>

        </form>
    </div>

    <script src="{{ asset('adminLte/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('adminLte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            // Memastikan CSRF token dikirim di header AJAX
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#form-login').submit(function(e) {
                e.preventDefault(); // Mencegah reload halaman

                var form = $(this);
                var btn = $('#btn-submit');

                // Ubah tombol jadi loading
                btn.prop('disabled', true).text('Loading...');

                $.ajax({
                    url: form.attr('action'),
                    type: "POST",
                    data: form.serialize(),
                    success: function(response) {
                        // Kalau SUKSES
                        Swal.fire({
                            icon: 'success',
                            title: 'Login Sukses!',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.href = response.redirect_url;
                        });
                    },
                    error: function(xhr) {
                        // Balikin tombol
                        btn.prop('disabled', false).text('Login');

                        // Kalau ERROR (Validasi, Password Salah, atau Role tidak diizinkan)
                        if (xhr.status === 422 || xhr.status === 401) {
                            let errors = xhr.responseJSON.errors || {};
                            let pesanError = '';

                            // Jika ada error validasi
                            if (Object.keys(errors).length > 0) {
                                pesanError = '<ul style="text-align: left; margin-left: 20px; list-style-type: none;">';
                                $.each(errors, function(key, value) {
                                    pesanError += '<li>' + value[0] + '</li>';
                                });
                                pesanError += '</ul>';
                            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                                // Jika ada pesan error non-validasi dari controller (misal: password salah)
                                pesanError = xhr.responseJSON.message;
                            } else {
                                pesanError = 'Email/Password salah atau akses ditolak.';
                            }


                            Swal.fire({
                                icon: 'error',
                                title: 'Akses Gagal',
                                html: pesanError,
                            });
                        } else {
                            // Error Server Lainnya
                            Swal.fire({
                                icon: 'error',
                                title: 'Error Server',
                                text: 'Ada masalah di server, coba lagi nanti. Status: ' + xhr.status
                            });
                        }
                    }
                });
            });
        });
    </script>
</body>

</html>