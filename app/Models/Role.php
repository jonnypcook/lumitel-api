<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'role';

    /**
     * The primary key name for the table
     *
     * @var string
     */
    protected $primaryKey = 'role_id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The permissions that belong to the role.
     */
    public function permissions()
    {
        return $this->belongsToMany('App\Models\Permission', 'role_permission', 'role_id', 'permission_id');
    }

    /**
     * The permissions that belong to the role.
     */
    public function roles()
    {
        return $this->belongsToMany('App\Models\Role', 'role_group', 'role_id', 'child_id');
    }
}
