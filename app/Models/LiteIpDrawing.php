<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LiteIpDrawing extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'liteip_drawing';

    /**
     * The primary key name for the table
     *
     * @var string
     */
    protected $primaryKey = 'liteip_drawing_id';

    /**
     * Get the liteip project source for the drawing
     */
    public function liteip()
    {
        return $this->belongsTo('App\Models\Liteip', 'liteip_id');
    }
}
