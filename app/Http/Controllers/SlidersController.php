<?php

namespace App\Http\Controllers;

use App\Models\Sliders;
use App\Helpers\Utilities;
use Illuminate\Http\Request;
use App\Repository\SlidersRepository;

class SlidersController extends Controller
{
    private $SlidersRepository;
    private $auth;
    public function __construct()
    {
        $this->SlidersRepository = new SlidersRepository(new Sliders());
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
        return $this->SlidersRepository->getList($skip, $take, $relations, $filter);
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
            'meal_id ' => 'required|integer|exists:meals,id'
        ]);

        $response =  $this->SlidersRepository->create($data);
        return Utilities::wrap($response, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Sliders  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->SlidersRepository->getById($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Sliders  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'meal_id ' => 'required|integer|exists:meals,id'
        ]);

        $response =  $this->SlidersRepository->update($id, $data);
        return Utilities::wrap($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Sliders  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = Sliders::where('id', $id)->firstOrFail();
        $this->SlidersRepository->delete($model);
        return Utilities::wrap(['message' => 'deleted successfully'], 200);
    }
}
