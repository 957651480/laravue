<?php


namespace App\Collection;


use Illuminate\Database\Eloquent\Collection;

class FileCollection extends Collection
{


    public function fileIds()
    {
        return $this->modelKeys();
    }

    public function urls()
    {
        return array_map(function ($model) {
            return $model->url;
        }, $this->items);
    }
}
