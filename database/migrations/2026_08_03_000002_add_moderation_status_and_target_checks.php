<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->string('moderation_status', 20)->default('pending')->after('is_approved')->index();
        });

        DB::table('reviews')
            ->where('is_approved', true)
            ->update(['moderation_status' => 'approved']);

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE reviews ADD CONSTRAINT chk_reviews_moderation_status CHECK (moderation_status IN ('pending', 'approved', 'rejected'))");
            DB::statement('ALTER TABLE reviews ADD CONSTRAINT chk_reviews_single_target CHECK ((place_id IS NOT NULL AND service_id IS NULL) OR (place_id IS NULL AND service_id IS NOT NULL))');
            DB::statement('ALTER TABLE favorites ADD CONSTRAINT chk_favorites_single_target CHECK ((place_id IS NOT NULL AND service_id IS NULL) OR (place_id IS NULL AND service_id IS NOT NULL))');
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE favorites DROP CHECK chk_favorites_single_target');
            DB::statement('ALTER TABLE reviews DROP CHECK chk_reviews_single_target');
            DB::statement('ALTER TABLE reviews DROP CHECK chk_reviews_moderation_status');
        }

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropIndex(['moderation_status']);
            $table->dropColumn('moderation_status');
        });
    }
};
