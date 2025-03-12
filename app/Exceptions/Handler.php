<?php

namespace App\Exceptions;

use App\Traits\ApiResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponse;

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
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (Throwable $e, Request $request) {
            if ($request->is('api/*') || $request->wantsJson()) {
                return $this->handleApiException($e, $request);
            }

            return null;
        });
    }

    /**
     * Handle API exceptions.
     *
     * @param Throwable $exception
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    private function handleApiException(Throwable $exception, Request $request)
    {
        if ($exception instanceof AuthenticationException) {
            return $this->unauthorizedResponse('Unauthenticated');
        }

        if ($exception instanceof AccessDeniedHttpException) {
            return $this->forbiddenResponse();
        }

        if ($exception instanceof ModelNotFoundException) {
            $modelName = strtolower(class_basename($exception->getModel()));
            return $this->notFoundResponse("{$modelName} not found");
        }

        if ($exception instanceof NotFoundHttpException) {
            // Extract model name from message if it's a converted ModelNotFoundException
            if (strpos($exception->getMessage(), 'No query results for model') !== false) {
                preg_match('/\[(.*?)\]/', $exception->getMessage(), $matches);
                if (isset($matches[1])) {
                    $modelName = strtolower(class_basename($matches[1]));
                    return $this->notFoundResponse("{$modelName} not found");
                }
            }

            return $this->notFoundResponse('The requested URL was not found');
        }

        if ($exception instanceof ValidationException) {
            return $this->validationErrorResponse($exception->errors());
        }

        if ($exception instanceof ThrottleRequestsException) {
            return $this->errorResponse('Too many requests', 429);
        }

        if (config('app.debug')) {
            return $this->errorResponse(
                $exception->getMessage(),
                500,
                [
                    'exception' => get_class($exception),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                    'trace' => $exception->getTrace(),
                ]
            );
        }

        return $this->errorResponse('Server Error', 500);
    }
}
