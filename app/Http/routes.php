<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'OpsDayController@showDay');
Route::get('/opday', 'OpsDayController@showDay');
Route::get('/api', 'OpsDayController@showDayAPI');
Route::post('/opday',['as' => 'opday', 'uses' => 'OpsDayController@showDay']);
Route::get('/calendar', 'OpsDayController@calendar');
Route::get('/reset', 'OpsDayController@reset');
Route::get('/start', 'OpsDayController@reset');
