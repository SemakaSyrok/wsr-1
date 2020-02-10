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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
Route::options('/{options}', 'ApiController@options');

Route::group(['middleware' => ['api']], function(){

    Route::post('/signup', 'ApiController@signup');
    Route::post('/login', 'ApiController@login');

    Route::group(['middleware' => ['auth:api']], function () {

        Route::post('/logout', 'ApiController@logout');
        Route::post('/photo', 'ApiController@photo');
        Route::get('/photo', 'ApiController@photos');
        Route::delete('/photo/{photo}', 'ApiController@delete');
        Route::post('/user/{user}/share', 'ApiController@share');
        Route::get('/user', 'ApiController@getUser');
//        Route::get('/user/{user}/share', 'ApiController@getUser');

    });

});

