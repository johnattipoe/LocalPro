<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('provider_profiles') || Schema::hasColumn('provider_profiles', 'availability')) {
            return;
        }

        Schema::table('provider_profiles', function (Blueprint $table) {
            $table->json('availability')->nullable();
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('provider_profiles') || ! Schema::hasColumn('provider_profiles', 'availability')) {
            return;
        }

        Schema::table('provider_profiles', fn (Blueprint $table) => $table->dropColumn('availability'));
    }
};
