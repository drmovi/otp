<?php

namespace App\Providers;

use App\repositories\UserPinRepository;
use App\repositories\UserPinEloquentRepository;
use App\Services\FisherYatesPinService;
use App\Services\PinService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(PinService::class, FisherYatesPinService::class);
        $this->app->bind(UserPinRepository::class, UserPinEloquentRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
