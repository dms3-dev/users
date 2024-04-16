<?php

namespace Mediamouse\Users\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */

    function render($request, Throwable $exception)
    {
        if ($this->isHttpException($exception)) {

            if ($exception->getStatusCode() == 403) {
                return response()->view('mediamouse-users::errors.403', [], 403);
            }
            if ($exception->getStatusCode() == 404) {
                return response()->view('mediamouse-users::errors.404', [], 404);
            }
            if ($exception->getStatusCode() == 500) {
                return response()->view('mediamouse-users::errors.500', [], 500);
            }
        }
        return parent::render($request, $exception);

    }


}
