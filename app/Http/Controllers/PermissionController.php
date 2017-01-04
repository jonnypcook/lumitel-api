<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PermissionController extends Controller
{
    /**
     * find permissions from roles
     * @param $roles
     * @param int $depth
     * @return array
     */
    private function findPermissions ($roles, $depth = 0) {
        if ($depth > 3) {
            return array();
        }

        $permissions = array();
        foreach ($roles as $role) {
            foreach ($role->permissions as $permission) {
                $permissions[$permission->permission_id] = $permission->name;
            }

            $permissions = $permissions + $this->findPermissions($role->roles, $depth + 1);
        }

        return $permissions;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $permissions = $this->findPermissions($request->user()->roles);

        return $this->response->withArray(array('permissions' => $permissions));
    }
}
