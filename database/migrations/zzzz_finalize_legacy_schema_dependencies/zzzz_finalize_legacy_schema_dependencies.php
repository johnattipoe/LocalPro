<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The original create_* migrations predate timestamped migrations and
     * sort after them. Defer changes that need those tables until all base
     * tables have been created on a fresh PostgreSQL installation.
     */
    public function up(): void
    {
        if (Schema::hasTable('messages') && ! Schema::hasColumn('messages', 'service_request_id')) {
            Schema::table('messages', function (Blueprint $table) {
                $table->unsignedBigInteger('service_request_id')->nullable()->index();
            });
        }

        if (Schema::hasTable('provider_profiles') && ! Schema::hasColumn('provider_profiles', 'availability')) {
            Schema::table('provider_profiles', function (Blueprint $table) {
                $table->json('availability')->nullable();
            });
        }

        if (Schema::hasTable('service_requests') && ! Schema::hasTable('support_tickets')) {
            Schema::create('support_tickets', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $table->foreignId('service_request_id')->nullable()->constrained('service_requests')->nullOnDelete();
                $table->string('name', 120);
                $table->string('email');
                $table->string('topic', 80);
                $table->text('message');
                $table->string('status', 24)->default('open')->index();
                $table->text('admin_response')->nullable();
                $table->timestamp('resolved_at')->nullable();
                $table->timestamps();
            });
        }

        if (Schema::hasTable('provider_profiles') && ! Schema::hasTable('provider_payouts')) {
            Schema::create('provider_payouts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('provider_profile_id')->constrained()->cascadeOnDelete();
                $table->foreignId('requested_by')->constrained('users')->cascadeOnDelete();
                $table->decimal('amount', 10, 2);
                $table->string('currency', 3)->default('GHS');
                $table->string('status', 24)->default('requested')->index();
                $table->string('transfer_reference')->nullable();
                $table->text('admin_note')->nullable();
                $table->timestamp('processed_at')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        // This compatibility migration only fills schema pieces skipped
        // because of legacy migration ordering. Leave data intact on rollback.
    }
};
