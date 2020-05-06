<?php


namespace App\Filter;


use App\Models\House;
use App\Traits\AuthorTrait;
use App\Traits\CityTrait;

/**
 * Trait HouseFilter
 * @package App\Filter
 * @mixin House
 * @this House
 */
Trait HouseScope
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
