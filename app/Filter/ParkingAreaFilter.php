<?php


namespace App\Filter;


use App\Models\ParkingArea;

/**
 * Trait ParkingAreaFilter
 * @package App\Filter
 * @mixin ParkingArea
 * @this ParkingArea
 */
Trait ParkingAreaFilter
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
        return $query->where('name','like', "%$name%");
    }

}
