<?php

namespace App\Http\Controllers;

use App\Helpers\Utilities;
use Illuminate\Http\Request;
use App\Models\ResturantsLanguages;
use App\Repository\ResturantsLanguagesRepository;

class ResturantsLanguagesController extends Controller
{
    private $ResturantsLanguagesRepository;
    private $auth;
    public function __construct()
    {
        $this->ResturantsLanguagesRepository = new ResturantsLanguagesRepository(new ResturantsLanguages());
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
        $relations = ['lang'];
        $filter = [];
        $take = $request->take;
        $skip = $request->skip;
        return $this->ResturantsLanguagesRepository->getList($skip, $take, $relations, $filter);
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
            'lang_id' => 'required|integer|exists:languages,id',
            'restaurant_id' => 'required|integer|exists:restaurants,id'
        ]);

        if ($request->hasfile('image')) { //check image
            $data['image'] = Utilities::uploadImage($request->file('image'));            
        }
        $response =  $this->ResturantsLanguagesRepository->create($data);
        return Utilities::wrap($response, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ResturantsLanguages  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $relations = [];
        return $this->ResturantsLanguagesRepository->getByIdModel($id, $relations);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ResturantsLanguages  $id
     * @return \Illuminate\Http\Response
     */
    public function getByRestaurantId($restaurant_id)
    {
        $relations = ['lang'];
        return $this->ResturantsLanguagesRepository->getByRestaurantId($restaurant_id, $relations);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ResturantsLanguages  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'lang_id' => 'integer|exists:languages,id',
            'restaurant_id' => 'integer|exists:restaurants,id',
        ]);

        $response =  $this->ResturantsLanguagesRepository->update($id, $data);
        return Utilities::wrap($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ResturantsLanguages  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = ResturantsLanguages::where('id', $id)->firstOrFail();
        $this->ResturantsLanguagesRepository->delete($model);
        return Utilities::wrap(['message' => 'deleted successfully'], 200);
    }
}
