<?php

declare(strict_types=1);

namespace App\Exceptions;

use Cloudinary\Api\Exception\AuthorizationRequired;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use PDOException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * logException
     * @param  \Exception  $e
     */
    public function logException(Throwable $e)
    {
        if (! ($e instanceof BaseException)
        && ! ($e instanceof ValidationException)
        && ! ($e instanceof AuthenticationException)
        && ! ($e instanceof ModelNotFoundException)
        ) {
            Log::error($e);
        }
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\JsonResponse.
     */
    public function render($request, Throwable $e)
    {
        $statusCode = 400;
        $errors = [];
        $message = $e->getMessage();

        $this->logException($e);

        switch (true) {
            case $e instanceof ValidationException:
                $message = __('messages.errors.input');
                $errors = $e->errors();
                $statusCode = Response::HTTP_UNPROCESSABLE_ENTITY;
                break;

            case $e instanceof AuthenticationException:
                $message = __('messages.' . $e->getMessage());
                $errors = $e->getMessage();
                $statusCode = Response::HTTP_UNAUTHORIZED;
                break;

            case $e instanceof ModelNotFoundException:
                $message = __('messages.errors.not_found');
                $errors = 'record.not_found';
                $statusCode = Response::HTTP_NOT_FOUND;
                break;

            case $e instanceof RouteNotFoundException:
            case $e instanceof NotFoundHttpException:
            case $e instanceof MethodNotAllowedHttpException:
                $message = __('messages.errors.route');
                $errors = 'route.not_found';
                $statusCode = Response::HTTP_NOT_FOUND;
                break;

            case $e instanceof AuthorizationRequired:
                $message = __('messages.errors.cloudinary_authorization_fail');
                $errors = 'error.cloudinary_authorization_fail';
                $statusCode = Response::HTTP_BAD_REQUEST;
                break;

            case $e instanceof BaseException:
                $message = $e->getMessage();
                $errors = $e->getMessageCode();
                $statusCode = $e->getCode();
                break;

            case $e instanceof ConnectionException:
            case $e instanceof PDOException:
                $statusCode = 500;
                break;

            default:
                break;
        }
        return response()->json([
            'message' => $message,
            'errors' => $errors,
            'code' => $statusCode,
        ], $statusCode);
    }
}
