<?php

namespace App\Http\Controllers;

use App\Models\Meals;
use App\Helpers\Utilities;
use Illuminate\Http\Request;
use App\Repository\MealsRepository;

class MealsController extends Controller
{
    private $MealsRepository;
    private $auth;
    public function __construct()
    {
        $this->MealsRepository = new MealsRepository(new Meals());
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
        $filter = [];
        $take = $request->take;
        $skip = $request->skip;
        return $this->MealsRepository->getList($skip, $take, $relations, $filter);
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
            'poster' => 'required|image|mimes:jpg,png,jpeg',
            'price' => 'required|float',
            'discount' => 'float',
            'currency' => 'required|string',
            'category_id' => 'required|integer|exists:categories,id',
            'restaurant_id' => 'required|integer|exists:restaurants,id'
        ]);

        if ($request->hasfile('poster')) { //check image
            $data['poster'] = Utilities::uploadImage($request->file('poster'));            
        }
        $response =  $this->MealsRepository->create($data);
        return Utilities::wrap($response, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Meals  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->MealsRepository->getById($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Meals  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'poster' => 'image|mimes:jpg,png,jpeg',
            'price' => 'float',
            'discount' => 'float',
            'currency' => 'string',
            'category_id' => 'integer|exists:categories,id',
            'restaurant_id' => 'integer|exists:restaurants,id'
        ]);

        if ($request->hasfile('poster')) {//check image
            $data['poster'] = Utilities::uploadImage($request->file('poster'));            
        }
        $response =  $this->MealsRepository->update($id, $data);
        return Utilities::wrap($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Meals  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = Meals::where('id', $id)->firstOrFail();
        $this->MealsRepository->delete($model);
        return Utilities::wrap(['message' => 'deleted successfully'], 200);
    }
}
