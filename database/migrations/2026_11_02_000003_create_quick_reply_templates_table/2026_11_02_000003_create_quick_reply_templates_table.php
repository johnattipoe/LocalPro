<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quick_reply_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('body');
            $table->boolean('is_system')->default(false);
            $table->timestamps();
            $table->index(['user_id', 'is_system']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quick_reply_templates');
    }
};
