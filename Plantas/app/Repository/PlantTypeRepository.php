<?php

namespace App\Repository;

use App\Models\PlantType;
use Exception;
use Illuminate\Database\Eloquent\Collection;


class PlantTypeRepository implements ICrud
{
    public string $connection1 = "mysql";
    public string $connection2 = "sqliteLocal";

    public function findById(int $id): object | null
    {
        $dto = null;
        try {
            $dto = PlantType::on($this->connection1)->find($id);
        } catch (Exception $e) {

            $dto = PlantType::on($this->connection2)->find($id);
        }
        return $dto;
    }
    public function findAll(): Collection
    {
        $dtos = [];
        try {
            $dtos = PlantType::on($this->connection1)->get();
        } catch (Exception $e) {
            $dtos = PlantType::on($this->connection2)->get();
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
    public function getOnlyTrash(){
        $dtos = [];
        try {
            $dtos = PlantType::onlyTrashed()->on($this->connection1)->get();
        } catch (Exception $e) {
            $dtos = PlantType::onlyTrashed()->on($this->connection2)->get();
        }
        return $dtos;
    }
    public function restore($id): bool{
        $dto = $this->findById($id);
        if ($dto) {
            try {
                $dto->setConnection($this->connection1)->restore();
                $dto->setConnection($this->connection2)->restore();
            } catch (Exception $e) {
                return false;
            }
            return true;
        }
        return false;
    }

    public function setTestMode()
    {
        $this->connection1 = "sqlite";
        $this->connection2 = "sqlite";
    }
}
