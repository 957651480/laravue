<?php

namespace App\Observers;

use App\Models\Region;

class RegionObserver
{
    /**
     * Handle the region "created" event.
     *
     * @param  \App\Models\Region  $region
     * @return void
     */
    public function created(Region $region)
    {
        //
        $cache_key = $region->getCacheKey();
        \Cache::delete($cache_key);
    }

    /**
     * Handle the region "updated" event.
     *
     * @param  \App\Models\Region  $region
     * @return void
     */
    public function updated(Region $region)
    {
        //
        $cache_key = $region->getCacheKey();
        \Cache::delete($cache_key);
    }

    /**
     * Handle the region "deleted" event.
     *
     * @param  \App\Models\Region  $region
     * @return void
     */
    public function deleted(Region $region)
    {
        //
        $cache_key = $region->getCacheKey();
        \Cache::delete($cache_key);
    }

    /**
     * Handle the region "restored" event.
     *
     * @param  \App\Models\Region  $region
     * @return void
     */
    public function restored(Region $region)
    {
        //
        $cache_key = $region->getCacheKey();
        \Cache::delete($cache_key);
    }

    /**
     * Handle the region "force deleted" event.
     *
     * @param  \App\Models\Region  $region
     * @return void
     */
    public function forceDeleted(Region $region)
    {
        //
        $cache_key = $region->getCacheKey();
        \Cache::delete($cache_key);

    }
}
