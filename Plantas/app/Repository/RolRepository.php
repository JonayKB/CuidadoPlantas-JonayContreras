<?php
namespace App\Repository;

use App\Models\Rol;
use Exception;
use ICrud;
use Illuminate\Database\Eloquent\Collection;

class RolRepository implements ICrud{

    public function findById(int $id): object | null{
        $dto = null;
        try {
            $dto = Rol::on("mysql")->find($id);
        } catch (Exception $e) {

            $dto = Rol::on("sqliteLocal")->find($id);

        }
        return $dto;
    }
    public function findAll(): Collection{
        $dtos = [];
        try {
            $dtos = Rol::on("mysql")->get();
        } catch (Exception $e) {
            $dtos = Rol::on("sqliteLocal")->get();
        }
        return $dtos;
    }
    public function save(object $dto): object{
        try {
            $dto->setConnection('mysql')->save();
            $dto->setConnection('sqliteLocal')->save();
        } catch (Exception $e) {
            $dto->setConnection('sqliteLocal')->save();
        }
        return $dto;


    }
    public function update(object $dto): bool{

    }
    public function delete($id): bool{

    }

}
