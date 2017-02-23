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
}
