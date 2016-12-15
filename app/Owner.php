<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Owner extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'owner';

    /**
     * The primary key name for the table
     *
     * @var string
     */
    protected $primaryKey = 'owner_id';

    /**
     * Get the installations for the owner.
     */
    public function installations()
    {
        return $this->hasMany('App\Installation', 'owner_id', 'owner_id');
    }
}
