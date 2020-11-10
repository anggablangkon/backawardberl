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

Route::post('/methodcekvalidation','HalamanController@cekvalidation');
Route::get('/viewdatasukses/{token}','HalamanController@viewdatasukses');
Route::post('/validationsukses','HalamanController@validationsukses');
Route::post('/createdetailrekening','HalamanController@createdetailrekening');
Route::post('/sendotpkembali','HalamanController@sendotpkembali');



#admin panel
Route::get('/loginsistem','LoginController@login');
Route::get('/logoutsistem','LoginController@logoutsistem');
Route::post('/formloginsistem','LoginController@proseslogin');
Route::get('/dashboard','AdminPanelController@dashboard');
Route::get('/sendnotifmessage','AdminPanelController@sendnotifmessage');
Route::post('/prosessendnotifmessage','AdminPanelController@prosessendnotifmessage');
Route::get('/detaildatainsertprosestransfer','AdminPanelController@detaildatainsertprosestransfer');
Route::post('/prosessendnotiftransfersukses','AdminPanelController@prosessendnotiftransfersukses');
Route::post('/stepsuksesnotiftransfer','AdminPanelController@stepsuksesnotiftransfer');
Route::get('/downloaddata/{datefrom}/{dateto}','AdminPanelController@downloaddata');
Route::get('/downloaddatasuccess/{datefrom}/{dateto}','AdminPanelController@downloaddatasuccess');