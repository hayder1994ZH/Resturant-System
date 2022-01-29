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
        return $this->CategoriesRepository->getList($skip, $take, $relations, $filter);
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
            'restaurant_id' => 'required|string',
        ]);
        $langData = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'lang_id' => 'required|string|exists:languages,id',
        ]);
        $category =  $this->CategoriesRepository->create($data);
        $langData['tbable_type '] = 'Categories';
        $langData['tbable_id  '] = $category->id;
        $this->LangBodysRepository->create($langData);
        
        //Response
        return Utilities::wrap(['message' => 'created successfully'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Categories  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
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
