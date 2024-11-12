<?php

use Illuminate\Database\Eloquent\Collection;

interface ICrud{
    public function findById(int $id): object | null;
    public function findAll(): Collection;
    public function save(object $dto): object;
    public function update(object $dto): bool;
    public function delete($id): bool;

}
