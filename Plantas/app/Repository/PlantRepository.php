<?php

namespace App\Repository;

use App\Models\Plant;
use Exception;
use Illuminate\Database\Eloquent\Collection;


class PlantRepository implements ICrud
{
    public final const AMOUNT_PER_PAGE = 10;
    public string $connection1 = "mysql";
    public string $connection2 = "sqliteLocal";

    /**
     * Find a Plant
     * @param int $id to find
     * @return object|null
     */
    public function findById(int $id): object | null
    {
        $dto = null;
        try {
            $dto = Plant::on($this->connection1)->find($id);
        } catch (Exception $e) {

            $dto = Plant::on($this->connection2)->find($id);
        }
        return $dto;
    }
    /**
     * Find with deleted plant
     * @param int $id to find
     * @return object|null
     */
    public function findByIdWithTrash(int $id): object | null
    {
        $dto = null;
        try {
            $dto = Plant::on($this->connection1)->withTrashed()->find($id);
        } catch (Exception $e) {

            $dto = Plant::on($this->connection2)->withTrashed()->find($id);
        }
        return $dto;
    }
    /**
     * Find all plants
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findAll(): Collection
    {
        $dtos = [];
        try {
            $dtos = Plant::on($this->connection1)->get();
        } catch (Exception $e) {
            $dtos = Plant::on($this->connection2)->get();
        }
        return $dtos;
    }
    /**
     * Saves a plant
     * @param object $dto to save
     * @return object|null
     */
    public function save(object $dto): object | null
    {
        try {
            $dto->setConnection($this->connection1)->save();
            $dto2 = new Plant();
            $dto2->fill($dto->toArray());
            if (!app()->runningUnitTests())
            $dto2->setConnection($this->connection2)->save();
        } catch (Exception $e) {
            return null;
        }
        return $dto;
    }
    /**
     * Updates a Plant
     * @param object $dto to update
     * @return bool
     */
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
    /**
     * Deletes a Plant
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
     * Gets pagination
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getPagination()
    {
        $dtos = [];
        try {
            $dtos = Plant::on($this->connection1)->paginate(self::AMOUNT_PER_PAGE);
        } catch (Exception $e) {
            $dtos = Plant::on($this->connection2)->paginate(self::AMOUNT_PER_PAGE);
        }
        return $dtos;
    }
    /**
     * Find all deleted plants
     * @return mixed
     */
    public function getOnlyTrash(){
        $dtos = [];
        try {
            $dtos = Plant::on($this->connection1)->onlyTrashed()->paginate(self::AMOUNT_PER_PAGE);
        } catch (Exception $e) {
            $dtos = Plant::on($this->connection2)->onlyTrashed()->paginate(self::AMOUNT_PER_PAGE);

        }
        return $dtos;
    }
    /**
     * Restore a deleted plant
     * @param mixed $id to restore
     * @return bool
     */
    public function restore($id): bool{
        $dto = $this->findByIdWithTrash($id);
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


    /**
     * Set test mode
     */
    public function setTestMode()
    {
        $this->connection1 = "sqlite";
        $this->connection2 = "sqlite";
    }
}
