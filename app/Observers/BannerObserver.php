<?php

namespace App\Observers;

use App\Models\Banner;

class BannerObserver
{

    /**
     * Handle the banner "creating" event.
     *
     * @param Banner $banner
     * @return void
     */
    public function creating(Banner $banner)
    {
        //
        if($user = auth()->user()){
            $banner->city_id=$user->city_id;
            $banner->author_id=$user->id;
        }
    }

    /**
     * Handle the banner "created" event.
     *
     * @param Banner $banner
     * @return void
     */
    public function created(Banner $banner)
    {
        //
    }

    /**
     * Handle the banner "updated" event.
     *
     * @param Banner $banner
     * @return void
     */
    public function updated(Banner $banner)
    {
        //
    }

    /**
     * Handle the banner "deleted" event.
     *
     * @param Banner $banner
     * @return void
     */
    public function deleted(Banner $banner)
    {
        //
    }

    /**
     * Handle the banner "restored" event.
     *
     * @param Banner $banner
     * @return void
     */
    public function restored(Banner $banner)
    {
        //
    }

    /**
     * Handle the banner "force deleted" event.
     *
     * @param Banner $banner
     * @return void
     */
    public function forceDeleted(Banner $banner)
    {
        //
    }
}
