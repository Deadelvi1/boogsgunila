<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\MahasiswaOtp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->only(['showLogin', 'login', 'showRegister', 'register', 'showOtpForm', 'verifyOtp']);
        $this->middleware('auth')->only('logout');
    }

    public function showRegister()
    {
        return view('auth.register', ['title' => 'Register']);
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'name.required' => 'Nama harus diisi',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        // Buat user namun belum login
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'role' => 'U',
        ]);

        // Generate OTP
        $otp = rand(100000, 999999);

        // Simpan OTP di tabel mahasiswa_otps
        MahasiswaOtp::create([
            'email' => $data['email'],
            'otp' => $otp,
            'expired_at' => now()->addMinutes(10),
        ]);

        // Kirim OTP via email
       Mail::send('emails.otp', ['otp' => $otp, 'user' => $user], function ($message) use ($user) {
    $message->to($user->email)
            ->subject('Kode OTP Verifikasi Akun');
});


        return redirect()->route('otp.verify.show', ['email' => $user->email]);
    }

    public function showOtpForm(Request $request)
    {
        return view('auth.verify-otp', [
            'title' => 'Verifikasi OTP',
            'email' => $request->email
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp'   => 'required|digits:6',
        ]);

        // Ambil OTP berdasarkan email + OTP
        $otp = MahasiswaOtp::where('email', $request->email)
            ->where('otp', $request->otp)
            ->latest()
            ->first();

        if (!$otp) {
            return back()->withErrors(['otp' => 'OTP salah']);
        }

        if (now()->greaterThan($otp->expired_at)) {
            return back()->withErrors(['otp' => 'OTP telah kedaluwarsa']);
        }

        // Hapus OTP setelah benar
        $otp->delete();

        // Login user
        $user = User::where('email', $request->email)->first();
        Auth::login($user);

        return redirect()->route('home')->with('success', 'Registrasi berhasil, Anda telah login.');
    }

    public function showLogin()
    {
        return view('auth.login', ['title' => 'Login']);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();
            if ($user->role === 'A') {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->intended(route('booking.index'));
        }

        return back()->withErrors(['email' => 'Kredensial tidak valid']);
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success', 'Anda telah berhasil logout.');
    }
}
