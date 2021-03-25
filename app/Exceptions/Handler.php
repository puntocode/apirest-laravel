<?php

namespace App\Exceptions;

use App\Traits\ApiResponser;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponser;

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {

        });

        $this->renderable(function (NotFoundHttpException $e, $request) {
            //dd($e);
            $message = "No se encontro la URL especificada";
            return response()->json($message, 404);
        });

        $this->renderable(function (ModelNotFoundException $e, $request) {
            $message = $e->getMessage();
            return response()->json($message, 404);
        });

        $this->renderable(function (ValidationException $e, $request) {
            //dd($e);
            $errors = $e->validator->errors()->getMessages();
            return $this->errorResponse($errors, 422);
        });

        $this->renderable(function (AuthenticationException $e) {
            $errors = 'El usuario no está autenticado';
            return $this->errorResponse($errors, 401);
        });

        $this->renderable(function (AuthorizationException $e) {
            $errors = 'No posee permisos para ejecutar esta acción';
            return $this->errorResponse($errors, 403);
        });

        $this->renderable(function (MethodNotAllowedHttpException $e) {
            $errors = 'El metodo especifiaco een la peticion no es valido';
            return $this->errorResponse($errors, 405);
        });

        $this->renderable(function (HttpException $e) {
            return $this->errorResponse($e->getMessage(), $e->getStatusCode());
        });

        $this->renderable(function (QueryException $e) {
            //dd($e);
            return $this->errorResponse($e->getMessage(), 500);
        });

        //$this->renderable(function (Throwable $e, $request) {
        //    return $this->errorResponse('No existe ninguna instancia de con el id especificado', 404);
        //});
    }


}
