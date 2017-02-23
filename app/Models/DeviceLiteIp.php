<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceLiteIp extends Model
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
     * Get the polymorphic device
     */
    public function device()
    {
        return $this->morphOne('App\Models\Device', 'provider');
    }
}
