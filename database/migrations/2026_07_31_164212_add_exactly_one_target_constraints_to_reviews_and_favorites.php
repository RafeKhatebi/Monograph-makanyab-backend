<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        $this->ensureExistingRowsHaveExactlyOneTarget('reviews');
        $this->ensureExistingRowsHaveExactlyOneTarget('favorites');

        DB::statement(
            'ALTER TABLE reviews ADD CONSTRAINT reviews_exactly_one_target_chk CHECK ((place_id IS NULL) <> (service_id IS NULL))'
        );

        DB::statement(
            'ALTER TABLE favorites ADD CONSTRAINT favorites_exactly_one_target_chk CHECK ((place_id IS NULL) <> (service_id IS NULL))'
        );
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::statement('ALTER TABLE favorites DROP CHECK favorites_exactly_one_target_chk');
        DB::statement('ALTER TABLE reviews DROP CHECK reviews_exactly_one_target_chk');
    }

    private function ensureExistingRowsHaveExactlyOneTarget(string $table): void
    {
        $invalidRows = DB::table($table)
            ->where(function ($query): void {
                $query->whereNull('place_id')
                    ->whereNull('service_id');
            })
            ->orWhere(function ($query): void {
                $query->whereNotNull('place_id')
                    ->whereNotNull('service_id');
            })
            ->exists();

        if ($invalidRows) {
            throw new RuntimeException("Cannot add {$table} target constraint while invalid rows exist.");
        }
    }
};
