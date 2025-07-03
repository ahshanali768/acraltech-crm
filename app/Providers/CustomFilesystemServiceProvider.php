<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Filesystem\Filesystem;

class CustomFilesystemServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('files', function ($app) {
            return new \Illuminate\Filesystem\Filesystem();
        });
    }

    public function boot()
    {
        // Ensure storage directories exist
        $paths = [
            storage_path('app/public'),
            storage_path('app/private'),
            storage_path('framework/sessions'),
            storage_path('framework/views'),
            storage_path('framework/cache'),
            storage_path('logs'),
        ];

        foreach ($paths as $path) {
            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }
        }

        // Create storage link if it doesn't exist
        if (!file_exists(public_path('storage'))) {
            $this->app->make('files')->link(
                storage_path('app/public'),
                public_path('storage')
            );
        }
    }
}
