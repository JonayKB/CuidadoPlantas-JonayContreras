<?php

namespace App\Http\Controllers;

use App\Models\Plant;
use App\Repository\PlantRepository;
use App\Repository\PlantTypeRepository;
use Illuminate\Http\Request;

class PlantController extends Controller
{
    public function indexDashboard(){
        $trash = false;
        $plantRepository = new PlantRepository();
        $plants = $plantRepository->getPagination();
        return view('dashboardPlants',compact('plants','trash'));
    }
    public function getTrash()
    {
        $trash = true;
        $plantRepository = new PlantRepository();
        $plants = $plantRepository->getOnlyTrash();
        return view('dashboardPlants', compact('plants', 'trash'));
    }
    public function restore($id){
        $plantRepository = new PlantRepository();
        $plantRepository->restore($id);
        return redirect()->route('dashboardPlants')->with('message', 'Plant restored correctly');
    }
    public function delete($id){
        $plantRepository = new PlantRepository();
        $plantRepository->delete($id);
        return redirect()->route('dashboardPlants')->with('message', 'Plant deleted correctly');
    }
    public function edit($id){
        $plantRepository = new PlantRepository();
        $plant = $plantRepository->findById($id);
        $plantTypeRepository = new PlantTypeRepository();
        $types = $plantTypeRepository->findAll();
        return view('editPlant', compact('plant','types'));
    }
    public function create(){
        $plantTypeRepository = new PlantTypeRepository();
        $types = $plantTypeRepository->findAll();
        return view('createPlant',compact('types'));
    }
    public function createPlant(Request $request){
        $plantRepository = new PlantRepository();
        $plant = new Plant();
        $plant->name = $request->name;
        $plant->type_id = $request->type;
        $plantRepository->save($plant);
        return redirect()->route('dashboardPlants')->with('message', 'Plant created correctly');
    }
    public function update(Request $request){
        $plantRepository = new PlantRepository();
        $plant_id = $request->plant_id;
        $plant = $plantRepository->findById($plant_id);
        $plant->name = $request->name;
        $plant->type_id = $request->type;
        $plantRepository->save($plant);
        return redirect()->route('dashboardPlants')->with('message', 'Plant updated correctly');
    }
}
