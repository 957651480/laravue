<?php

namespace App\Observers;

use App\Models\House;

class HouseObserver
{
    /**
     * Handle the banner "creating" event.
     *
     * @param  \App\Models\House  $house
     * @return void
     */
    public function creating(House $house)
    {
        //
        $house->city_id=auth()->user()->city_id;
    }
    /**
     * Handle the house "created" event.
     *
     * @param  \App\Models\House  $house
     * @return void
     */
    public function created(House $house)
    {
        //
    }

    /**
     * Handle the house "updated" event.
     *
     * @param  \App\Models\House  $house
     * @return void
     */
    public function updated(House $house)
    {
        //
    }

    /**
     * Handle the house "deleted" event.
     *
     * @param  \App\Models\House  $house
     * @return void
     */
    public function deleted(House $house)
    {
        //
    }

    /**
     * Handle the house "restored" event.
     *
     * @param  \App\Models\House  $house
     * @return void
     */
    public function restored(House $house)
    {
        //
    }

    /**
     * Handle the house "force deleted" event.
     *
     * @param  \App\Models\House  $house
     * @return void
     */
    public function forceDeleted(House $house)
    {
        //
    }
}
