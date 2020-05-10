<?php

namespace App\Observers;

use App\Models\Lottery;

class LotteryObserver
{

    /**
     * Handle the banner "creating" event.
     *
     * @param Lottery $lottery
     * @return void
     */
    public function creating(Lottery $lottery)
    {
        //
        if($user = auth()->user()){
            $lottery->city_id=$user->city_id;
            $lottery->author_id=$user->id;
        }
    }

    /**
     * Handle the banner "created" event.
     *
     * @param Lottery $lottery
     * @return void
     */
    public function created(Lottery $lottery)
    {
        //
    }

    /**
     * Handle the banner "updated" event.
     *
     * @param Lottery $lottery
     * @return void
     */
    public function updated(Lottery $lottery)
    {
        //
    }

    /**
     * Handle the banner "deleted" event.
     *
     * @param Lottery $lottery
     * @return void
     */
    public function deleted(Lottery $lottery)
    {
        //
    }

    /**
     * Handle the banner "restored" event.
     *
     * @param Lottery $lottery
     * @return void
     */
    public function restored(Lottery $lottery)
    {
        //
    }

    /**
     * Handle the banner "force deleted" event.
     *
     * @param Lottery $lottery
     * @return void
     */
    public function forceDeleted(Lottery $lottery)
    {
        //
    }
}
