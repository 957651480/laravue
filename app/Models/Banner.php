<?php

namespace App\Models;

use App\Traits\CityTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Banner
 *
 * @property int $banner_id 轮播图id
 * @property string $title 标题
 * @property int $image_id 文件id
 * @property int $type_id 10首页banner 20 楼盘banner
 * @property int $sort 排序
 * @property int $show 10显示 20隐藏
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\File $image
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Banner newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Banner newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Banner onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Banner query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Banner whereBannerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Banner whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Banner whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Banner whereImageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Banner whereShow($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Banner whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Banner whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Banner whereTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Banner whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Banner withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Banner withoutTrashed()
 * @mixin \Eloquent
 * @property int $city_id 城市id
 * @property int $author_id 作者id
 * @property-read \App\Models\User $author
 * @property-read \App\Models\Region $city
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Banner whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Banner whereCityId($value)
 */
class Banner extends EloquentModel
{
    //
    use SoftDeletes,CityTrait;
    protected $guarded = [];
    protected $table='banner';
    protected $primaryKey='banner_id';

    public function image()
    {
        return $this->belongsTo(File::class,'image_id');
    }



    public function author()
    {
        return $this->belongsTo(\App\Models\User::class,'author_id');
    }
}
