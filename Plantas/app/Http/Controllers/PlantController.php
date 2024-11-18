<?php

namespace App\Http\Controllers;

use App\Repository\PlantRepository;
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
        return view('editPlant', compact('plant'));
    }
    public function create(){
        return view('createPlant');
    }
}
