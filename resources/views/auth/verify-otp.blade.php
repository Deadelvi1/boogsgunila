<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Verifikasi OTP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            height: 100vh;
            background: url('{{ asset("img/GSGunila.jpg") }}') center/cover no-repeat;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .otp-card {
            width: 430px;
            background: white;
            border-radius: 18px;
            padding: 35px;
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        .brand-title {
            font-size: 32px;
            font-weight: 900;
        }

        .brand-title span:first-child { color: #2563eb; }
        .brand-title span:last-child { color: #111827; }

        .otp-container {
            display: flex;
            justify-content: space-between;
            gap: 8px;
        }

        .otp-input {
            width: 48px;
            height: 58px;
            text-align: center;
            font-size: 26px;
            border: 2px solid #ddd;
            border-radius: 10px;
            font-weight: bold;
            transition: 0.2s;
        }

        .otp-input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 6px rgba(37, 99, 235, 0.4);
        }
    </style>
</head>

<body>

    <div class="otp-card">

        <!-- GAMBAR DI ATAS (sesuai permintaan) -->
        <img src="{{ asset('images/otp.png') }}" alt="OTP Image" style="width: 120px; margin-bottom: 15px;">

        <div class="brand-title mb-2">
            <span>Boo</span><span>GSG.</span>
        </div>

        <h4 class="otp-title mb-3">Verifikasi Kode OTP</h4>

        @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('otp.verify') }}">
            @csrf

            <input type="hidden" name="email" value="{{ $email }}">
            <input type="hidden" name="otp" id="otp-hidden">

            <label class="form-label fw-bold">Masukkan Kode OTP</label>

            <div class="otp-container mb-3">
                <input maxlength="1" class="otp-input" type="text">
                <input maxlength="1" class="otp-input" type="text">
                <input maxlength="1" class="otp-input" type="text">
                <input maxlength="1" class="otp-input" type="text">
                <input maxlength="1" class="otp-input" type="text">
                <input maxlength="1" class="otp-input" type="text">
            </div>

            <button class="btn btn-primary btn-lg w-100 mt-2">Verifikasi</button>
        </form>

        <p class="text-center mt-3 text-muted" style="font-size: 14px;">
            Kode OTP berlaku selama <strong>10 menit</strong>.
        </p>
    </div>

    <script>
        const inputs = document.querySelectorAll(".otp-input");
        const hiddenOtp = document.getElementById("otp-hidden");

        inputs.forEach((input, index) => {
            input.addEventListener("input", () => {
                if (input.value.length === 1 && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
                updateHiddenOtp();
            });

            input.addEventListener("keydown", (e) => {
                if (e.key === "Backspace" && !input.value && index > 0) {
                    inputs[index - 1].focus();
                }
            });
        });

        function updateHiddenOtp() {
            let otpValue = "";
            inputs.forEach(i => otpValue += (i.value || ""));
            hiddenOtp.value = otpValue;
        }
    </script>

</body>

</html>
