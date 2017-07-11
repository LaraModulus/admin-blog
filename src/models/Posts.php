<?php

namespace LaraMod\Admin\Blog\Models;

use LaraMod\Admin\Core\Scopes\AdminCoreOrderByCreatedAtScope;
use LaraMod\Admin\Core\Traits\HelpersTrait;
use LaraMod\Admin\Files\Models\Files;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Posts extends Model
{
    protected $table = 'blog_posts';
    public $timestamps = true;

    use SoftDeletes, HelpersTrait;

    protected $guarded = ['id'];
    protected $dates = ['publish_date', 'deleted_at'];
    protected $casts = [
        'viewable' => 'boolean',
        'views'    => 'integer',
    ];
    protected $appends = ['status'];

    protected $fillable = [
        'cover_id',
        'publish_date',
        'viewable',
        'allow_comments',
        'users_id',
        'slug',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        foreach (config('app.locales', [config('app.fallback_locale', 'en')]) as $locale) {
            $this->fillable = array_merge($this->fillable, [
                'title_' . $locale,
                'content_' . $locale,
                'excerpt_' . $locale,
                'meta_title_' . $locale,
                'meta_description_' . $locale,
                'meta_keywords_' . $locale,
            ]);
        }
    }

    protected function bootIfNotBooted()
    {
        parent::boot();
        static::addGlobalScope(new AdminCoreOrderByCreatedAtScope());
    }

    public function scopeVisible($q)
    {
        return $q->whereViewable(true);
    }


    public function categories()
    {
        return $this->belongsToMany(Categories::class, 'blog_posts_categories');
    }

    public function comments()
    {
        return $this->hasMany(Comments::class);
    }

    public function tags(){
        return $this->belongsToMany(Tags::class,'blog_post_tag','post_id','tag_id');
    }

    public function series(){
        return $this->belongsToMany(Series::class,'blog_post_series','post_id', 'serie_id');
    }

    public function getStatusAttribute()
    {
        if ($this->deleted_at) {
            return 'deleted';
        }
        if (!$this->viewable) {
            return 'hidden';
        }
        if ($this->publish_date->gt(\Carbon\Carbon::now())) {
            return 'upcoming';
        }

        return 'published';
    }

    public function files()
    {
        return $this->morphToMany(Files::class, 'relation', 'files_relations', 'relation_id', 'files_id');
    }

    public function getTitleAttribute()
    {
        return $this->{'title_' . config('app.locale', 'en')};
    }

    public function getContentAttribute()
    {
        return $this->{'content_' . config('app.locale', 'en')};
    }

    public function getExcerptAttribute()
    {
        return $this->{'excerpt_' . config('app.locale', 'en')};
    }

    public function getMetaTitleAttribute()
    {
        return $this->{'meta_title_' . config('app.locale', 'en')};
    }

    public function getMetaDescriptionAttribute()
    {
        return $this->{'meta_description_' . config('app.locale', 'en')};
    }

    public function getMetaKeywordsAttribute()
    {
        return $this->{'meta_keywords_' . config('app.locale', 'en')};
    }

    public function setPublishDateAttribute($value = null)
    {
        $this->attributes['publish_date'] = $value ?: \Carbon\Carbon::now();
    }

}