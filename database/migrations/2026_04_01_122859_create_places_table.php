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
        Schema::create('places', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('place_categories_id')->constrained('place_categories')->onDelete('cascade');

            $table->string('name');
            $table->string('slug')->unique();
            $table->string('tagline')->nullable();
            $table->text('description')->nullable();

            $table->string('phone_1');
            $table->string('phone_2')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('website')->nullable();
            $table->json('social_links')->nullable();

            $table->string('address');
            $table->string('country');
            $table->string('province');
            $table->string('city');
            $table->string('district');
            $table->string('subdistrict')->nullable();
            $table->string('village')->nullable();
            $table->string('rt_rw')->nullable();
            $table->string('neighborhood')->nullable();
            $table->string('postal_code')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();

            $table->string('cover_image')->nullable();
            $table->json('gallery')->nullable();

            $table->enum('status', ['open', 'closed', 'temporarily_closed'])->default('open');
            $table->enum('price_level', ['low', 'medium', 'high', 'luxury'])->default('medium');
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('places');
    }
};
