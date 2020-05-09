<?php

namespace App\Models;

use App\Filter\RegionFilter;
use Arr;
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
 * @property string $short_name 短名称
 * @property string $merger_name 合并名称
 * @property string $pinyin 拼音
 * @property string $code 长途区号
 * @property string $zip_code 邮编
 * @property string $first 首字母
 * @property string $lng 经度
 * @property string $lat 纬度
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Region whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Region whereFirst($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Region whereLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Region whereLng($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Region whereMergerName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Region wherePinyin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Region whereShortName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Region whereZipCode($value)
 */
class Region extends EloquentModel
{
    //
    use SoftDeletes,RegionFilter;
    //
    protected $guarded = [];
    protected $table='region';
    protected $primaryKey='region_id';


    public function fetchAll()
    {
        $cache_key = $this->getCacheKey();
        if($data = \Cache::get($cache_key)){
            return $data;
        }
        if($data = $this->all(['region_id','name','parent_id','level','pinyin','first'])->toArray()){
            \Cache::forever($cache_key,$data);
        }
        return $data;
    }

    public function fetchLevelAll($need_level = 0)
    {
        $all_region = $this->fetchAll();
        if($need_level){
            $all_region = Arr::where($all_region, function ($value, $key) use($need_level){
                return $value['level']<=$need_level;
            });
        }
        return $all_region;
    }

    public function fetchAllProvince()
    {
        return $this->filterByLevel(1);
    }
    public function fetchAllCity()
    {
       return $this->filterByLevel(2);
    }

    public function fetchAllDistinct()
    {
        return $this->filterByLevel(3);
    }

    public function filterByLevel($level)
    {
        $all_region = $this->fetchAll();
        $all_region = Arr::where($all_region, function ($value, $key) use($level){
            return $value['level']==$level;
        });
        return array_values($all_region);
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


    public function getTree($regions,$parent_id=0,$level=1)
    {
        $tree = array();
        foreach($regions as $k => $v)
        {
            if($v['parent_id'] == $parent_id)
            {        //父亲找到儿子
                $v['children'] = $this->getTree($regions, $v['region_id'],$level+1);
                $tree[] = $v;
                unset($regions[$k]);
            }
        }
        return $tree;
    }
    //获取某个分类的所有子分类
    public function fetchSubs($regions,$parent_id=0,$level=1,$need_level=0){
        static $subs=array();
        foreach($regions as $key=>$item){
            if($item['parent_id']==$parent_id)
            {
                $subs[]=$item;
                if($need_level&&$level>=$need_level){
                    continue;
                }
                unset($regions[$key]);
                $this->fetchSubs($regions,$item['region_id'],$level);
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
