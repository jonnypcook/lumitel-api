<?php

namespace App\Exceptions;

use Illuminate\Http\Request;

trait ApiDetector
{

    /**
     * Determines if request is an api call.
     *
     * If the request URI contains '/api/'.
     *
     * @param Request $request
     * @return bool
     */
    protected function isApiCall(Request $request)
    {
        return $request->expectsJson() || strpos($request->getUri(), '/api/') !== false;
    }

}