@extends('admincore::layouts.dashboard')
@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Comments</h1>
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
                            <th>#</th>
                            <th>Content</th>

                            <th>Status</th>
                            <th>For post</th>
                            <th>Created at</th>
                            <th><i class="fa fa-cogs"></i></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($comments as $comment)
                            <tr>
                                <td>
                                    {{$comment->id}}
                                </td>
                                <td>
                                    {{$comment->content }}
                                </td>
                                <td>
                                    {!! $comment->isTrashed ? '<i class="fa fa-trash"></i>' : '<i class="fa fa-eye"></i>' !!}
                                </td>
                                <td>
                                    #{{$comment->post ? $comment->post->id : 'Not existing post'}}
                                </td>
                                <td>
                                    {{$comment->created_at->format('d.m.Y H:i')}}
                                </td>
                                <td>
                                    <a href="{{route('admin.blog.comments.form', ['id' => $comment->id])}}" class="btn btn-success btn-xs"><i class="fa fa-pencil"></i></a>
                                    <a href="{{route('admin.blog.comments.delete', ['id' => $comment->id])}}" class="btn btn-danger btn-xs require-confirm"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="6">{{$comments->links()}}</td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

    </div>
@stop