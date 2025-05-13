<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\TasksService;

class TasksServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(TasksService::class, function(Application $app){
            return new TasksService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
