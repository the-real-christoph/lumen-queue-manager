<?php

namespace LumenQueueManager\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class QueueManagerServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Add namespaces to allow usage of views without the need of copying them into your own project.
        $viewPath = config('lumen-queue-manager.viewPath', __DIR__ . '/../views');
        View::addNamespace('lumen-queue-manager', $viewPath);
        Blade::componentNamespace($viewPath, 'lumen-queue-manager');
    }

    public function boot()
    {
        $router = $this->app['router'];
        $router->get('/queue-manager', ['uses' => 'LumenQueueManager\Http\Controllers\QueueManagerController@index', 'as' => 'queue-manager-index'])->namedRoutes;
        $router->get('/queue-manager/view/{jobId}', 'LumenQueueManager\Http\Controllers\QueueManagerController@view');
        $router->get('/queue-manager/delete/{jobId}', 'LumenQueueManager\Http\Controllers\QueueManagerController@delete');

/*        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../views' => $this->app->resourcePath('views/vendor/lumen-queue-manager'),
            ], 'lumen-queue-manager');
        }*/
    }
}
