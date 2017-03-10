<?php
/**
 * Created by PhpStorm.
 * User: jonathancook
 * Date: 10/03/2017
 * Time: 14:37
 */

namespace App\Http\Middleware;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;

trait RouteModifier
{
    public function replaceRouteIdWithModel (Route $route, $routeId, Model $model) {
        $reflect = new \ReflectionClass($model);
        $modelName = lcfirst($reflect->getShortName());

        $route->forgetParameter($routeId);
        $route->setParameter($modelName, $model);
    }
}