<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IotSource extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'iot_source';

    /**
     * The primary key name for the table
     *
     * @var string
     */
    protected $primaryKey = 'iot_source_id';

    /**
     * Get the installation for the iotSource
     */
    public function installation()
    {
        return $this->belongsTo('App\Models\Installation', 'installation_id');
    }

    /**
     * Get all of the owning provider models.
     */
    public function provider()
    {
        return $this->morphTo();
    }
}
