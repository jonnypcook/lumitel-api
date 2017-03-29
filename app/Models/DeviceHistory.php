<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceHistory extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'device_history';

    /**
     * The primary key name for the table
     *
     * @var string
     */
    protected $primaryKey = 'device_history_id';


    /**
     * Fields that we allow to be set via array on create and update
     *
     * @var array
     */
    protected $fillable = ['device_id', 'telemetry_id', 'reading_current', 'reading_day', 'utc_created_at', 'local_created_at'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'utc_created_at', 'local_created_at',
    ];

    /**
     * Get the device for the history item
     */
    public function device()
    {
        return $this->belongsTo('App\Models\Device', 'device_id');
    }

    /**
     * Get the device for the history item
     */
    public function telemetry()
    {
        return $this->belongsTo('App\Models\Telemetry', 'telemetry_id');
    }

}
