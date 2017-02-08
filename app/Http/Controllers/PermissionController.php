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
    private function findAuthorization ($roles, $depth = 0) {
        if ($depth > 3) {
            return array('permissions' => array(), 'roles' => array());
        }

        $authorization = array('permissions' => array(), 'roles' => array());
        foreach ($roles as $role) {
            $authorization['roles'][$role->role_id] = $role->name;
            foreach ($role->permissions as $permission) {
                $authorization['permissions'][$permission->permission_id] = $permission->name;
            }

            $auth = $this->findAuthorization($role->roles, $depth + 1);
            $authorization['permissions'] += $auth['permissions'];
            $authorization['roles'] += $auth['roles'];
        }

        return $authorization;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $authorization = $this->findAuthorization($request->user()->roles);

        return $this->response->withArray($authorization);
    }
}
