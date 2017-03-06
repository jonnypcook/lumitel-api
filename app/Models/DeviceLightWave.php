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
     * Fields that we allow to be set via array on create and update
     *
     * @var array
     */
    protected $fillable = ['vendor_id', 'wfl_id', 'dm_id', 'active', 'device_number', 'serial', 'rank',
        'energy_rank', 'trigger_rank', 'heating_rank', 'unit_rate', 'device_type_name', 'wfl_code', 'is_heating'];

    /**
     * Get the polymorphic device
     */
    public function device()
    {
        return $this->morphOne('App\Models\Device', 'provider');
    }
}
