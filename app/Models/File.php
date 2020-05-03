<?php

namespace App\Models;

use App\Collection\FileCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\File
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
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\File newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\File newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\File query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\File whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\File whereExtension($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\File whereFileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\File whereFilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\File whereMimeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\File whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\File whereSourceFilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\File whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\File onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\File whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\File withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\File withoutTrashed()
 */
class File extends Model
{
    use SoftDeletes;
    //
    protected $guarded = [];
    protected $table='file';
    protected $primaryKey='file_id';
    protected $appends=[
        'url'
    ];

    public function author()
    {
        return $this->belongsTo(\App\Laravue\Models\User::class,'author_id');
    }
    public function newCollection(array $models = [])
    {
        return new FileCollection($models);
    }

    public function getUrlAttribute()
    {
        return sprintf("%s/%s",url('uploads'),$this->filename);
    }
}
