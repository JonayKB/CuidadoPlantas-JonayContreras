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

    /**
     * Finds a User
     * @param int $id to find
     * @return object|null
     */
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
    /**
     * Finds a user if is or not deleted
     * @param int $id to find
     * @return object|null
     */
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
    /**
     * Finds a User
     * @param mixed $email from user
     * @return object|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|null
     */
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
    /**
     * Returns all users
     * @return Collection<User>
     */
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
    /**
     * Creates a new User and saves it
     * @param \Illuminate\Http\Request $request
     * @return object|null
     */
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
    /**
     * Saves an User
     * @param object $dto to save
     * @return object|null
     */
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
    /**
     * Updates an User
     * @param object $dto to update
     * @return bool
     */
    public function update(object $dto): bool
{
    try {
        $dto->setConnection($this->connection1)->save();

        if (!app()->runningUnitTests()) {
            DB::connection($this->connection2)->table('users')->where('id','=', $dto->id)->update([
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
        }

        return true;
    } catch (Exception $e) {
        return false;
    }
}



    /**
     * Deletes a User
     * @param mixed $id to delete
     * @return bool
     */
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

    /**
     * Returns only deleted users
     * @return mixed
     */
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
    /**
     * Returns not verifieds Users
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
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
    /**
     * Restores a deleted user
     * @param mixed $id to restore
     * @return bool
     */
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
    /**
     * Verifies a user
     * @param mixed $id to verify
     * @return bool
     */
    public function verify($id)
    {
        $dto = $this->findById($id);
        if ($dto) {
            $dto->verified = true;
           $this->update($dto);
        }
        return false;
    }

    /**
     * Get Pagination
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
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

    /**
     * Set Test Mode
     * @return void
     */
    public function setTestMode()
    {
        $this->connection1 = "sqlite";
        $this->connection2 = "sqlite";
    }
}
