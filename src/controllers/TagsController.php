<?php

namespace LaraMod\Admin\Blog\Controllers;

use App\Http\Controllers\Controller;
use \LaraMod\Admin\Blog\Models\Tags;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class TagsController extends Controller
{

    private $data = [];

    public function __construct()
    {
        config()->set('admincore.menu.blog.active', true);
    }

    public function index()
    {
        $this->data['items'] = Tags::paginate(20);

        return view('adminblog::tags.list', $this->data);
    }

    public function getForm(Request $request)
    {
        $this->data['item'] = ($request->has('id') ? Tags::find($request->get('id')) : new Tags());

        return view('adminblog::tags.form', $this->data);
    }

    public function postForm(Request $request)
    {

        $item = Tags::firstOrCreate(['id' => $request->get('id')]);
        try {
            $item->autoFill($request);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['errors' => $e->getMessage()]);
        }

        return redirect()->route('admin.blog.tags')->with('message', [
            'type' => 'success',
            'text' => 'Item saved.',
        ]);
    }

    public function delete(Request $request)
    {
        if (!$request->has('id')) {
            return redirect()->route('admin.blog.tags')->with('message', [
                'type' => 'danger',
                'text' => 'No ID provided!',
            ]);
        }
        try {
            Tags::find($request->get('id'))->delete();
        } catch (\Exception $e) {
            return redirect()->route('admin.blog.tags')->with('message', [
                'type' => 'danger',
                'text' => $e->getMessage(),
            ]);
        }

        return redirect()->route('admin.blog.tags')->with('message', [
            'type' => 'success',
            'text' => 'Item moved to trash.',
        ]);
    }

    public function dataTable()
    {
        $items = Tags::select(['id', 'created_at', 'name']);

        return DataTables::of($items)
            ->addColumn('action', function ($item) {
                return '<a href="' . route('admin.blog.tags.form',
                        ['id' => $item->id]) . '" class="btn btn-success btn-xs"><i class="fa fa-pencil"></i></a>'
                    . '<a href="' . route('admin.blog.tags.delete',
                        ['id' => $item->id]) . '" class="btn btn-danger btn-xs require-confirm"><i class="fa fa-trash"></i></a>';
            })
            ->editColumn('created_at', function ($item) {
                return $item->created_at->format('d.m.Y H:i');
            })
            ->orderColumn('created_at $1', 'id $1')
            ->make('true');
    }


}