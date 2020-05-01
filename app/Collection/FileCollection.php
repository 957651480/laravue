<?php


namespace App\Collection;


use Illuminate\Database\Eloquent\Collection;

class FileCollection extends Collection
{


    public function fileIds()
    {
        return $this->modelKeys();
    }
}
