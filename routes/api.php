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

Route::post('login', 'OpenStackController@login');
Route::post('loginWithScope', 'OpenStackController@loginWithScope');
Route::post('getVolumes', 'OpenStackController@getVolumes');
Route::post('getInstances', 'OpenStackController@getInstances');
Route::post('getImage', 'OpenStackController@getImage');
Route::post('createImage', 'OpenStackController@createImage');
Route::post('getFlavors', 'OpenStackController@getFlavors');
Route::post('createFlavor', 'OpenStackController@createFlavor');
Route::post('createVolume', 'OpenStackController@createVolume');
Route::post('deleteVolume', 'OpenStackController@deleteVolume');
Route::post('postImage', 'OpenStackController@postImage');
Route::post('deleteFlavor', 'OpenStackController@deleteFlavor');
Route::post('createInstance', 'OpenStackController@createInstance');
Route::post('getNetworks', 'OpenStackController@getNetworks');
