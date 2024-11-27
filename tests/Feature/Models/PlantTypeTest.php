<?php

namespace Tests\Feature;

use App\Models\PlantType;
use App\Repository\PlantTypeRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PlantTypeTest extends TestCase
{

    use RefreshDatabase;
    public function test_001_get_plants(): void
    {
        $plantType = PlantType::find(5);

        $this->assertEquals(20, $plantType->plants->count());


    }
}
