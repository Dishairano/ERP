<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;


return Application::configure(basePath: dirname(__DIR__))
  ->withRouting(
    web: __DIR__ . '/../routes/web.php',
    commands: __DIR__ . '/../routes/console.php',
    health: '/up',
  )
  ->withMiddleware(function (Middleware $middleware) {
    //
  })->withMiddleware(function (Middleware $middleware) {
    $middleware->web(append: [
      \App\Http\Middleware\CheckUserAuthenticated::class,
    ]);
  })
  ->withExceptions(function (Exceptions $exceptions) {
    //
  })->create();
if ($exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
  if ($request->is(['admin', 'admin/*'])) {
    if ($exception->getStatusCode() == 404) {
      return response()->view("/errors/404", [], 404);
    }

    if ($exception->getStatusCode() == 403) {
      return response()->view("admin.errors.403", [], 403);
    }

    if ($exception->getStatusCode() == 500) {
      return response()->view("admin.errors.500", [], 500);
    }

    if ($exception->getStatusCode() == 503) {
      return response()->view("admin.errors.503", [], 503);
    }
  }

  if ($exception->getStatusCode() == 404) {
    return response()->view("customer.errors.404", [], 404);
  }
}
