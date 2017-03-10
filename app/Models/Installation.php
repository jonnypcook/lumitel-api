<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Installation extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'installation';

    /**
     * The primary key name for the table
     *
     * @var string
     */
    protected $primaryKey = 'installation_id';

    /**
     * Get the spaces for the installation.
     */
    public function spaces()
    {
        return $this->hasMany('App\Models\Space', 'installation_id', 'installation_id');
    }

    /**
     * Get the sources for the installation.
     */
    public function iotSources()
    {
        return $this->hasMany('App\Models\IotSource', 'installation_id', 'installation_id');
    }

    /**
     * Get the Owner for the Installation
     */
    public function owner()
    {
        return $this->belongsTo('App\Models\Owner', 'owner_id');
    }

    /**
     * Get the address record associated with the installation.
     */
    public function address()
    {
        return $this->hasOne('App\Models\Address');
    }

    /**
     * The installations that belong to the user.
     */
    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'user_installation', 'installation_id', 'user_id');
    }

    /**
     * check to see if userToCheck is part of installation user set
     *
     * @param User $userToCheck
     * @return bool
     */
    public function hasUser(User $userToCheck) {
        if (!empty($this->users)) {
            foreach ($this->users as $user) {
                if ($user->user_id === $userToCheck->user_id) {
                    return true;
                }
            }
        }


        return false;
    }
}
