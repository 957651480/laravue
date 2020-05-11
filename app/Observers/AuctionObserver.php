<?php

namespace App\Observers;

use App\Models\Auction;

class AuctionObserver
{

    /**
     * Handle the banner "creating" event.
     *
     * @param Auction $auction
     * @return void
     */
    public function creating(Auction $auction)
    {
        //
        if($user = auth()->user()){
            $auction->city_id=$user->city_id;
            $auction->author_id=$user->id;
        }
    }

    /**
     * Handle the banner "created" event.
     *
     * @param Auction $auction
     * @return void
     */
    public function created(Auction $auction)
    {
        //
    }

    /**
     * Handle the banner "updated" event.
     *
     * @param Auction $auction
     * @return void
     */
    public function updated(Auction $auction)
    {
        //
    }

    /**
     * Handle the banner "deleted" event.
     *
     * @param Auction $auction
     * @return void
     */
    public function deleted(Auction $auction)
    {
        //
    }

    /**
     * Handle the banner "restored" event.
     *
     * @param Auction $auction
     * @return void
     */
    public function restored(Auction $auction)
    {
        //
    }

    /**
     * Handle the banner "force deleted" event.
     *
     * @param Auction $auction
     * @return void
     */
    public function forceDeleted(Auction $auction)
    {
        //
    }
}
