<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Region extends EloquentModel
{
    //
    use SoftDeletes;
    //
    protected $guarded = [];
    protected $primaryKey='region_id';


    public function childRegion() {
        return $this->hasMany('App\Models\Region', 'parent_id', 'id');
    }

    public function allChildrenRegion()
    {
        return $this->childRegion()->with('childRegion');
    }
}
