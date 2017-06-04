@extends('admincore::layouts.dashboard')

@section('content')
    <div id="page-wrapper" data-ng-app="App" data-ng-controller="blogController">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Blog posts</h1>
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
        <form action="{{route('admin.blog.posts.form', ['id' => $post->id])}}" method="post" role="form">
            <div class="row">
                <div class="col-md-9">
                    <!-- TAB NAVIGATION -->
                    <ul class="nav nav-tabs" role="tablist">
                        @foreach(config('app.locales', ['en']) as $key => $locale)
                            <li @if($key==0) class="active" @endif><a href="#{{$locale}}" role="tab"
                                                                      data-toggle="tab">{{$locale}}</a></li>
                        @endforeach
                    </ul>
                    <!-- TAB CONTENT -->
                    <div class="tab-content">
                        @foreach(config('app.locales', ['en']) as $key => $locale)
                            <div class="@if($key==0) active fade in @endif tab-pane panel panel-default"
                                 id="{{$locale}}">

                                <div class="panel-body">
                                    <div class="form-group">
                                        <label for="title_{{$locale}}" class="control-label">Title</label>

                                        <input type="text" class="form-control" name="title_{{$locale}}"
                                               id="title_{{$locale}}"
                                               placeholder=""
                                               value="{{old('title_'.$locale, $post->{'title_'.$locale})}}">

                                    </div>
                                    <div class="form-group">
                                        <label for="content_{{$locale}}"
                                               class="control-label">Content </label>

                                        <textarea name="content_{{$locale}}" id="content_{{$locale}}" cols="30"
                                                  rows="10"
                                                  class="form-control editor">{{old('content_'.$locale, $post->{'content_'.$locale})}}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="excerpt_{{$locale}}"
                                               class="control-label">Excerpt </label>

                                        <textarea name="excerpt_{{$locale}}" id="excerpt_{{$locale}}" cols="30" rows="3"
                                                  class="form-control ">{{old('excerpt_'.$locale, $post->{'excerpt_'.$locale})}}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="meta_title_{{$locale}}" class="control-label">Meta title</label>

                                        <input type="text" class="form-control" name="meta_title_{{$locale}}"
                                               id="meta_title_{{$locale}}"
                                               placeholder=""
                                               value="{{old('meta_title_'.$locale, $post->{'meta_title_'.$locale})}}">

                                    </div>
                                    <div class="form-group">
                                        <label for="meta_description_{{$locale}}"
                                               class="control-label">Meta Description </label>

                                        <textarea name="meta_description_{{$locale}}" id="meta_description_{{$locale}}"
                                                  cols="30" rows="3"
                                                  class="form-control ">{{old('meta_description_'.$locale, $post->{'meta_description_'.$locale})}}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="meta_keywords_{{$locale}}" class="control-label">Meta
                                            keywords</label>

                                        <input type="text" class="form-control" name="meta_keywords_{{$locale}}"
                                               id="meta_keywords_{{$locale}}"
                                               placeholder=""
                                               data-role="tagsinput"
                                               value="{{old('meta_keywords_'.$locale, $post->{'meta_keywords_'.$locale})}}">

                                    </div>

                                </div>

                            </div>
                        @endforeach
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-body">

                            <div class="form-group">
                                <label for="tags">Tags</label>
                                <input class="form-control" name="tags" id="tags" autocomplete="off"
                                       @if($post)
                                       value="{{implode(',', $post->tags->pluck('name')->toArray())}}"
                                       @endif
                                       data-url="{{route('admin.blog.posts.tags')}}?tagsinput=true">
                            </div>
                            <div class="form-group">
                                <label for="series_ids">Series</label>
                                {{--<input type="hidden" id="products_ids" name="products">--}}
                                <select multiple class="form-control" name="series[]" id="series_ids">
                                    @foreach($post->series as $s)
                                        <option value="{{$s->id}}">{{$s->title_en}}</option>
                                    @endforeach
                                </select>
                            </div>
                                    <div class="form-group">
                                        <label for="slug">Slug</label>
                                        <div class="input-group">
                                            <div class="input-group-addon">{{url('/blog/first-category-slug-cid/')}}/</div>
                                            <input pattern="[A-z0-9\-]+" type="text" name="slug" id="slug" class="form-control" value="{{old('slug', $post->slug)}}" placeholder="Example: hello-world-123. Set automatically if empty.">
                                            <div class="input-group-addon">-{{($post->id ? $post->id : $post->max('id') + 1)}}</div>
                                        </div>
                                    </div>
                        </div>
                    </div>
                    @if(class_exists(\LaraMod\Admin\Files\AdminFilesServiceProvider::class))
                        <div class="panel panel-default" data-ng-controller="filesContainerController">
                            <div class="panel-body">
                                <div data-ng-class="{hidden: !files.item_files.length}">
                                    <ul class="list-inline files-list" data-ng-if="files.item_files.length">
                                        <li class="item" data-ng-repeat="file in files.item_files track by $index">

                                            <div class="text-center image-block">
                                                <div class="editor-block text-right">
                                                    <button type="button" class="btn btn-xs btn-primary"
                                                            data-ng-click="editFile($index)"><i
                                                                class="fa fa-pencil"></i></button>
                                                    <button type="button" class="btn btn-xs btn-danger"
                                                            data-ng-click="removeFile($index)"><i
                                                                class="fa fa-times"></i></button>
                                                </div>
                                                <img src="@{{ file.thumb.encoded }}" alt="@{{ file.filename }}">
                                            </div>
                                            <div class="help-block small"
                                                 title="@{{ file.filename }} (@{{ file.file_size | bytes}})">
                                                @{{ file.filename }} (@{{ file.file_size | bytes}})
                                            </div>
                                        </li>

                                    </ul>
                                    <hr>
                                </div>
                                <button class="btn btn-primary btn-md" data-target="#filesModal" data-toggle="modal"
                                        type="button">Add files
                                </button>
                            </div>
                            <div class="modal fade" id="filesModal">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                                &times;
                                            </button>
                                            <h4 class="modal-title">Add file</h4>
                                        </div>
                                        <div class="modal-body">
                                            @include('adminfiles::_partials.manager')
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close
                                            </button>
                                            <button type="button" class="btn btn-primary"
                                                    data-ng-click="addSelectedFiles()">Add file
                                            </button>
                                        </div>
                                    </div><!-- /.modal-content -->
                                </div><!-- /.modal-dialog -->
                            </div><!-- /.modal -->
                        </div>

                    @endif

                </div>
                <div class="col-md-3">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="form-group">
                                <label for="publish_date">Publish date</label>
                                <input type="text" name="publish_date" id="publish_date" class="form-control datetimepicker"
                                       value="{{old('publish_date', $post->publish_date)}}">
                            </div>
                            <div class="form-group">
                                <label for="viewable">Visible?</label>
                                <div class="checkbox">
                                    <input type="checkbox" value="1" id="viewable" name="viewable"
                                           @if($post->viewable || !$post->id) checked @endif>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="allow_comments">Allow comments?</label>
                                <div class="checkbox">
                                    <input type="checkbox" value="1" id="allow_comments" name="allow_comments"
                                           @if($post->allow_comments || !$post->id) checked @endif>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="post_categories">Categories</label>

                                <select class="form-control select2" name="post_categories[]" id="post_categories"
                                        multiple tabindex="-1" aria-hidden="true">
                                    @foreach(\LaraMod\Admin\Blog\Models\Categories::all() as $category)
                                        <option value="{{$category->id}}"
                                                @if(in_array($category->id, $post->categories->pluck('id')->toArray())) selected @endif
                                        >{{$category->{'title_'.config('app.fallback_locale', 'en')} }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <textarea class="hidden" name="files" id="files_input">@{{ files.item_files }}</textarea>
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-primary btn-lg btn-block">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>


    </div>
    @if(class_exists(\LaraMod\Admin\Files\AdminFilesServiceProvider::class))
        <script>
            app.controller('blogController', function ($scope, $http, SweetAlert, CSRF_TOKEN, $window, Files) {
                $scope.files = Files;
                $scope.files_loading = false;

                $scope.$watch($scope.files, function (newVal, oldVal) {
                    Files = $scope.files;
                }, true);

                $http.get(window.location.href)
                    .then(function (response) {
                        if (response.data.post.files) {
                            $scope.files.item_files = response.data.post.files;
                        }
                    });
                $scope.removeFile = function (idx) {
                    $scope.files.item_files.splice(idx, 1);
                };

            });

        </script>
    @endif
@stop
@section('js')
    <script>
        $(function(){
            $('#tags').tagsInput({
                width: '100%',
                height: 'auto',
                autocomplete_url: $('#tags').data('url'),
                autocomplete: {
                    selectFirst: true,
                    autoFill: true
                },
                interactive: true,
                minChars: 2
            });
        });
        function formatItems (item) {
            if (item.loading) return item.text;

            var markup = '<ul class="list-unstyled">' +
                '<li>['+item.id+'] ' + item.title_en + '</li>';

            markup += '</ul>';

            return markup;
        }

        function formatItemsSelection (item) {
            return item.title_en;
        }

        $(document).ready(function(){

            $("#series_ids").select2({
                theme: 'bootstrap',
                multiple: true,
                data: {!! $post->series()->select(['id','title_en'])->get()->map(function($post){
                        return [
                            "id" => $post->id,
                            "title_en" => $post->title_en
                        ];
                    }) !!},
                ajax: {
                    url: "{{route('admin.blog.series')}}",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term, // search term
                            page: params.page || 1
                        };
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;
                        return {
                            results: data.items.data,
                            pagination: {
                                more: (params.page * 20) < data.items.total
                            }
                        };
                    },
                    cache: true
                },
                escapeMarkup: function (markup) { return markup; },
                minimumInputLength: 1,
                templateResult: formatItems,
                templateSelection: formatItemsSelection //

            })
                .val({!! $post->series->pluck('id') !!})
                .trigger('change');
        });
    </script>
@stop