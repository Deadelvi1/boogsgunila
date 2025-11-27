<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Verifikasi OTP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="d-flex justify-content-center align-items-center" style="height:100vh;">

    <div class="card p-4 shadow" style="width: 400px;">
        <h4 class="text-center mb-3">Verifikasi Kode OTP</h4>

        @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('otp.verify') }}">
            @csrf

            {{-- WAJIB ADA, kalau tidak OTP selalu gagal --}}
            <input type="hidden" name="email" value="{{ $email }}">

            <div class="mb-3">
                <label class="form-label">Kode OTP</label>
                <input type="text" name="otp" class="form-control" placeholder="Masukkan kode OTP" required>
            </div>

            <button class="btn btn-primary w-100">Verifikasi</button>
        </form>
    </div>

</body>

</html>