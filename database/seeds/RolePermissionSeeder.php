<?php

use Illuminate\Database\Seeder;

use App\Models\Permission;
use App\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::insert(array(
            array('permission_id' => 10, 'name' => 'owner.create'),
            array('permission_id' => 11, 'name' => 'owner.read'),
            array('permission_id' => 12, 'name' => 'owner.update'),
            array('permission_id' => 13, 'name' => 'owner.delete'),

            array('permission_id' => 20, 'name' => 'installation.create'),
            array('permission_id' => 21, 'name' => 'installation.read'),
            array('permission_id' => 22, 'name' => 'installation.update'),
            array('permission_id' => 23, 'name' => 'installation.delete'),

            array('permission_id' => 30, 'name' => 'space.create'),
            array('permission_id' => 31, 'name' => 'space.read'),
            array('permission_id' => 32, 'name' => 'space.update'),
            array('permission_id' => 33, 'name' => 'space.delete'),

            array('permission_id' => 40, 'name' => 'floor.create'),
            array('permission_id' => 41, 'name' => 'floor.read'),
            array('permission_id' => 42, 'name' => 'floor.update'),
            array('permission_id' => 43, 'name' => 'floor.delete'),

            array('permission_id' => 50, 'name' => 'device.create'),
            array('permission_id' => 51, 'name' => 'device.read'),
            array('permission_id' => 52, 'name' => 'device.update'),
            array('permission_id' => 53, 'name' => 'device.delete'),

            array('permission_id' => 60, 'name' => 'user.create'),
            array('permission_id' => 61, 'name' => 'user.read'),
            array('permission_id' => 62, 'name' => 'user.update'),
            array('permission_id' => 63, 'name' => 'user.delete'),
        ));

        Role::insert(array(
            // super group roles
            array('role_id' => 1, 'name' => 'administrator'),
            array('role_id' => 2, 'name' => 'member'),

            array('role_id' => 110, 'name' => 'OWNER_READER'),
            array('role_id' => 111, 'name' => 'OWNER_UPDATER'),

            array('role_id' => 120, 'name' => 'INSTALLATION_READER'),
            array('role_id' => 121, 'name' => 'INSTALLATION_UPDATER'),

            array('role_id' => 130, 'name' => 'SPACE_READER'),
            array('role_id' => 131, 'name' => 'SPACE_UPDATER'),

            array('role_id' => 140, 'name' => 'FLOOR_READER'),
            array('role_id' => 141, 'name' => 'FLOOR_UPDATER'),

            array('role_id' => 150, 'name' => 'DEVICE_READER'),
            array('role_id' => 151, 'name' => 'DEVICE_UPDATER'),

            array('role_id' => 160, 'name' => 'USER_READER'),
            array('role_id' => 161, 'name' => 'USER_UPDATER'),
        ));

        // add permissions to roles
        $role = Role::find(110);
        $role->permissions()->attach([11]);
        $role = Role::find(111);
        $role->permissions()->attach([10,11,12,13]);

        $role = Role::find(120);
        $role->permissions()->attach([21]);
        $role = Role::find(121);
        $role->permissions()->attach([20,21,22,23]);

        $role = Role::find(130);
        $role->permissions()->attach([31]);
        $role = Role::find(131);
        $role->permissions()->attach([30,31,32,33]);

        $role = Role::find(140);
        $role->permissions()->attach([41]);
        $role = Role::find(141);
        $role->permissions()->attach([40,41,42,43]);

        $role = Role::find(150);
        $role->permissions()->attach([51]);
        $role = Role::find(151);
        $role->permissions()->attach([50,51,52,53]);

        $role = Role::find(160);
        $role->permissions()->attach([61]);
        $role = Role::find(161);
        $role->permissions()->attach([60,61,62,63]);

        // add role groups
        $role = Role::find(1);
        $role->roles()->attach([110, 111, 120, 121, 130, 131, 140, 141, 150, 151, 160, 161]);

        $role = Role::find(2);
        $role->roles()->attach([110, 120, 130, 140, 150, 160]);
    }
}
