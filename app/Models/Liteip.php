<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Liteip extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'liteip';

    /**
     * The primary key name for the table
     *
     * @var string
     */
    protected $primaryKey = 'liteip_id';

    /**
     * Get the polymorphic iotSource
     */
    public function iotSource()
    {
        return $this->morphOne('App\Models\IotSource', 'provider');
    }
}
