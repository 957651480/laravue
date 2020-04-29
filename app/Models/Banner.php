<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Banner extends Model
{
    //
    use SoftDeletes;
    protected $guarded = [];
    protected $primaryKey='banner_id';

    public function image()
    {
        return $this->belongsTo(File::class,'image_id');
    }
}
