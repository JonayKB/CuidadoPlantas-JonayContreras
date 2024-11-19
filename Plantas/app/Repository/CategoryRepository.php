<?php

namespace App\Repository;

use App\Models\Category;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class CategoryRepository implements ICrud
{
    public final const AMOUNT_PER_PAGE = 10;

    public string $connection1 = "mysql";
    public string $connection2 = "sqliteLocal";

    /**
     * Finds a Category by id
     * @param int $id to find
     * @return object|null
     */
    public function findById(int $id): object | null
    {
        $dto = null;
        try {
            $dto = Category::on($this->connection1)->find($id);
        } catch (Exception $e) {

            $dto = Category::on($this->connection2)->find($id);
        }
        return $dto;
    }
    /**
     * Find with deleted cateogry
     * @param int $id to find
     * @return object|null
     */
    public function findByIdWithTrash(int $id): object | null
    {
        $dto = null;
        try {
            $dto = Category::on($this->connection1)->withTrashed()->find($id);
        } catch (Exception $e) {

            $dto = Category::on($this->connection2)->withTrashed()->find($id);
        }
        return $dto;
    }
    /**
     * Returns all Categories
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findAll(): Collection
    {
        $dtos = [];
        try {
            $dtos = Category::on($this->connection1)->get();
        } catch (Exception $e) {
            $dtos = Category::on($this->connection2)->get();
        }
        return $dtos;
    }
    /**
     * Save an Category
     * @param object $dto to save
     * @return object|null
     */
    public function save(object $dto): object | null
    {
        try {
            $dto->setConnection($this->connection1)->save();
            $dto2 = new Category();
            $dto2->fill($dto->toArray());
            $dto2->setConnection($this->connection2)->save();
        } catch (Exception $e) {
            return null;
        }
        return $dto;
    }
    /**
     * Updates an Category
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
     * Delete a Category
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
     * Returns only deleted Categories
     * @return Collection|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Support\Collection
     */
    public function getOnlyTrash(){
        $dtos = [];
        try {
            $dtos = Category::on($this->connection1)->onlyTrashed()->paginate(self::AMOUNT_PER_PAGE);
        } catch (Exception $e) {
            $dtos = Category::on($this->connection2)->onlyTrashed()->paginate(self::AMOUNT_PER_PAGE);

        }
        return $dtos;
    }
    /**
     * Restore a deleted Category
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
     * Returns paginated Categories
     * @param int $page
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     *
     */
    public function getPagination(){
        $dtos = [];
        try {
            $dtos = Category::on($this->connection1)->paginate(self::AMOUNT_PER_PAGE);
        } catch (Exception $e) {
            $dtos = Category::on($this->connection2)->paginate(self::AMOUNT_PER_PAGE);
        }
        return $dtos;
    }

    /**
     * Set test mode
     * @return void
     */
    public function setTestMode()
    {
        $this->connection1 = "sqlite";
        $this->connection2 = "sqlite";
    }
}
