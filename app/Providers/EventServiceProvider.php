<?php

namespace App\Providers;

use App\Events\CronBeforeRun;
use App\Events\CronJobSuccess;
use App\Events\CronLocked;
use App\Events\CronAfterRun;
use App\Events\CronCollectJobs;
use App\Events\CronJobError;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
	/**
	 * The event listener mappings for the application.
	 *
	 * @var array
	 */
	protected $listen = [
		'Illuminate\Auth\Events\Login' => [
			'App\Listeners\LogSuccessfulLogin',
		],
		'Illuminate\Auth\Events\Failed' => [
			'App\Listeners\LogFailedLogin',
		],
		'Illuminate\Auth\Events\Logout' => [
			'App\Listeners\LogSuccessfulLogout',
		],
		'Illuminate\Auth\Events\Authenticated' => [
			'App\Listeners\LogAuthenticated',
		],
		'Api\Events\CronCollectJobs' => [
			'App\Listeners\LogСronCollectJobs',
		],
		'Api\Events\CronBeforeRun' => [
			'App\Listeners\LogCronBeforeRun',
		],
		'Api\Events\CronJobError' => [
			'App\Listeners\LogCronJobError',
		],
		'Api\Events\CronJobSuccess' => [
			'App\Listeners\LogCronJobSuccess',
		],
		'Api\Events\CronAfterRun' => [
			'App\Listeners\LogCronAfterRun',
		],
		'Api\Events\CronLocked' => [
			'App\Listeners\LogCronLockedFile',
		],
		'Illuminate\Cache\Events\CacheHit' => [
			'App\Listeners\LogCacheHit',
		],
		'Illuminate\Cache\Events\CacheMissed' => [
			'App\Listeners\LogCacheMissed',
		],
		'Illuminate\Cache\Events\KeyWritten' => [
			'App\Listeners\LogKeyWritten',
		],
		'Illuminate\Cache\Events\KeyForgotten' => [
			'App\Listeners\LogKeyForgotten',
		],

	];

	/**
	 * Register any events for your application.
	 *
	 * @return void
	 */
	public function boot()
	{
		parent::boot();

		///Переобернули события старого типа в более удобный тип

		Event::listen('cron.collectJobs', function () {
			Event::fire(new CronCollectJobs());
		});
		Event::listen('cron.beforeRun', function ($RunDate) {
			Event::fire(new CronBeforeRun($RunDate));
		});
		Event::listen('cron.jobError', function ($name, $return, $runtime, $rundate) {
			Event::fire(new CronJobError($name, $return, $runtime, $rundate));
		});
		Event::listen('cron.jobSuccess', function ($name, $runtime, $rundate) {
			Event::fire(new CronJobSuccess($name, $runtime, $rundate));
		});
		Event::listen('cron.afterRun', function ($name, $return, $runtime, $rundate) {
			Event::fire(new CronAfterRun($name, $return, $runtime, $rundate));
		});
		Event::listen('cron.locked', function ($lockfile) {
			Event::fire(new CronLocked($lockfile));
		});
	}
}
