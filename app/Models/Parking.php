<?php

namespace App\Models;

use App\Traits\AuthorTrait;
use App\Traits\CityTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Parking
 *
 * @property int $parking_id 车位id
 * @property string $code 车位编号
 * @property int $city_id 城市id
 * @property int $author_id 作者id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $author
 * @property-read \App\Models\Region $city
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Parking newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Parking newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Parking onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Parking query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Parking whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Parking whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Parking whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Parking whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Parking whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Parking whereParkingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Parking whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Parking withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Parking withoutTrashed()
 * @mixin \Eloquent
 */
class Parking extends EloquentModel
{
    //
    use SoftDeletes,CityTrait,AuthorTrait;
    protected $guarded = [];
    protected $table='parking';
    protected $primaryKey='parking_id';

}
