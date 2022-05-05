<?php

namespace App\Providers;

use App\Repositories\MeetingRepository\MeetingRepository;
use App\Repositories\MeetingRepository\MeetingRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class MeetingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(MeetingRepositoryInterface::class , MeetingRepository::class);
    }
}
