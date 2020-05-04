<?php

namespace App\Models;

use App\Traits\AuthorTrait;
use App\Traits\CityTrait;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * App\Models\Lottery
 *
 * @property int $lottery_id 转盘id
 * @property string $title 资讯标题
 * @property string $desc 资讯简介
 * @property string $content 详情
 * @property int $city_id 城市id
 * @property int $author_id 作者id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $author
 * @property-read \App\Models\Region $city
 * @property-read \App\Collection\FileCollection|\App\Models\File[] $images
 * @property-read int|null $images_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\LotteryPrize[] $prizes
 * @property-read int|null $prizes_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Lottery newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Lottery newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Lottery onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Lottery query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Lottery whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Lottery whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Lottery whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Lottery whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Lottery whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Lottery whereDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Lottery whereLotteryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Lottery whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Lottery whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Lottery withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Lottery withoutTrashed()
 * @mixin \Eloquent
 */
class Lottery extends EloquentModel
{
    //
    use SoftDeletes,CityTrait,AuthorTrait;
    protected $guarded = [];
    protected $table='lottery';
    protected $primaryKey='lottery_id';


    public function images()
    {
        return $this->belongsToMany(File::class,'lottery_image','lottery_id','image_id');
    }


    public function prizes()
    {
        return $this->hasMany(LotteryPrize::class,'lottery_id');
    }

}
