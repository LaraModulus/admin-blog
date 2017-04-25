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
                    <table class="table table-striped" id="items_table"
                           data-page-length="10">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Author</th>
                            <th>Email</th>
                            <th>IP</th>
                            <th>Language</th>
                            <th>Created at</th>
                            <th>Post</th>
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
                ajax: '{!! route('admin.blog.comments.datatable') !!}',
                order: [
                    [5, 'desc']
                ],
                columns: [
                    {data:'id', name: 'ID'},
                    {data:'author_names', name: 'author_names'},
                    {data:'author_email', name:'author_email'},
                    {data:'ip_address', name: 'ip_address'},
                    {data:'lang', name:'lang', searchable:false},
                    {data:'created_at', name: 'created_at',searchable:false},
                    {data: 'post', name:'post', searchable:false,sortable:false},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            });
        });
    </script>
@stop