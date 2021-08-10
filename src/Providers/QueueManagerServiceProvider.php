<?php

namespace LumenQueueManager\Providers;

use Illuminate\Pagination\Paginator;
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
        $router->get('/queue-manager', [
            'uses' => 'LumenQueueManager\Http\Controllers\JobsController@index',
            'as' => 'queue-manager-index'
        ]);
        $router->get('/queue-manager/view/{jobId}', [
            'uses' => 'LumenQueueManager\Http\Controllers\JobsController@view',
            'as' => 'queue-manager-view'
        ]);
        $router->get('/queue-manager/delete/{jobId}', [
            'uses' =>'LumenQueueManager\Http\Controllers\JobsController@delete',
            'as' => 'queue-manager-delete'
        ]);

        $router->get('/queue-manager/failed-jobs', [
            'uses' => 'LumenQueueManager\Http\Controllers\FailedJobsController@index',
            'as' => 'queue-manager-failed-index'
        ]);
        $router->get('/queue-manager/failed-jobs/retry/{jobUuid}', [
            'uses' => 'LumenQueueManager\Http\Controllers\FailedJobsController@retry',
            'as' => 'queue-manager-failed-retry'
        ]);
        $router->get('/queue-manager/failed-jobs/delete/{jobId}', [
            'uses' => 'LumenQueueManager\Http\Controllers\FailedJobsController@delete',
            'as' => 'queue-manager-failed-delete'
        ]);
        $router->get('/queue-manager/failed-jobs/view/{jobId}', [
            'uses' => 'LumenQueueManager\Http\Controllers\FailedJobsController@view',
            'as' => 'queue-manager-failed-view'
        ]);


        Paginator::useBootstrap();
    }
}
