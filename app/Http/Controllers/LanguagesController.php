<?php

namespace App\Http\Controllers;

use App\Models\Languages;
use App\Helpers\Utilities;
use Illuminate\Http\Request;
use App\Repository\LanguagesRepository;

class LanguagesController extends Controller
{
    private $LanguagesRepository;
    private $auth;
    public function __construct()
    {
        $this->LanguagesRepository = new LanguagesRepository(new Languages());
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
        return $this->LanguagesRepository->getList($skip, $take, $relations, $filter);
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
            'name' => 'required|string',
        ]);

        $response =  $this->LanguagesRepository->create($data);
        return Utilities::wrap($response, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Languages  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->LanguagesRepository->getById($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Languages  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'string',
        ]);

        $response =  $this->LanguagesRepository->update($id, $data);
        return Utilities::wrap($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Languages  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = Languages::where('id', $id)->firstOrFail();
        $this->LanguagesRepository->softDelete($model);
        return Utilities::wrap(['message' => 'deleted successfully'], 200);
    }
}
