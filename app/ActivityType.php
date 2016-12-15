<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActivityType extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'activity_type';

    /**
     * The primary key name for the table
     *
     * @var string
     */
    protected $primaryKey = 'activity_type_id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get the devices for the device type.
     */
    public function activities()
    {
        return $this->hasMany('App\Activity', 'activity_type_id', 'activity_type_id');
    }
}
