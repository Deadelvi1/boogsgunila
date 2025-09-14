<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('event_name');
            $table->string('event_type'); // seminar, konser, dll
            $table->integer('capacity');
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('proposal_file')->nullable(); // simpan path file
            $table->char('status', 1)->default('1'); // 1=Menunggu,2=Disetujui,3=Ditolak,4=Selesai
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
