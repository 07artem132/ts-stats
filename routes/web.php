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

Route::get( '/', function () {
	return view( 'welcome' );
} );

Auth::routes();

Route::get( '/home', 'HomeController@index' )->name( 'home' );

//todo add route user-list
Route::get( '/users', 'UserController@list' )->name( 'user list' );
Route::get( '/users/{id/delete}', 'UserController@list' )->name( 'delete' );
Route::get( '/users/{id}/admin', 'UserController@list' )->name( 'change admin status' );


Route::get( '/list/statistics/', 'InstanceStatisticsController@list' )->name( 'instanceStatistics' );


Route::get( '/list', 'InstanseController@list' )->name( 'list' );
Route::get( '/add', 'InstanseController@add' );
Route::post( '/add', 'InstanseController@store' )->name( 'add' );
Route::get( '/{id}/delete', 'InstanseController@delete' );
Route::get( '/{id}/edit', 'InstanseController@edit' );
Route::post( '/{id}/edit', 'InstanseController@update' );

Route::get( '/cron', 'CronController@list' )->name( 'Cron' );
Route::get( '/cron/{id}/log', 'CronController@log' )->name( 'CronLog' );
Route::get( '/cron/{id}/CronEdit', 'CronController@edit' )->name( 'CronEdit' );
Route::post( '/cron/{id}/CronEdit', 'CronController@update' )->name( 'CronEditUpdate' );
Route::get( '/cron/{id}/activate', 'CronController@activate' )->name( 'CronActivate' );
Route::get( '/cron/{id}/deactivate', 'CronController@deactivate' )->name( 'CronDeactivate' );


Route::get( '/api/tokens', 'ApiTokensController@list' )->name( 'ApiTokenList' );
Route::get( '/api/tokens/add', 'ApiTokensController@add' )->name( 'AddApiToken' );
