<?php

namespace App\Http\Controllers;

use App\Helpers\Utilities;
use Illuminate\Http\Request;
use App\Models\RestaurantPhones;
use App\Repository\RestaurantPhonesRepository;

class RestaurantPhonesController extends Controller
{
    private $RestaurantPhonesRepository;
    private $auth;
    public function __construct()
    {
        $this->RestaurantPhonesRepository = new RestaurantPhonesRepository(new RestaurantPhones());
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
        return $this->RestaurantPhonesRepository->getList($skip, $take, $relations, $filter);
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
            'phone' => 'required|string',
            'restaurant_id' => 'required|integer|exists:restaurants,id',
        ]);
        $response =  $this->RestaurantPhonesRepository->create($data);
        return Utilities::wrap($response, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RestaurantPhones  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->RestaurantPhonesRepository->getById($id);
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
        return $this->RestaurantPhonesRepository->getByRestaurantId($uid, $relations);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RestaurantPhones  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'phone' => 'string',
        ]);
        $response =  $this->RestaurantPhonesRepository->update($id, $data);
        return Utilities::wrap($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RestaurantPhones  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = RestaurantPhones::where('id', $id)->firstOrFail();
        $this->RestaurantPhonesRepository->delete($model);
        return Utilities::wrap(['message' => 'deleted successfully'], 200);
    }
}
