<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Command extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'command';

    /**
     * The primary key name for the table
     *
     * @var string
     */
    protected $primaryKey = 'command_id';

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
        return $this->belongsToMany('App\Models\DeviceType', 'device_type_command', 'command_id', 'device_type_id');
    }

}
