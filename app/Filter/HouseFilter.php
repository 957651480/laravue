<?php


namespace App\Filter;


use App\Models\House;

/**
 * Trait HouseFilter
 * @package App\Filter
 * @mixin House
 * @this House
 */
Trait HouseFilter
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
