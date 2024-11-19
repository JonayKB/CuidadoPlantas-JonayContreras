<?php

namespace App\Repository;

use App\Models\Post;
use Exception;
use Illuminate\Database\Eloquent\Collection;


class PostRepository implements ICrud
{
    public final const AMOUNT_PER_PAGE = 6;

    public string $connection1 = "mysql";
    public string $connection2 = "sqliteLocal";

    /**
     * Finds a Post
     * @param int $id to find
     * @return object|null
     */
    public function findById(int $id): object | null
    {
        $dto = null;
        try {
            $dto = Post::on($this->connection1)->find($id);
        } catch (Exception $e) {

            $dto = Post::on($this->connection2)->find($id);
        }
        return $dto;
    }
    /**
     * Finds a post with deleted posts
     * @param int $id to find
     * @return object|null
     */
    public function findByIdWithTrash(int $id): object | null
    {
        $dto = null;
        try {
            $dto = Post::on($this->connection1)->withTrashed()->find($id);
        } catch (Exception $e) {

            $dto = Post::on($this->connection2)->withTrashed()->find($id);
        }
        return $dto;
    }
    /**
     * Returns all posts
     * @return Collection
     */
    public function findAll(): Collection
    {
        $dtos = [];
        try {
            $dtos = Post::on($this->connection1)->get();
        } catch (Exception $e) {
            $dtos = Post::on($this->connection2)->get();
        }
        return $dtos;
    }
    /**
     * Saves a Post
     * @param object $dto to save
     * @return object|null
     */
    public function save(object $dto): object | null
    {
        try {
            $dto->setConnection($this->connection1)->save();
            $dto2 = new Post();
            $dto2->fill($dto->toArray());
            if (!app()->runningUnitTests())
                $dto2->setConnection($this->connection2)->save();
        } catch (Exception $e) {
            return null;
        }
        return $dto;
    }
    /**
     * Updates a post
     * @param object $dto to post
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
     * Deletes a Post
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
     * Finds only deleted posts
     * @return mixed
     */
    public function getOnlyTrash()
    {
        $dtos = [];
        try {
            $dtos = Post::on($this->connection1)->onlyTrashed()->orderByDesc('created_at')->paginate(self::AMOUNT_PER_PAGE + 10);
        } catch (Exception $e) {
            $dtos = Post::on($this->connection1)->onlyTrashed()->orderByDesc('created_at')->paginate(self::AMOUNT_PER_PAGE + 10);
        }
        return $dtos;
    }
    /**
     * Restores a deleted post
     * @param mixed $id to restore
     * @return bool
     */
    public function restore($id): bool
    {
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
     * Returns pagination of reproted posts
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getReportedPosts()
    {
        $dtos = [];
        try {
            $dtos = Post::on($this->connection1)->where('reports', '>', 0)->orderByDesc('created_at')->paginate(self::AMOUNT_PER_PAGE + 10);
        } catch (Exception $e) {
            $dtos = Post::on($this->connection2)->where('reports', '>', 0)->orderByDesc('created_at')->paginate(self::AMOUNT_PER_PAGE + 10);
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
    /**
     * Get Pagination
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getPagination()
    {
        $dtos = [];
        try {
            $dtos = Post::on($this->connection1)->orderByDesc('created_at')->paginate(self::AMOUNT_PER_PAGE);
        } catch (Exception $e) {
            $dtos = Post::on($this->connection2)->orderByDesc('created_at')->paginate(self::AMOUNT_PER_PAGE);
        }
        return $dtos;
    }
}
