<?php

namespace App\Http\Controllers;

use App\Helpers\Utilities;
use Illuminate\Http\Request;
use App\Models\FoodObjectRestaurants;
use App\Repository\FoodObjectRestaurantsRepository;

class FoodObjectRestaurantsController extends Controller
{
    private $FoodObjectRestaurantsRepository;
    private $auth;
    public function __construct()
    {
        $this->FoodObjectRestaurantsRepository = new FoodObjectRestaurantsRepository(new FoodObjectRestaurants());
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
        $relations = ['restaurant'];
        $filter = [];
        $take = $request->take;
        $skip = $request->skip;
        return $this->FoodObjectRestaurantsRepository->getList($skip, $take, $relations, $filter);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getRestaurantByFoodsId(Request $request, $id) // Admin
    {
        //validations
        $request->validate([
            'skip' => 'Integer',
            'take' => 'required|Integer'
        ]);
        $relations = ['restaurant'];
        $filter = [];
        $take = $request->take;
        $skip = $request->skip;
        return $this->FoodObjectRestaurantsRepository->getListFood($skip, $take, $relations, $filter, $id);
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
            'food_id' => 'required|integer|exists:food_objects,id',
            'restaurant_id' => 'required|integer|exists:restaurants,id',
        ]);

        $exists =  $this->FoodObjectRestaurantsRepository->exists($data);
        if(!$exists)
            $this->FoodObjectRestaurantsRepository->create($data);
        return Utilities::wrap('create successfully', 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addToAllRestaurant(Request $request)
    {
        $data = $request->validate([
            'food_id' => 'required|integer|exists:food_objects,id',
        ]);
        $response = $this->FoodObjectRestaurantsRepository->addAll($data['food_id']);
        return Utilities::wrap(['message' => $response], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FoodObjectRestaurants  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->FoodObjectRestaurantsRepository->getById($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FoodObjectRestaurants  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'food_id' => 'integer|exists:food_objects,id',
            'restaurant_id' => 'integer|exists:restaurants,id',
        ]);
        if(array_key_exists('restaurant_id', $request->all()))
            $exists =  $this->FoodObjectRestaurantsRepository->exists($data);
            if(!$exists)
                $this->FoodObjectRestaurantsRepository->update($id, $data);

        return Utilities::wrap('create successfully', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FoodObjectRestaurants  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = FoodObjectRestaurants::where('id', $id)->firstOrFail();
        $this->FoodObjectRestaurantsRepository->delete($model);
        return Utilities::wrap(['message' => 'deleted successfully'], 200);
    }
}
