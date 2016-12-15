<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'device';

    /**
     * The primary key name for the table
     *
     * @var string
     */
    protected $primaryKey = 'device_id';

    /**
     * Get the deviceType for the device
     */
    public function deviceType()
    {
        return $this->belongsTo('App\DeviceType', 'device_type_id');
    }

    /**
     * Get the Space for the device
     */
    public function space()
    {
        return $this->belongsTo('App\Space', 'space_id');
    }

    /**
     * Get all of the owning provider models.
     */
    public function provider()
    {
        return $this->morphTo();
    }

}
