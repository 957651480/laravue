<?php
/**
 * File Permission.php
 *
 * @author Tuan Duong <bacduong@gmail.com>
 * @package Laravue
 * @version 1.0
 */

namespace App\Models;
use App\Laravue\Acl;
use Illuminate\Database\Query\Builder;

/**
 * Class Permission
 *
 * @package App\Laravue\Models
 * @property int $id
 * @property string $name
 * @property string $guard_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Laravue\Models\Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Laravue\Models\Role[] $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Laravue\Models\User[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Laravue\Models\Permission allowed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Laravue\Models\Permission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Laravue\Models\Permission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Spatie\Permission\Models\Permission permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Laravue\Models\Permission query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Spatie\Permission\Models\Permission role($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Laravue\Models\Permission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Laravue\Models\Permission whereGuardName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Laravue\Models\Permission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Laravue\Models\Permission whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Laravue\Models\Permission whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $display_name 显示名
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Laravue\Models\Permission whereDisplayName($value)
 */
class Permission extends \Spatie\Permission\Models\Permission
{
    public $guard_name = 'api';

    /**
     * To exclude permission management from the list
     *
     * @param $query
     * @return Builder
     */
    public function scopeAllowed($query)
    {
        return $query->where('name', '!=', Acl::PERMISSION_PERMISSION_MANAGE);
    }
}
