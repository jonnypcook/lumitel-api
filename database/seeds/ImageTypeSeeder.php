<?php

use Illuminate\Database\Seeder;
use App\Models\ImageType;

class ImageTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $imageTypes = ImageType::all();
        if (!empty($imageTypes)) {
            foreach ($imageTypes as $imageType) {
                $imageType->delete();
            }
        }

        ImageType::insert(array(
                array('image_type_id' => 1, 'extension' =>'jpg', 'description' =>'jpeg image'),
                array('image_type_id' => 2, 'extension' =>'jpeg', 'description' =>'jpeg image'),
                array('image_type_id' => 3, 'extension' =>'png', 'description' =>'png image'),
            )
        );
    }
}
