<?php


namespace App\Filter;


use App\Models\House;

/**
 * Trait HouseFilter
 * @package App\Filter
 * @mixin House
 * @this House
 */
Trait HouseScope
{

    /**
     *
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  mixed                                 $name
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLikeTitle($query, $name)
    {
        return $name?$query->where('name','like', "%$name%"):$query;
    }

    /**
     *
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  mixed                                 $city_id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCityId($query, $city_id)
    {
        return $city_id?$query->whereCityId($city_id):$query;
    }
}
