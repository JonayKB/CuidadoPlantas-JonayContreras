<?php

namespace App\Http\Controllers;

use App\Models\Plant;
use App\Repository\PlantRepository;
use App\Repository\PlantTypeRepository;
use Illuminate\Http\Request;

class PlantController extends Controller
{
    /**
     * Get the paginations needed to show plants dashboard
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function indexDashboard(){
        $trash = false;
        $plantRepository = new PlantRepository();
        $plants = $plantRepository->getPagination();
        return view('dashboardPlants',compact('plants','trash'));
    }
    /**
     * Get the deleted plants and returns to a view
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function getTrash()
    {
        $trash = true;
        $plantRepository = new PlantRepository();
        $plants = $plantRepository->getOnlyTrash();
        return view('dashboardPlants', compact('plants', 'trash'));
    }
    /**
     * Restore a plant
     * @param mixed $id plant id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore($id){
        $plantRepository = new PlantRepository();
        $plantRepository->restore($id);
        return redirect()->route('dashboardPlants')->with('message', 'Plant restored correctly');
    }
    /**
     * Delete a plant
     * @param mixed $id plant id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($id){
        $plantRepository = new PlantRepository();
        $plantRepository->delete($id);
        return redirect()->route('dashboardPlants')->with('message', 'Plant deleted correctly');
    }
    /**
     * Returns to the edit view
     * @param mixed $id to edit
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit($id){
        $plantRepository = new PlantRepository();
        $plant = $plantRepository->findById($id);
        $plantTypeRepository = new PlantTypeRepository();
        $types = $plantTypeRepository->findAll();
        return view('editPlant', compact('plant','types'));
    }
    /**
     * Returns to the create view
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create(){
        $plantTypeRepository = new PlantTypeRepository();
        $types = $plantTypeRepository->findAll();
        return view('createPlant',compact('types'));
    }
    /**
     * Creates a new plant
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createPlant(Request $request){
        $plantRepository = new PlantRepository();
        $plant = new Plant();
        $plant->name = $request->name;
        $plant->type_id = $request->type;
        $plantRepository->save($plant);
        return redirect()->route('dashboardPlants')->with('message', 'Plant created correctly');
    }
    /**
     * Updates a plant
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
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
