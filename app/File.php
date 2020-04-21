<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    //
    protected $guarded = [];
    protected $primaryKey='file_id';
    protected $casts = [
        'id'=>'integer',
        'filename'=>'string',
        'source_filename'=>'string',
        'extension'=>'string',
        'size'=>'integer',
        'mime_type'=>'string',
    ];
    protected $appends=[
        'url'
    ];

    public function getUrlAttribute()
    {
        return sprintf("%s/%s",url('uploads'),$this->filename);
    }
}
