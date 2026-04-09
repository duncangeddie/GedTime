<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $Middleware): void {
        $Middleware->redirectGuestsTo(fn (Request $Request) => route('login'));
        $Middleware->redirectUsersTo(fn (Request $Request) => route('dashboard'));
    })
    ->withExceptions(function (Exceptions $Exceptions): void {
        //
    })->create();
