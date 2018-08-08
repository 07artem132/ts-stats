<?php

use Illuminate\Database\Seeder;
use App\Task;

class TasksSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
		$Task              = new Task();
		$Task->priority    = 1000;
		$Task->class_name  = 'App\Task\InstanceStatisticsCollectionsTask';
		$Task->is_enabled  = 1;
		$Task->is_periodic = 1;
		$Task->frequency   = '* * * * *';
		$Task->name        = 'Сбор статистики с teamspeak инстансов';
		$Task->description = '';
		$Task->saveOrFail();

		$Task              = new Task();
		$Task->priority    = 1003;
		$Task->class_name  = 'App\Task\VirtualServersStatisticsCollectionsTask';
		$Task->is_enabled  = 1;
		$Task->is_periodic = 1;
		$Task->frequency   = '* * * * *';
		$Task->name        = 'Сбор статистики с виртуальных серверов teamspeak';
		$Task->description = '';
		$Task->saveOrFail();

		$Task              = new Task();
		$Task->priority    = 1004;
		$Task->class_name  = 'App\Task\VirtualServersClientsStatisticsCollectionsTask';
		$Task->is_enabled  = 1;
		$Task->is_periodic = 1;
		$Task->frequency   = '* * * * *';
		$Task->name        = 'Сбор статистики по клиентам с виртуальных серверов teamspeak';
		$Task->description = '';
		$Task->saveOrFail();

		$Task              = new Task();
		$Task->priority    = 1005;
		$Task->class_name  = 'App\Task\StatisticsInstancesCacheUpdateTask';
		$Task->is_enabled  = 1;
		$Task->is_periodic = 1;
		$Task->frequency   = '* * * * *';
		$Task->name        = 'Обновление кеша для teamspeak 3 инстансов (статистика)';
		$Task->description = '';
		$Task->saveOrFail();
	}
}
