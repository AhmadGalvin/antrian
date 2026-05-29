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
        Schema::create('branch_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches')->cascadeOnDelete();
            $table->enum('type', ['image', 'video'])->default('image');
            $table->string('file_path')->comment('Path to media file');
            $table->string('title', 100)->nullable();
            $table->unsignedInteger('display_order')->default(0);
            $table->unsignedInteger('duration_seconds')->default(10)->comment('Display duration for images');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['branch_id', 'is_active', 'display_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branch_media');
    }
};
