<?php


namespace App\Filter;


use App\Models\Parking;

/**
 * Trait ParkingFilter
 * @package App\Filter
 * @mixin Parking
 * @this Parking
 */
Trait ParkingFilter
{

    use CityFilter,AuthorFilter;
    /**
     *
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  mixed                                 $code
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLikeCode($query, $code)
    {
        return $query->where($this->qualifyColumn('code'),'like', "%$code%");
    }

    /**
     *
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  mixed                                 $size_id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSizeId($query, $size_id)
    {
        return $query->where($this->qualifyColumn('size_id'),'=', $size_id);
    }

    /**
     *
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  mixed                                 $type_id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTypeId($query, $type_id)
    {
        return $query->where($this->qualifyColumn('type_id'),'=', $type_id);
    }
}
