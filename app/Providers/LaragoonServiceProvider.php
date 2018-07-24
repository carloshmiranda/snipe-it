<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Laragoon\LaragoonService;

class LaragoonServiceProvider extends ServiceProvider
{
    public function boot()
    {
    }

    public function register() 
    {
        LaragoonService::overloadLagoonEnvironment(env('LAGOON_ENVIRONMENT_TYPE'));
    }

    public function provides() {
    }
}

