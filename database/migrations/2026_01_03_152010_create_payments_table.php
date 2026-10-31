<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            // Relasi ke order
            $table->unsignedBigInteger('order_id');

            // Metode & provider pembayaran
            $table->string('payment_method'); // online | cash
            $table->string('provider')->nullable(); // xendit, midtrans, dll

            // Reference dari payment gateway
            $table->string('reference_id')->nullable(); // invoice_id Xendit
            $table->string('external_id')->nullable();  // external_id Xendit

            // Nominal & status
            $table->decimal('amount', 15, 2);
            $table->enum('status', ['pending', 'paid', 'failed', 'expired'])->default('pending'); // pending, paid, failed, expired

            // Response / metadata dari gateway
            $table->json('payload')->nullable();

            $table->timestamps();

            $table->index('order_id');
            $table->index('reference_id');
            $table->index('external_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
