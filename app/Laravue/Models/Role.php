<?php
/**
 * File Role.php
 *
 * @author Tuan Duong <bacduong@gmail.com>
 * @package Laravue
 * @version
 */
namespace App\Laravue\Models;

use App\Laravue\Acl;
use Spatie\Permission\Models\Permission;

/**
 * Class Role
 *
 * @property Permission[] $permissions
 * @property string $name
 * @package App\Laravue\Models
 * @property int $id
 * @property string $guard_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Laravue\Models\User[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Laravue\Models\Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Laravue\Models\Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Spatie\Permission\Models\Role permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Laravue\Models\Role query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Laravue\Models\Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Laravue\Models\Role whereGuardName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Laravue\Models\Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Laravue\Models\Role whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Laravue\Models\Role whereUpdatedAt($value)
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
}
