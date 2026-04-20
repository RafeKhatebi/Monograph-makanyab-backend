<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('service_category_id')->constrained('service_categories')->restrictOnDelete();

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
            $table->string('country', 100);
            $table->string('province', 100);
            $table->string('city', 100);
            $table->string('district', 100);
            $table->string('subdistrict', 100)->nullable();
            $table->string('village', 100)->nullable();
            $table->string('rt_rw', 20)->nullable();
            $table->string('neighborhood', 100)->nullable();
            $table->string('postal_code', 10)->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();

            $table->enum('status', ['open', 'closed', 'temporarily_closed'])->default('open');
            $table->enum('price_level', ['low', 'medium', 'high', 'luxury'])->default('medium');
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_active')->default(true);

            $table->index('status');
            $table->index('is_active');
            $table->index('is_verified');
            $table->index('city');
            $table->index(['city', 'status', 'is_active']);
            $table->index(['latitude', 'longitude']);

            $table->softDeletes();
            $table->index('deleted_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
