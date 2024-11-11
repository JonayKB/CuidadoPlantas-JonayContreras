<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id('role_id');
            $table->string('name', 50);
        });


        DB::table('roles')->insert([
            ['role_id' => 1, 'name' => 'user'],
            ['role_id' => 2, 'name' => 'admin'],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('roles');
    }
};
