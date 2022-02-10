<?php
namespace App\Helpers;

use App\Models\BlockVideos;
use App\Models\Channels;
use App\Models\Languages;
use App\Models\Restaurants;
use Illuminate\Support\Str;
use App\Models\Subcategories;
use App\Models\Views;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class Utilities {

    //static properties 
    public static $test = '';
    
    public static function auth()//check auth
    {
        if(Auth::check()){
           return auth()->user()->load('rules');
        }
        return null;
    }

    public static function getRestaurant($uuid)//get restaurant object
    {
        $retaurant = Restaurants::where('uid', $uuid)->first();
        return ($retaurant)? $retaurant:null;
    }

    public static function getHeader()//get header
    {
        return request()->header('lang');
    }

    public static function getLang()//get lanuage id
    {
        $lang = Languages::where('name', self::getHeader())->first();
        return ($lang)? $lang->id:null; 
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
