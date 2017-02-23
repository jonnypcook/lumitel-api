<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceType extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'device_type';

    /**
     * The primary key name for the table
     *
     * @var string
     */
    protected $primaryKey = 'device_type_id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get the devices for the device type.
     */
    public function devices()
    {
        return $this->hasMany('App\Models\Device', 'device_type_id', 'device_type_id');
    }
}
