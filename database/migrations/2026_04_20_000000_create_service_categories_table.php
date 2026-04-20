<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()
                ->constrained('service_categories')
                ->nullOnDelete();

            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('icon_name')->nullable();
            $table->string('color_code', 7)->default('#3b82f6');

            $table->boolean('has_menu')->default(false);
            $table->boolean('has_booking')->default(false);
            $table->boolean('has_delivery')->default(false);

            $table->text('keywords')->nullable();
            $table->string('schema_type')->nullable();

            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);

            $table->index('is_active');
            $table->index('sort_order');
            $table->index('parent_id');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_categories');
    }
};
