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
            $table->string('name', 25);
            $table->softDeletes();
        });
        DB::table('plants_types')->insert([
            ['name' => 'Trees'],
            ['name' => 'Shrubs'],
            ['name' => 'Herbs'],
            ['name' => 'Climbers'],
            ['name' => 'Creepers'],
            ['name' => 'Ferns'],
            ['name' => 'Grasses'],
            ['name' => 'Succulents'],
            ['name' => 'Cacti'],
            ['name' => 'Aquatic Plants'],
            ['name' => 'Mosses'],
            ['name' => 'Algae'],
            ['name' => 'Lichens'],
            ['name' => 'Flowering Plants'],
            ['name' => 'Non-Flowering Plants'],
            ['name' => 'Other'],
        ]);

    }



    public function down()
    {
        Schema::dropIfExists('plants_type');
    }
};
