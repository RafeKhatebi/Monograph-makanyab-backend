<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('place_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('rating')->comment('1–5 enforced by check constraint');
            $table->text('comment')->nullable();
            $table->boolean('is_approved')->default(false); // added: moderation support
            $table->timestamps();

            $table->unique(['user_id', 'place_id']);
            $table->index(['place_id', 'rating']);
            $table->index(['place_id', 'created_at']);
            $table->index(['place_id', 'is_approved']); // for fetching only approved reviews
        });

        // DB-level rating range enforcement (MySQL 8.0.16+)
        DB::statement('ALTER TABLE reviews ADD CONSTRAINT chk_reviews_rating CHECK (rating BETWEEN 1 AND 5)');
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
