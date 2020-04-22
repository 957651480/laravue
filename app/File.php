<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\File
 *
 * @property int $file_id
 * @property string $filename 文件名称
 * @property string $source_filename 源文件名
 * @property string $extension 文件名扩展名
 * @property int $size 文件大小
 * @property string $mime_type 文件格式
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $url
 * @method static \Illuminate\Database\Eloquent\Builder|\App\File newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\File newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\File query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\File whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\File whereExtension($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\File whereFileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\File whereFilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\File whereMimeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\File whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\File whereSourceFilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\File whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class File extends Model
{
    use SoftDeletes;
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
