<?php

namespace App;

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
     * Get the polymorphic device
     */
    public function device()
    {
        return $this->morphOne('App\Device', 'provider');
    }

}
