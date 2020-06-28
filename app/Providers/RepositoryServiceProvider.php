<?php

namespace App\Providers;

use App\Bus;
use App\BusStop;
use App\BusStopBus;
use Illuminate\Support\ServiceProvider;
use App\Contracts\Repositories\BusRepository;
use App\Contracts\Repositories\BusStopRepository;
use App\Contracts\Repositories\BusStopBusRepository;
use Illuminate\Contracts\Support\DeferrableProvider;
use App\Repositories\Eloquent\BusRepository as EloquentBusRepository;
use App\Repositories\Eloquent\BusStopRepository as EloquentBusStopRepository;
use App\Repositories\Eloquent\BusStopBusRepository as EloquentBusStopBusRepository;

class RepositoryServiceProvider extends ServiceProvider implements DeferrableProvider
{

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(BusStopRepository::class, function () {
            return new EloquentBusStopRepository(new BusStop());
        });

        $this->app->singleton(BusRepository::class, function () {
            return new EloquentBusRepository(new Bus());
        });

        $this->app->singleton(BusStopBusRepository::class, function () {
            return new EloquentBusStopBusRepository(new BusStopBus());
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            BusStopRepository::class,
            BusRepository::class,
            BusStopBusRepository::class,
        ];
    }
}