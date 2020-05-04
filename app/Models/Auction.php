<?php

namespace App\Models;

use App\Scopes\CityScope;
use App\Traits\AuthorTrait;
use App\Traits\CityTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Auction
 *
 * @property int $auction_id 竞拍id
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
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Auction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Auction newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Auction onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Auction query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Auction whereAuctionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Auction whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Auction whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Auction whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Auction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Auction whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Auction whereDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Auction whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Auction whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Auction withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Auction withoutTrashed()
 * @mixin \Eloquent
 */
class Auction extends EloquentModel
{
    use SoftDeletes,CityTrait,AuthorTrait;
    //
    protected $guarded = [];
    protected $table='auction';
    protected $primaryKey='auction_id';


    /**
     * 模型的「booted」方法
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope(new CityScope());
    }


    public function images()
    {
        return $this->belongsToMany(File::class,'auction_image','auction_id','image_id');
    }


}
