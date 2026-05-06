<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('trash', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('collection_id');
        $table->string('collection_name');
        $table->string('collection_slug');
        $table->json('palettes');
        $table->timestamps();
    });
    
    
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trashes');
    }
};
