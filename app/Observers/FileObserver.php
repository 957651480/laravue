<?php

namespace App\Observers;

use App\Models\File;

class FileObserver
{

    /**
     * Handle the file "creating" event.
     *
     * @param File $file
     * @return void
     */
    public function creating(File $file)
    {
        //
        if($user = auth()->user()){
            $file->author_id=$user->id;
        }
    }

    /**
     * Handle the file "created" event.
     *
     * @param File $file
     * @return void
     */
    public function created(File $file)
    {
        //
    }

    /**
     * Handle the file "updated" event.
     *
     * @param File $file
     * @return void
     */
    public function updated(File $file)
    {
        //
    }

    /**
     * Handle the file "deleted" event.
     *
     * @param File $file
     * @return void
     */
    public function deleted(File $file)
    {
        //
    }

    /**
     * Handle the file "restored" event.
     *
     * @param File $file
     * @return void
     */
    public function restored(File $file)
    {
        //
    }

    /**
     * Handle the file "force deleted" event.
     *
     * @param File $file
     * @return void
     */
    public function forceDeleted(File $file)
    {
        //
    }
}
