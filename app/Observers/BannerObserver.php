<?php

namespace App\Observers;

use App\Models\Banner;

class BannerObserver
{

    /**
     * Handle the banner "creating" event.
     *
     * @param  \App\Models\Banner  $banner
     * @return void
     */
    public function creating(Banner $banner)
    {
        //
        $banner->city_id=auth()->user()->city_id;
    }

    /**
     * Handle the banner "created" event.
     *
     * @param  \App\Models\Banner  $banner
     * @return void
     */
    public function created(Banner $banner)
    {
        //
    }

    /**
     * Handle the banner "updated" event.
     *
     * @param  \App\Models\Banner  $banner
     * @return void
     */
    public function updated(Banner $banner)
    {
        //
    }

    /**
     * Handle the banner "deleted" event.
     *
     * @param  \App\Models\Banner  $banner
     * @return void
     */
    public function deleted(Banner $banner)
    {
        //
    }

    /**
     * Handle the banner "restored" event.
     *
     * @param  \App\Models\Banner  $banner
     * @return void
     */
    public function restored(Banner $banner)
    {
        //
    }

    /**
     * Handle the banner "force deleted" event.
     *
     * @param  \App\Models\Banner  $banner
     * @return void
     */
    public function forceDeleted(Banner $banner)
    {
        //
    }
}
