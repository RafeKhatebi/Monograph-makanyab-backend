<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        if (Schema::hasTable('place_suggestions')) {
            DB::statement('ALTER TABLE place_suggestions MODIFY place_category_id BIGINT UNSIGNED NULL');
            DB::statement('ALTER TABLE place_suggestions MODIFY phone_1 VARCHAR(255) NULL');
            DB::statement('ALTER TABLE place_suggestions MODIFY address VARCHAR(255) NULL');
            DB::statement('ALTER TABLE place_suggestions MODIFY province VARCHAR(100) NULL');
            DB::statement('ALTER TABLE place_suggestions MODIFY city VARCHAR(100) NULL');
            DB::statement('ALTER TABLE place_suggestions MODIFY district VARCHAR(100) NULL');
        }

        if (Schema::hasTable('service_suggestions')) {
            DB::statement('ALTER TABLE service_suggestions MODIFY phone_1 VARCHAR(20) NULL');
            DB::statement('ALTER TABLE service_suggestions MODIFY address VARCHAR(500) NULL');
            DB::statement('ALTER TABLE service_suggestions MODIFY country VARCHAR(100) NULL');
            DB::statement('ALTER TABLE service_suggestions MODIFY province VARCHAR(100) NULL');
            DB::statement('ALTER TABLE service_suggestions MODIFY city VARCHAR(100) NULL');
            DB::statement('ALTER TABLE service_suggestions MODIFY district VARCHAR(100) NULL');
        }

        if (Schema::hasTable('posts')) {
            DB::statement('ALTER TABLE posts MODIFY content LONGTEXT NULL');
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        if (Schema::hasTable('place_suggestions')) {
            DB::statement('ALTER TABLE place_suggestions MODIFY place_category_id BIGINT UNSIGNED NOT NULL');
            DB::statement('ALTER TABLE place_suggestions MODIFY phone_1 VARCHAR(255) NOT NULL');
            DB::statement('ALTER TABLE place_suggestions MODIFY address VARCHAR(255) NOT NULL');
            DB::statement('ALTER TABLE place_suggestions MODIFY province VARCHAR(100) NOT NULL');
            DB::statement('ALTER TABLE place_suggestions MODIFY city VARCHAR(100) NOT NULL');
            DB::statement('ALTER TABLE place_suggestions MODIFY district VARCHAR(100) NOT NULL');
        }

        if (Schema::hasTable('service_suggestions')) {
            DB::statement('ALTER TABLE service_suggestions MODIFY phone_1 VARCHAR(20) NOT NULL');
            DB::statement('ALTER TABLE service_suggestions MODIFY address VARCHAR(500) NOT NULL');
            DB::statement('ALTER TABLE service_suggestions MODIFY country VARCHAR(100) NOT NULL');
            DB::statement('ALTER TABLE service_suggestions MODIFY province VARCHAR(100) NOT NULL');
            DB::statement('ALTER TABLE service_suggestions MODIFY city VARCHAR(100) NOT NULL');
            DB::statement('ALTER TABLE service_suggestions MODIFY district VARCHAR(100) NOT NULL');
        }

        if (Schema::hasTable('posts')) {
            DB::statement('ALTER TABLE posts MODIFY content LONGTEXT NOT NULL');
        }
    }
};
