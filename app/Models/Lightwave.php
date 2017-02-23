<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lightwave extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'lightwave';

    /**
     * The primary key name for the table
     *
     * @var string
     */
    protected $primaryKey = 'lightwave_id';

    /**
     * Get the polymorphic iotSource
     */
    public function iotSource()
    {
        return $this->morphOne('App\Models\IotSource', 'provider');
    }

}
