<?php

namespace Simmatrix\ACHProcessor\Providers;

use Illuminate\Support\ServiceProvider;

class ACHProcessorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this -> publishes([
            __DIR__ . '/../../config/ach_processor.php' => base_path('config/ach_processor.php')
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this -> mergeConfigFrom( __DIR__ .'./../../config/ach_processor.php', 'ach_processor' );
    }
}
