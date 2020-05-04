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
use App\Models\Acl;
use Spatie\Permission\Models\Permission;


/**
 * App\Models\Role
 *
 * @property int $id
 * @property string $name
 * @property string $display_name 显示名
 * @property string $guard_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Spatie\Permission\Models\Role permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role whereDisplayName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role whereGuardName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
