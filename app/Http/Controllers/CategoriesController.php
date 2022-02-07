<?php

namespace App\Http\Controllers;

use App\Models\LangBodys;
use App\Helpers\Utilities;
use App\Models\Categories;
use Illuminate\Http\Request;
use App\Repository\LangBodysRepository;
use App\Repository\CategoriesRepository;

class CategoriesController extends Controller
{
    private $LangBodysRepository;
    private $CategoriesRepository;
    private $auth;
    public function __construct()
    {
        $this->LangBodysRepository = new LangBodysRepository(new LangBodys());
        $this->CategoriesRepository = new CategoriesRepository(new Categories());
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
        $relations = ['restaurant', 'langBody'];
        $filter = ['restaurant.name','langBody.title', 'restaurant.uid'];
        $take = $request->take;
        $skip = $request->skip;
        if($this->auth->rules->name == 'owner'){
            return $this->CategoriesRepository->getList($skip, $take, $relations, $filter);
        }
        return $this->CategoriesRepository->getListAdmin($skip, $take, $relations, $filter, $this->auth->restaurant_id);
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
            'restaurant_id' => 'required|integer|exists:restaurants,id',
            'image' => 'required|file|mimes:jpg,jpeg,png',
        ]);
        $langData = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'lang_id' => 'required|integer|exists:languages,id',
        ]);
        if ($request->hasfile('image')) {//check image
            $data['image'] = Utilities::uploadImage($request->file('image'));            
        }
        $category =  $this->CategoriesRepository->create($data);
        $langData['tbable_type'] = 'Categories';
        $langData['tbable_id'] = $category->id;
        $this->LangBodysRepository->create($langData);
        
        //Response
        return Utilities::wrap(['message' => 'created category successfully'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Categories  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->CategoriesRepository->getById($id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Categories  $id
     * @return \Illuminate\Http\Response
     */
    public function getLangCategory($id)
    {
        $relations = ['lang'];
        return $this->CategoriesRepository->getCategoryLang($id, $relations);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Categories  $id
     * @return \Illuminate\Http\Response
     */
    public function addCategoryLanguage(Request $request)
    {
        $langData = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'lang_id' => 'required|string|exists:languages,id',
            'tbable_id' => 'required|string|exists:categories,id',
        ]);
        $langData['tbable_type'] = 'Categories';
        $checkLang = LangBodys::where('tbable_id', $langData['tbable_id'])->where('tbable_type', 'Categories')->where('lang_id', $langData['lang_id'])->first();
        if($checkLang)
            return Utilities::wrap(['error' => 'this lang already exists'], 400);

        $this->LangBodysRepository->create($langData);
        
        //Response
        return Utilities::wrap(['message' => 'created category language successfully'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Categories  $id
     * @return \Illuminate\Http\Response
     */
    public function updateCategoryLanguage(Request $request, $id)
    {
        $langData = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'lang_id' => 'required|integer|exists:languages,id',
            'tbable_id' => 'required|integer|exists:categories,id'
        ]);
        $checkLang = LangBodys::where('tbable_id', $langData['tbable_id'])->where('tbable_type', 'Categories')->where('lang_id', $langData['lang_id'])->first();
        if($checkLang)
            if($checkLang->id != $id)
                return Utilities::wrap(['error' => 'this lang already exists'], 400);
            
        $this->LangBodysRepository->update($id, $langData);
        
        //Response
        return Utilities::wrap(['message' => 'update category language successfully'], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Categories  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'restaurant_id' => 'required|integer|exists:restaurants,id',
        ]);
        if ($request->hasfile('image')) {//check image
            $data['image'] = Utilities::uploadImage($request->file('image'));            
        }
        $this->CategoriesRepository->update($id, $data);
        //Response
        return Utilities::wrap(['message' => 'updated category successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Categories  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = Categories::where('id', $id)->firstOrFail();
        $this->CategoriesRepository->softDelete($model);
        return Utilities::wrap(['message' => 'deleted successfully'], 200);
    }
}
