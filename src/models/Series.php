<?php

namespace LaraMod\Admin\Blog\Models;

use LaraMod\Admin\Core\Scopes\AdminCoreOrderByCreatedAtScope;
use LaraMod\Admin\Core\Scopes\AdminCoreOrderByPosScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Series extends Model
{
    public $timestamps = true;
    protected $table = 'blog_series';

    use SoftDeletes;
    protected $guarded = ['id'];

    protected $casts = [
        'viewable'    => 'boolean',
        'pos'         => 'integer',
    ];

    protected $dates = ['deleted_at'];

    protected $appends = ['title'];


    protected $fillable = [
        'viewable',
        'pos',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        foreach (config('app.locales', [config('app.fallback_locale', 'en')]) as $locale) {
            $this->fillable = array_merge($this->fillable, [
                'title_'.$locale,
                'sub_title_'.$locale,
                'short_description_'.$locale,
                'description_'.$locale,
                'meta_title_'.$locale,
                'meta_description_'.$locale,
                'meta_keywords_'.$locale
            ]);
        }
    }

    public function scopeVisible($q)
    {
        return $q->whereViewable(true);
    }

    public function posts(){
        return $this->belongsToMany(Posts::class,'blog_post_series','serie_id', 'post_id');
    }

    public function getTitleAttribute()
    {
        return $this->{'title_' . config('app.fallback_locale', 'en')};
    }

    public function getSubTitleAttribute()
    {
        return $this->{'sub_title_' . config('app.fallback_locale', 'en')};
    }

    public function getShortDescriptionAttribute()
    {
        return $this->{'short_description_' . config('app.fallback_locale', 'en')};
    }

    public function getDescriptionAttribute()
    {
        return $this->{'description_' . config('app.fallback_locale', 'en')};
    }

    public function getMetaTitleAttribute()
    {
        return $this->{'meta_title_' . config('app.fallback_locale', 'en')};
    }

    public function getMetaDescriptionAttribute()
    {
        return $this->{'meta_description_' . config('app.fallback_locale', 'en')};
    }

    public function getMetaKeywordsAttribute()
    {
        return $this->{'meta_keywords_' . config('app.fallback_locale', 'en')};
    }

    protected function bootIfNotBooted()
    {
        parent::boot();
        static::addGlobalScope(new AdminCoreOrderByPosScope());
        static::addGlobalScope(new AdminCoreOrderByCreatedAtScope());
    }


}