<?php

namespace Illuminate\Filesystem;

use FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;

class FilesystemServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Storage::extend('files', function ($app, $config) {
            return new FilesystemAdapter(
                new Filesystem(),
                $config
            );
        });
    }

    public function register()
    {
        $this->app->singleton('files', function () {
            return new Filesystem();
        });
    }
}
