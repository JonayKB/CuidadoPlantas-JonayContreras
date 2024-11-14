<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('plants', function (Blueprint $table) {
            $table->id('plant_id');
            $table->string('name', 100);
            $table->foreignId('type_id')->constrained('plants_types');
            $table->softDeletes();
        });
        DB::table('plants')->insert([
            ['plant_id' => 1, 'name' => 'Cactus','type_id'=>9],
            ['plant_id' => 2, 'name' => 'Fern','type_id'=>6],
        ]);
    }


    public function down()
    {
        Schema::dropIfExists('plants');
    }
};
