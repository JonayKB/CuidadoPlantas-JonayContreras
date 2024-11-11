<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id('post_id');
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->foreignId('plant_id')->constrained('plants', 'plant_id')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->binary('image')->nullable();
            $table->string('imageMimeType', 50)->nullable();
            $table->integer('reports')->default(0);
            $table->timestamps();
        });
        DB::table('posts')->insert([
            ['post_id' => 1, 'title' => 'First post', 'description' => 'This is the first post', 'plant_id' => 1, 'user_id' => 1, 'image' => null, 'imageMimeType' => null, 'reports' => 0],
            ['post_id' => 2, 'title' => 'Second post', 'description' => 'This is the second post', 'plant_id' => 2, 'user_id' => 2, 'image' => null, 'imageMimeType' => null, 'reports' => 0],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('posts');
    }
};
