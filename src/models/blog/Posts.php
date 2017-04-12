<?php
namespace Escapeboy\AdminBlog\Models\Blog;
use Escapeboy\AdminCore\Scopes\AdminCoreOrderByCreatedAtScope;
use Escapeboy\AdminFiles\Models\Files;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Posts extends Model {
    protected $table = 'blog_posts';
    public $timestamps = true;

    use SoftDeletes;

    protected $guarded = ['id'];
    protected $dates = ['publish_date', 'deleted_at'];
    protected $casts = [
        'viewable' => 'boolean',
        'views' => 'integer'
    ];

    protected function bootIfNotBooted(){
        parent::boot();
        static::addGlobalScope(new AdminCoreOrderByCreatedAtScope());
    }

    public function scopeVisible($q){
        return $q->whereViewable(true);
    }


    public function categories(){
        return $this->belongsToMany(Categories::class, 'blog_posts_categories');
    }

    public function comments(){
        return $this->hasMany(Comments::class);
    }

    public function getStatusAttribute(){
        if($this->deleted_at) return 'deleted';
        if(!$this->viewable) return 'hidden';
        if($this->publish_date->gt(\Carbon\Carbon::now())) return 'upcoming';
        return 'published';
    }

    public function files(){
        return $this->morphToMany(Files::class,'relation','files_relations','relation_id', 'files_id');
    }

    public function getTitleAttribute(){
        return $this->{'title_'.config('app.fallback_locale', 'en')};
    }
    public function getContentAttribute(){
        return $this->{'content_'.config('app.fallback_locale', 'en')};
    }
    public function getExcerptAttribute(){
        return $this->{'excerpt_'.config('app.fallback_locale', 'en')};
    }
    public function getMetaTitleAttribute(){
        return $this->{'meta_title_'.config('app.fallback_locale', 'en')};
    }
    public function getMetaDescriptionAttribute(){
        return $this->{'meta_description_'.config('app.fallback_locale', 'en')};
    }
    public function getMetaKeywordsAttribute(){
        return $this->{'meta_keywords_'.config('app.fallback_locale', 'en')};
    }

}