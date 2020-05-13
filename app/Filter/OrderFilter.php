<?php


namespace App\Filter;


use App\Models\Order;

/**
 * Trait OrderFilter
 * @package App\Filter
 * @mixin Order
 * @this Order
 */
Trait OrderFilter
{

    /**
     *
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  mixed                                 $order_no
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrderNo($query, $order_no)
    {
        return $query->where($this->qualifyColumn('order_no'),'=', $order_no);
    }

}
