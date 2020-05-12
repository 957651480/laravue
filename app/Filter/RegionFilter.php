<?php


namespace App\Filter;


trait RegionFilter
{


    /**
     *
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  mixed                                 $level
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLevel($query, $level)
    {
        return $query->where($this->qualifyColumn('level'),$level);
    }

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
