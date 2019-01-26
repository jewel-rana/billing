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

//ApiUserController Routes
Route::apiResources(['/user' => 'API\ApiUserController']);

//ApiCustomerController Routes
Route::apiResources(['/customer' => 'API\CustomerApiController']);
Route::get('/customer/search/suggestion', 'API\CustomerApiController@getSuggestions');

//ApiAreaController Routes
Route::apiResources(['/area' => 'API\AreaApiController']);
Route::get('/area/childs/{id}', 'API\AreaApiController@childs');
Route::get('/area/customer/{id}', 'API\AreaApiController@customers');

//ApiPackageController Routes
Route::apiResources(['/package' => 'API\ApiPackageController']);

//ApiBillingController Routes
Route::apiResources(['/billing' => 'API\BillingApiController']);
Route::get('/billing/due/list', 'API\BillingApiController@dueCustomerList');
Route::get('/billing/payment/list', 'API\BillingApiController@paymentList');

//ApiReportController Routes
Route::apiResources(['/report' => 'API\ReportApiController']);

//ApiRequestController Routes
Route::apiResources(['/requests' => 'API\RequestController']);
Route::get('/requests/action/{id}', 'API\RequestController@takeAction');