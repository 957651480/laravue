<?php


namespace App\Models;


use App\Exceptions\ApiException;
use Illuminate\Database\Eloquent\Model;

/**
 * Class EloquentModel
 * @package App\Models
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
     * @throws ApiException
     * @throws \Throwable
     */
    public function getModelByIdOrFail($id)
    {
        $model = $this->getModelById($id);
        throw_unless($model,ApiException::class,'数据不存在');
        return $model;
    }

}
