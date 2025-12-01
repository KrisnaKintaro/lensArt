<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lensart Photography</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Mr+De+Haviland&display=swap" rel="stylesheet">
    
    <style>
        /* Reset dan Pengaturan Dasar */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            /* Warna latar belakang gelap */
            background-color: #2c2928; 
            color: white; 
            font-family: Arial, sans-serif;
            height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative; 
        }

        /* Header (MacBook Air - 1) */
        .header-note {
            position: absolute;
            top: 15px;
            left: 15px;
            font-size: 14px;
            opacity: 0.7; 
        }

        /* Kontainer Utama */
        .container {
            text-align: center;
            flex-grow: 1; 
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            transform: translateY(-20px); 
        }

        /* Area Logo */
        .logo-area {
            margin-bottom: 20px;
        }

        /* Styling untuk Gambar Logo */
        .logo-area img {
            width: 100px; /* Atur lebar sesuai keinginan Anda */
            height: auto;
            max-width: 100%;
        }
        
        /* Tagline/Teks Fotografi */
        .tagline {
            font-family: 'Mr De Haviland', cursive; 
            font-size: 48px;
            font-weight: 400;
            opacity: 0;
            transition: opacity 1.5s ease-in-out;
            letter-spacing: 2px;
            margin-top: 10px;
        }

        /* Footer (Copyright) */
        .footer-note {
            position: absolute;
            bottom: 30px;
            font-size: 14px;
            letter-spacing: 0.5px;
            opacity: 0.8;
        }
    </style>
</head>
<body>
    
    <div class="header-note">MacBook Air - 1</div>
    
    <div class="container">
        <div class="logo-area">
            {{-- Bagian INI yang diubah menggunakan helper asset() --}}
            <img src="{{ asset('assetslensart/logo/Logo Lensart Putih.png') }}" alt="Logo Lensart Photography">
            {{-- Pastikan nama file logo Anda adalah 'logo.png' (atau sesuaikan ekstensi) --}}
        </div>
        <p class="tagline" id="lensart-tagline">Lensart Photography</p>
    </div>

    <div class="footer-note">
        Copyright @Lensart_Photography
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const taglineElement = document.getElementById('lensart-tagline');
            
            // Memberikan efek fade-in halus pada tagline
            setTimeout(() => {
                taglineElement.style.opacity = 1;
            }, 500);
        });
    </script>
</body>
</html>