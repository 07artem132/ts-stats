<?php

namespace Api\Console\Commands;

use Illuminate\Console\Command;

class ApiGenerateDoc extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api:generatedoc';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Генерация документации с использованием apidocjs';

    /**
     * ApiGenerateDoc constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(): void
    {
        $app = base_path('app/');
        $publicDocs = base_path('public/docs/');
        $template = base_path('resources/ApiDocTemplate/');

        $this->info('Генерация документации начата');

        $this->info(shell_exec('apidoc -f .php -i ' . $app . ' -o ' . $publicDocs . ' -t ' . $template));

        $this->info('Генерация документации выполнена');
        return;
    }
}
