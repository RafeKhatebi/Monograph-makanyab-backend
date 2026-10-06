<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['place_suggestions', 'service_suggestions'] as $table) {
            Schema::table($table, function (Blueprint $blueprint): void {
                $blueprint->enum('price_level', ['low', 'medium', 'high', 'luxury'])
                    ->nullable()
                    ->default(null)
                    ->change();
            });
        }
    }

    public function down(): void
    {
        foreach (['place_suggestions', 'service_suggestions'] as $table) {
            DB::table($table)->whereNull('price_level')->update(['price_level' => 'medium']);

            Schema::table($table, function (Blueprint $blueprint): void {
                $blueprint->enum('price_level', ['low', 'medium', 'high', 'luxury'])
                    ->default('medium')
                    ->change();
            });
        }
    }
};
