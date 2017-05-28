<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePostsPivotTable extends Migration
{

    public function up()
    {
        Schema::create('blog_posts_categories', function (Blueprint $table) {
            $table->integer('posts_id')->unsigned()->index();
            $table->integer('categories_id')->unsigned()->index();
            $table->primary(['posts_id', 'categories_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('blog_posts_categories');
    }
}