<?php

namespace App\Observers;

use App\Models\Parking;

class ParkingObserver
{

    /**
     * Handle the banner "creating" event.
     *
     * @param Parking $parking
     * @return void
     */
    public function creating(Parking $parking)
    {
        //
        if($user = auth()->user()){
            $parking->city_id=$user->city_id;
            $parking->author_id=$user->id;
        }
    }

    /**
     * Handle the banner "created" event.
     *
     * @param Parking $parking
     * @return void
     */
    public function created(Parking $parking)
    {
        //
    }

    /**
     * Handle the banner "updated" event.
     *
     * @param Parking $parking
     * @return void
     */
    public function updated(Parking $parking)
    {
        //
    }

    /**
     * Handle the banner "deleted" event.
     *
     * @param Parking $parking
     * @return void
     */
    public function deleted(Parking $parking)
    {
        //
    }

    /**
     * Handle the banner "restored" event.
     *
     * @param Parking $parking
     * @return void
     */
    public function restored(Parking $parking)
    {
        //
    }

    /**
     * Handle the banner "force deleted" event.
     *
     * @param Parking $parking
     * @return void
     */
    public function forceDeleted(Parking $parking)
    {
        //
    }
}
