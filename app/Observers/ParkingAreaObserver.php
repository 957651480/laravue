<?php

namespace App\Observers;

use App\Models\ParkingArea;

class ParkingAreaObserver
{

    /**
     * Handle the banner "creating" event.
     *
     * @param ParkingArea $parkingArea
     * @return void
     */
    public function creating(ParkingArea $parkingArea)
    {
        //
        if($user = auth()->user()){
            $parkingArea->city_id=$user->city_id;
            $parkingArea->author_id=$user->id;
        }
    }

    /**
     * Handle the banner "created" event.
     *
     * @param ParkingArea $parkingArea
     * @return void
     */
    public function created(ParkingArea $parkingArea)
    {
        //
    }

    /**
     * Handle the banner "updated" event.
     *
     * @param ParkingArea $parkingArea
     * @return void
     */
    public function updated(ParkingArea $parkingArea)
    {
        //
    }

    /**
     * Handle the banner "deleted" event.
     *
     * @param ParkingArea $parkingArea
     * @return void
     */
    public function deleted(ParkingArea $parkingArea)
    {
        //
    }

    /**
     * Handle the banner "restored" event.
     *
     * @param ParkingArea $parkingArea
     * @return void
     */
    public function restored(ParkingArea $parkingArea)
    {
        //
    }

    /**
     * Handle the banner "force deleted" event.
     *
     * @param ParkingArea $parkingArea
     * @return void
     */
    public function forceDeleted(ParkingArea $parkingArea)
    {
        //
    }
}
