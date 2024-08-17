<?php
// app/Console/Commands/MakeRepositoryCommand.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class MakeRepositoryCommand extends Command
{
    protected $signature = 'make:repository {repository} {--model=}';

    protected $description = 'Create a new repository class';

    public function handle()
    {
        $repositoryName = $this->argument('repository');
        $modelName = $this->option('model') ?? 'App\Models\\' . $repositoryName;
        $repositoryInterface = 'App\Contracts\\' . $repositoryName . 'Interface';

        // Create the model
        Artisan::call("make:model {$modelName}");

        // Create the repository
        Artisan::call("make:repository Eloquent{$repositoryName}Repository --model={$modelName}");

        // Implement the interface
        Artisan::call("make:interface {$repositoryInterface}");

        $this->info("Repository {$repositoryName} has been created successfully!");
    }
}
