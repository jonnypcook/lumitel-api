<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImageType extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'image_type';

    /**
     * The primary key name for the table
     *
     * @var string
     */
    protected $primaryKey = 'image_type_id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get the images for the image type.
     */
    public function images()
    {
        return $this->hasMany('App\Models\Image', 'image_type_id', 'image_type_id');
    }
}
