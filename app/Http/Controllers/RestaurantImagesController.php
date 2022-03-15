<?php

namespace App\Http\Controllers;

use App\Helpers\Utilities;
use Illuminate\Http\Request;
use App\Models\RestaurantImages;
use App\Repository\RestaurantImagesRepository;

class RestaurantImagesController extends Controller
{
    private $RestaurantImagesRepository;
    private $auth;
    public function __construct()
    {
        $this->RestaurantImagesRepository = new RestaurantImagesRepository(new RestaurantImages());
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
        $filter = ['phone'];
        $take = $request->take;
        $skip = $request->skip;
        return $this->RestaurantImagesRepository->getList($skip, $take, $relations, $filter);
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
            'image' => 'required|image|mimes:jpg,png,jpeg',
            'restaurant_id' => 'required|integer|exists:restaurants,id',
        ]);
        if ($request->hasfile('image')) { //check image
            $data['image'] = Utilities::uploadImage($request->file('image'));            
        }
        $response =  $this->RestaurantImagesRepository->create($data);
        return Utilities::wrap($response, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RestaurantImages  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->RestaurantImagesRepository->getById($id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ResturantsLanguages  $id
     * @return \Illuminate\Http\Response
     */
    public function getByRestaurantId($uid)
    {
        $relations = [];
        return $this->RestaurantImagesRepository->getByRestaurantId($uid, $relations);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RestaurantImages  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'image' => 'image|mimes:jpg,png,jpeg',
        ]);
        if ($request->hasfile('image')) { //check image
            $data['image'] = Utilities::uploadImage($request->file('image'));            
        }
        $response =  $this->RestaurantImagesRepository->update($id, $data);
        return Utilities::wrap($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RestaurantImages  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = RestaurantImages::where('id', $id)->firstOrFail();
        $this->RestaurantImagesRepository->delete($model);
        return Utilities::wrap(['message' => 'deleted successfully'], 200);
    }
}
