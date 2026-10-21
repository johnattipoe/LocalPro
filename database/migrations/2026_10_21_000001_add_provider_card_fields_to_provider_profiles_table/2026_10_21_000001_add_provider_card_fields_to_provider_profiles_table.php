<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('provider_profiles')) {
            return;
        }

        Schema::table('provider_profiles', function (Blueprint $table) {
            if (! Schema::hasColumn('provider_profiles', 'profile_photo_path')) {
                $table->string('profile_photo_path')->nullable();
            }
            if (! Schema::hasColumn('provider_profiles', 'profile_photo_disk')) {
                $table->string('profile_photo_disk')->nullable();
            }
            if (! Schema::hasColumn('provider_profiles', 'accepting_requests')) {
                $table->boolean('accepting_requests')->default(true);
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('provider_profiles')) {
            return;
        }

        Schema::table('provider_profiles', function (Blueprint $table) {
            foreach (['profile_photo_path', 'profile_photo_disk', 'accepting_requests'] as $column) {
                if (Schema::hasColumn('provider_profiles', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
