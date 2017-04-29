@extends('admincore::layouts.dashboard')

@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Blog comments</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        @if(count($errors))
            <div class="row">
                <div class="col-xs-12">
                    <div class="alert alert-danger">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{$error}}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
    @endif
    <!-- /.row -->
        <form action="{{route('admin.blog.comments.form', ['id' => $comment->id])}}" method="post" role="form">
            <div class="row">
                <div class="col-md-9">

                    <div class="tab-content">
                        <div class="panel panel-default">

                            <div class="panel-body">
                                <div class="form-group">
                                    <label for="author_names">Author</label>
                                    <input type="text" class="form-control" name="author_names" id="author_names"
                                           value="{{old('author_names', $comment->author_names)}}">
                                </div>
                                <div class="form-group">
                                    <label for="author_url">Author url</label>
                                    <input type="text" class="form-control" name="author_url" id="author_url"
                                           value="{{old('author_url', $comment->author_url)}}">
                                </div>
                                <div class="form-group">
                                    <label for="author_email">Author email</label>
                                    <input type="text" class="form-control" name="author_email" id="author_email"
                                           value="{{old('author_email', $comment->author_email)}}">
                                </div>
                                <div class="form-group">
                                    <label for="ip_address">IP Address</label>
                                    <input type="text" readonly class="form-control" name="ip_address" id="ip_address"
                                           value="{{$comment->ip_address}}">

                                </div>
                                <div class="form-group">
                                    <label for="content"
                                           class="control-label">Content </label>

                                    <textarea name="content" id="content" cols="30" rows="10"
                                              class="form-control editor">{{old('content', $comment->content) }}</textarea>
                                </div>

                            </div>

                        </div>
                    </div>


                </div>
                <div class="col-md-3">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            @if($comment->id)
                                <div class="form-group">
                                    <label>Created at</label>
                                    <p class="form-control-static">{{$comment->created_at->format('d.m.Y H:i')}}
                                        ({{$comment->created_at->diffForHumans()}})</p>
                                </div>
                            @endif
                            <div class="form-group">
                                <label for="lang">Language</label>
                                <select class="form-control" name="lang" id="lang">
                                    @foreach(config('app.locales', [config('app.fallback_locale','en')]) as $lang)
                                        <option value="{{$lang}}"
                                                @if($lang==$comment->lang) selected @endif>{{$lang}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="blog_posts_id">Post</label>
                                <select class="form-control select2" name="blog_posts_id" id="blog_posts_id">
                                    @foreach(\LaraMod\Admin\Blog\Models\Posts::select(['id', 'title_en'])->get() as $post)
                                        <option value="{{$post->id}}"
                                                @if($comment->blog_posts_id==$post->id) selected @endif >{{$post->title_en}}</option>
                                    @endforeach
                                </select>
                            </div>
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-primary btn-lg btn-block">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>


    </div>
@stop