<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login User</title>
    <!-- Pastikan path CSS ini benar -->
    <link rel="stylesheet" href="{{ asset('adminLte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">

    <style>
        /* CSS Sederhana & Bersih (Sama seperti sebelumnya) */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-card {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .login-header h2 {
            color: #333;
            margin-bottom: 0.5rem;
        }

        .form-group {
            margin-bottom: 1.2rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #666;
            font-size: 0.9rem;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        .form-control:focus {
            border-color: #4a90e2;
            outline: none;
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            background-color: #4a90e2;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s;
        }

        .btn-login:hover {
            background-color: #357abd;
        }

        .password-wrapper {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #666;
            font-size: 0.8rem;
            user-select: none;
        }

        /* Loading state untuk tombol */
        .btn-login:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }
    </style>
</head>

<body>
    @php
        $message = session()->get('failed');
    @endphp

    @if (Session::get('failed'))
        <a href="">{{ session()->get('failed'); }}</a>
    @endif
    {{-- @if ($errors->any())
        <script>
            $(document).ready(function() {
                var message = "";
                @foreach ($errors->all() as $error)
                    message += "";
                @endforeach

                Swal.fire({
                    icon: 'error',
                    title: 'Akses Ditolak!',
                    html: message,
                    showConfirmButton: true
                });
            });
        </script>
    @endif --}}
    <div class="login-card">
        <div class="login-header">
            <h2>Selamat Datang</h2>
            <p>Silakan login ke akun Anda</p>
        </div>

        <form id="form-login" action="{{ route('prosesLogin') }}" method="POST">
            @csrf

            <div class="form-group">
                <label>Email atau Nama Lengkap</label>
                <input type="text" name="identity" class="form-control" placeholder="Contoh: Budi" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>

            <button type="submit" class="btn-login" id="btn-submit">MASUK</button>
        </form>
    </div>

    <script src="{{ asset('adminLte/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('adminLte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('#form-login').submit(function(e) {
                e.preventDefault(); // Stop reload halaman

                var form = $(this);
                var btn = $('#btn-submit');

                // Ubah tombol jadi loading
                btn.prop('disabled', true).text('Loading...');

                $.ajax({
                    url: form.attr('action'),
                    type: "POST",
                    data: form.serialize(), // Pake ini aja cukup buat login (text only)
                    success: function(response) {
                        // Kalau SUKSES
                        Swal.fire({
                            icon: 'success',
                            title: 'Mantap!',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.href = response.redirect_url;
                        });
                    },
                    error: function(xhr) {
                        // Balikin tombol
                        btn.prop('disabled', false).text('MASUK');

                        // Kalau ERROR (Validasi atau Password Salah)
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            let pesanError =
                            '<ul style="text-align: left; margin-left: 20px;">';

                            // Loop error message persis kayak script lu
                            $.each(errors, function(key, value) {
                                pesanError += '<li>' + value[0] + '</li>';
                            });
                            pesanError += '</ul>';

                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal Masuk',
                                html: pesanError,
                            });
                        } else {
                            // Error Server Lainnya
                            Swal.fire({
                                icon: 'error',
                                title: 'Error Server',
                                text: 'Ada masalah di server, coba lagi nanti.'
                            });
                        }
                    }
                });
            });
        });
    </script>
</body>

</html>
