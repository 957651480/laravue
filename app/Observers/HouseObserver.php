<?php

namespace App\Observers;

use App\Models\House;

class HouseObserver
{
    /**
     * Handle the banner "creating" event.
     *
     * @param House $house
     * @return void
     */
    public function creating(House $house)
    {
        //
        if($user = auth()->user()){
            $house->city_id=$user->city_id;
            $house->author_id=$user->id;
        }
    }
    /**
     * Handle the house "created" event.
     *
     * @param House $house
     * @return void
     */
    public function created(House $house)
    {
        //
    }

    /**
     * Handle the house "updated" event.
     *
     * @param House $house
     * @return void
     */
    public function updated(House $house)
    {
        //
    }

    /**
     * Handle the house "deleted" event.
     *
     * @param House $house
     * @return void
     */
    public function deleted(House $house)
    {
        //
    }

    /**
     * Handle the house "restored" event.
     *
     * @param House $house
     * @return void
     */
    public function restored(House $house)
    {
        //
    }

    /**
     * Handle the house "force deleted" event.
     *
     * @param House $house
     * @return void
     */
    public function forceDeleted(House $house)
    {
        //
    }
}
