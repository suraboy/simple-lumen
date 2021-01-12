<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Class AppServiceProvider
 * @package App\Providers
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(\Illuminate\Contracts\Routing\ResponseFactory::class, function () {
            return new \Laravel\Lumen\Http\ResponseFactory();
        });

        $this->setupRepository();

    }

    /**
     *
     */
    private function setupRepository()
    {
        $this->app->bind('App\Repositories\Tickets\TicketsRepository', 'App\Repositories\Tickets\TicketsRepositoryEloquent');
    }

}
