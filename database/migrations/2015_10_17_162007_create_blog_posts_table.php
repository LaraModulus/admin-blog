<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBlogPostsTable extends Migration {

	public function up()
	{
		Schema::create('blog_posts', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->softDeletes();
            foreach(config('app.locales', [config('app.fallback_locale', 'en')]) as $locale){
                $table->string('title_'.$locale, 255)->nullable();
                $table->text('content_'.$locale)->nullable();
                $table->string('meta_title_'.$locale, 255)->nullable();
                $table->string('meta_description_'.$locale, 255)->nullable();
                $table->string('meta_keywords_'.$locale, 255)->nullable();
                $table->string('excerpt_'.$locale, 255)->nullable();
            }

			$table->integer('cover_id')->unsigned()->nullable();
			$table->datetime('publish_date');
			$table->boolean('viewable')->index()->default(1);
			$table->boolean('allow_comments')->index()->default(1);
            $table->integer('views')->unsigned()->default(0);
            $table->integer('users_id')->unsigned()->default(0);
		});
	}

	public function down()
	{
		Schema::drop('blog_posts');
	}
}