@extends('admin.layouts.main')

@section('pageLevelPlugins')
    <link href="{{ asset('assets/global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css') }}" rel="stylesheet" type="text/css" />

    <link href="{{ asset('assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/global/plugins/bootstrap-markdown/css/bootstrap-markdown.min.css') }}">
    <link href="{{ asset('assets/global/plugins/bootstrap-summernote/summernote.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('themeGlobalStyle')
    <link href="{{ asset('assets/global/css/components-rounded.min.css') }}" rel="stylesheet" id="style_components" type="text/css" />
    <link href="{{ asset('assets/global/css/plugins.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/jquery-notific8/jquery.notific8.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('themeLayoutStyle')
    <link href="{{ asset('assets/layouts/layout4/css/layout.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/layouts/layout4/css/themes/light.min.css') }}" rel="stylesheet" type="text/css" id="style_color" />
    <link href="{{ asset('assets/layouts/layout4/css/custom.min.css') }}" rel="stylesheet" type="text/css" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection

@section('title', 'Back-end users - All User Roles')
@section('pageBodyClass','page-container-bg-solid page-header-fixed page-sidebar-closed-hide-logo')

@section('pageContentBody')
    <div class="page-content fix_padding_top_0">
        <div class="row">
            <div class="col-md-12">
                <div class="portlet light bordered">
                    <div class="portlet-body">
                        <div class="row form-group">
                            <div class="col-md-12">
                                <div class="btn-group">
                                    <a href="/admin/templates_email/add" class="btn sbold green">Add new template <i class="fa fa-plus"></i></a>
                                </div>
                            </div>
                        </div>

                        <table class="table table-striped table-bordered table-hover table-checkable order-column" id="all_permissions">
                            <thead>
                            <tr>
                                <th> № </th>
                                <th> Hook </th>
                                <th> Variables </th>
                                <th> Country </th>
                                <th> Edit </td>
                                <th> Delete </th>
                            </tr>
                            </thead>
                            <tbody>
                            @for($i = 0; $i < count($templates); $i++)
                                <tr class="odd gradeX">
                                    <td> {{ $i + 1 }} </td>
                                    <td>
                                        {{ $templates[$i]["hook"] }}
                                    </td>
                                    <td>
                                        @foreach($templates[$i]["variables"] as $index => $var)
                                            <span class="label label-default label-var">{{ $var }}</span>
                                        @endforeach
                                    </td>
                                    <td>
                                        {{ $templates[$i]["country"]->name }}
                                    </td>
                                    <td class="text-center">
                                        <a href="/admin/templates_email/edit/{{ $templates[$i]['id'] }}" class="edit">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <a href="javascript:void(0);"  data-id="{{ $templates[$i]['id'] }}" class="delete">
                                            <i class="fa fa-trash-o"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endfor
                            @if ( ! $templates)
                                <tr>
                                    <td colspan="6">
                                        No records found
                                    </td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="confirm-delete-template">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Delete Template</h4>
                </div>
                <div class="modal-body">
                    <p>Really want to delete the template email?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirm-delete">Yes</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('pageBelowLevelPlugins')
    <script src="{{ asset('assets/global/scripts/datatable.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/datatables/datatables.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/jquery-validation/js/jquery.validate.min.js') }}" type="text/javascript"></script>
@endsection

@section('pageBelowLevelScripts')
    <script src="{{ asset('assets/global/plugins/jquery-notific8/jquery.notific8.min.js') }}" type="text/javascript"></script>
@endsection

@section('themeBelowLayoutScripts')
    <script src="{{ asset('assets/layouts/layout4/scripts/layout.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/layouts/layout4/scripts/demo.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/layouts/global/scripts/quick-sidebar.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.js') }}"></script>
    <script src="{{ asset('assets/global/plugins/bootstrap-summernote/summernote.js') }}"></script>
@endsection

@section('pageCustomJScripts')
    <script type="text/javascript">

        $(document).ready(function(){
            $(".delete").click(function(){
                var template_id = $(this).data("id");
                $("#confirm-delete-template").modal("show");
                $("#confirm-delete").click(function(){
                    $.ajax({
                        url: '{{ route('admin/templates_email/delete') }}',
                        type: "post",
                        data: {
                            'id' : template_id
                        },
                        success: function(data){
                            if(data.success)
                            {
                                show_notification(data.title, data.message, 'lime', 3500, 0);
                                setTimeout(function(){
                                    location.reload();
                                },2000);
                            }
                            else
                            {
                                show_notification(data.title, data.errors, 'ruby', 3500, 0);
                            }
                        }
                    });
                });
            });
        });



    </script>
@endsection