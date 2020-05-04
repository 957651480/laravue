<?php

namespace App\Models;

use App\Traits\AuthorTrait;
use App\Traits\CityTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\House
 *
 * @property int $house_id 楼盘id
 * @property string $name 楼盘名称
 * @property string $desc 楼盘简介
 * @property string $content 详情
 * @property string $household 住户数量
 * @property string $house_region 省市区选择的数据列表
 * @property int $region_id 区域关联id
 * @property int $city_id 城市id
 * @property int $author_id 作者id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $author
 * @property-read \App\Models\Region $city
 * @property-read \App\Collection\FileCollection|\App\Models\File[] $images
 * @property-read int|null $images_count
 * @property-read \App\Models\Region $region
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\House newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\House newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\House onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\House query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\House whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\House whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\House whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\House whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\House whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\House whereDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\House whereHouseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\House whereHouseRegion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\House whereHousehold($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\House whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\House whereRegionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\House whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\House withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\House withoutTrashed()
 * @mixin \Eloquent
 */
class House extends EloquentModel
{
    use SoftDeletes,CityTrait,AuthorTrait;
    //
    protected $guarded = [];
    protected $table='house';
    protected $primaryKey='house_id';




    public function images()
    {
        return $this->belongsToMany(File::class,'house_image','house_id','image_id');
    }

    public function region()
    {
        return $this->belongsTo(Region::class,'region_id');
    }



    public function setHouseRegionAttribute($value)
    {
        $this->attributes['house_region'] = json_encode($value);
    }

    public function getHouseRegionAttribute($value)
    {
        return json_decode($value,true);
    }

}
