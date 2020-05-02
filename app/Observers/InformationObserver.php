<?php

namespace App\Observers;

use App\Models\Information;

class InformationObserver
{

    /**
     * Handle the information "creating" event.
     *
     * @param Information $information
     * @return void
     */
    public function creating(Information $information)
    {
        //
        if($user = auth()->user()){
            $information->city_id=$user->city_id;
            $information->author_id=$user->id;
        }
    }

    /**
     * Handle the information "created" event.
     *
     * @param Information $information
     * @return void
     */
    public function created(Information $information)
    {
        //
    }

    /**
     * Handle the information "updated" event.
     *
     * @param Information $information
     * @return void
     */
    public function updated(Information $information)
    {
        //
    }

    /**
     * Handle the information "deleted" event.
     *
     * @param Information $information
     * @return void
     */
    public function deleted(Information $information)
    {
        //
    }

    /**
     * Handle the information "restored" event.
     *
     * @param Information $information
     * @return void
     */
    public function restored(Information $information)
    {
        //
    }

    /**
     * Handle the information "force deleted" event.
     *
     * @param Information $information
     * @return void
     */
    public function forceDeleted(Information $information)
    {
        //
    }
}
