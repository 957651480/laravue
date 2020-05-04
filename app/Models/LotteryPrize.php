<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * App\Models\LotteryPrize
 *
 * @property int $lottery_prize 奖项id
 * @property string $name 奖品名称
 * @property int $number 数量
 * @property int $probability 概率
 * @property int $author_id 作者id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Lottery $lottery
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LotteryPrize newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LotteryPrize newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LotteryPrize query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LotteryPrize whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LotteryPrize whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LotteryPrize whereLotteryPrize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LotteryPrize whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LotteryPrize whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LotteryPrize whereProbability($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LotteryPrize whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class LotteryPrize extends EloquentModel
{
    //
    protected $guarded = [];
    protected $primaryKey='lottery_prize_id';
    protected $table='lottery_prize';


    public function lottery()
    {
        return $this->belongsTo(Lottery::class,'lottery_id');
    }


}
