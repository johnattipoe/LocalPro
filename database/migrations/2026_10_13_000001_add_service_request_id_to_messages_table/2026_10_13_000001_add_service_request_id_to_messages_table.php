<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Fresh installs may run this dated migration before the legacy
        // create_messages_table migration. The final compatibility migration
        // adds this column after all base tables exist.
        if (! Schema::hasTable('messages') || Schema::hasColumn('messages', 'service_request_id')) {
            return;
        }

        Schema::table('messages', function (Blueprint $table) {
            $table->unsignedBigInteger('service_request_id')->nullable()->index();
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('messages') || ! Schema::hasColumn('messages', 'service_request_id')) {
            return;
        }

        Schema::table('messages', function (Blueprint $table) {
            $table->dropIndex(['service_request_id']);
            $table->dropColumn('service_request_id');
        });
    }
};
