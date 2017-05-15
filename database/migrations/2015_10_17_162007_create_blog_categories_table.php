<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBlogCategoriesTable extends Migration
{

    public function up()
    {
        Schema::create('blog_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();
            foreach (config('app.locales', [config('app.fallback_locale', 'en')]) as $locale) {
                $table->string('title_' . $locale, 255)->nullable();
                $table->text('content_' . $locale)->nullable();
                $table->string('meta_title_' . $locale, 255)->nullable();
                $table->string('meta_description_' . $locale, 255)->nullable();
                $table->string('meta_keywords_' . $locale, 255)->nullable();
            }
            $table->boolean('viewable')->default(true)->index();
            $table->integer('categories_id')->unsigned()->nullable()->index();
            $table->smallInteger('pos')->unsigned()->index()->default(0);
        });
    }

    public function down()
    {
        Schema::drop('blog_categories');
    }
}