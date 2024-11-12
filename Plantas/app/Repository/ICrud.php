<?php
interface ICrud{
    public function findById($dto): object | null;
    public function findAll(): array;
    public function save(object $dto): object;
    public function update(object $dto): bool;
    public function delete($id): bool;

}
