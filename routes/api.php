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
        Route::get('category/lang/{id}', 'CategoriesController@getLangCategory');
        Route::post('category/lang', 'CategoriesController@addCategoryLanguage');
        Route::put('category/lang/{id}', 'CategoriesController@updateCategoryLanguage');
        
        //Rules
        Route::apiResource('rule', 'RulesController');

        //ExtraMeals
        Route::apiResource('extra/meal', 'ExtraMealsController');
        
        //ResturantsLanguages 
        Route::apiResource('restaurant/language', 'ResturantsLanguagesController');
        Route::get('show/restaurant/language/{restaurant_id}', 'ResturantsLanguagesController@getByRestaurantId');

        //LangBodys
        Route::apiResource('lang/body', 'LangBodysController');

        //Languages
        Route::apiResource('language', 'LanguagesController');

        //Meals getLangMeal
        Route::apiResource('meal', 'MealsController');
        Route::get('meal/lang/{id}', 'MealsController@getLangMeal');
        Route::post('meal/lang', 'MealsController@addNewMealLanguage');
        Route::put('meal/lang/{id}', 'MealsController@updateMealLanguage');
        Route::get('meal/extra/{id}', 'MealsController@getExtraMeals');

        //Restaurants
        Route::apiResource('restaurant', 'RestaurantsController');
        Route::get('my/restaurant/{uid}', 'RestaurantsController@getByUid');

        //Sliders
        Route::apiResource('slider', 'SlidersController');

        //RestaurantSliders 
        Route::apiResource('resturantSlider', 'RestaurantSlidersController');
        Route::post('add/all/restaurant/slider', 'RestaurantSlidersController@addToAllRestaurant');
        Route::get('all/restaurant/slider/{id}', 'RestaurantSlidersController@getRestaurantBySlidersId');

        //Food Objects
        Route::apiResource('food/objects', 'FoodObjectsController');

        //FoodObjectRestaurants
        Route::apiResource('foodObjectsRestaurant', 'FoodObjectRestaurantsController');
        Route::post('add/all/restaurant/food', 'FoodObjectRestaurantsController@addToAllRestaurant');
        Route::get('all/restaurant/food/{id}', 'FoodObjectRestaurantsController@getRestaurantByFoodsId');

    });
});
Route::group(['prefix' => 'web'], function () {
    
    //Category
    Route::get('category/{uuid}', 'CategoriesController@getListWeb');
    Route::get('category/{id}/{uuid}', 'CategoriesController@getCategory');

    //Meals
    Route::get('meal/{uuid}', 'MealsController@getListWeb');
    Route::get('meal/favorite/{uuid}', 'MealsController@getListWebFavorite');
    Route::get('meal/{id}/{uuid}', 'MealsController@getMeal');
    Route::get('slider/{uuid}', 'SlidersController@getListWeb');

    //restaurant languages
    Route::get('restaurant/support/language/{uid}', 'ResturantsLanguagesController@getByRestaurantUid');

});
