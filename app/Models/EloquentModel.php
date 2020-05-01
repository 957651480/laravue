<?php


namespace App\Models;


use App\Exceptions\ApiException;
use Illuminate\Database\Eloquent\Model;

/**
 * Class EloquentModel
 *
 * @package App\Models
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EloquentModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EloquentModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EloquentModel query()
 * @mixin \Eloquent
 */
class EloquentModel extends Model
{




    /**
     * @param $id
     * @param $with
     * @return \Illuminate\Database\Eloquent\Model|object|static|null
     */
    public function getModelById($id,$with=[])
    {
        $query = $this->newQuery();
        $query->when($with,function ($query)use($with){
            $query->with($with);
        });
        return $query->where($this->primaryKey,$id)->first();
    }

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Model|object|static|null
     * @throws @ApiException
     */
    public function getModelByIdOrFail($id,$with=[])
    {
        $model = $this->getModelById($id,$with);
        throw_unless($model,ApiException::class,'数据不存在');
        return $model;
    }

    public function getCacheKey()
    {
        return get_called_class();
    }
}
