<?php


namespace App\Filter;


trait AuthorFilter
{

    /**
     *
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  mixed                                 $author_id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAuthorId($query, $author_id)
    {
        return $query->whereAuthorId($author_id);
    }
}
