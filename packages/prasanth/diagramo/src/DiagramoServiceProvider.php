<?php

namespace prasanth\diagramo;

use Illuminate\Support\ServiceProvider;

class DiagramoServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        include __DIR__.'/routes.php';
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->make('prasanth\diagramo\DiagramoController');
        $this->loadViewsFrom(__DIR__.'/views', 'diagramo');
    }
}
