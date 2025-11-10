<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop all tables in reverse order of dependencies
        Schema::dropIfExists('payments');
        Schema::dropIfExists('booking_fasilitas');
        Schema::dropIfExists('bookings');
        Schema::dropIfExists('fasilitas');
        Schema::dropIfExists('gedung');
        Schema::dropIfExists('personal_access_tokens');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('failed_jobs');
        Schema::dropIfExists('users');

        // Create users table
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['A', 'U'])->default('U'); // A=admin, U=user
            $table->rememberToken();
            $table->timestamps();
        });

        // Create gedung table
        Schema::create('gedung', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama');
            $table->string('lokasi')->nullable();
            $table->integer('kapasitas')->default(0);
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        // Create fasilitas table
        Schema::create('fasilitas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->unsignedInteger('stok')->default(0);
            $table->decimal('harga', 12, 2)->default(0);
            $table->timestamps();
        });

        // Create bookings table
        Schema::create('bookings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->uuid('gedung_id');
            $table->string('event_name');
            $table->string('event_type');
            $table->integer('capacity');
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('proposal_file')->nullable();
            $table->enum('status', ['1', '2', '3', '4'])->default('1'); // 1=pending, 2=approved, 3=rejected, 4=completed
            $table->text('catatan')->nullable();
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('gedung_id')->references('id')->on('gedung')->cascadeOnUpdate()->restrictOnDelete();
        });

        // Create booking_fasilitas table
        Schema::create('booking_fasilitas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('booking_id');
            $table->uuid('fasilitas_id');
            $table->unsignedInteger('jumlah')->default(1);
            $table->decimal('harga', 12, 2)->default(0); // Store price at time of booking
            $table->timestamps();

            $table->foreign('booking_id')->references('id')->on('bookings')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('fasilitas_id')->references('id')->on('fasilitas')->cascadeOnUpdate()->restrictOnDelete();
        });

        // Create payments table
        Schema::create('payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('booking_id');
            $table->decimal('amount', 12, 2);
            $table->string('method');
            $table->string('proof_file')->nullable();
            $table->char('status', 1)->default('0'); // 0=pending, 1=verified, 2=rejected
            $table->timestamps();

            $table->foreign('booking_id')->references('id')->on('bookings')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
        Schema::dropIfExists('booking_fasilitas');
        Schema::dropIfExists('bookings');
        Schema::dropIfExists('fasilitas');
        Schema::dropIfExists('gedung');
        Schema::dropIfExists('users');
    }
};
