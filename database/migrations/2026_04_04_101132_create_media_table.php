<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->morphs('mediable'); 
            $table->string('file_path');
            $table->string('disk')->default('public'); 
            $table->string('mime_type', 100)->nullable(); 
            $table->unsignedBigInteger('file_size')->nullable()->comment('size in bytes');
            $table->enum('type', ['image', 'video'])->default('image');
            $table->boolean('is_cover')->default(false);
            $table->unsignedSmallInteger('sort_order')->default(0); 
            $table->timestamps();
            $table->index(['mediable_type', 'mediable_id', 'is_cover']); // cover image lookup per model
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
