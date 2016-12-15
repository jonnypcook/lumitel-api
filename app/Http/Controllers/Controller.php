<?php

namespace App\Http\Controllers;

use EllipseSynergie\ApiResponse\Laravel\Response;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }
}
