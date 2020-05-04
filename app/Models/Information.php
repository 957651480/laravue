<?php

namespace App\Models;

use App\Traits\AuthorTrait;
use App\Traits\CityTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Information
 *
 * @property int $information_id 资讯id
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
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Information newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Information newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Information onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Information query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Information whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Information whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Information whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Information whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Information whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Information whereDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Information whereInformationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Information whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Information whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Information withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Information withoutTrashed()
 * @mixin \Eloquent
 */
class Information extends EloquentModel
{
    use SoftDeletes,CityTrait,AuthorTrait;
    //
    protected $guarded = [];
    protected $table='information';
    protected $primaryKey='information_id';



    public function images()
    {
        return $this->belongsToMany(File::class,'information_image','information_id','image_id');
    }


}
