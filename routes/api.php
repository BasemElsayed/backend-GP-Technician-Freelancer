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


Route::get('getServices', 'API\ServiceController@viewAllService');
Route::get('getRegions', 'API\RegionsController@viewAllRegions');


Route::group(['middleware' => 'auth:api'], function(){

    // User Controller
    Route::get('details', 'API\UserController@viewCurrentUser');
    Route::get('logoutAPI', 'API\UserController@logoutAPI');
    Route::post('edit/{id}', 'API\UserController@edit');
    Route::post('uploadPhoto/{id}', 'API\UserController@uploadPhoto');
        
    // Client Controller
    Route::get('getClient/{email}', 'API\ClientController@getClientByEmail');
    Route::get('getClientByID/{id}', 'API\ClientController@getClientByID');
    Route::get('getFreelancersByGategory/{category}', 'API\ClientController@showWorkersByGategory');
    Route::get('getFreelancersByGPS/{latitude}/{longitutde}/{category}', 'API\ClientController@showWorkersByGPS');
    
    // Freelancer Controller
    Route::get('getFreelancer/{email}', 'API\FreelancerController@getFreelancerByEmail');
    Route::get('getFreelancerByID/{id}', 'API\FreelancerController@getFreelancerByID');
    
    
    // Portfolio Controller
    Route::post('updatePortfolio', 'API\PortfolioController@updatePortfolio');
    Route::get('viewAllPortfolio/{freelancer_id}', 'API\PortfolioController@viewAllPortfolio');
    Route::get('deletePortfolio/{id}', 'API\PortfolioController@delete');
    
    
    // Comment Controller
    Route::get('getComments/{email}', 'API\CommentController@showAllClientComments');
    Route::get('getClientCommentsByID/{id}', 'API\CommentController@getClientCommentsByID');
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

    // Service Controller
    Route::get('getServiceByID/{id}', 'API\ServiceController@viewService');
    Route::post('editService/{id}', 'API\ServiceController@editService');
    Route::post('updateServiceIcon/{id}', 'API\ServiceController@updateServiceIcon');
    Route::post('addService', 'API\ServiceController@addService');
    Route::get('deleteService/{id}', 'API\ServiceController@deleteService');
    

    // Region Controller
    Route::post('addRegion', 'API\RegionsController@addRegion');
    Route::get('deleteRegion/{id}', 'API\RegionsController@deleteRegion');
    
    
    // Admin Controller
    Route::get('viewFreelancers', 'API\AdminController@showAllFreelancersUsers');
    Route::get('viewStatistics', 'API\AdminController@viewStatistics');
    Route::get('blockFreelancer/{id}', 'API\AdminController@blockAccount');
    Route::get('approveFreelancer/{id}', 'API\AdminController@approveAccount');
    

     


});





