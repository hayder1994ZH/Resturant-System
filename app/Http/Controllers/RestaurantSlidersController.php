<?php

namespace App\Http\Controllers;

use App\Models\RestaurantSliders;
use App\Helpers\Utilities;
use Illuminate\Http\Request;
use App\Repository\RestaurantSlidersRepository;

class RestaurantSlidersController extends Controller
{
    private $RestaurantSlidersRepository;
    private $auth;
    public function __construct()
    {
        $this->RestaurantSlidersRepository = new RestaurantSlidersRepository(new RestaurantSliders());
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
        return $this->RestaurantSlidersRepository->getList($skip, $take, $relations, $filter);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getRestaurantBySlidersId(Request $request, $id) // Admin
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
        return $this->RestaurantSlidersRepository->getListSlider($skip, $take, $relations, $filter, $id);
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
            'slider_id' => 'required|integer|exists:sliders,id',
            'restaurant_id' => 'required|integer|exists:restaurants,id',
        ]);

        $exists =  $this->RestaurantSlidersRepository->exists($data);
        if(!$exists)
            $this->RestaurantSlidersRepository->create($data);
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
            'slider_id' => 'required|integer|exists:sliders,id',
        ]);
        $response = $this->RestaurantSlidersRepository->addAll($data['slider_id']);
        return Utilities::wrap(['message' => $response], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RestaurantSliders  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->RestaurantSlidersRepository->getById($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RestaurantSliders  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'slider_id' => 'required|integer|exists:sliders,id',
            'restaurant_id' => 'integer|exists:restaurants,id',
        ]);
        if(array_key_exists('restaurant_id', $request->all()))
            $exists =  $this->RestaurantSlidersRepository->exists($data);
            if(!$exists)
                $this->RestaurantSlidersRepository->update($id, $data);

        return Utilities::wrap('create successfully', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RestaurantSliders  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = RestaurantSliders::where('id', $id)->firstOrFail();
        $this->RestaurantSlidersRepository->delete($model);
        return Utilities::wrap(['message' => 'deleted successfully'], 200);
    }
}
