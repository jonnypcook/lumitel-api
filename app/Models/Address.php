<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'address';

    /**
     * The primary key name for the table
     *
     * @var string
     */
    protected $primaryKey = 'address_id';


    /**
     * Get the installations for the owner.
     */
    public function installations()
    {
        return $this->hasMany('App\Models\Installation', 'address_id', 'address_id');
    }
}
