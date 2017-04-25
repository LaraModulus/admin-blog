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
                    <table class="table table-striped" id="items_table"
                           data-page-length="10">
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
                    </table>
                </div>
            </div>
        </div>

    </div>
@stop
@section('js')
    <script type="text/javascript">
        $(function(){
            $('#items_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('admin.blog.categories.datatable') !!}',
                order: [
                    [4, 'desc']
                ],
                columns: [
                    {data:'id', name: 'ID'},
                    {data:'title_en', name: 'title_en'},
                    {data:'posts_count', name:'posts_count', searchable: false, orderable: false},
                    {data:'status', searchable:false, orderable:false},
                    {data:'created_at', searchable:false},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            });
        });
    </script>
@stop