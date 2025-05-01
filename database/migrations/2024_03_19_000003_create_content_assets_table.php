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
        Schema::create('content_assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('content_id')->constrained()->onDelete('cascade');
            $table->string('type'); // video, audio, subtitle, thumbnail, document
            $table->string('url');
            $table->string('path');
            $table->string('mime_type');
            $table->unsignedInteger('size');
            $table->unsignedInteger('duration')->nullable(); // in seconds
            $table->string('resolution')->nullable(); // e.g., 1920x1080
            $table->unsignedInteger('bitrate')->nullable(); // in kbps
            $table->string('status')->default('processing'); // processing, active, failed
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_assets');
    }
}; 