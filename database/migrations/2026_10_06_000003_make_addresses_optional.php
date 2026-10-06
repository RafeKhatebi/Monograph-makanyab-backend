<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const TABLES = ['place_suggestions', 'service_suggestions', 'places', 'services'];

    public function up(): void
    {
        foreach (self::TABLES as $table) {
            Schema::table($table, function (Blueprint $blueprint): void {
                $blueprint->string('address', 500)->nullable()->change();
            });
        }
    }

    public function down(): void
    {
        foreach (self::TABLES as $table) {
            DB::table($table)->whereNull('address')->update(['address' => '']);

            Schema::table($table, function (Blueprint $blueprint): void {
                $blueprint->string('address', 500)->nullable(false)->change();
            });
        }
    }
};
