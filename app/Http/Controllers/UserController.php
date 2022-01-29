<?php

namespace App\Http\Controllers;


use JWTAuth;
use App\Models\User;
use App\Helpers\Utilities;
use Illuminate\Http\Request;
use App\Repository\UserRepository;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    private $UserRepository;
    private $auth;
    public function __construct()
    {
        $this->UserRepository = new UserRepository(new User());
        $this->middleware('role:admin,owner', ['only' => ['index', 'update', 'store']]);
        $this->middleware('role:owner', ['only' => ['destroy']]);
        $this->auth = Utilities::auth();
    }

    //get all
    public function index(Request $request) // Admin
    {
        //validations
        $request->validate([
            'skip' => 'Integer',
            'take' => 'required|Integer'
        ]);
        $relations = ['rules'];
        $filter = ['full_name', 'username', 'phone'];
        $take = $request->take;
        $skip = $request->skip;
        return $this->UserRepository->getList($skip, $take, $relations, $filter);
    }

    //get user by id
    public function show($id) // Anyone
    {
        return $this->UserRepository->getById($id);
    }

    //create user
    public function store(Request $request) // Admin
    {
        $data = $request->validate([
            'full_name' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username',
            'phone' => 'required|unique:users,phone',
            'password' => 'required|string|min:6',
            'rule_id' => 'integer|exists:rules,id',
            'image' => 'nullable|file'
        ]);
        
        //Processing
        $data['password'] = Hash::make($data['password']);
        if ($request->hasfile('image')) { //check image
            $data['image'] = Utilities::uploadImage($request->file('image'));            
        }
        //Response
        $response =  $this->UserRepository->create($data);
        return Utilities::wrap($response, 200);

    }

    //update user
    public function update(Request $request, $id) // Admin
    {
        $this->UserRepository->getById($id);
        $data = $request->validate([//Validation
            'full_name' => 'string',
            'username' => 'string|unique:users,username,'.$id,
            'phone' => 'string',
            'rule_id' => 'integer|exists:rules,id',
            'password' => 'string|min:6',
            'image' => 'file'
        ]);

        //Processing
        if (array_key_exists("password", $request->all())) {
            if (!is_null($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }
        }
        if ($request->hasfile('image')) { //check image
            $data['image'] = Utilities::uploadImage($request->file('image'));            
        }
        //Response
        $response = $this->UserRepository->update($id, $data);
        return Utilities::wrap($response, 200);
    }

    // ======================================  auth functions  =========================================//
    public function register(Request $request) // Anyone
    {
        $data = $request->validate([//Validation
            'full_name' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username',
            'phone' => 'required|unique:users,phone',
            'password' => 'required|string|min:6',
            'rule_id' => 'integer|exists:rules,id',
        ]);
       
        //Processing
        $data['password'] = Hash::make($data['password']);
        $data['rule_id'] = 1;
        //Response
        $response = $this->UserRepository->create($data);
        return Utilities::wrap($response, 200);

    }

    //login
    public function login(Request $request) // Anyone
    {
        $valiation = $request->validate([//Validation
            'username' => 'required',
            'password' => 'required|min:6',
        ]);
        
        //Response
        $response = $this->UserRepository->authenticate($valiation);
        // return $response['error'];
        return ($response['code'] == 200)? Utilities::wrap(['token' => $response['token']], 200):
        Utilities::wrap(['error' => $response['error']], $response['code']);
    }

    public function logout() // Anyone
    {
        //Response
        $response = $this->UserRepository->logoutUser();
        return Utilities::wrap($response, 200);
    }

    public function me() // Anyone
    {
        return auth()->user()->load('rules');
    }

    public function updateProfile(Request $request) // Anyone
    {
        $data = $request->validate([
            'full_name' => 'string',
            'username' => 'string|unique:users,username,'.$this->auth->id,
            'phone' => 'string',
            'password' => 'string|min:6',
            'image' => 'file'
        ]);
        
        //Processing
        if (array_key_exists("password", $request->all())) {
            $data['password'] = Hash::make($data['password']);
        }
        if ($request->hasfile('image')) { //check image
            $data['image'] = Utilities::uploadImage($request->file('image'));            
        }
        //Response
        $response = auth()->user()->update($data);
        return Utilities::wrap($response, 200);
    }

    //delete user
    public function destroy($id) // Admin
    {
        //Processing
        $model = $this->UserRepository->getById($id);
        //Response
        $response = $this->UserRepository->softDelete($model);
        return Utilities::wrap($response, 200);
    }
}
