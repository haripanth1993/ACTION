<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ButtonController;
use App\Http\Controllers\ActionButtonApiController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| 
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will  
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/','App\Http\Controllers\CustomerController@index');

// Customers urls:
Route::resource('customers', CustomerController::class);    
Route::post('/customers/widgetspost/','App\Http\Controllers\CustomerController@widgetspost');
Route::get('/customer/widgets/data/{id}','App\Http\Controllers\CustomerController@widgetsdata');
Route::get('/customers/existing-counters/{id}','App\Http\Controllers\CustomerController@widgetslisting');
Route::get('/customers/existing-counters/delete/{id}','App\Http\Controllers\CustomerController@widgetDelete');

// Projects urls:
Route::resource('projects', ProjectController::class);
Route::get('/projects/widget/{id}','App\Http\Controllers\ProjectController@widgets');
Route::post('/projects/widgetspost/','App\Http\Controllers\ProjectController@widgetspost');
Route::get('/projects/widgets/data/{id}','App\Http\Controllers\ProjectController@widgetsdata');
Route::get('/projects/existing-counters/{id}','App\Http\Controllers\ProjectController@widgetslisting');
Route::get('/projects/existing-counters/delete/{id}','App\Http\Controllers\ProjectController@widgetDelete');

// Buttons urls:
Route::resource('buttons', ButtonController::class);
Route::get('/buttons/detail/{id}','App\Http\Controllers\ButtonController@detail');
Route::get('/buttons/widgets/{id}','App\Http\Controllers\ButtonController@widgets');
Route::get('/buttons/widgets/data/{id}','App\Http\Controllers\ButtonController@widgetsdata');
Route::post('/buttons/widgetspost/','App\Http\Controllers\ButtonController@widgetspost');
Route::get('/buttons/existing-counters/{id}','App\Http\Controllers\ButtonController@widgetslisting');
Route::get('/buttons/existing-counters/delete/{id}','App\Http\Controllers\ButtonController@widgetDelete');

// Update Buttons data urls:
Route::get('/update-buttons-summary','App\Http\Controllers\ActionButtonApiController@UpdateButtonSummaryAuto');
Route::get('/update-counters','App\Http\Controllers\ActionButtonApiController@UpdateCounters');

// Clear application cache:
Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    return 'Application cache has been cleared';
});

//Clear route cache:
Route::get('/route-cache', function() {
	Artisan::call('route:cache');
    return 'Routes cache has been cleared';
});

//Clear config cache:
Route::get('/config-cache', function() {
 	Artisan::call('config:cache');
 	return 'Config cache has been cleared';
}); 

// Clear view cache:
Route::get('/view-clear', function() {
    Artisan::call('view:clear');
    return 'View cache has been cleared';
});

Route::get('/live-button', function () {
    return view('buttons.test-live-button');
});
 