<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('provider_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('provider_profile_id')->index();
            $table->unsignedBigInteger('service_category_id')->nullable()->index();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('pricing_type')->default('quote');
            $table->decimal('price', 10, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('provider_services'); }
};
