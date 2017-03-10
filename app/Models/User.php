<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
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
     * Get the activities for the user.
     */
    public function activities()
    {
        return $this->hasMany('App\Models\Activity', 'user_id', 'user_id');
    }

    /**
     * The permissions that belong to the role.
     */
    public function roles()
    {
        return $this->belongsToMany('App\Models\Role', 'user_role', 'user_id', 'role_id');
    }

    /**
     * The installations that belong to the user.
     */
    public function installations()
    {
        return $this->belongsToMany('App\Models\Installation', 'user_installation', 'user_id', 'installation_id');
    }

    /**
     * @param $roleToCheck
     * @return bool
     */
    public function hasRole($roleToCheck) {
        if (!empty($this->roles)) {
            foreach ($this->roles as $role) {
                if (($role->name === 'administrator') || ($role->name === $roleToCheck)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param $permissionToCheck
     * @return bool
     */
    public function hasPermission($permissionToCheck) {
        if (!empty($this->roles)) {
            foreach ($this->roles as $role) {
                if ($role->name === 'administrator') {
                    return true;
                }

                foreach ($role->permissions as $permission) {
                    if ($permission->name === $permissionToCheck) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

}
