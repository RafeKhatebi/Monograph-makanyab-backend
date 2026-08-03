<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->foreignUuid('place_id')->nullable()->change();
            $table->foreignUuid('service_id')->nullable()->after('place_id')
                ->constrained()->cascadeOnDelete();
            $table->unique(['user_id', 'service_id']);
            $table->index(['service_id', 'is_approved']);
        });

        Schema::table('favorites', function (Blueprint $table) {
            $table->foreignUuid('place_id')->nullable()->change();
            $table->foreignUuid('service_id')->nullable()->after('place_id')
                ->constrained()->cascadeOnDelete();
            $table->unique(['user_id', 'service_id']);
            $table->index('service_id');
        });
    }

    public function down(): void
    {
        Schema::table('favorites', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'service_id']);
            $table->dropIndex(['service_id']);
            $table->dropConstrainedForeignId('service_id');
            $table->foreignUuid('place_id')->nullable(false)->change();
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'service_id']);
            $table->dropIndex(['service_id', 'is_approved']);
            $table->dropConstrainedForeignId('service_id');
            $table->foreignUuid('place_id')->nullable(false)->change();
        });
    }
};
