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
            if (! Schema::hasColumn('provider_profiles', 'location_latitude')) {
                $table->decimal('location_latitude', 10, 7)->nullable();
            }
            if (! Schema::hasColumn('provider_profiles', 'location_longitude')) {
                $table->decimal('location_longitude', 10, 7)->nullable();
            }
            if (! Schema::hasColumn('provider_profiles', 'service_mode')) {
                $table->string('service_mode', 32)->default('both');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('provider_profiles')) {
            return;
        }

        Schema::table('provider_profiles', function (Blueprint $table) {
            foreach (['location_latitude', 'location_longitude', 'service_mode'] as $column) {
                if (Schema::hasColumn('provider_profiles', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
