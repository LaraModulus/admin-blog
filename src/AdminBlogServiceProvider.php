<?php

namespace LaraMod\AdminBlog;

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
            __DIR__.'/views' => base_path('resources/views/laramod/admin-blog'),
        ]);
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
    }
}
