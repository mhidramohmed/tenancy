<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

return Application::configure(basePath: dirname(__DIR__))
  ->withRouting(
    web: __DIR__ . '/../routes/web.php',
    commands: __DIR__ . '/../routes/console.php',
    health: '/up',
    // On configure api pour charger les deux fichiers
    api: __DIR__ . '/../routes/api.php',
  )
  ->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
      'tenancy'         => InitializeTenancyByDomain::class,
      'prevent_central' => PreventAccessFromCentralDomains::class,
    ]);
  })
  ->withExceptions(function (Exceptions $exceptions) {
    $exceptions->render(function (RouteNotFoundException $e, Request $request) {
      return response()->json(['error' => 'You do not have permission to access this path'], 401);
    });
  })
  ->create();
