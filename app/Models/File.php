<?php

namespace App\Models;

use App\Collection\FileCollection;
use App\Traits\AuthorTrait;
use App\Traits\CityTrait;
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
 * @property int $city_id 城市id
 * @property int $author_id 作者id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $author
 * @property-read \App\Models\Region $city
 * @property-read mixed $url
 * @method static \App\Collection\FileCollection|static[] all($columns = ['*'])
 * @method static \App\Collection\FileCollection|static[] get($columns = ['*'])
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\File newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\File newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\File onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\File query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\File whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\File whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\File whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\File whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\File whereExtension($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\File whereFileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\File whereFilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\File whereMimeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\File whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\File whereSourceFilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\File whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\File withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\File withoutTrashed()
 * @mixin \Eloquent
 */
class File extends EloquentModel
{
    use SoftDeletes,CityTrait,AuthorTrait;
    //
    protected $guarded = [];
    protected $table='file';
    protected $primaryKey='file_id';
    protected $appends=[
        'url'
    ];

    public function newCollection(array $models = [])
    {
        return new FileCollection($models);
    }

    public function getUrlAttribute()
    {
        return sprintf("%s/%s",url('uploads'),$this->filename);
    }
}
