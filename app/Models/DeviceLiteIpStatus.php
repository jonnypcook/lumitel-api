<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceLiteIpStatus extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'device_liteip_status';

    /**
     * The primary key name for the table
     *
     * @var string
     */
    protected $primaryKey = 'device_liteip_status_id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get the devices for the device type.
     */
    public function deviceliteips()
    {
        return $this->hasMany('App\Models\DeviceLiteIp', 'device_liteip_status_id', 'device_liteip_status_id');
    }
}
