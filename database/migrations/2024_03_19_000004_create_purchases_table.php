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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('content_id')->constrained()->onDelete('cascade');
            $table->decimal('price_paid', 10, 2);
            $table->decimal('commission_amount', 10, 2);
            $table->decimal('commission_pct', 5, 2);
            $table->string('payment_method'); // stripe, paypal, etc.
            $table->string('payment_id'); // external payment processor ID
            $table->string('status')->default('pending'); // pending, completed, failed, refunded
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
}; 