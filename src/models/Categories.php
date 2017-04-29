<?php
namespace LaraMod\Admin\Blog\Models;
use LaraMod\Admin\Core\Scopes\AdminCoreOrderByCreatedAtScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categories extends Model {
    protected $table = 'blog_categories';
    public $timestamps = true;

    use SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'viewable' => 'boolean'
    ];
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'viewable',
        'categories_id',
        'pos'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        foreach (config('app.locales', [config('app.fallback_locale', 'en')]) as $locale) {
            $this->fillable = array_merge($this->fillable, [
                'title_' . $locale,
                'content_' . $locale,
                'meta_title_' . $locale,
                'meta_description_' . $locale,
                'meta_keywords_' . $locale,
            ]);
        }
    }

    protected function bootIfNotBooted(){
        parent::boot();
        static::addGlobalScope(new AdminCoreOrderByCreatedAtScope());
    }

    public function posts(){
        return $this->belongsToMany(Posts::class, 'blog_posts_categories')->withPivot(['posts_id', 'categories_id']);
    }

    public function scopeVisible($q){
        return $q->whereViewable(true);
    }

    public function getTitleAttribute(){
        return $this->{'title_'.config('app.locale', config('app.fallback_locale', 'en'))};
    }
    public function getContentAttribute(){
        return $this->{'content_'.config('app.locale', config('app.fallback_locale', 'en'))};
    }
    public function getMetaTitleAttribute(){
        return $this->{'meta_title_'.config('app.locale', config('app.fallback_locale', 'en'))};
    }
    public function getMetaDescriptionAttribute(){
        return $this->{'meta_description_'.config('app.locale', config('app.fallback_locale', 'en'))};
    }
    public function getMetaKeywordsAttribute(){
        return $this->{'meta_keywords_'.config('app.locale', config('app.fallback_locale', 'en'))};
    }
    public function getStatusAttribute(){
        if(!$this->viewable) return 'hidden';
        return 'visible';
    }
}