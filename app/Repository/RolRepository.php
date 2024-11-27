<?php

namespace App\Repository;

use App\Models\Rol;
use Exception;
use Illuminate\Database\Eloquent\Collection;


class RolRepository implements ICrud
{
    public string $connection1 = "mysql";
    public string $connection2 = "sqliteLocal";


    /**
     * Finds a Rol
     * @param int $id to find
     * @return object|null
     */
    public function findById(int $id): object | null
    {
        $dto = null;
        try {
            $dto = Rol::on($this->connection1)->find($id);
        } catch (Exception $e) {

            $dto = Rol::on($this->connection2)->find($id);
        }
        return $dto;
    }
    /**
     * Finds all roles
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findAll(): Collection
    {
        $dtos = [];
        try {
            $dtos = Rol::on($this->connection1)->get();
        } catch (Exception $e) {
            $dtos = Rol::on($this->connection2)->get();
        }
        return $dtos;
    }
    /**
     * Saves a Rol
     * @param object $dto to save
     * @return object|null
     */
    public function save(object $dto): object | null
    {
        try {
            $dto->setConnection($this->connection1)->save();
            $dto2 = new Rol();
            $dto2->fill($dto->toArray());
            if (!app()->runningUnitTests()) {
                $dto2->setConnection($this->connection2)->save();
            }
        } catch (Exception $e) {
            return null;
        }
        return $dto;
    }
    /**
     * Updates a Rol
     * @param object $dto to update
     * @return bool
     */
    public function update(object $dto): bool
    {
        try {
            $dto->setConnection($this->connection1)->save();
            if (!app()->runningUnitTests())
                $dto->setConnection($this->connection2)->save();
        } catch (Exception $e) {
            return false;
        }
        return true;
    }
    /**
     * Deletes a Rol
     * @param mixed $id to delete
     * @return bool
     */
    public function delete($id): bool
    {
        $dto = $this->findById($id);
        if ($dto) {
            try {
                $dto->setConnection($this->connection1)->delete();
            if (!app()->runningUnitTests())
                $dto->setConnection($this->connection2)->delete();
            } catch (Exception $e) {
                return false;
            }
            return true;
        }
        return true;
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
