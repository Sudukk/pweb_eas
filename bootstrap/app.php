<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );

        // Total POST melebihi post_max_size PHP - $_POST/$_FILES dikosongkan PHP,
        // sehingga validasi biasa tak sempat jalan. Tangani agar user dapat pesan jelas.
        $exceptions->render(function (PostTooLargeException $e, Request $request) {
            $maks = ini_get('post_max_size');
            $pesan = "Ukuran data yang dikirim terlalu besar (maksimal {$maks}). "
                . 'Silakan unggah file/foto dengan ukuran lebih kecil.';

            if ($request->expectsJson()) {
                return response()->json(['message' => $pesan], 413);
            }

            return redirect()->back()->withInput($request->except('foto'))->with('error', $pesan);
        });
    })->create();
