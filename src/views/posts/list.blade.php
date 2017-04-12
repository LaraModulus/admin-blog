@extends('admincore::layouts.dashboard')
@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Posts</h1>
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
                                <a href="{{route('admin.blog.posts.form')}}" class="btn btn-md btn-primary">Create</a>
                            </td>
                        </tr>
                        <tr>
                            <th>#</th>
                            <th>Title</th>

                            <th>Status</th>
                            <th>Views</th>
                            <th>Publish date</th>
                            <th><i class="fa fa-cogs"></i></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($posts as $post)
                            <tr>
                                <td>
                                    {{$post->id}}
                                </td>
                                <td>
                                    {{$post->{'title_'.config('app.fallback_locale', 'en')} }}
                                </td>
                                <td>
                                    {{$post->status}}
                                </td>
                                <td>
                                    {{$post->views}}
                                </td>
                                <td>
                                    {{$post->publish_date->format('d.m.Y H:i')}}
                                </td>
                                <td>
                                    <a href="{{route('admin.blog.posts.form', ['id' => $post->id])}}" class="btn btn-success btn-xs"><i class="fa fa-pencil"></i></a>
                                    <a href="{{route('admin.blog.posts.delete', ['id' => $post->id])}}" class="btn btn-danger btn-xs require-confirm"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="6">{{$posts->links()}}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

    </div>
@stop