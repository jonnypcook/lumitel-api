<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceLightWave extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'device_lightwave';

    /**
     * The primary key name for the table
     *
     * @var string
     */
    protected $primaryKey = 'device_lightwave_id';

    /**
     * Get the polymorphic device
     */
    public function device()
    {
        return $this->morphOne('App\Models\Device', 'provider');
    }
}
