<?php

namespace App\Observers;

use App\Models\LotteryPrize;

class LotteryPrizeObserver
{

    /**
     * Handle the banner "creating" event.
     *
     * @param LotteryPrize $lotteryPrize
     * @return void
     */
    public function creating(LotteryPrize $lotteryPrize)
    {
        //
        if($user = auth()->user()){
            $lotteryPrize->city_id=$user->city_id;
            $lotteryPrize->author_id=$user->id;
        }
    }

    /**
     * Handle the banner "created" event.
     *
     * @param LotteryPrize $lotteryPrize
     * @return void
     */
    public function created(LotteryPrize $lotteryPrize)
    {
        //
    }

    /**
     * Handle the banner "updated" event.
     *
     * @param LotteryPrize $lotteryPrize
     * @return void
     */
    public function updated(LotteryPrize $lotteryPrize)
    {
        //
    }

    /**
     * Handle the banner "deleted" event.
     *
     * @param LotteryPrize $lotteryPrize
     * @return void
     */
    public function deleted(LotteryPrize $lotteryPrize)
    {
        //
    }

    /**
     * Handle the banner "restored" event.
     *
     * @param LotteryPrize $lotteryPrize
     * @return void
     */
    public function restored(LotteryPrize $lotteryPrize)
    {
        //
    }

    /**
     * Handle the banner "force deleted" event.
     *
     * @param LotteryPrize $lotteryPrize
     * @return void
     */
    public function forceDeleted(LotteryPrize $lotteryPrize)
    {
        //
    }
}
