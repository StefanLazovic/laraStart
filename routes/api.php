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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
//
// });


Route::apiResource('user', 'API\UserController');

// search form (app.js, master.blade.php, Users.vue, UserController@search, api.php)
Route::get('findUser', 'API\UserController@search');

// display profile settings data (UserController@profile, Profile.vue, api.php)
Route::get('profile', 'API\UserController@displayProfileInfo');

// update profile settings (UserController@updateProfileInfo, Profile.vue, api.php)
Route::put('profile', 'API\UserController@updateProfileInfo');
