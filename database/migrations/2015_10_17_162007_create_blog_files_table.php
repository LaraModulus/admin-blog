<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBlogFilesTable extends Migration
{

    public function up()
    {
        if (class_exists('\LaraMod\Admin\Files')) {
            Schema::create('blog_posts_files', function (Blueprint $table) {
                $table->integer('blog_posts_id')->unsigned()->index();
                $table->integer('files_id')->unsigned()->index();
                $table->primary(['blog_posts_id', 'files_id']);
            });
        }
    }

    public function down()
    {
        if (class_exists('\LaraMod\Admin\Files')) {
            Schema::dropIfExists('blog_posts_files');
        }
    }
}