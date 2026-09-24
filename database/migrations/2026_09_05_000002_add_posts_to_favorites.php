<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('favorites', function (Blueprint $table): void {
            $table->foreignId('post_id')->nullable()->after('service_id')
                ->constrained()->cascadeOnDelete();
            $table->unique(['user_id', 'post_id']);
            $table->index('post_id');
        });

        if (DB::getDriverName() === 'mysql') {
            $this->dropCheckIfExists('favorites_exactly_one_target_chk');
            $this->dropCheckIfExists('chk_favorites_single_target');

            DB::statement(
                'ALTER TABLE favorites ADD CONSTRAINT favorites_exactly_one_target_chk CHECK (((place_id IS NOT NULL) + (service_id IS NOT NULL) + (post_id IS NOT NULL)) = 1)'
            );
        }
    }

    public function down(): void
    {
        DB::table('favorites')->whereNotNull('post_id')->delete();

        if (DB::getDriverName() === 'mysql') {
            $this->dropCheckIfExists('favorites_exactly_one_target_chk');

            DB::statement(
                'ALTER TABLE favorites ADD CONSTRAINT favorites_exactly_one_target_chk CHECK ((place_id IS NULL) <> (service_id IS NULL))'
            );
            DB::statement(
                'ALTER TABLE favorites ADD CONSTRAINT chk_favorites_single_target CHECK ((place_id IS NOT NULL AND service_id IS NULL) OR (place_id IS NULL AND service_id IS NOT NULL))'
            );
        }

        Schema::table('favorites', function (Blueprint $table): void {
            $table->dropUnique(['user_id', 'post_id']);
            $table->dropIndex(['post_id']);
            $table->dropConstrainedForeignId('post_id');
        });
    }

    private function dropCheckIfExists(string $constraint): void
    {
        try {
            DB::statement("ALTER TABLE favorites DROP CHECK {$constraint}");
        } catch (\Throwable) {
            // Databases migrated from different revisions may not have every historical check name.
        }
    }
};
