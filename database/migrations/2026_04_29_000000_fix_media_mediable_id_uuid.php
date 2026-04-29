<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE media MODIFY mediable_id CHAR(36) NOT NULL');
        } else {
            Schema::table('media', function (Blueprint $table) {
                $table->uuid('mediable_id')->change();
            });
        }
    }

    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE media MODIFY mediable_id BIGINT UNSIGNED NOT NULL');
        } else {
            Schema::table('media', function (Blueprint $table) {
                $table->unsignedBigInteger('mediable_id')->change();
            });
        }
    }
};
