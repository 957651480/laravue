<?php

namespace App\Models;

use App\Scopes\CityScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Parking extends EloquentModel
{
    //
    use SoftDeletes;
    //
    protected $guarded = [];
    protected $table='parking';
    protected $primaryKey='parking_id';

    /**
     * 模型的「booted」方法
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope(new CityScope());
    }
}
