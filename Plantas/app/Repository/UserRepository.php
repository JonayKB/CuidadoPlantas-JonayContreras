<?php

namespace App\Repository;

use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserRepository implements ICrud
{
    public final const AMOUNT_PER_PAGE = 10;

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
    public function findByIdWithTrash(int $id): object | null
    {
        $dto = null;
        try {
            $dto = User::on($this->connection1)->withTrashed()->find($id);
        } catch (Exception $e) {

            $dto = User::on($this->connection2)->withTrashed()->find($id);
        }
        return $dto;
    }
    public function findByEmail($email)
    {
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
    public function create(Request $request): object | null
    {
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
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'password' => $user->password,
            'verified' => $user->verified,
            'remember_token' => $user->remember_token,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ]);
        if (!app()->runningUnitTests()) {
            $user2->setConnection($this->connection2)->save();
            $user2->setConnection($this->connection2)->roles()->attach($rol);
        }
        return $user;
    }
    public function save(object $dto): object | null
    {
        try {
            $dto->setConnection($this->connection1)->save();

            $dto2 = new User([
                'id' => $dto->id,
                'name' => $dto->name,
                'email' => $dto->email,
                'password' => $dto->password,
                'verified' => $dto->verified,
                'remember_token' => $dto->remember_token,
                'created_at' => $dto->created_at,
                'updated_at' => $dto->updated_at,
                'email_verified_at' => $dto->email_verified_at,
                'deleted_at' => $dto->deleted_at,
            ]);
            if (!app()->runningUnitTests()) {
                $dto2->setConnection($this->connection2)->save();
            }
        } catch (Exception $e) {
            return null;
        }
        return $dto;
    }
    public function update(object $dto): bool
    {
        try {
            $dto->setConnection($this->connection1)->save();

            $dto2 = new User([
                'id' => $dto->id,
                'name' => $dto->name,
                'email' => $dto->email,
                'password' => $dto->password,
                'verified' => $dto->verified,
                'remember_token' => $dto->remember_token,
                'created_at' => $dto->created_at,
                'updated_at' => $dto->updated_at,
                'email_verified_at' => $dto->email_verified_at,
                'deleted_at' => $dto->deleted_at,
            ]);
            if (!app()->runningUnitTests()) {
                $dto2->setConnection($this->connection2)->save();
            }
            return true;
        } catch (Exception $e) {
            return false;
        }
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
    public function getOnlyTrash()
    {
        $dtos = [];
        try {
            $dtos = User::on($this->connection1)->onlyTrashed()->orderByDesc('created_at')->paginate(self::AMOUNT_PER_PAGE);
        } catch (Exception $e) {
            $dtos = User::on($this->connection2)->onlyTrashed()->orderByDesc('created_at')->paginate(self::AMOUNT_PER_PAGE);
        }
        return $dtos;
    }
    public function getNotVerified()
    {
        $dtos = [];
        try {
            $dtos = User::on($this->connection1)->where('verified', false)->orderByDesc('created_at')->paginate(self::AMOUNT_PER_PAGE);
        } catch (Exception $e) {
            $dtos = User::on($this->connection2)->where('verified', false)->orderByDesc('created_at')->paginate(self::AMOUNT_PER_PAGE);
        }
        return $dtos;
    }
    public function restore($id): bool
    {
        $dto = $this->findByIdWithTrash($id);
        if ($dto) {
            try {
                $dto->setConnection($this->connection1)->restore();

                $dto2 = new User([
                    'id' => $dto->id,
                    'name' => $dto->name,
                    'email' => $dto->email,
                    'password' => $dto->password,
                    'verified' => $dto->verified,
                    'remember_token' => $dto->remember_token,
                    'created_at' => $dto->created_at,
                    'updated_at' => $dto->updated_at,
                    'email_verified_at' => $dto->email_verified_at,
                    'deleted_at' => $dto->deleted_at,
                ]);
                if (!app()->runningUnitTests()) {
                    $dto2->setConnection($this->connection2)->restore();
                }
            } catch (Exception $e) {
                return false;
            }
            return true;
        }
        return false;
    }
    public function verify($id)
    {
        $dto = $this->findById($id);
        if ($dto) {
            $dto->verified = true;
            try {
                $dto->setConnection($this->connection1)->save();
                $dto->setConnection($this->connection2)->save();
            } catch (Exception $e) {
                return false;
            }
            return true;
        }
        return false;
    }

    public function getPagination()
    {
        $dtos = [];
        try {
            $dtos = User::on($this->connection1)->orderByDesc('created_at')->paginate(self::AMOUNT_PER_PAGE);
        } catch (Exception $e) {
            $dtos = User::on($this->connection2)->orderByDesc('created_at')->paginate(self::AMOUNT_PER_PAGE);
        }
        return $dtos;
    }

    public function setTestMode()
    {
        $this->connection1 = "sqlite";
        $this->connection2 = "sqlite";
    }
}
