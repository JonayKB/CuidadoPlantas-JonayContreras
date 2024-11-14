<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 25);
            $table->softDeletes();
        });
        DB::table('categories')->insert([
            ['name' => 'Bugs and Pests'],
            ['name' => 'Diseases'],
            ['name' => 'Growth Tips'],
            ['name' => 'Soil and Fertilizers'],
            ['name' => 'Watering Guidelines'],
            ['name' => 'Light Requirements'],
            ['name' => 'Propagation'],
            ['name' => 'Pruning and Maintenance'],
            ['name' => 'Plant Properties'],
            ['name' => 'Common Problems'],
            ['name' => 'Seasonal Care'],
            ['name' => 'Companion Planting'],
            ['name' => 'Harvesting'],
            ['name' => 'Plant Identification'],
            ['name' => 'Toxicity and Safety'],
        ]);


    }



    public function down()
    {
        Schema::dropIfExists('categories');
    }
};
