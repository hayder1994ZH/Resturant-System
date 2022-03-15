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
        
        //LangBodys
        Route::apiResource('lang/body', 'LangBodysController');

        //Languages
        Route::apiResource('language', 'LanguagesController');

        //Meals 
        Route::apiResource('meal', 'MealsController');
        Route::get('meal/lang/{id}', 'MealsController@getLangMeal');
        Route::post('meal/lang', 'MealsController@addNewMealLanguage');
        Route::put('meal/lang/{id}', 'MealsController@updateMealLanguage');
        Route::get('meal/extra/{id}', 'MealsController@getExtraMeals');
        Route::get('meal/favorite/{id}', 'MealsController@addFavorite');

        //Sliders
        Route::apiResource('slider', 'SlidersController');

        //Food Objects
        Route::apiResource('food/objects', 'FoodObjectsController');

        //Restaurants
        Route::apiResource('restaurant', 'RestaurantsController');
        Route::get('my/restaurant/{uid}', 'RestaurantsController@getByUid');
        Route::get('my/restaurant', 'RestaurantsController@getMyRestaurant');

        //RestaurantSliders  
        Route::apiResource('resturantSlider', 'RestaurantSlidersController');
        Route::post('add/all/restaurant/slider', 'RestaurantSlidersController@addToAllRestaurant');
        Route::get('all/restaurant/slider/{id}', 'RestaurantSlidersController@getRestaurantBySlidersId');

        //ResturantsLanguages 
        Route::apiResource('restaurant/language', 'ResturantsLanguagesController');
        Route::get('show/restaurant/language/{restaurant_id}', 'ResturantsLanguagesController@getByRestaurantId');

        //Restaurant Phones 
        Route::apiResource('phone/restaurant', 'RestaurantPhonesController');
        Route::get('show/phone/restaurant/{restaurant_id}', 'RestaurantPhonesController@getByRestaurantId');

        //Restaurant Images 
        Route::apiResource('image/restaurant', 'RestaurantImagesController');
        Route::get('show/image/restaurant/{restaurant_id}', 'RestaurantImagesController@getByRestaurantId');

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

    //Food Object
    Route::get('food/order/restaurant/{uuid}', 'FoodObjectsController@getListWeb');

    //restaurant languages
    Route::get('restaurant/support/language/{uid}', 'ResturantsLanguagesController@getByRestaurantUid');

});
