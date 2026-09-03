<?php

namespace Workbench\App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

use function Orchestra\Testbench\workbench_path;

class WorkbenchServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
           /**
         * Serve Workbench assets dynamically.
         *
         * Resolves the requested asset path, determines its MIME type,
         * and returns the file response if the asset exists.
         */
         Route::get("assets/{path}",function(string $path){
            $assetUrl=workbench_path("public\\$path");
            
            $mimeTypes = [
                'css'   => 'text/css',
                'js'    => 'application/javascript',
                'json'  => 'application/json',
                'svg'   => 'image/svg+xml',
                'png'   => 'image/png',
                'jpg'   => 'image/jpeg',
                'jpeg'  => 'image/jpeg',
                'webp'  => 'image/webp',
                'woff'  => 'font/woff',
                'woff2' => 'font/woff2',
            ];

            $extension = pathinfo($assetUrl, PATHINFO_EXTENSION);

            return (is_file($assetUrl)) ? response()->file($assetUrl, [
                                         'Content-Type' => $mimeTypes[$extension] ,
                                        ])
                                        : abort(404);
        })->where('path','.*'); 
    }
}
