<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Floor extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'floor';

    /**
     * The primary key name for the table
     *
     * @var string
     */
    protected $primaryKey = 'floor_id';

    /**
     * Get the spaces for the floor.
     */
    public function spaces()
    {
        return $this->hasMany('App\Space', 'floor_id', 'floor_id');
    }

    /**
     * Get the Installation for the floor
     */
    public function installation()
    {
        return $this->belongsTo('App\Installation', 'installation_id');
    }
}
