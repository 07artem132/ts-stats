<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider {
	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot() {
		\Horizon::auth( function ( $request ) {
			return \Auth::check();
		} );


	}

	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register() {
		$loader = \Illuminate\Foundation\AliasLoader::getInstance();

		if ( $this->app->environment() != 'production' ) {
			$this->app->register( \Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class );
			$this->app->register( \Barryvdh\Debugbar\ServiceProvider::class );
		}

	}
}
