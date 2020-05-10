<?php


namespace App\Filter;


use App\Models\ParkingFloor;

/**
 * Trait ParkingFloorFilter
 * @package App\Filter
 * @mixin ParkingFloor
 * @this ParkingFloor
 */
Trait ParkingFloorFilter
{

    use CityFilter,AuthorFilter;
    /**
     *
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  mixed                                 $name
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLikeName($query, $name)
    {
        return $query->where($this->qualifyColumn('name'),'like', "%$name%");
    }

}
