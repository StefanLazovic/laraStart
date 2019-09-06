<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Email Verification -> ['verify' => true] (web.php, User.php, .env - mailtrap.io)
Auth::routes(['verify' => true]);

// Email Verification - middleware('verified') - you have to verify your email address first before you see the /home page
Route::get('/home', 'HomeController@index')->middleware('verified');


// if you try to refresh the vue component link without the code below, it will redirect to 404|NotFound page
/* but if we are logout, vue component links and any link that doesn't exist will throw some errors what guest should not see,
so we need ->middleware('auth') to redirect guest to the login page */
// Email Verification - middleware('verified') - you have to verify your email address first before you see the /home, /dashboard, /users, /developer and /profile pages
Route::get('{path}', function() {
  return view('layouts.master');
})->where('path', '[\/\w\.-]*')->middleware('auth')->middleware('verified');
