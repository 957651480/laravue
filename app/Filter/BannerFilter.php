<?php


namespace App\Filter;


trait BannerFilter
{

    use CityFilter,AuthorFilter;
    /**
     *
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  mixed                                 $title
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLikeTitle($query, $title)
    {
        return $query->where($this->qualifyColumn('title'),'like', "%$title%");
    }
}
