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

// Route::get('/', function () {
//     // return view('welcome');
//     redirect
// });
Route::get('/', function () {
      $type=Auth()->user()->type;
       if($type=='admin')
       {
        return view('admin.admin');
       }
       elseif($type=='customer')
       {
        return view('admin.vendor');
       }
       else
       {
         Auth::logout();
         return redirect('/login');
       }
})->middleware('auth');

Route::get('/terms', function () {
    return view('admin.terms');
});

Route::get('/refund', function () {
    return view('admin.refund');
});

Route::get('/privacy', function () {
    return view('admin.privacy');
});


Route::get('/mail/{orderId}', 'API\Common\DeliveryPersonOrderController@mail');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


// For preving erros and loading correct vue component according to vue routes
Route::get('{path}','HomeController@index')->where( 'path', '([A-z\d\-\/_.]+)' );
