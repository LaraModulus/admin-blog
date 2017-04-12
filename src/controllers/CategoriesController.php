<?php
namespace LaraMod\AdminBlog\Controllers;

use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use LaraMod\AdminBlog\Models\Blog\Categories;
use LaraMod\AdminBlog\Models\Blog\Posts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CategoriesController extends Controller
{

    private $data = [];
    public function __construct()
    {
        config()->set('admincore.menu.blog.active', true);
    }

    public function index()
    {
        $this->data['categories'] = Categories::paginate(20);
        return view('adminblog::categories.list', $this->data);
    }

    public function getForm(Request $request)
    {
        $this->data['category'] = ($request->has('id') ? Categories::find($request->get('id')) : new Categories());
        return view('adminblog::categories.form', $this->data);
    }

    public function postForm(Request $request)
    {

        $category = $request->has('id') ? Categories::find($request->get('id')) : new Categories();
        try{
            foreach(config('app.locales', [config('app.fallback_locale', 'en')]) as $locale){
                $category->{'title_'.$locale} = $request->get('title_'.$locale);
                $category->{'content_'.$locale} = $request->get('content_'.$locale);
                $category->{'meta_title_'.$locale} = $request->get('meta_title_'.$locale);
                $category->{'meta_description_'.$locale} = $request->get('meta_description_'.$locale);
                $category->{'meta_keywords_'.$locale} = $request->get('meta_keywords_'.$locale);
            }
            $category->viewable = $request->get('visible', 0);
            $category->save();
        }catch (\Exception $e){
            return redirect()->back()->withInput()->withErrors(['errors' => $e->getMessage()]);
        }

        return redirect()->route('admin.blog.categories')->with('message', [
            'type' => 'success',
            'text' => 'Category saved.'
        ]);
    }

    public function delete(Request $request){
        if(!$request->has('id')){
            return redirect()->route('admin.blog.categories')->with('message', [
                'type' => 'danger',
                'text' => 'No ID provided!'
            ]);
        }
        try {
            Categories::find($request->get('id'))->delete();
        }catch (\Exception $e){
            return redirect()->route('admin.blog.categories')->with('message', [
                'type' => 'danger',
                'text' => $e->getMessage()
            ]);
        }

        return redirect()->route('admin.blog.categories')->with('message', [
            'type' => 'success',
            'text' => 'Categories moved to trash.'
        ]);
    }


}