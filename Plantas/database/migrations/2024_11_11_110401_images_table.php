<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->string('path', 150);
            $table->foreignId('post_id')->constrained('posts','post_id')->onDelete('cascade');
            $table->softDeletes();
        });
        DB::table('images')->insert([
            ['id' => 1, 'path' => 'image.png', 'post_id'=>1],
            ['id' => 2, 'path' => 'image2.png', 'post_id'=>1],
            ['id' => 3, 'path' => 'image3.png', 'post_id'=>2],
        ]);

    }



    public function down()
    {
        Schema::dropIfExists('images');
    }
};
