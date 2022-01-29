<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['prefix' => 'v1'], function () {
    Route::post('auth/register', 'UserController@register');
    Route::post('auth/login', 'UserController@login');

    //auth Request 
    Route::group(['middleware' => ['auth']], function (){

        //Resources
        //User
        Route::apiResource('user', 'UserController');
        Route::put('profile/user/{id}', 'UserController@updateProfile');
        Route::get('profile/user', 'UserController@me');
        Route::get('profile/user/videos', 'UserController@getUserVideos');
        
        //Categories
        Route::apiResource('category', 'CategoriesController');
        
        //Rules
        Route::apiResource('rule', 'RulesController');

        //ExtraMeals
        Route::apiResource('extra/meal', 'ExtraMealsController');

        //LangBodys
        Route::apiResource('lang/body', 'LangBodysController');

        //Languages
        Route::apiResource('language', 'LanguagesController');

        //Meals
        Route::apiResource('meal', 'MealsController');

        //Restaurants
        Route::apiResource('restaurant', 'RestaurantsController');

        //Sliders
        Route::apiResource('slider', 'SlidersController');

    });
});
