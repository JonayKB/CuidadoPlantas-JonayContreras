<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('user_rol', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('rol_id')->constrained('roles')->onDelete('cascade');
        });

        DB::table('user_rol')->insert([
            ['id' => 1, 'user_id' => 2, 'rol_id' => 1],
            ['id'=>2,'user_id'=>1,'rol_id'=>2],
            ['id'=>3,'user_id'=>3,'rol_id'=>1],
            ['id'=>4,'user_id'=>3,'rol_id'=>2],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('user_rol');
    }
};

