<?php

namespace App\Laravue\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;

/**
 * Class User
 *
 * @property string $name
 * @property string $email
 * @property string $password
 * @property Role[] $roles
 * @method static User create(array $user)
 * @package App
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Laravue\Models\Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Laravue\Models\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Laravue\Models\User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Laravue\Models\User permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Laravue\Models\User query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Laravue\Models\User role($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Laravue\Models\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Laravue\Models\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Laravue\Models\User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Laravue\Models\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Laravue\Models\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Laravue\Models\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Laravue\Models\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Laravue\Models\User whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $display_name 显示名
 * @property string $nickName 微信nickname
 * @property string $open_id 微信openId
 * @property string $avatarUrl 微信头像
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Laravue\Models\User whereAvatarUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Laravue\Models\User whereDisplayName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Laravue\Models\User whereNickName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Laravue\Models\User whereOpenId($value)
 */
class User extends Authenticatable
{
    use Notifiable, HasRoles, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','password','city_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Set permissions guard to API by default
     * @var string
     */
    protected $guard_name = 'api';

    /**
     * @inheritdoc
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * @inheritdoc
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Check if user has a permission
     * @param String
     * @return bool
     */
    public function hasPermission($permission): bool
    {
        foreach ($this->roles as $role) {
            if (in_array($permission, $role->permissions->toArray())) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        foreach ($this->roles as $role) {
            if ($role->isAdmin()) {
                return true;
            }
        }

        return false;
    }
}
