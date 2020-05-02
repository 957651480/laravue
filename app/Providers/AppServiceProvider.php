<?php

namespace App\Providers;

use App\Models\Banner;
use App\Models\House;
use App\Models\Region;
use App\Observers\BannerObserver;
use App\Observers\HouseObserver;
use App\Observers\RegionObserver;
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
        //
        Banner::observe(BannerObserver::class);
        House::observe(HouseObserver::class);
        Region::observe(RegionObserver::class);
    }
}
