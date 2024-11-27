<?php

namespace Tests\Feature;

use App\Models\PlantType;
use App\Repository\PlantTypeRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlantTypeRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_001_get_all_plant_types()
    {
        $repository = new PlantTypeRepository();
        $repository->setTestMode();

        $plantTypes = $repository->findAll();
        $this->assertEquals(16, $plantTypes->count());
        $this->assertEquals('Trees', $plantTypes->first()->name);
    }

    public function test_002_get_plant_type_by_id()
    {
        $repository = new PlantTypeRepository();
        $repository->setTestMode();

        $plantType = $repository->findById(1);
        $this->assertEquals('Trees', $plantType->name);
    }

    public function test_003_save_new_plant_type()
    {
        $repository = new PlantTypeRepository();
        $repository->setTestMode();

        $newPlantType = new PlantType(['name' => 'Bamboos']);
        $savedPlantType = $repository->save($newPlantType);

        $this->assertNotNull($savedPlantType);
        $this->assertEquals('Bamboos', $savedPlantType->name);
        $this->assertEquals(17, PlantType::count());
    }

    public function test_004_update_plant_type()
    {
        $repository = new PlantTypeRepository();
        $repository->setTestMode();

        $plantType = PlantType::find(1);
        $plantType->name = 'Updated Trees';
        $updated = $repository->update($plantType);

        $this->assertTrue($updated);
        $this->assertEquals('Updated Trees', PlantType::find(1)->name);
    }

    public function test_005_delete_plant_type()
    {
        $repository = new PlantTypeRepository();
        $repository->setTestMode();

        $deleted = $repository->delete(1);
        $this->assertTrue($deleted);
        $this->assertNull(PlantType::find(1));
        $this->assertEquals(15, PlantType::count());
    }

    public function test_006_get_plant_type_with_invalid_id()
    {
        $repository = new PlantTypeRepository();
        $repository->setTestMode();

        $plantType = $repository->findById(999);
        $this->assertNull($plantType);
    }

    public function test_007_save_with_exception()
    {
        $repository = new PlantTypeRepository();
        $repository->setTestMode();

        $invalidPlantType = new PlantType();
        $savedPlantType = $repository->save($invalidPlantType);

        $this->assertNull($savedPlantType);
    }
}
