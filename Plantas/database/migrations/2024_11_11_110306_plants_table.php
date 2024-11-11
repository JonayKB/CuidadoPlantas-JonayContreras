<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('plants', function (Blueprint $table) {
            $table->id('plant_id');
            $table->string('name', 100);
        });
    }

    public function down()
    {
        Schema::dropIfExists('plants');
    }
};
