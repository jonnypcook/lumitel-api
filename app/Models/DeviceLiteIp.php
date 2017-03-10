<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceLiteIp extends Model implements DeviceSanitise
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'device_liteip';

    /**
     * The primary key name for the table
     *
     * @var string
     */
    protected $primaryKey = 'device_liteip_id';

    /**
     * Fields that we allow to be set via array on create and update
     *
     * @var array
     */
    protected $fillable = ['device_liteip_status_id', 'serial', 'vendor_id', 'profile_id', 'emergency_checked', 'emergency'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'emergency_checked'];


    /**
     * Get the polymorphic device
     */
    public function device()
    {
        return $this->morphOne('App\Models\Device', 'provider');
    }

    /**
     * mapping to DeviceLiteIpStatus
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status() {
        return $this->belongsTo('App\Models\DeviceLiteIpStatus', 'device_liteip_status_id');
    }

    /**
     * implementation of DeviceSanitise sanitise
     */
    public function sanitise()
    {
        $sanitisedData = [
            'vendor_id' => $this->vendor_id,
            'profile_id' => $this->profile_id,
            'serial' => $this->serial,
            'emergency_checked' => !empty($this->emergency_checked) ? $this->emergency_checked->format('Y-m-d\TH:i:s') : null,
            'emergency' => $this->emergency
        ];

        if (!empty($this->status)) {
            $sanitisedData['status'] = ['name' => $this->status->name, 'fault' => $this->status->fault];
        }

        return $sanitisedData;
    }
}
