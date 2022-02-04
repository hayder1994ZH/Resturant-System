<?php

namespace App\Http\Controllers;

use App\Helpers\Utilities;
use App\Models\Restaurants;
use Illuminate\Http\Request;
use App\Repository\RestaurantsRepository;

class RestaurantsController extends Controller
{
    private $RestaurantsRepository;
    private $auth;
    public function __construct()
    {
        $this->RestaurantsRepository = new RestaurantsRepository(new Restaurants());
        $this->middleware('role:admin,owner', ['only' => ['index', 'update', 'store']]);
        $this->middleware('role:owner', ['only' => ['destroy']]);
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
        $filter = ['name', 'details'];
        $take = $request->take;
        $skip = $request->skip;
        return $this->RestaurantsRepository->getList($skip, $take, $relations, $filter);
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
            'logo' => 'required|image|mimes:jpg,png,jpeg',
            'name' => 'required|string',
            'details' => 'string',
        ]);

        if ($request->hasfile('logo')) { //check image
            $data['logo'] = Utilities::uploadImage($request->file('logo'));            
        }
        $response =  $this->RestaurantsRepository->create($data);
        return Utilities::wrap($response, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Restaurants  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->RestaurantsRepository->getById($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Restaurants  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'logo' => 'image|mimes:jpg,png,jpeg',
            'name' => 'string',
            'details' => 'string',
        ]);

        if ($request->hasfile('logo')) { //check image
            $data['logo'] = Utilities::uploadImage($request->file('logo'));            
        }
        $response =  $this->RestaurantsRepository->update($id, $data);
        return Utilities::wrap($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Restaurants  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = Restaurants::where('id', $id)->firstOrFail();
        $this->RestaurantsRepository->softDelete($model);
        return Utilities::wrap(['message' => 'deleted successfully'], 200);
    }
}
