<?php
/**
 * File Role.php
 *
 * @author Tuan Duong <bacduong@gmail.com>
 * @package Laravue
 * @version
 */
namespace App\Models;

use App\Exceptions\ApiException;
use App\Laravue\Acl;
use Spatie\Permission\Models\Permission;


class Role extends \Spatie\Permission\Models\Role
{
    public $guard_name = 'api';

    /**
     * Check whether current role is admin
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->name === Acl::ROLE_ADMIN;
    }

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
}
