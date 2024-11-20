<?php

namespace App\Repository;

use App\Models\PlantType;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class PlantTypeRepository implements ICrud
{
    public string $connection1 = "mysql";
    public string $connection2 = "sqliteLocal";

    /**
     * Finds a PlantType by id
     * @param int $id to find
     * @return PlantType | null
     */
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
    /**
     * Returns all PlantTypes
     * @return Collection<PlantType>
     */
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
    /**
     * Saves a plantType
     * @param object $dto to save
     * @return object|null
     */
    public function save(object $dto): object | null
    {
        try {
            $dto->setConnection($this->connection1)->save();
            $dto2 = new PlantType();
            $dto2->fill($dto->toArray());
            if(!app()->runningUnitTests())
            $dto2->setConnection($this->connection2)->save();
        } catch (Exception $e) {
            return null;
        }
        return $dto;
    }
    /**
     * Updates a planttype
     * @param object $dto to update
     * @return bool
     */
    public function update(object $dto): bool
    {
        try {
            $dto->setConnection($this->connection1)->save();
            if(!app()->runningUnitTests()){
                DB::connection($this->connection2)->table('plants_types')->where('id','=', $dto->id)->update([
                    'name'=>$dto->name,
                    'deleted_at'=>$dto->deleted_at,
                ]);
            }
        } catch (Exception $e) {
            return false;
        }
        return true;
    }
    /**
     * Deletes a plantType
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
     * Set Test Mode
     * @return void
     */
    public function setTestMode()
    {
        $this->connection1 = "sqlite";
        $this->connection2 = "sqlite";
    }
}
