<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Region
 *
 * @property int $region_id 地区id
 * @property string $name 名称
 * @property int $parent_id 父id
 * @property int $level 层级
 * @property int $sort 排序
 * @property int $show 10显示 20隐藏
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Region[] $childRegion
 * @property-read int|null $child_region_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Region newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Region newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Region onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Region query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Region whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Region whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Region whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Region whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Region whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Region whereRegionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Region whereShow($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Region whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Region whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Region withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Region withoutTrashed()
 * @mixin \Eloquent
 */
class Region extends EloquentModel
{
    //
    use SoftDeletes;
    //
    protected $guarded = [];
    protected $primaryKey='region_id';


    public function childRegion() {
        return $this->hasMany('App\Models\Region', 'parent_id', 'id');
    }

    public function allChildrenRegion()
    {
        return $this->childRegion()->with('childRegion');
    }
}
