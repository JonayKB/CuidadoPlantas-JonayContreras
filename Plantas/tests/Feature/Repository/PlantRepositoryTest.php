<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Plant;
use App\Repository\PlantRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

class PlantRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected PlantRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new PlantRepository();
        $this->repository->setTestMode();
    }

    public function test_findAll_returns_all_plants()
    {
        // No es necesario insertar los registros ya que los datos ya están insertados en la base de datos
        $result = $this->repository->findAll();

        // Verifica que el número de plantas en la base de datos coincide con el número esperado
        $this->assertCount(91, $result);
    }

    public function test_getOnlyTrash_returns_only_deleted_plants()
    {
        // Marcar una planta como eliminada
        $plantToDelete = Plant::find(1);
        $plantToDelete->delete();  // Marca la planta con id 1 como eliminada

        // Ejecutar la consulta para obtener solo las plantas eliminadas
        $result = $this->repository->getOnlyTrash();

        // Verifica que solo haya una planta eliminada y que esa planta sea la que hemos marcado como eliminada
        $this->assertCount(1, $result->items());
        $this->assertEquals('Cactus', $result->items()[0]->name);

        // Verifica que la planta realmente esté marcada como eliminada (soft delete)
        $this->assertSoftDeleted('plants', ['plant_id' => 1]);

        // Verifica que el resto de las plantas no estén eliminadas
        $this->assertDatabaseHas('plants', ['plant_id' => 2]);
        $this->assertDatabaseHas('plants', ['plant_id' => 3]);
    }

    public function test_findById_returns_null_when_not_found()
    {
        $result = $this->repository->findById(999); // ID no existente
        $this->assertNull($result);
    }

    public function test_findById_returns_plant_when_found()
    {
        $plant = Plant::find(1); // ID existente
        $result = $this->repository->findById($plant->plant_id);
        $this->assertNotNull($result);
        $this->assertEquals('Cactus', $result->name);
    }

    public function test_save_creates_plant()
    {
        $plant = new Plant([
            'plant_id' => 92, // ID ficticio
            'name' => 'New Plant',
            'type_id' => 9,
        ]);
        $result = $this->repository->save($plant);
        $this->assertNotNull($result);
        $this->assertDatabaseHas('plants', ['name' => 'New Plant']);
    }

    public function test_update_updates_existing_plant()
    {
        $plant = Plant::find(1); // Obtener la planta con ID 1
        $plant->name = 'Updated Cactus';
        $updated = $this->repository->update($plant);
        $this->assertTrue($updated);
        $this->assertDatabaseHas('plants', ['name' => 'Updated Cactus']);
    }

    public function test_delete_removes_plant()
    {
        $plant = Plant::find(1); // Obtener la planta con ID 1
        $deleted = $this->repository->delete($plant->plant_id);
        $this->assertTrue($deleted);
        $this->assertSoftDeleted('plants', ['plant_id' => $plant->plant_id]);
    }

    public function test_getPagination_returns_paginated_results()
    {
        $paginated = $this->repository->getPagination();
        // Verifica que los resultados estén paginados
        $this->assertGreaterThanOrEqual(1, $paginated->total());
    }

    public function test_restore_restores_deleted_plant()
    {
        $plant = Plant::find(1); // Obtener la planta con ID 1
        $this->repository->delete($plant->plant_id); // Eliminarla
        $restored = $this->repository->restore($plant->plant_id); // Restaurar la planta
        $this->assertTrue($restored);
        $this->assertDatabaseHas('plants', ['plant_id' => $plant->plant_id, 'deleted_at' => null]);
    }
}
