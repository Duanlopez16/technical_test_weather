<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

/**
 * Handler
 */
class Handler extends ExceptionHandler
{
    /**
     * dontReport
     *
     * @var array
     */
    protected $dontReport = [];

    /**
     * dontFlash
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * register
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(function (Exception $e, $request) {
            return $this->handleException($request, $e);
        });
    }
    /**
     * handleException
     *
     * @param  mixed $request
     * @param  mixed $exception
     * @return void
     */
    public function handleException($request, Exception $exception)
    {
        if ($exception instanceof RouteNotFoundException) {
            return response()->json(["status" => 'error', "message" => "Error de autenticación"], 401);
        }
        if ($exception instanceof HttpException) {
            return response()->json(["status" => 'error', "message" => "No se encontró la ruta."], 404);
        }
        if ($exception instanceof AuthorizationException) {
            return response()->json(["status" => 'error', "message" => "Error de autorización, no tiene permisos"], 403);
        }
    }
}
