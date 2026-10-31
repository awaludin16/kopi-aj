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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('table_id')->constrained('tables')->onDelete('cascade');
            $table->foreignId('table_session_id')->constrained('table_sessions')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('pb1', 10, 2)->nullable()->default(0);
            $table->decimal('service_charge', 10, 2)->nullable()->default(0);
            $table->decimal('rounding', 10, 2)->nullable()->default(0);
            $table->decimal('total', 10, 2)->nullable()->default(0);
            $table->enum('status', ['draft', 'submitted', 'paid', 'cancelled'])->default('draft');
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
