<?php

namespace App\Providers;

use App\Task;
use \Liebig\Cron\Cron;
use Liebig\Cron\RunCommand;
use Liebig\Cron\ListCommand;
use Liebig\Cron\KeygenCommand;
use Illuminate\Support\ServiceProvider;

class CronServiceProvider extends ServiceProvider {
	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot() {
		$configPath = __DIR__ . '/../../vendor/liebig/cron/src/config/config.php';
		$this->publishes( [ $configPath => config_path( 'liebigCron.php' ) ], 'config' );

		\Event::listen( 'cron.collectJobs', function () {
			$Tasks = Task::Active()->orderBy( 'priority' )->get();

			foreach ( $Tasks as $Task ) {
				Cron::add( $Task->name, $Task->frequency, [
					$c = new $Task->class_name,
					'CronCallback'
				], (bool) $Task->is_enabled );
			}
		} );
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register() {
		$this->app->singleton( 'cron', function () {
			return new Cron;
		} );

		$this->app->booting( function () {
			$loader = \Illuminate\Foundation\AliasLoader::getInstance();
			$loader->alias( 'Cron', 'Liebig\Cron\Facades\Cron' );
		} );

		$this->app->singleton( 'cron::command.run', function () {
			return new RunCommand;
		} );
		$this->commands( 'cron::command.run' );

		$this->app->singleton( 'cron::command.list', function () {
			return new ListCommand;
		} );
		$this->commands( 'cron::command.list' );

		$this->app->singleton( 'cron::command.keygen', function () {
			return new KeygenCommand;
		} );
		$this->commands( 'cron::command.keygen' );
	}
}
