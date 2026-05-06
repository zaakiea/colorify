<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('color_palettes', function (Blueprint $table) {
            $table->timestamp('saved_on')->useCurrent()->change();
        });
    }

    public function down()
    {
        Schema::table('color_palettes', function (Blueprint $table) {
            $table->timestamp('saved_on')->nullable(false)->change();
        });
    }
};
