<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Space extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'space';

    /**
     * The primary key name for the table
     *
     * @var string
     */
    protected $primaryKey = 'space_id';

    /**
     * Get the devices for the space.
     */
    public function devices()
    {
        return $this->hasMany('App\Device', 'space_id', 'space_id');
    }

    /**
     * Get the Floor for the space
     */
    public function floor()
    {
        return $this->belongsTo('App\Floor', 'floor_id');
    }
}
