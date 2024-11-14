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
            $table->text('description');
            $table->foreignId('plant_id')->constrained('plants', 'plant_id')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories');
            $table->integer('reports')->default(0);
            $table->timestamps();
            $table->softDeletes();

        });
        DB::table('posts')->insert([
            ['post_id' => 1, 'title' => 'First post', 'description' => 'This is the first post',
             'plant_id' => 1, 'user_id' => 1, 'reports' => 0,'created_at'=>now(), 'category_id'=>2],
            ['post_id' => 2, 'title' => 'Second post', 'description' => 'This is the second post',
             'plant_id' => 2, 'user_id' => 2, 'reports' => 0,'created_at'=>now(), 'category_id'=>1],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('posts');
    }
};
