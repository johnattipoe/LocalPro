<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void { Schema::create('provider_profiles', function (Blueprint $table) { $table->id(); $table->unsignedBigInteger('user_id')->unique(); $table->string('profession'); $table->text('bio')->nullable(); $table->string('city')->default('Ho'); $table->string('region')->default('Volta Region'); $table->string('service_area')->nullable(); $table->decimal('starting_price', 10, 2)->nullable(); $table->string('verification_status')->default('pending'); $table->decimal('average_rating', 2, 1)->default(0); $table->unsignedInteger('review_count')->default(0); $table->timestamps(); }); }
    public function down(): void { Schema::dropIfExists('provider_profiles'); }
};

