<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
 <h1>Tampilan customer</h1>
 <h3>Halo {{ auth()->user()->namaLengkap }}</h3>
 <a href="{{ route('logout') }}">
    <button>Logout</button>
 </a>
</body>
</html>
