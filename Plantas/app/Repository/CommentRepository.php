<?php

namespace App\Repository;

use App\Models\Comment;
use Exception;
use Illuminate\Database\Eloquent\Collection;


class CommentRepository implements ICrud
{
    public string $connection1 = "mysql";
    public string $connection2 = "sqliteLocal";

    /**
     * Finds a Comment by id
     * @param int $id to find
     * @return object|null
     */
    public function findById(int $id): object | null
    {
        $dto = null;
        try {
            $dto = Comment::on($this->connection1)->find($id);
        } catch (Exception $e) {

            $dto = Comment::on($this->connection2)->find($id);
        }
        return $dto;
    }
    /**
     * Returns all Comments
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findAll(): Collection
    {
        $dtos = [];
        try {
            $dtos = Comment::on($this->connection1)->get();
        } catch (Exception $e) {
            $dtos = Comment::on($this->connection2)->get();
        }
        return $dtos;
    }
    /**
     * Saves an Comment
     * @param object $dto to save
     * @return object|null
     */
    public function save(object $dto): object | null
    {
        try {
            $dto->setConnection($this->connection1)->save();
            $dto2 = new Comment();
            $dto2->fill($dto->toArray());
            $dto2->setConnection($this->connection2)->save();
        } catch (Exception $e) {

            return null;
        }
        return $dto;
    }
    /**
     * Updates an Comment
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
     * Deletes an Comment
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
     * Returns only deletes comments
     * @return Collection|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Support\Collection
     */
    public function getOnlyTrash(){
        $dtos = [];
        try {
            $dtos = Comment::onlyTrashed()->on($this->connection1)->get();
        } catch (Exception $e) {
            $dtos = Comment::onlyTrashed()->on($this->connection2)->get();
        }
        return $dtos;
    }
    /**
     * Restores a deleted Comment
     * @param mixed $id to deleted
     * @return bool
     */
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

    /**
     * Set Test mode
     * @return void
     */
    public function setTestMode()
    {
        $this->connection1 = "sqlite";
        $this->connection2 = "sqlite";
    }
}
