<?php


namespace App\Filter;


trait AuthorScope
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
        return $author_id?$query->whereAuthorId($author_id):$query;
    }
}
