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
        Schema::create('contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producer_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('type'); // e.g., 'movie', 'series', 'documentary'
            $table->string('status')->default('draft'); // draft, pending, active, rejected
            $table->unsignedInteger('views_count')->default(0);
            $table->unsignedInteger('downloads_count')->default(0);
            $table->decimal('price', 10, 2)->default(0);
            $table->boolean('featured')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contents');
    }
}; 