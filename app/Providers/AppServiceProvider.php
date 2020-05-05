<?php

namespace App\Providers;

use App\Models\Banner;
use App\Models\File;
use App\Models\House;
use App\Models\Information;
use App\Models\Parking;
use App\Models\Region;
use App\Observers\BannerObserver;
use App\Observers\FileObserver;
use App\Observers\HouseObserver;
use App\Observers\InformationObserver;
use App\Observers\ParkingObserver;
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
        File::observe(FileObserver::class);
        Information::observe(InformationObserver::class);
        Parking::observe(ParkingObserver::class);
    }
}
