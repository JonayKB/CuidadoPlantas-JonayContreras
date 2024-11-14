<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('plants_types', function (Blueprint $table) {
            $table->id();
            $table->string('type', 25);
            $table->softDeletes();
        });
        DB::table('plants_types')->insert([
            ['type' => 'Trees'],
            ['type' => 'Shrubs'],
            ['type' => 'Herbs'],
            ['type' => 'Climbers'],
            ['type' => 'Creepers'],
            ['type' => 'Ferns'],
            ['type' => 'Grasses'],
            ['type' => 'Succulents'],
            ['type' => 'Cacti'],
            ['type' => 'Aquatic Plants'],
            ['type' => 'Mosses'],
            ['type' => 'Algae'],
            ['type' => 'Lichens'],
            ['type' => 'Flowering Plants'],
            ['type' => 'Non-Flowering Plants'],
        ]);

    }



    public function down()
    {
        Schema::dropIfExists('plants_type');
    }
};
