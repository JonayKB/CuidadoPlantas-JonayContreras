<?php

namespace App\Repository;

use App\Models\Image;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ImageRepository implements ICrud
{
    public string $connection1 = "mysql";
    public string $connection2 = "sqliteLocal";


    /**
     * Find an image
     * @param int $id to find
     * @return object|null
     */
    public function findById(int $id): object | null
    {
        $dto = null;
        try {
            $dto = Image::on($this->connection1)->find($id);
        } catch (Exception $e) {

            $dto = Image::on($this->connection2)->find($id);
        }
        return $dto;
    }
    /**
     * Returns all images
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findAll(): Collection
    {
        $dtos = [];
        try {
            $dtos = Image::on($this->connection1)->get();
        } catch (Exception $e) {
            $dtos = Image::on($this->connection2)->get();
        }
        return $dtos;
    }
    /**
     * Saves a Image
     * @param object $dto to save
     * @return object|null
     */
    public function save(object $dto): object | null
    {
        try {
            $dto->setConnection($this->connection1)->save();
            $dto2 = new Image();
            $dto2->fill($dto->toArray());
            if (!app()->runningUnitTests())
                $dto2->setConnection($this->connection2)->save();
        } catch (Exception $e) {
            return null;
        }
        return $dto;
    }
    /**
     * Updates an Image
     * @param object $dto to update
     * @return bool
     */
    public function update(object $dto): bool
    {
        try {
            $dto->setConnection($this->connection1)->save();
            if (!app()->runningUnitTests()){
                DB::connection($this->connection2)->table('images')->where('id','=', $dto->id)->update([
                    'id'=>$dto->id,
                    'path'=>$dto->path,
                    'post_id'=>$dto->post_id,
                    'deleted_at'=>$dto->deleted_at,
                ]);
            }
        } catch (Exception $e) {
            return false;
        }
        return true;
    }
    /**
     * Deletes a Image
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
