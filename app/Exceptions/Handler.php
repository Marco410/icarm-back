<?php

namespace App\Exceptions;

use App\Traits\Http\Controllers\ApiResponses;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
// // // // // use Illuminate\Validation\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponses;

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
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        //return parent::render($request, $exception);
        $response = $this->handleException($request, $exception);
        return $response;
    }

    public function handleException($request, Throwable $exception)
    {
        //echo get_class($exception); $d = 1;

        //echo '<br>TokenExpiredException-'.$d++;
        if ($exception instanceof TokenExpiredException) {
            return $this->convertTokenExpiredExceptionToApiResponse($request, $exception);
        }

        //echo '<br>TokenInvalidException-'.$d++;
        if ($exception instanceof TokenInvalidException) {
            return $this->convertTokenInvalidExceptionToApiResponse($request, $exception);
        }

        //echo '<br>JWTException-'.$d++;
        if ($exception instanceof JWTException) {
            return $this->convertJWTExceptionToApiResponse($request, $exception);
        }

        //echo '<br>ValidationException-'.$d++;
        if ($exception instanceof ValidationException) {
            return $this->convertValidationExceptionToApiResponse($request, $exception);
        }

        //echo '<br>ModelNotFoundException-'.$d++;
        if ($exception instanceof ModelNotFoundException) {
            return $this->convertModelNotFoundExceptionToApiResponse($request, $exception);
        }

        //echo '<br>AuthenticationException-'.$d++;
        // // // // // if ($exception instanceof AuthenticationException) {
        // // // // //     return $this->convertAuthenticationExceptionToApiResponse($request, $exception);
        // // // // // }

        //echo '<br>AuthorizationException-'.$d++;
        if ($exception instanceof AuthorizationException) {
            return $this->convertAuthorizationExceptionToApiResponse($request, $exception);
        }

        //echo '<br>NotFoundHttpException-'.$d++;
        if ($exception instanceof NotFoundHttpException) {
            return $this->convertNotFoundHttpExceptionToApiResponse($request, $exception);
        }

        //echo '<br>MethodNotAllowedHttpException-'.$d++;
        if ($exception instanceof MethodNotAllowedHttpException) {
            return $this->convertMethodNotAllowedHttpExceptionToApiResponse($request, $exception);
        }

        //echo '<br>QueryException-'.$d++;
        if ($exception instanceof QueryException) {
            return $this->convertQueryExceptionToApiResponse($request, $exception);
        }

        //echo '<br>TokenMismatchException-'.$d++;
        if ($exception instanceof TokenMismatchException) {
            return redirect()->back()->withInput($request->input());
        }
        //return $this->errorResponse(501, [get_class($exception), $exception->getMessage()]);

        //echo '<br>HttpException-'.$d++;
        if ($exception instanceof HttpException) {
            return $this->convertHttpExceptionToApiResponse($request, $exception);
        }

        if (config('app.debug')) {
            return parent::render($request, $exception);
        }

        return $this->errorResponse(501, 'Falla inesperada. Intente luego');
    }

    /**
     * Create a response object
     *
     * @param  \Illuminate\Http\Request $request
     * @param  AuthenticationException $exception
     * @return \App\Traits\Http\Controllers\ApiResponses
     */
    // // // // // protected function convertAuthenticationExceptionToApiResponse($request, AuthenticationException $exception)
    // // // // // {
    // // // // //     //$errors = $exception->validator->errors()->getMessages();
    // // // // //     return $this->errorResponse(401);
    // // // // // }

    /**
     * Create a response object
     *
     * @param  \Illuminate\Http\Request $request
     * @param  ValidationException $exception
     * @return \App\Traits\Http\Controllers\ApiResponses
     */
    protected function convertValidationExceptionToApiResponse($request, ValidationException $exception)
    {
        $errors = $exception->validator->errors()->getMessages();
        return $this->errorResponse(400, $errors);
    }

    /**
     * Create a response object
     *
     * @param  \Illuminate\Http\Request $request
     * @param  ValidationException $exception
     * @return \App\Traits\Http\Controllers\ApiResponses
     */
    protected function convertModelNotFoundExceptionToApiResponse($request, ModelNotFoundException $exception)
    {
        $modelo = strtolower(class_basename($exception->getModel()));
        return $this->errorResponse(404, "No existe ninguna instancia de {$modelo} con el id especificado.");
    }

    /**
     * Create a response object
     *
     * @param  \Illuminate\Http\Request $request
     * @param  AuthorizationException $exception
     * @return \App\Traits\Http\Controllers\ApiResponses
     */
    protected function convertAuthorizationExceptionToApiResponse($request, AuthorizationException $exception)
    {
        return $this->errorResponse(403);
    }

    /**
     * Create a response object
     *
     * @param  \Illuminate\Http\Request $request
     * @param  NotFoundHttpException $exception
     * @return \App\Traits\Http\Controllers\ApiResponses
     */
    protected function convertNotFoundHttpExceptionToApiResponse($request, NotFoundHttpException $exception)
    {
        return $this->errorResponse(404, 'No se encontró la URL especificada.');
    }

    /**
     * Create a response object
     *
     * @param  \Illuminate\Http\Request $request
     * @param  MethodNotAllowedHttpException $exception
     * @return \App\Traits\Http\Controllers\ApiResponses
     */
    protected function convertMethodNotAllowedHttpExceptionToApiResponse($request, MethodNotAllowedHttpException $exception)
    {
        return $this->errorResponse(405, 'El método especificado en la petición no es válido.');
    }

    /**
     * Create a response object
     *
     * @param  \Illuminate\Http\Request $request
     * @param  HttpException $exception
     * @return \App\Traits\Http\Controllers\ApiResponses
     */
    protected function convertHttpExceptionToApiResponse($request, HttpException $exception)
    {
        return $this->errorResponseStatusCode($exception->getStatusCode(), $exception->getMessage());
    }

    /**
     * Create a response object
     *
     * @param  \Illuminate\Http\Request $request
     * @param  QueryException $exception
     * @return \App\Traits\Http\Controllers\ApiResponses
     */
    protected function convertQueryExceptionToApiResponse($request, QueryException $exception)
    {
        $codigo = $exception->errorInfo[1];
        if ($codigo == 1451) {
            return $this->errorResponse(409, 'No se puede eliminar de forma permamente el recurso porque está relacionado con algún otro.');
        }
        return $this->errorResponse(409, $exception->getMessage());
    }


    /**
     * Create a response object
     *
     * @param  \Illuminate\Http\Request $request
     * @param  TokenExpiredException $exception
     * @return \App\Traits\Http\Controllers\ApiResponses
     */
    protected function convertTokenExpiredExceptionToApiResponse($request, TokenExpiredException $exception)
    {
        return $this->errorResponse(401.2);
    }

    /**
     * Create a response object
     *
     * @param  \Illuminate\Http\Request $request
     * @param  TokenInvalidException $exception
     * @return \App\Traits\Http\Controllers\ApiResponses
     */
    protected function convertTokenInvalidExceptionToApiResponse($request,  TokenInvalidException $exception)
    {
        return $this->errorResponse(401.1);
    }

    /**
     * Create a response object
     *
     * @param  \Illuminate\Http\Request $request
     * @param  JWTException $exception
     * @return \App\Traits\Http\Controllers\ApiResponses
     */
    protected function convertJWTExceptionToApiResponse($request, JWTException $exception)
    {
        return $this->errorResponse(401);
    }
}