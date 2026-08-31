<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contact_messages', function (Blueprint $table) {
            if (! Schema::hasColumn('contact_messages', 'read_at')) {
                $table->timestamp('read_at')->nullable()->after('message')->index();
            }

            if (! Schema::hasColumn('contact_messages', 'archived_at')) {
                $table->timestamp('archived_at')->nullable()->after('read_at')->index();
            }
        });
    }

    public function down(): void
    {
        Schema::table('contact_messages', function (Blueprint $table) {
            if (Schema::hasColumn('contact_messages', 'read_at')) {
                if (Schema::hasIndex('contact_messages', 'contact_messages_read_at_index')) {
                    $table->dropIndex(['read_at']);
                }

                $table->dropColumn('read_at');
            }

            if (Schema::hasColumn('contact_messages', 'archived_at')) {
                if (Schema::hasIndex('contact_messages', 'contact_messages_archived_at_index')) {
                    $table->dropIndex(['archived_at']);
                }

                $table->dropColumn('archived_at');
            }
        });
    }
};
