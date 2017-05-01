<?php

namespace LaraMod\Admin\Blog\Controllers;

use App\Http\Controllers\Controller;
use LaraMod\Admin\Blog\Models\Comments;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class CommentsController extends Controller
{

    private $data = [];

    public function __construct()
    {
        config()->set('admincore.menu.blog.active', true);
    }

    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            return Comments::paginate(20);
        }

        return view('adminblog::comments.list');
    }

    public function getForm(Request $request)
    {
        $this->data['comment'] = ($request->has('id') ? Comments::find($request->get('id')) : new Comments());
        if ($request->wantsJson()) {
            return $this->data;
        }

        return view('adminblog::comments.form', $this->data);
    }

    public function postForm(Request $request)
    {

        $comment = Comments::firstOrCreate(['id' => $request->get('id')]);
        try {
            /**
             * TODO: Implement language selection and users id
             */
            $comment->update(array_filter($request->only($comment->getFillable()), function($key) use ($request, $comment){
                return in_array($key, array_keys($request->all())) || @$comment->getCasts()[$key]=='boolean';
            }, ARRAY_FILTER_USE_KEY));
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['errors' => $e->getMessage()]);
        }

        return redirect()->route('admin.blog.comments')->with('message', [
            'type' => 'success',
            'text' => 'Comment saved.',
        ]);
    }

    public function delete(Request $request)
    {
        if (!$request->has('id')) {
            return redirect()->route('admin.blog.comments')->with('message', [
                'type' => 'danger',
                'text' => 'No ID provided!',
            ]);
        }
        try {
            Comments::find($request->get('id'))->delete();
        } catch (\Exception $e) {
            return redirect()->route('admin.blog.comments')->with('message', [
                'type' => 'danger',
                'text' => $e->getMessage(),
            ]);
        }

        return redirect()->route('admin.blog.comments')->with('message', [
            'type' => 'success',
            'text' => 'Comment moved to trash.',
        ]);
    }

    public function dataTable()
    {
        $items = Comments::select([
            'id',
            'author_names',
            'author_email',
            'ip_address',
            'lang',
            'created_at',
            'blog_posts_id',
        ]);

        return DataTables::of($items)
            ->addColumn('action', function ($item) {
                return '<a href="' . route('admin.blog.comments.form', ['id' => $item->id]) . '" class="btn btn-success btn-xs"><i class="fa fa-pencil"></i></a>
                                    <a href="' . route('admin.blog.comments.delete',
                        ['id' => $item->id]) . '" class="btn btn-danger btn-xs require-confirm"><i class="fa fa-trash"></i></a>';
            })
            ->addColumn('post', function ($item) {
                if (!$item->post) {
                    return 'No post';
                }

                return '<a target="_blank" href="' . route('admin.blog.posts.form',
                        ['id' => $item->blog_posts_id]) . '" title="#' . $item->blog_posts_id . ' ' . $item->post->title_en . '">Post #' . $item->blog_posts_id . '</a>';
            })
            ->editColumn('created_at', function ($item) {
                return $item->created_at->format('d.m.Y H:i');
            })
            ->orderColumn('created_at $1', 'id $1')
            ->make('true');
    }


}