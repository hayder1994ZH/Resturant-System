<?php

namespace App\Http\Controllers;

use App\Models\Meals;
use App\Models\LangBodys;
use App\Helpers\Utilities;
use Illuminate\Http\Request;
use App\Repository\MealsRepository;
use App\Repository\LangBodysRepository;

class MealsController extends Controller
{
    private $MealsRepository;
    private $LangBodysRepository;
    private $auth;
    public function __construct()
    {
        $this->LangBodysRepository = new LangBodysRepository(new LangBodys());
        $this->MealsRepository = new MealsRepository(new Meals());
        $this->middleware('role:admin,owner', ['only' => ['index', 'update', 'store', 'destroy']]);
        // $this->middleware('role:owner', ['only' => ['destroy']]);
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
        $relations = ['restaurant', 'category', 'langBody'];
        $filter = ['restaurant.name', 'langBody.title'];
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
            'price' => 'required|numeric',
            'discount' => 'numeric',
            'currency' => 'required|string',
            'category_id' => 'required|integer|exists:categories,id',
            'restaurant_id' => 'required|integer|exists:restaurants,id'
        ]);
        $langData = $request->validate([
            'title' => 'required|string',
            'description' => 'string',
            'lang_id' => 'required|integer|exists:languages,id'
        ]);

        if ($request->hasfile('poster')) { //check image
            $data['poster'] = Utilities::uploadImage($request->file('poster'));            
        }
        $meals =  $this->MealsRepository->create($data);//create new meal
        
        $langData['tbable_type'] = 'Meals';
        $langData['tbable_id'] = $meals->id;
        $this->LangBodysRepository->create($langData);//add meal data
        return Utilities::wrap(['message' => 'create meal successfully'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Meals  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $relations = ['category', 'langBody'];
        return $this->MealsRepository->getByIdModel($id, $relations);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Categories  $id
     * @return \Illuminate\Http\Response 
     */
    public function getLangMeal($id)
    {
        $relations = ['lang'];
        return $this->MealsRepository->getMealLang($id);
    }
    
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Categories  $id
     * @return \Illuminate\Http\Response getExtraMeal
     */
    public function getExtraMeals($id)
    {
        $relations = [];
        return $this->MealsRepository->getExtraMeal($id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Meals  $id
     * @return \Illuminate\Http\Response
     */
    public function addNewMealLanguage(Request $request)
    {
        $langData = $request->validate([
            'title' => 'required|string',
            'description' => 'string',
            'lang_id' => 'required|integer|exists:languages,id',
            'tbable_id' => 'required|integer|exists:meals,id'
        ]);

        $langData['tbable_type'] = 'Meals';
        $checkLang = LangBodys::where('tbable_id', $langData['tbable_id'])->where('tbable_type', 'Meals')->where('lang_id', $langData['lang_id'])->first();
        if($checkLang)
            return Utilities::wrap(['error' => 'this lang already exists'], 400);
        $this->LangBodysRepository->create($langData);//add meal language
        return Utilities::wrap(['message' => 'create new meal language successfully'], 200);
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Meals  $id
     * @return \Illuminate\Http\Response
     */
    public function updateMealLanguage(Request $request,$id)
    {
        $langData = $request->validate([
            'title' => 'string',
            'description' => 'string',
            'lang_id' => 'integer|exists:languages,id',
            'tbable_id' => 'integer|exists:meals,id'
        ]);
        $checkLang = LangBodys::where('tbable_id', $langData['tbable_id'])->where('tbable_type', 'Meals')->where('lang_id', $langData['lang_id'])->first();
        if($checkLang)
            if($checkLang->id != $id)
                return Utilities::wrap(['error' => 'this lang already exists'], 400);
        $this->LangBodysRepository->update($id, $langData);//update meal language
        return Utilities::wrap(['message' => 'update meal language successfully'], 200);
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
            'price' => 'numeric',
            'discount' => 'numeric',
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
