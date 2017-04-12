<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBlogCommentsTable extends Migration {

	public function up()
	{
		Schema::create('blog_comments', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
            $table->softDeletes();
            $table->string('author_names')->default('Guest');
            $table->string('author_url')->nullable();
            $table->string('author_email')->nullable();
            $table->integer('users_id')->nullable()->index();
			$table->text('content');
			$table->string('ip_address', 50);
			$table->string('lang', 3)->index()->default(config('app.fallback_locale', 'en'));
			$table->integer('blog_posts_id')->unsigned()->index();
		});
	}

	public function down()
	{
		Schema::drop('blog_comments');
	}
}