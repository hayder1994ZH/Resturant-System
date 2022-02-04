<?php

namespace App\Http\Controllers;

use App\Helpers\Utilities;
use App\Models\ExtraMeals;
use Illuminate\Http\Request;
use App\Repository\ExtraMealsRepository;

class ExtraMealsController extends Controller
{
    private $ExtraMealsRepository;
    private $auth;
    public function __construct()
    {
        $this->ExtraMealsRepository = new ExtraMealsRepository(new ExtraMeals());
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
        return $this->ExtraMealsRepository->getList($skip, $take, $relations, $filter);
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
            'meal_id' => 'required|integer|exists:meals,id'
        ]);

        if ($request->hasfile('image')) { //check image
            $data['image'] = Utilities::uploadImage($request->file('image'));            
        }
        $response =  $this->ExtraMealsRepository->create($data);
        return Utilities::wrap($response, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ExtraMeals  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $relations = [];
        return $this->ExtraMealsRepository->getByIdModel($id, $relations);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ExtraMeals  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'image' => 'image|mimes:jpg,png,jpeg',
            'meal_id ' => 'integer|exists:meals,id'
        ]);

        if ($request->hasfile('image')) { //check image
            $data['image'] = Utilities::uploadImage($request->file('image'));            
        }
        $response =  $this->ExtraMealsRepository->update($id, $data);
        return Utilities::wrap($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ExtraMeals  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = ExtraMeals::where('id', $id)->firstOrFail();
        $this->ExtraMealsRepository->delete($model);
        return Utilities::wrap(['message' => 'deleted successfully'], 200);
    }
}
