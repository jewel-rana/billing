<?php

use Illuminate\Http\Request;

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

Route::group(['prefix' => '/v1', 'middleware' => ['auth:api']], function(){
	//Rewource Route for version 1
});

Route::apiResources(['/user' => 'API\ApiUserController']);
Route::apiResources(['/customer' => 'API\CustomerApiController']);
Route::apiResources(['/area' => 'API\AreaApiController']);
Route::get('/area/childs/{id}', 'API\AreaApiController@childs');
Route::apiResources(['/package' => 'API\ApiPackageController']);
Route::apiResources(['/billing' => 'API\BillingApiController']);
Route::apiResources(['/report' => 'API\ReportApiController']);