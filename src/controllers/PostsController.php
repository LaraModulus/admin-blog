<?php

namespace LaraMod\Admin\Blog\Controllers;

use App\Http\Controllers\Controller;
use LaraMod\Admin\Blog\Models\Posts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use LaraMod\Admin\Blog\Models\Tags;
use Yajra\Datatables\Datatables;

class PostsController extends Controller
{

    private $data = [];

    public function __construct()
    {
        config()->set('admincore.menu.blog.active', true);
    }

    public function index(Request $request)
    {

        $items = Posts::with(['files']);
        if($request->has('q')){
            $items->where('title_en', 'like', '%'.$request->get('q').'%');
        }
        $this->data['items'] = $items->paginate(20);

        if ($request->wantsJson()) {
            return response()->json($this->data);
        }

        return view('adminblog::posts.list', $this->data);
    }

    public function getForm(Request $request)
    {
        $this->data['post'] = ($request->has('id') ? Posts::with(['files'])->find($request->get('id')) : new Posts());
        if ($request->wantsJson()) {
            return response()->json($this->data);
        }

        return view('adminblog::posts.form', $this->data);
    }

    public function postForm(Request $request)
    {

        $post = Posts::firstOrCreate(['id' => $request->get('id')]);
        try {
            if(!$request->has('slug')){
                $request->merge(['slug' => $post->createSlug(
                    $request->get('title_'.config('app.fallback_locale', 'en'))
                )]);
            }
            $post->autoFill($request);

            $post->categories()->sync($request->get('post_categories', []));

            $post_tags = [];
            foreach(explode(',', $request->get('tags')) as $tag){
                $t = Tags::firstOrCreate(['name' => $tag]);
                $post_tags[] = $t->id;
            }
            $post->tags()->sync($post_tags);
            $post->series()->sync($request->get('series', []));

            $files = [];
            if ($request->get('files') && Schema::hasTable('files_relations')) {
                $files_data = json_decode($request->get('files'));

                foreach ($files_data as $f) {
                    $files[$f->id] = [];
                    foreach (config('app.locales', [config('app.fallback_locale', 'en')]) as $locale) {
                        $files[$f->id]['title_' . $locale] = $f->{'title_' . $locale};
                        $files[$f->id]['description_' . $locale] = $f->{'description_' . $locale};
                    }
                }
                $post->files()->sync($files);
            }
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['message' => $e->getMessage()]);
        }

        return redirect()->route('admin.blog.posts')->with('message', [
            'type' => 'success',
            'text' => 'Post saved.',
        ]);
    }

    public function delete(Request $request)
    {
        if (!$request->has('id')) {
            return redirect()->route('admin.blog.posts')->with('message', [
                'type' => 'danger',
                'text' => 'No ID provided!',
            ]);
        }
        try {
            Posts::find($request->get('id'))->delete();
        } catch (\Exception $e) {
            return redirect()->route('admin.blog.posts')->with('message', [
                'type' => 'danger',
                'text' => $e->getMessage(),
            ]);
        }

        return redirect()->route('admin.blog.posts')->with('message', [
            'type' => 'success',
            'text' => 'Post moved to trash.',
        ]);
    }

    public function getTags(Request $request){
        if($request->has('post_id')){
            return Posts::find($request->get('post_id'))->tags;
        }
        $tags = new Tags();
        if($request->has('term')){
            $tags = $tags->where('name','like','%'.$request->get('term').'%');
        }
        if($request->has('tagsinput')){
            $tags = $tags->select(\DB::raw('name as value'));
        }
        return $tags->limit(5)->get();
    }

    public function dataTable()
    {
        $items = Posts::select(['id', 'title_en', 'views', 'publish_date', 'deleted_at', 'viewable']);

        return DataTables::of($items)
            ->addColumn('action', function ($item) {
                return '<a href="' . route('admin.blog.posts.form', ['id' => $item->id]) . '" class="btn btn-success btn-xs"><i class="fa fa-pencil"></i></a>
                                    <a href="' . route('admin.blog.posts.delete',
                        ['id' => $item->id]) . '" class="btn btn-danger btn-xs require-confirm"><i class="fa fa-trash"></i></a>';
            })
            ->editColumn('status', function ($item) {
                switch ($item->status) {
                    case 'published':
                        return '<i class="fa fa-eye"></i>';
                        break;
                    case 'deleted':
                        return '<i class="fa fa-trash"></i>';
                        break;
                    case 'upcoming':
                        return '<i class="fa fa-clock-o"></i>';
                        break;
                    case 'hidden':
                        return '<i class="fa fa-eye-slash"></i>';
                        break;
                    default:
                        return $item->status;
                        break;
                }
            })
            ->editColumn('publish_date', function ($item) {
                return $item->publish_date->format('d.m.Y H:i');
            })
            ->orderColumn('publish_date $1', 'created_at $1')
            ->make('true');
    }

}