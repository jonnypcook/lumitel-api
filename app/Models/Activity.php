<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'activity';

    /**
     * The primary key name for the table
     *
     * @var string
     */
    protected $primaryKey = 'activity_id';

    /**
     * Get the activityType for the device
     */
    public function activityType()
    {
        return $this->belongsTo('App\Models\ActivityType', 'activity_type_id');
    }

    /**
     * Get the user for the device
     */
    public function user()
    {
        return $this->belongsTo('App\Models\Users', 'user_id');
    }

}
