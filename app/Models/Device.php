<?php

namespace App\Models;

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
     * Fields that we allow to be set via array on create and update
     *
     * @var array
     */
    protected $fillable = ['device_type_id', 'space_id', 'provider_id', 'provider_type', 'label', 'emergency', 'x', 'y'];

    /**
     * Get the deviceType for the device
     */
    public function deviceType()
    {
        return $this->belongsTo('App\Models\DeviceType', 'device_type_id');
    }

    /**
     * Get the Space for the device
     */
    public function space()
    {
        return $this->belongsTo('App\Models\Space', 'space_id');
    }

    /**
     * Get all of the owning provider models.
     */
    public function provider()
    {
        return $this->morphTo();
    }

}
