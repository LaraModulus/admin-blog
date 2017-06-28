<?php

namespace LaraMod\Admin\Blog\Models;

use App\User;
use LaraMod\Admin\Core\Scopes\AdminCoreOrderByCreatedAtScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use LaraMod\Admin\Core\Traits\HelpersTrait;

class Comments extends Model
{
    protected $table = 'blog_comments';
    public $timestamps = true;

    use SoftDeletes, HelpersTrait;

    protected $guarded = ['id'];
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'author_names',
        'author_url',
        'author_email',
        'users_id',
        'content',
        'ip_address',
        'lang',
        'blog_posts_id',
    ];

    protected function bootIfNotBooted()
    {
        parent::boot();
        static::addGlobalScope(new AdminCoreOrderByCreatedAtScope());
    }

    public function post()
    {
        return $this->hasOne(Posts::class, 'id', 'blog_posts_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'users_id');
    }

}