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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('login', 'API\UserController@login');
Route::post('register', 'API\UserController@register');

Route::post('freeLancerLogin', 'API\FreelancerController@login');
Route::post('freelancerRegister', 'API\FreelancerController@register');

// Service Controller URLs
Route::post('addService', 'API\ServiceController@addService');
Route::get('getServices', 'API\ServiceController@viewAllService');



Route::post('addRegion', 'API\RegionsController@addRegion');
Route::get('getRegions', 'API\RegionsController@viewAllRegions');

Route::group(['middleware' => 'auth:api'], function(){

    // User Controller
    Route::get('details', 'API\UserController@viewCurrentUser');
    Route::get('logoutAPI', 'API\UserController@logoutAPI');
    Route::post('edit/{id}', 'API\UserController@edit');
    Route::post('uploadPhoto/{id}', 'API\UserController@uploadPhoto');
        
    // Client Controller
    Route::get('getClient/{email}', 'API\ClientController@getClientByEmail');
    Route::get('getFreelancersByGategory/{category}', 'API\ClientController@showWorkersByGategory');
    Route::get('getFreelancersByGPS/{latitude}/{longitutde}/{category}', 'API\ClientController@showWorkersByGPS');

    // Freelancer Controller
    Route::get('getFreelancer/{email}', 'API\FreelancerController@getFreelancerByEmail');
    
    
    

    // Comment Controller
    Route::get('getComments/{email}', 'API\CommentController@showAllClientComments');
    Route::get('getFrelancersComments/{email}', 'API\CommentController@showAllFreelancerComments');
    Route::post('addComment', 'API\CommentController@review');
    
    // Request Controller
    Route::post('requestWorker', 'API\RequestsController@requestWorker');
    Route::get('getFinishedRequests/{id}', 'API\RequestsController@showFinishedRequests');
    Route::get('getUnfinishedRequests/{id}', 'API\RequestsController@showUnfinishedRequests');
    Route::get('getCancelledRequests/{id}', 'API\RequestsController@showCancelledRequests');
    Route::get('getWaitingRequests/{id}', 'API\RequestsController@showWaitingRequests');
    Route::get('getWaitingRequestsFreelancer/{id}', 'API\RequestsController@showWaitingRequestsFreelancer');
    Route::get('showAcceptingRequestsFreelancer/{id}', 'API\RequestsController@showAcceptingRequestsFreelancer');
    Route::get('showFinishedRequestsFreelancer/{id}', 'API\RequestsController@showFinishedRequestsFreelancer');
    Route::get('showFinishedRequestsNeedsRate/{id}', 'API\RequestsController@showFinishedRequestsNeedsRate');
    Route::get('updateRate/{id}/{rate}', 'API\RequestsController@updateRate');
    Route::get('updateRateFreelancer/{id}/{rate}', 'API\RequestsController@updateRateFreelancer');
    Route::get('cancelRequest/{id}', 'API\RequestsController@cancelRequest');
    Route::post('updateRequest/{id}', 'API\RequestsController@updateStatus');
    
    
});

