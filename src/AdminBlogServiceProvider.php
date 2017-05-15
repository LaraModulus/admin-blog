<?php

namespace LaraMod\Admin\Blog;

use Illuminate\Support\ServiceProvider;
use LaraMod\Admin\Blog\Controllers\CategoriesController;
use LaraMod\Admin\Blog\Controllers\CommentsController;
use LaraMod\Admin\Blog\Controllers\PostsController;
use LaraMod\Admin\Blog\Controllers\SeriesController;
use LaraMod\Admin\Core\Traits\DashboardTrait;

class AdminBlogServiceProvider extends ServiceProvider
{
    use DashboardTrait;
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/views', 'adminblog');
        $this->publishes([
            __DIR__ . '/views' => base_path('resources/views/laramod/admin/blog'),
        ]);
        $this->publishes([
            __DIR__ . '/../database/migrations/' => database_path('migrations'),
        ], 'migrations');

        /**
         * Add comments widget to controller
         */
        try{
            $this->addWidget($this->app->make(CommentsController::class)->commentsWidget());
        }catch (\Exception $e){
            $this->addWidget($e->getMessage());
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        include __DIR__ . '/routes.php';
        $this->app->make(CommentsController::class);
        $this->app->make(PostsController::class);
        $this->app->make(CategoriesController::class);
        $this->app->make(SeriesController::class);
    }
}
