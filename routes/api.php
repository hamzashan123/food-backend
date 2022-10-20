<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

//User
Route::post('login', [App\Http\Controllers\API\UserController::class, 'login']);
Route::post('register', [App\Http\Controllers\API\UserController::class, 'register']);

//Dish
Route::post('getDishes', [App\Http\Controllers\API\DishController::class, 'getDishes']);
Route::post('getDishById', [App\Http\Controllers\API\DishController::class, 'getDishById']);

//Meal
Route::post('getMeals', [App\Http\Controllers\API\MealController::class, 'getMeals']);
Route::post('getMealById', [App\Http\Controllers\API\MealController::class, 'getMealById']);

//Landing
Route::post('getLandingPage', [App\Http\Controllers\API\LandingController::class, 'getLandingPage']);

Route::group(['middleware' => 'auth:api'], function() {

    Route::post('updateUserProfile', [App\Http\Controllers\API\UserController::class, 'updateProfile']);
});