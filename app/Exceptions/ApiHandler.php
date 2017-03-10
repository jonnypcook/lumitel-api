<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

trait ApiHandler
{

    /**
     * Creates a new JSON response based on exception type.
     *
     * @param Request $request
     * @param Exception $e
     * @return \Illuminate\Http\JsonResponse
     */
    protected function getJsonResponseForException(Request $request, Exception $e)
    {
        switch(get_class($e)) {
            case ModelNotFoundException::class:
                return $this->notFound();
            case AuthorizationException::class:
                return empty($e->getMessage()) ? $this->forbidden() : $this->forbidden($e->getMessage());
            case AuthenticationException::class:
                return $this->notAuthenticated();
            case BadRequestHttpException::class:
                return $this->badRequest('Bad Request: ' . $e->getMessage());
            case ValidationException::class:
                return $this->badRequest('Bad Request: ' . $e->getMessage());
        }

        return $this->badRequest($e->getMessage());
    }

    /**
     * Returns json response for generic bad request.
     *
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function badRequest($message='Bad request')
    {
        return $this->jsonResponse(['error' => $message], 400);
    }

    /**
     * Returns json response for not found exception.
     *
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function notFound($message='Resource not found')
    {
        return $this->jsonResponse(['error' => $message], 404);
    }

    /**
     * Returns json response for authorisation exception.
     *
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function forbidden($message='Authorisation not granted to resource')
    {
        return $this->jsonResponse(['error' => $message], 403);
    }

    /**
     * Returns json response for authentication exception.
     *
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function notAuthenticated($message = 'Unauthenticated') {
        return response()->json(['error' => $message], 401);
    }
    /**
     * Returns json response.
     *
     * @param array|null $payload
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function jsonResponse(array $payload=null, $statusCode=404)
    {
        $payload = $payload ?: [];

        return response()->json($payload, $statusCode);
    }


}