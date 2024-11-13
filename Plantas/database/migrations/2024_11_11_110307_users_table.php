<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('password');
            $table->boolean('verified')->default(false);
            $table->string('email')->unique();
            $table->timestamps();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->softDeletes();

        });
        DB::table('users')->insert([
            [
                'id' => 1,
                'name' => 'Jonay',
                'password' => '$2y$12$grWVsrbcpM.PDKYNYaT.ueA7Y/cEPlxStdrc2jU8raPAZW9hMCAYi',
                'verified' => false,
                'created_at' => '2024-11-09 18:50:10',
                'email' => 'jonaykb@gmail.com',
                'email_verified_at' => '2024-12-09 18:50:10',
            ],
            [
                'id' => 2,
                'name' => 'test',
                'password' => '$2y$12$4l2kXM3BQngdLc9OlaDPCef/ACyt1vnBgj26Lqhf8KhIu7vKIb0H6',
                'verified' => false,
                'created_at' => '2024-11-09 22:36:40',
                'email' => 'test@gmail.com',
                'email_verified_at' => '2024-12-09 22:36:40',
            ],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};

