<?php

namespace App\Http\Controllers;

use App\Models\Rules;
use App\Helpers\Utilities;
use Illuminate\Http\Request;
use App\Models\Permissions_Rules;
use App\Repository\RulesRepository;

class RulesController extends Controller
{
       
    private $RulesRepository;
    private $id;
    public function __construct()
    {
        $this->RulesRepository = new RulesRepository(new Rules());
        $this->middleware('role:Admin', ['only' => ['store', 'index']]);
    }

    
    //All Rules data
    public function index(Request $request)
    {
       $request->validate([
           'skip' => 'Integer',
           'take' => 'required|Integer'
       ]);
       
       $relations = [];
       $filter = ['name'];
       $take = $request->take;
       $skip = $request->skip;       
       return $this->RulesRepository->getList($skip, $take, $relations, $filter);
    }
     
    //Get Single Rules
    public function show($id)
    {
      return $this->RulesRepository->getById($id);
    }

    //Add Rules
    public function store(Request $request)
    {
        // Validations
        $Rules = $request->validate([
            'name' => 'required|string',
        ]);
        return $this->RulesRepository->create($Rules);
    }

    //Update Rules
    public function update(Request $request, $id)
    {
        // $Rules = $request->validate([
        //     'name' => 'nullable|string',
        // ]);
        // $response = $this->RulesRepository->update($id, $Rules);
        // return Utilities::wrap(['message' => 'updated Rules successfully'],$response['code']);
    }
   

    //Delete Rules
    public function destroy($id)
    {
        // $RulesModel = Rules::where('id', $id)->firstOrFail();
        // $response = $this->RulesRepository->softDelete($RulesModel);
        // return Utilities::wrap(['message' => $response['message']], $response['code']);
    }


    //Add Rules
    // public function addPermissions_Rules(Request $request)
    // {
    //     //Validations
    //     $Rules = $request->validate([
    //         'Rules_id' => 'required|string',
    //         'permissions_id' => 'required|string',
    //     ]);

    //     //Processing
    //     $response = Permissions_Rules::create($Rules);

    //     //Response
    //     return Utilities::wrap(['message' => 'Add permissions Rules successfully'],$response['code']);
    // }

}
