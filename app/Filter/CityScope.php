<?php


namespace App\Filter;


use App\Models\EloquentModel;

/**
 * Trait CityScope
 * @package App\Filter
 */
trait CityScope
{

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
