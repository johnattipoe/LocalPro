<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('bookings')) {
            return;
        }

        Schema::table('bookings', function (Blueprint $table) {
            if (! Schema::hasColumn('bookings', 'recurrence_pattern')) {
                $table->string('recurrence_pattern')->default('none')->after('status');
            }
            if (! Schema::hasColumn('bookings', 'parent_booking_id')) {
                $table->foreignId('parent_booking_id')->nullable()->constrained('bookings')->cascadeOnDelete()->after('recurrence_pattern');
            }
            if (! Schema::hasColumn('bookings', 'next_occurrence')) {
                $table->timestamp('next_occurrence')->nullable()->after('parent_booking_id');
            }
            if (! Schema::hasColumn('bookings', 'occurrence_count')) {
                $table->unsignedSmallInteger('occurrence_count')->default(1)->after('next_occurrence');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('bookings')) {
            return;
        }

        Schema::table('bookings', function (Blueprint $table) {
            foreach (['recurrence_pattern', 'parent_booking_id', 'next_occurrence', 'occurrence_count'] as $column) {
                if (Schema::hasColumn('bookings', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
