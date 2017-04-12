<?php

namespace Escapeboy\AdminBlog;

use Illuminate\Support\ServiceProvider;

class AdminBlogServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/views', 'adminblog');
        $this->publishes([
            __DIR__.'/views' => base_path('resources/views/escapeboy/admin-blog'),
        ]);
//        $this->publishes([
//            __DIR__.'/assets' => public_path('assets/escapeboy/dashboard'),
//        ], 'public');
//        $this->publishes([
//            __DIR__.'/../config/adminusers.php' => config_path('adminusers.php')
//        ], 'config');
        $this->publishes([
            __DIR__.'/../database/migrations/' => database_path('migrations')
        ], 'migrations');

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        include __DIR__.'/routes.php';
//        $this->mergeConfigFrom(
//            __DIR__.'/../config/admincore.php', 'admincore'
//        );
//        $this->app->make('Escapeboy\AdminBlog\Controllers\PostsController');
    }
}
