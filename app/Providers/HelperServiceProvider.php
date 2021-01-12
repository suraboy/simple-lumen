<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class HelperServiceProvider extends ServiceProvider
{
    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        foreach (glob(base_path('app/Helpers/Functionals/*.php')) as $file) {
            require_once($file);
        }
    }
}
