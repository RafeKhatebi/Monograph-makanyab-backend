<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['place_suggestions', 'service_suggestions'] as $tableName) {
            if (Schema::hasTable($tableName)) {
                if (Schema::getConnection()->getDriverName() === 'mysql') {
                    DB::statement("ALTER TABLE {$tableName} MODIFY suggestion_status VARCHAR(30) NOT NULL DEFAULT 'draft'");
                }

                Schema::table($tableName, function (Blueprint $table) use ($tableName): void {
                    if (! Schema::hasColumn($tableName, 'extra_information')) {
                        $table->text('extra_information')->nullable()->after('postal_code');
                    }
                });
            }
        }

        if (Schema::hasTable('posts')) {
            Schema::table('posts', function (Blueprint $table): void {
                if (! Schema::hasColumn('posts', 'submission_status')) {
                    $table->string('submission_status', 30)->default('draft')->after('content');
                }
                if (! Schema::hasColumn('posts', 'admin_note')) {
                    $table->text('admin_note')->nullable()->after('submission_status');
                }
                if (! Schema::hasColumn('posts', 'extra_information')) {
                    $table->text('extra_information')->nullable()->after('admin_note');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('posts')) {
            Schema::table('posts', function (Blueprint $table): void {
                $table->dropColumn(['submission_status', 'admin_note', 'extra_information']);
            });
        }

        foreach (['place_suggestions', 'service_suggestions'] as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName): void {
                    if (Schema::hasColumn($tableName, 'extra_information')) {
                        $table->dropColumn('extra_information');
                    }
                });

                if (Schema::getConnection()->getDriverName() === 'mysql') {
                    DB::statement("ALTER TABLE {$tableName} MODIFY suggestion_status ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending'");
                }
            }
        }
    }
};
