@extends('admincore::layouts.dashboard')
@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Categories</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-xs-12">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <td colspan="4">
                                <a href="{{route('admin.blog.categories.form')}}" class="btn btn-md btn-primary">Create</a>
                            </td>
                        </tr>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Posts</th>
                            <th>Status</th>
                            <th>Created at</th>
                            <th><i class="fa fa-cogs"></i></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($categories as $category)
                            <tr>
                                <td>
                                    {{$category->id}}
                                </td>
                                <td>
                                    {{$category->{'title_'.config('app.fallback_locale', 'en')} }}
                                </td>
                                <td>
                                    {{$category->posts->count()}}
                                </td>
                                <td>
                                    {!! !$category->visible ? '<i class="fa fa-eye-slash"></i>' : '<i class="fa fa-eye"></i>' !!}
                                </td>
                                <td>
                                    {{$category->created_at->format('d.m.Y H:i')}}
                                </td>
                                <td>
                                    <a href="{{route('admin.blog.categories.form', ['id' => $category->id])}}" class="btn btn-success btn-xs"><i class="fa fa-pencil"></i></a>
                                    <a href="{{route('admin.blog.categories.delete', ['id' => $category->id])}}" class="btn btn-danger btn-xs require-confirm"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="6">{{$categories->links()}}</td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

    </div>
@stop