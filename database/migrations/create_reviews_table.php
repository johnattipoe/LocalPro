<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_id')->index();
            $table->unsignedBigInteger('customer_id')->index();
            $table->unsignedBigInteger('provider_profile_id')->index();
            $table->unsignedTinyInteger('rating');
            $table->text('comment')->nullable();
            $table->string('status')->default('published');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('reviews'); }
};
