<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Telemetry extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'telemetry';

    /**
     * The primary key name for the table
     *
     * @var string
     */
    protected $primaryKey = 'telemetry_id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The compatibilities that belong to the role.
     */
    public function deviceTypes()
    {
        return $this->belongsToMany('App\Models\DeviceType', 'device_type_telemetry', 'telemetry_id', 'device_type_id');
    }

}
