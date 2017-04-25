<?php
namespace LaraMod\Admin\Blog\Models;
use App\User;
use LaraMod\AdminCore\Scopes\AdminCoreOrderByCreatedAtScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comments extends Model {
    protected $table = 'blog_comments';
    public $timestamps = true;

    use SoftDeletes;

    protected $guarded = ['id'];
    protected $dates = ['deleted_at'];

    protected function bootIfNotBooted(){
        parent::boot();
        static::addGlobalScope(new AdminCoreOrderByCreatedAtScope());
    }

    public function post()
    {
        return $this->hasOne(Posts::class, 'id', 'blog_posts_id');
    }

    public function user(){
        return $this->hasOne(User::class, 'id', 'users_id');
    }

}