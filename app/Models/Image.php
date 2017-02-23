<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'image';

    /**
     * The primary key name for the table
     *
     * @var string
     */
    protected $primaryKey = 'image_id';

    /**
     * Get the imageType for the image
     */
    public function imageType()
    {
        return $this->belongsTo('App\Models\ImageType', 'image_type_id');
    }

}
