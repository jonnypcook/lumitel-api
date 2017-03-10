<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceLightWave extends Model implements DeviceSanitise
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

    /**
     * implementation of DeviceSanitise sanitise
     */
    public function sanitise()
    {
        return [
            'vendor_id' => $this->vendor_id,
            'wfl_id' => $this->wfl_id,
            'dm_id' => $this->dm_id,
            'active' => $this->active,
            'device_number' => $this->device_number,
            'serial' => $this->serial,
            'rank' => $this->rank,
            'energy_rank' => $this->energy_rank,
            'trigger_rank' => $this->trigger_rank,
            'heating_rank' => $this->heating_rank,
            'unit_rate' => $this->unit_rate,
            'device_type_name' => $this->device_type_name,
            'wfl_code' => $this->wfl_code,
            'is_heating' => $this->is_heating
        ];
    }
}
