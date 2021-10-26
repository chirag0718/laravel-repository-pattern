<?php

namespace App\Providers;

use App\Repository\BaseRepositoryInterface;
use App\Repository\TodoRepository;
use App\Repository\TodoRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class TodoServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
