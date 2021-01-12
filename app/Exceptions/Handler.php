<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Exceptions\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
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
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
         if ($exception instanceof ValidationException) {
            $code = 422;
            $response = [
                'errors' => [
                    'status_code' => $code,
                    'message' => 'The given data was invalid.',
                    'errors' => $exception->getResponse(),
                ],
            ];
        } elseif ($exception instanceof \Illuminate\Validation\UnauthorizedException) {
            $code = 401;
            $response = [
                'errors' => [
                    'status_code' => 4016,
                    'message' => 'Unauthorized',
                ],
            ];
        } elseif ($exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
            $code = 404;
            $response = [
                'errors' => [
                    'status_code' => $code,
                    'message' => 'Not Found',
                ],
            ];
        } elseif ($exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
            $code = 404;
            $response = [
                'errors' => [
                    'status_code' => $code,
                    'message' => 'Not Found',
                ],
            ];

        } elseif ($exception instanceof \Illuminate\Database\QueryException) {
            $code = 500;
            $message = 'Internal Server Error';
            $statement = substr(strtolower(trim($exception->getSql())), 0, 6);
            if (in_array($statement, ['insert', 'update'])) {
                $code = 417;
                $message = 'Expectation Field';
            }
            $response = [
                'errors' => [
                    'status_code' => $code,
                    'message' => $message,
                ],
            ];
        } elseif ($exception instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException) {
            $code = 405;
            $response = [
                'errors' => [
                    'status_code' => $code,
                    'message' => 'Method not Allowed',
                ],
            ];
        } elseif ($exception instanceof InsertException || $exception instanceof UpdateException || $exception instanceof DeleteException) {
            $code = 417;
            $response = [
                'errors' => [
                    'status_code' => $code,
                    'message' => $exception->getMessage(),
                ],
            ];
        } elseif ($exception instanceof NotAcceptableException) {
            $code = 406;
            $response = [
                'errors' => [
                    'status_code' => $code,
                    'message' => 'Not Acceptable',
                    'errors' => $exception->getMessage(),
                ],
            ];
        } elseif ($exception instanceof NotAcceptableExceptionArray) {
            $code = 406;
            $message = json_decode($exception->getMessage(), true);
            $response = [
                'errors' => [
                    'status_code' => $code,
                    'message' => 'Not Acceptable',
                    'errors' => $message,
                ],
            ];
        } else {
            $code = 400;
            $response = [
                'errors' => [
                    'status_code' => $code,
                    'message' => $exception->getMessage(),
                ],
            ];
        }

        if (env('APP_DEBUG',false) === true) {
            $response['exception'] = [
                'code' => $exception->getCode(),
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString(),
            ];
        }

        return response()->json($response, $code);

    }
}
