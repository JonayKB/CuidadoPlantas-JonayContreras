<?php

namespace App\Repository;

use App\Models\Rol;
use Exception;
use ICrud;
use Illuminate\Database\Eloquent\Collection;

class RolRepository implements ICrud
{
    public string $connection1 = "mysql";
    public string $connection2 = "sqliteLocal";

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
    public function save(object $dto): object | null
    {
        try {
            $dto->setConnection($this->connection1)->save();
            $dto->setConnection($this->connection2)->save();
        } catch (Exception $e) {
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
