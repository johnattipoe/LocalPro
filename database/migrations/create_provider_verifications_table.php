<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('provider_verifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('provider_profile_id')->index();
            $table->unsignedBigInteger('reviewer_id')->nullable();
            $table->string('document_type');
            $table->string('document_path');
            $table->string('status')->default('pending');
            $table->text('review_notes')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('provider_verifications'); }
};
