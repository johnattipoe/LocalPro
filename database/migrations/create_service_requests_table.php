<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('service_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->index();
            $table->unsignedBigInteger('provider_profile_id')->nullable()->index();
            $table->unsignedBigInteger('service_category_id')->nullable()->index();
            $table->text('description');
            $table->string('city')->default('Ho');
            $table->string('address')->nullable();
            $table->dateTime('preferred_at')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('service_requests'); }
};
