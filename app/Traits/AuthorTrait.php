<?php


namespace App\Traits;


trait AuthorTrait
{

    public function author()
    {
        return $this->belongsTo(\App\Models\User::class,'author_id');
    }
}
