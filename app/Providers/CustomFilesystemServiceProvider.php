<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Filesystem\Filesystem;

class CustomFilesystemServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('files', function () {
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
        $filesystem = $this->app->make('files');
        $target = storage_path('app/public');
        $link = public_path('storage');

        if (!file_exists($link) && file_exists($target)) {
            try {
                if (function_exists('symlink')) {
                    $filesystem->link($target, $link);
                } else {
                    exec('ln -s ' . escapeshellarg($target) . ' ' . escapeshellarg($link));
                }
            } catch (\Throwable $e) {
                // Fallback to copying directory on failure
                $filesystem->copyDirectory($target, $link);
            }
        }
    }
}
