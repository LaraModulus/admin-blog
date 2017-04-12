<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBlogFilesTable extends Migration {

	public function up()
	{
	    if(class_exists('\Escapeboy\AdminFiles')){
            Schema::create('blog_posts_files', function(Blueprint $table) {
                $table->integer('blog_posts_id')->unsigned()->index();
                $table->integer('files_id')->unsigned()->index();
            });
        }
	}

	public function down()
	{
        if(class_exists('\Escapeboy\AdminFiles')) {
            Schema::drop('blog_posts_files');
        }
	}
}