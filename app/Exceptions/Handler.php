<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        // $this->reportable(function (Throwable $e) {
        //
        // });

        $this->renderable(
            function (NotFoundHttpException $e, $request) {
                //
                if ($request->is("api/products/*")) {
                    $ok = false;
                    $errors = ["Product not found"];
                    return response()->json(compact("ok", "errors"), 404);
                }
                if ($request->is("api/storehouses/*")) {
                    $ok = false;
                    $errors = ["Storehouse not found"];
                    return response()->json(compact("ok", "errors"), 404);
                }
                if ($request->is("api/sales/*")) {
                    $ok = false;
                    $errors = ["Sale not found"];
                    return response()->json(compact("ok", "errors"), 404);
                }
            }
        );
    }
}
