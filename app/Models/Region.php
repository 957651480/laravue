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


    public function fetchAll()
    {
        $cache_key = $this->getCacheKey();
        if($data = \Cache::get($cache_key)){
            return $data;
        }
        if($data = $this->all(['region_id','name','parent_id','level'])->toArray()){
            \Cache::forever($cache_key,$data);
        }
        return $data;
    }

    //获取某分类的直接子分类
    public function fetchSons($regions, $parent_id=0){
        $sons=array();
        foreach($regions as $item){
            if($item['parent_id']==$parent_id)
                $sons[]=$item;
        }
        return $sons;
    }

    //获取某个分类的所有子分类
    public function fetchSubs($regions,$parent_id=0,$level=1){
        $subs=array();
        foreach($regions as $item){
            if($item['parent_id']==$parent_id){
                $item['level']=$level;
                $subs[]=$item;
                $subs=array_merge($subs,$this->fetchSubs($regions,$item['categoryId'],$level+1));

            }
        }
        return $subs;
    }

    //获取祖先
    public function getParents($regions, $parent_id){
        $tree=array();
        while($parent_id != 0){
            foreach($regions as $item){
                if($item['region_id']==$parent_id){
                    $tree[]=$item;
                    $parent_id=$item['parent_id'];
                    break;
                }
            }
        }
        return $tree;
    }

    public function getKeyByLevel($level)
    {
        $levels=[
            1=>'province',
            2=>'city',
            3=>'district',
        ];
        return $levels[$level]??'';
    }
    public function getParentsObjectWithKey($regions, $parent_id){
        $tree=array();
        while($parent_id != 0){
            foreach($regions as $item){
                if($item['region_id']==$parent_id){
                    $tree[$this->getKeyByLevel($item['level'])]=$item['region_id'];
                    $parent_id=$item['parent_id'];
                    break;
                }
            }
        }
        return $tree;
    }

    public function childRegion() {
        return $this->hasMany('App\Models\Region', 'parent_id', 'id');
    }

    public function allChildrenRegion()
    {
        return $this->childRegion()->with('childRegion');
    }


}
