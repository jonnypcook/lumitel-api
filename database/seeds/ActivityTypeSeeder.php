<?php

use Illuminate\Database\Seeder;
use App\ActivityType;

class ActivityTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $activityTypes = ActivityType::all();
        if (!empty($activityTypes)) {
            foreach ($activityTypes as $activityType) {
                $activityType->delete();
            }
        }

        ActivityType::insert(array(
                array('activity_type_id' => 1, 'name' =>'logon'),
                array('activity_type_id' => 2, 'name' =>'logoff'),
                array('activity_type_id' => 3, 'name' =>'password change'),
                array('activity_type_id' => 4, 'name' =>'password reset'),
                array('activity_type_id' => 5, 'name' =>'installation viewed'),
                array('activity_type_id' => 6, 'name' =>'installation modified')
            )
        );
    }
}
