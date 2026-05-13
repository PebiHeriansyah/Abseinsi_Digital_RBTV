<!DOCTYPE html>
<html>
<head>
    <title>Absensi RBTV</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="text-center mt-5">

    <h1 class="mb-4">Sistem Absensi RBTV</h1>

    <a href="{{ route('absensi.scan') }}" class="btn btn-primary mb-3">
        Scan Absensi
    </a>
    <br>
    <a href="{{ route('login') }}" class="btn btn-success">
        Login Admin
    </a>

</body>
</html>