<?php
namespace App\Helpers;

use App\Models\BlockVideos;
use App\Models\Channels;
use Illuminate\Support\Str;
use App\Models\Subcategories;
use App\Models\Views;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class Utilities {

    //static properties 
    public static $test = '';
    
    public static function auth()//check auth
    {
        if(Auth::check()){
           return auth()->user();
        }
        return null;
    }

    public static function wrap($data, $code)//get response with status code
    {
        return response()->json($data, $code);
    }

    public static function wrapStatus($data, int $httpCode)//get response with status code
    {
        return response()->json($data, $httpCode);
    }
    public static function uploadImage($image)//upload images
    {
       return $image->store('');
    }

}
