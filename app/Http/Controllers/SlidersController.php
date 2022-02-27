<?php

namespace App\Http\Controllers;

use App\Models\Sliders;
use App\Helpers\Utilities;
use Illuminate\Http\Request;
use App\Repository\SlidersRepository;

class SlidersController extends Controller
{
    private $SlidersRepository;
    private $auth;//
    public function __construct()
    {
        $this->SlidersRepository = new SlidersRepository(new Sliders());
        $this->middleware('role:admin,owner', ['only' => ['index', 'update', 'store']]);
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
        if($this->auth->rules->name == 'owner')
            return $this->SlidersRepository->getList($skip, $take, $relations, $filter);
        return $this->SlidersRepository->getListAdmin($skip, $take, $relations, $filter, $this->auth->restaurant_id);
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
            'file' => 'required|file',
            'url' => 'required|string',
            'type' => 'required|string|in:image,video',
        ]);
        if ($request->hasfile('file')) { //check file
            $data['file'] = Utilities::uploadImage($request->file('file'));            
        }
        $response =  $this->SlidersRepository->create($data);
        return Utilities::wrap($response, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Sliders  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->SlidersRepository->getById($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Sliders  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'file' => 'file',
            'url' => 'string',
            'type' => 'string|in:image,video',
        ]);
        if ($request->hasfile('file')) { //check file
            $data['file'] = Utilities::uploadImage($request->file('file'));            
        }
        $response =  $this->SlidersRepository->update($id, $data);
        return Utilities::wrap($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Sliders  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = Sliders::where('id', $id)->firstOrFail();
        if($this->auth->rules->name != 'owner'){
            return Utilities::wrap(['message' => 'permission denied'], 401);
        }
        $this->SlidersRepository->delete($model);
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
        return $this->SlidersRepository->getWeb($skip, $take, Utilities::getRestaurant($uuid)->id);
    }
}
