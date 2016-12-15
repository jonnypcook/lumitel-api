<?php

namespace App;

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
     * Get the installation that has the address.
     */
    public function installation()
    {
        return $this->belongsTo('App\Installation', 'address_id');
    }
}
