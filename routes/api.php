<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\CustomerController;
use App\Http\Controllers\API\V1\ProjectController;
use App\Http\Controllers\API\V1\ButtonController;
use App\Http\Controllers\API\V1\ButtonStatusController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!  
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Customer API links
Route::apiResource('customers', CustomerController::class);
Route::post('/customers/update/', 'App\Http\Controllers\API\V1\CustomerController@updated');

// Projects API links
Route::apiResource('projects', ProjectController::class); 
Route::post('/projects/update/', 'App\Http\Controllers\API\V1\ProjectController@updated');

// Buttons API links
Route::apiResource('buttons', ButtonController::class);    
Route::post('/buttons/new/add', 'App\Http\Controllers\API\V1\ButtonController@added');
Route::post('/buttons/update/{id}', 'App\Http\Controllers\API\V1\ButtonController@updated');  
Route::get('/buttons/validation/code', 'App\Http\Controllers\API\V1\ButtonController@checkbutton');  
Route::post('/button/status', 'App\Http\Controllers\API\V1\ButtonStatusController@ButtonStatus');

   