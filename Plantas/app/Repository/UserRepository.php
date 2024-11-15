<?php

namespace App\Repository;

use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserRepository implements ICrud
{
    public string $connection1 = "mysql";
    public string $connection2 = "sqliteLocal";

    public function findById(int $id): object | null
    {
        $dto = null;
        try {
            $dto = User::on($this->connection1)->find($id);
        } catch (Exception $e) {

            $dto = User::on($this->connection2)->find($id);
        }
        return $dto;
    }
    public function findByEmail($email){
        $dto = null;
        try {
            $dto = User::on($this->connection1)->where('email', $email)->first();
        } catch (Exception $e) {
            $dto = User::on($this->connection2)->where('email', $email)->first();
        }
        return $dto;
    }
    public function findAll(): Collection
    {
        $dtos = [];
        try {
            $dtos = User::on($this->connection1)->get();
        } catch (Exception $e) {
            $dtos = User::on($this->connection2)->get();
        }
        return $dtos;
    }
    public function create(Request $request): object | null{
        $rolRepository = new RolRepository();
        if (app()->runningUnitTests()) {
            $rolRepository->setTestMode();
        }
        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
           'verified' => false,
        ]);
        $rol = $rolRepository->findById(1);
        $user->setConnection($this->connection1)->save();
        $user->setConnection($this->connection1)->roles()->attach($rol);
        $user2 = new User([
            'id'=>$user->id,
            'name'=>$user->name,
            'email'=>$user->email,
            'password'=>$user->password,
           'verified'=>$user->verified,
           'remember_token'=>$user->remember_token,
           'created_at'=>$user->created_at,
           'updated_at'=>$user->updated_at,
        ]);
        $user2->setConnection($this->connection2)->save();
        $user2->setConnection($this->connection2)->roles()->attach($rol);
        return $user;


    }
    public function save(object $dto): object | null
    {
        try {
            $dto->setConnection($this->connection1)->save();

            $dto->setConnection($this->connection2)->save();
        } catch (Exception $e) {
            dd($e);
            return null;
        }
        return $dto;
    }
    public function update(object $dto): bool
    {
        try {
            $dto->setConnection($this->connection1)->save();
            $dto->setConnection($this->connection2)->save();
        } catch (Exception $e) {
            return false;
        }
        return true;
    }
    public function delete($id): bool
    {
        $dto = $this->findById($id);
        if ($dto) {
            try {
                $dto->setConnection($this->connection1)->delete();
                $dto->setConnection($this->connection2)->delete();
            } catch (Exception $e) {
                return false;
            }
            return true;
        }
        return true;
    }

    public function setTestMode()
    {
        $this->connection1 = "sqlite";
        $this->connection2 = "sqlite";
    }
}
