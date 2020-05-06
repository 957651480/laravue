<?php


namespace App\Filter;


use App\Models\ParkingFloor;

/**
 * Trait ParkingFloorFilter
 * @package App\Filter
 * @mixin ParkingFloor
 * @this ParkingFloor
 */
Trait ParkingFloorScope
{

    use CityScope,AuthorScope;
    /**
     *
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  mixed                                 $name
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLikeName($query, $name)
    {
        return $name?$query->where('name','like', "%$name%"):$query;
    }

}
