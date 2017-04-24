<?php
namespace LaraModulus\AdminBlog\Controllers;

use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use LaraModulus\AdminBlog\Models\Blog\Categories;
use LaraModulus\AdminBlog\Models\Blog\Comments;
use LaraModulus\AdminBlog\Models\Blog\Posts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CommentsController extends Controller
{

    private $data = [];
    public function __construct()
    {
        config()->set('admincore.menu.blog.active', true);
    }

    public function index()
    {
        $this->data['comments'] = Comments::paginate(20);
        return view('adminblog::comments.list', $this->data);
    }

    public function getForm(Request $request)
    {
        $this->data['comment'] = ($request->has('id') ? Comments::find($request->get('id')) : new Comments());
        return view('adminblog::comments.form', $this->data);
    }

    public function postForm(Request $request)
    {

        $comment = $request->has('id') ? Comments::find($request->get('id')) : new Comments();
        try{
            $comment->content = $request->get('content');
            $comment->author_names = $request->get('author_names');
            $comment->author_url = $request->get('author_names');
            $comment->author_email = $request->get('author_email');
            $comment->blog_posts_id = $request->get('blog_posts_id');
            /**
             * TODO: Implement language selection and users id
             */
            $comment->lang = $request->get('lang', config('app.fallback_locale'));
            $comment->users_id = $request->get('users_id');
            $comment->save();
        }catch (\Exception $e){
            return redirect()->back()->withInput()->withErrors(['errors' => $e->getMessage()]);
        }

        return redirect()->route('admin.blog.comments')->with('message', [
            'type' => 'success',
            'text' => 'Comment saved.'
        ]);
    }

    public function delete(Request $request){
        if(!$request->has('id')){
            return redirect()->route('admin.blog.comments')->with('message', [
                'type' => 'danger',
                'text' => 'No ID provided!'
            ]);
        }
        try {
            Comments::find($request->get('id'))->delete();
        }catch (\Exception $e){
            return redirect()->route('admin.blog.comments')->with('message', [
                'type' => 'danger',
                'text' => $e->getMessage()
            ]);
        }

        return redirect()->route('admin.blog.comments')->with('message', [
            'type' => 'success',
            'text' => 'Comment moved to trash.'
        ]);
    }


}