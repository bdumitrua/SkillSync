<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class MakeModelMigration extends Command
{
    protected $signature = 'make:model:migration {model}';

    protected $description = 'Creates migration, factory and seeder for model';

    public function handle()
    {
        $model = $this->argument('model');
        $model_snake_case = $this->pascalToSnake($model);
        Artisan::call('make:migration', ['name' => "create_{$model_snake_case}s_table"]);
        Artisan::call('make:factory', ['name' => "{$model}Factory"]);
        Artisan::call('make:seeder', ['name' => "{$model}TableSeeder"]);
    }

    protected function pascalToSnake($string)
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $string));
    }
}
