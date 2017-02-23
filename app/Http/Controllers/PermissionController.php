<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

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
    public function index(Request $request, UserRepository $users)
    {
//        $url = 'http://portal.liteip.com/8p3/GetProjectID.aspx';
//        $client = new Client(); //GuzzleHttp\Client
//
//        $res = $client->get($url);
//        echo $res->getStatusCode();
//// "200"
//        //echo $res->getHeader('content-type');
//// 'application/json; charset=utf8'
//        $body =  json_decode($res->getBody());
//        print_r($body);
//
//        die('STOP!!');
////        $client = new Client(); //GuzzleHttp\Client
//
//
//        die('errr');
//        // Find all entities
//        foreach ($users->findAll() as $user) {
//            echo $user->name, '<br>';
//        }
//
//        echo $users->find(1)->name, '<br>';
//        die('STOP');
        $authorization = $this->findAuthorization($request->user()->roles);

        return $this->response->withArray($authorization);
    }
}
