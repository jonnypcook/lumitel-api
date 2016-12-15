<?php

namespace App;

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
     * Get the polymorphic device
     */
    public function device()
    {
        return $this->morphOne('App\Device', 'provider');
    }
}
