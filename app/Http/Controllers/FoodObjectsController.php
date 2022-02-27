<?php

namespace App\Http\Controllers;

use App\Helpers\Utilities;
use App\Models\FoodObjects;
use Illuminate\Http\Request;
use App\Repository\FoodObjectsRepository;

class FoodObjectsController extends Controller
{
    private $FoodObjectsRepository;
    private $auth;//
    public function __construct()
    {
        $this->FoodObjectsRepository = new FoodObjectsRepository(new FoodObjects());
        $this->middleware('role:owner', ['only' => ['index', 'update', 'store','destroy']]);
        $this->auth = Utilities::auth();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) // Admin
    {
        //validations
        $request->validate([
            'skip' => 'Integer',
            'take' => 'required|Integer'
        ]);
        $relations = [];
        $filter = [];
        $take = $request->take;
        $skip = $request->skip;
        return $this->FoodObjectsRepository->getList($skip, $take, $relations, $filter);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'logo' => 'required|file',
            'ios_url' => 'required|string',
            'android_url' => 'required|string',
            'name' => 'required|string',
        ]);
        if ($request->hasfile('logo')) { //check logo
            $data['logo'] = Utilities::uploadImage($request->file('logo'));            
        }
        $response =  $this->FoodObjectsRepository->create($data);
        return Utilities::wrap($response, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FoodObjects  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->FoodObjectsRepository->getById($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FoodObjects  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'logo' => 'file',
            'ios_url' => 'string',
            'android_url' => 'string',
            'name' => 'string',
        ]);
        if ($request->hasfile('file')) { //check file
            $data['file'] = Utilities::uploadImage($request->file('file'));            
        }
        $response =  $this->FoodObjectsRepository->update($id, $data);
        return Utilities::wrap($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FoodObjects  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = FoodObjects::where('id', $id)->firstOrFail();
        if($this->auth->rules->name != 'owner'){
            return Utilities::wrap(['message' => 'permission denied'], 401);
        }
        $this->FoodObjectsRepository->delete($model);
        return Utilities::wrap(['message' => 'deleted successfully'], 200);
    }

    // ======= web api
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getListWeb(Request $request, $uuid) // Admin
    {
        //validations
        $request->validate([
            'skip' => 'Integer',
            'take' => 'required|Integer'
        ]);
        $take = $request->take;
        $skip = $request->skip;
        if(is_null(Utilities::getRestaurant($uuid))){
            return Utilities::wrap(['message' => 'You Don`t have License'], 400);
        }
        return $this->FoodObjectsRepository->getWeb($skip, $take, Utilities::getRestaurant($uuid)->id);
    }
}
