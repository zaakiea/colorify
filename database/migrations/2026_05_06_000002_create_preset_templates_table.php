<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('preset_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('preset_id')->constrained('presets')->onDelete('cascade');
            $table->string('name');
            $table->json('colors'); // exactly 5 hex colors
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('preset_templates');
    }
};
