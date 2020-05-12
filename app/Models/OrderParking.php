<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class OrderParking extends Model
{
    //
    use SoftDeletes;
    protected $guarded = [];
    protected $table='order_parking';
    protected $primaryKey='order_parking_id';


}
