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
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary(); // UUID sebagai primary key

            $table->foreignId('booking_id')->constrained()->onDelete('cascade');

            $table->decimal('amount', 15, 2);

            $table->enum('status', [
                'waiting_payment',       // Baru buat booking, belum bayar
                'waiting_verification',  // Sudah upload bukti
                'paid',                  // Sudah diverifikasi admin
                'rejected',              // Ditolak admin
                'expired',               // Lebih dari 24 jam, tidak bayar
            ])->default('waiting_payment');

            $table->string('payment_proof')->nullable();
            $table->timestamp('uploaded_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expired_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
