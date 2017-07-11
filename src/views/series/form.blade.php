@extends('admincore::layouts.dashboard')

@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Series</h1>
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
        <form action="{{route('admin.blog.series.form', ['id' => $item->id])}}" method="post" role="form">
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
                                               value="{{old('title_'.$locale, $item->{'title_'.$locale})}}">

                                    </div>
                                    <div class="form-group">
                                        <label for="sub_title_{{$locale}}" class="control-label">Sub title</label>

                                        <input type="text" class="form-control" name="sub_title_{{$locale}}"
                                               id="sub_title_{{$locale}}"
                                               placeholder=""
                                               value="{{old('sub_title_'.$locale, $item->{'sub_title_'.$locale})}}">

                                    </div>
                                    <div class="form-group">
                                        <label for="short_description_{{$locale}}"
                                               class="control-label">Short description </label>

                                        <textarea name="short_description_{{$locale}}" id="short_description_{{$locale}}" cols="30"
                                                  rows="10"
                                                  class="form-control editor">{{old('short_description_'.$locale, $item->{'short_description_'.$locale})}}</textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="description_{{$locale}}"
                                               class="control-label">Description </label>

                                        <textarea name="description_{{$locale}}" id="description_{{$locale}}" cols="30"
                                                  rows="10"
                                                  class="form-control editor">{{old('description_'.$locale, $item->{'description_'.$locale})}}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="meta_title_{{$locale}}" class="control-label">Meta title</label>

                                        <input type="text" class="form-control" name="meta_title_{{$locale}}"
                                               id="meta_title_{{$locale}}"
                                               placeholder=""
                                               value="{{old('meta_title_'.$locale, $item->{'meta_title_'.$locale})}}">

                                    </div>
                                    <div class="form-group">
                                        <label for="meta_description_{{$locale}}"
                                               class="control-label">Meta Description </label>

                                        <textarea name="meta_description_{{$locale}}" id="meta_description_{{$locale}}"
                                                  cols="30" rows="3"
                                                  class="form-control ">{{old('meta_description_'.$locale, $item->{'meta_description_'.$locale})}}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="meta_keywords_{{$locale}}" class="control-label">Meta
                                            keywords</label>

                                        <input type="text" class="form-control" name="meta_keywords_{{$locale}}"
                                               id="meta_keywords_{{$locale}}"
                                               placeholder=""
                                               data-role="tagsinput"
                                               value="{{old('meta_keywords_'.$locale, $item->{'meta_keywords_'.$locale})}}">

                                    </div>

                                </div>

                            </div>
                        @endforeach
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="form-group">
                                <label for="posts_ids">Posts</label>
                                <select multiple class="form-control" name="posts[]" id="posts_ids">
                                    @foreach($item->posts as $p)
                                        <option value="{{$p->id}}">{{$p->{'title_'.config('app.fallback_locale', 'en')} }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="slug">Slug</label>
                                <div class="input-group">
                                    <div class="input-group-addon">{{url('/blog/s/')}}/</div>
                                    <input pattern="[A-z0-9\-]+" type="text" name="slug" id="slug" class="form-control" value="{{old('slug', $item->slug)}}" placeholder="Example: hello-world-123. Set automatically if empty.">
                                    <div class="input-group-addon">-{{($item->id ? $item->id : $item->max('id') + 1)}}</div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
                <div class="col-md-3">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="form-group">
                                <label for="viewable">Visible?</label>
                                <div class="checkbox">
                                    <input type="checkbox" value="1" id="viewable" name="viewable"
                                           @if($item->viewable || !$item->id) checked @endif>
                                </div>
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
@section('js')
    <script type="text/javascript">
        function formatItems (item) {
            if (item.loading) return item.text;

            var markup = '<ul class="list-unstyled">' +
                '<li>['+item.id+'] ' + item.title_{{config('app.fallback_locale', 'en')}} + '</li>';

            markup += '</ul>';

            return markup;
        }

        function formatItemsSelection (item) {
            return item.title_{{config('app.fallback_locale', 'en')}};
        }

        $(document).ready(function(){

            $("#posts_ids").select2({
                theme: 'bootstrap',
                multiple: true,
                data: {!! $item->posts()->select(['id','title_'.config('app.fallback_locale', 'en')])->get()->map(function($item){
                        return [
                            "id" => $item->id,
                            "title_".config('app.fallback_locale', 'en') => $item->{'title_'.config('app.fallback_locale', 'en')}
                        ];
                    }) !!},
                ajax: {
                    url: "{{route('admin.blog.posts')}}",
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
                .val({!! $item->posts->pluck('id') !!})
                .trigger('change');
        });
    </script>
@stop