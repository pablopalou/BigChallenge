<?php

namespace App\Providers;

use App\Services\CdnService;
use App\Services\DOCdnService;
use Illuminate\Database\Eloquent\Model;
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
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Model::unguard();
        $this->app->bind(CdnService::class, DOCdnService::class);
    }
}
