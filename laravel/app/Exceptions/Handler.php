<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
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
    public function render($request, Throwable $exception) {

        // figure out if this is an API request
        // --> simply logic: 
        //      * if a request is sent to /api/... or
        //      * if something was sent as JSON 
        $isApiRequest = false;
        $contextPath = $request->getRequestUri();
        if (substr($contextPath, 0, 4 ) === "/api") {
            $isApiRequest = true;
        } else {
            $isApiRequest = $request->isJson();
        }

        if ($isApiRequest) {
        
            if ($exception instanceof ModelNotFoundException) { // 404
                return response()->json([
                    'message' => 'Resource not found'
                ], 404);
            } else if ($exception instanceof ValidationException) { // 400
                return response()->json([
                    'message' => 'Invalid Request',
                    'message details' => $exception->validator->errors()->toArray(),
                ], 400);
            } else if ($exception instanceof InvalidRequestException) { // 400
                return response()->json([
                    'message' => 'Invalid Request',
                    'message details' => $exception->getMessage()
                ], 400);
            } else { // 500
                return response()->json([
                    'message' => 'Internal Server Error: ' . $exception->getMessage(),
                    'stacktrace' => $exception->getTraceAsString()
                ], 500);
            }

        }

        return parent::render($request, $exception);
    }
}
