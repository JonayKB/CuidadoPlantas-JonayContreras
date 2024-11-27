<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id('comment_id');
            $table->text('content');
            $table->foreignId('parent_comment_id')->nullable()->constrained('comments','comment_id')->onDelete('set null');
            $table->foreignId('post_id')->constrained('posts','post_id')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();

        });
        DB::table('comments')->insert([
            ['comment_id' => 1, 'content' => 'First comment', 'parent_comment_id' => null, 'post_id' => 1, 'user_id' => 1],
            ['comment_id' => 2, 'content' => 'Second comment', 'parent_comment_id' => 1, 'post_id' => 1, 'user_id' => 2],
            ['comment_id' => 3, 'content' => 'Reply to second comment', 'parent_comment_id' => 1, 'post_id' => 1, 'user_id' => 1],

        ]);
    }

    public function down()
    {
        Schema::dropIfExists('comments');
    }
};
