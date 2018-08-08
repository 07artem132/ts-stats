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

Route::get( '/charts/instance/real-time', function () {
	if ( class_exists( '\Debugbar' ) ) {
		\Debugbar::disable();
	}
	$realTimeCharts = new  App\Charts\StatisticsInstancesRealTimeCharts();

	return response( $realTimeCharts->response() );
} );

Route::get( '/charts/client/version/month', function () {
	if ( class_exists( '\Debugbar' ) ) {
		\Debugbar::disable();
	}
	$usageClientVersion = new  App\Charts\StatisticUsageClientVersionMonthCharts();

	return response( $usageClientVersion->response() );
} );
Route::get( '/charts/client/country/month', function () {
	if ( class_exists( '\Debugbar' ) ) {
		\Debugbar::disable();
	}
	$clientFromTheCountry = new  App\Charts\StatisticClientByCountryMonthCharts();

	return response( $clientFromTheCountry->response() );
} );

Route::get( '/home', 'HomeController@index' )->name( 'home' );

Route::get( '/list', 'InstanseController@list' )->name( 'list' );
Route::get( '/add', 'InstanseController@add' );
Route::post( '/add', 'InstanseController@store' )->name( 'add' );
Route::get( '/{id}/activate', 'InstanseController@activate' );
Route::get( '/{id}/deactivate', 'InstanseController@deactivate' );
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
