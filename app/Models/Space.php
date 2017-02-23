<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Space extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'space';

    /**
     * The primary key name for the table
     *
     * @var string
     */
    protected $primaryKey = 'space_id';

    /**
     * Get the Installation for the device
     */
    public function space()
    {
        return $this->belongsTo('App\Models\Installation', 'installation_id');
    }


    /**
     * Get the devices for the space.
     */
    public function devices()
    {
        return $this->hasMany('App\Models\Device', 'space_id', 'space_id');
    }

    /**
     * self-referential parent
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo('App\Models\Space', 'parent_id');
    }

    /**
     * self-referential children
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany('App\Models\Space', 'parent_id');
    }

}
