<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('place_suggestions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('place_category_id')->constrained('place_categories')->onDelete('restrict');

            $table->string('name');
            $table->string('tagline')->nullable();
            $table->text('description')->nullable();

            $table->string('phone_1');
            $table->string('phone_2')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('website')->nullable();
            $table->json('social_links')->nullable();

            $table->string('address');
            $table->string('country', 100)->default('Afghanistan');
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

            $table->string('submitted_by_name')->nullable();
            $table->string('submitted_by_email')->nullable();
            $table->enum('suggestion_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_note')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();

            $table->timestamps();

            $table->index('place_category_id');
            $table->index('city');
            $table->index('status');
            $table->index('price_level');
            $table->index('suggestion_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('place_suggestions');
    }
};
