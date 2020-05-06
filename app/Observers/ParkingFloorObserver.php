<?php

namespace App\Observers;

use App\Models\ParkingFloor;

class ParkingFloorObserver
{

    /**
     * Handle the banner "creating" event.
     *
     * @param ParkingFloor $parkingFloor
     * @return void
     */
    public function creating(ParkingFloor $parkingFloor)
    {
        //
        if($user = auth()->user()){
            $parkingFloor->city_id=$user->city_id;
            $parkingFloor->author_id=$user->id;
        }
    }

    /**
     * Handle the banner "created" event.
     *
     * @param ParkingFloor $parkingFloor
     * @return void
     */
    public function created(ParkingFloor $parkingFloor)
    {
        //
    }

    /**
     * Handle the banner "updated" event.
     *
     * @param ParkingFloor $parkingFloor
     * @return void
     */
    public function updated(ParkingFloor $parkingFloor)
    {
        //
    }

    /**
     * Handle the banner "deleted" event.
     *
     * @param ParkingFloor $parkingFloor
     * @return void
     */
    public function deleted(ParkingFloor $parkingFloor)
    {
        //
    }

    /**
     * Handle the banner "restored" event.
     *
     * @param ParkingFloor $parkingFloor
     * @return void
     */
    public function restored(ParkingFloor $parkingFloor)
    {
        //
    }

    /**
     * Handle the banner "force deleted" event.
     *
     * @param ParkingFloor $parkingFloor
     * @return void
     */
    public function forceDeleted(ParkingFloor $parkingFloor)
    {
        //
    }
}
