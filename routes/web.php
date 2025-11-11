<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GedungController;
use App\Http\Controllers\FasilitasController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public routes
Route::get('/home', [PublicController::class, 'home'])->name('home');
Route::get('/availability', [PublicController::class, 'availability'])->name('public.availability');
Route::get('/quote', [PublicController::class, 'quote'])->name('public.quote');
// Public sewa pages
Route::view('/sewa/gedung', 'sewa.gedung')->name('public.sewa.gedung');
Route::view('/sewa/fasilitas', 'sewa.fasilitas')->name('public.sewa.fasilitas');
Route::get('/jadwal', [PublicController::class, 'jadwal'])->name('public.jadwal');

// Guest only routes
Route::middleware('guest')->group(function () {
    Route::get('/', [PublicController::class, 'home']);
    
    // Auth routes
    Route::get('/register', [AuthController::class, 'showRegister'])->name('auth.register.form');
    Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
    Route::get('/login', [AuthController::class, 'showLogin'])->name('auth.login.form');
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
});

// Authenticated routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    
    // Booking routes
    Route::get('/booking', [BookingController::class, 'index'])->name('booking.index');
    Route::get('/booking/create', [BookingController::class, 'create'])->name('booking.create');
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/booking/{id}/edit', [BookingController::class, 'edit'])->name('booking.edit');
    Route::put('/booking/{id}', [BookingController::class, 'update'])->name('booking.update');
    Route::delete('/booking/{id}', [BookingController::class, 'destroy'])->name('booking.destroy');
    Route::get('/booking/{id}/invoice', [BookingController::class, 'invoice'])->name('booking.invoice');
    
    // Payments
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::post('/payments/{id}/upload', [PaymentController::class, 'uploadProof'])->name('payments.upload');
    
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
});

// Admin routes (admin only)
Route::middleware(['auth', 'role:A'])->group(function () {
    // Admin Dashboard
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/users', [AdminController::class, 'usersIndex'])->name('admin.users.index');
    Route::get('/admin/schedules', [AdminController::class, 'schedulesIndex'])->name('admin.schedules.index');
    Route::get('/admin/rentals', [AdminController::class, 'rentalsIndex'])->name('admin.rentals.index');
    
    // Gedung management
    Route::get('/gedung', [GedungController::class, 'index'])->name('gedung.index');
    Route::get('/gedung/create', [GedungController::class, 'create'])->name('gedung.create');
    Route::post('/gedung', [GedungController::class, 'store'])->name('gedung.store');
    Route::get('/gedung/{id}/edit', [GedungController::class, 'edit'])->name('gedung.edit');
    Route::put('/gedung/{id}', [GedungController::class, 'update'])->name('gedung.update');
    Route::delete('/gedung/{id}', [GedungController::class, 'destroy'])->name('gedung.destroy');
    
    // Fasilitas management
    Route::get('/fasilitas', [FasilitasController::class, 'index'])->name('fasilitas.index');
    Route::get('/fasilitas/create', [FasilitasController::class, 'create'])->name('fasilitas.create');
    Route::post('/fasilitas', [FasilitasController::class, 'store'])->name('fasilitas.store');
    Route::get('/fasilitas/{id}/edit', [FasilitasController::class, 'edit'])->name('fasilitas.edit');
    Route::put('/fasilitas/{id}', [FasilitasController::class, 'update'])->name('fasilitas.update');
    Route::delete('/fasilitas/{id}', [FasilitasController::class, 'destroy'])->name('fasilitas.destroy');
    
    // Admin booking actions
    Route::put('/admin/booking/{id}/approve', function ($id) {
        \App\Models\Booking::where('id', $id)->update(['status' => '2']);
        return redirect()->back()->with('success', 'Booking disetujui.');
    })->name('admin.booking.approve');
    
    Route::put('/admin/booking/{id}/reject', function ($id) {
        \App\Models\Booking::where('id', $id)->update(['status' => '3']);
        return redirect()->back()->with('success', 'Booking ditolak.');
    })->name('admin.booking.reject');
    
    // Payment verification
    Route::get('/admin/payments', [PaymentController::class, 'adminIndex'])->name('admin.payments.index');
    Route::put('/admin/payments/{id}/status', [\App\Http\Controllers\PaymentController::class, 'adminMark'])->name('admin.payments.status');
});
