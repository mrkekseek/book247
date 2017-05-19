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
                                    <button data-toggle="modal" href='#add_new_template' class="btn sbold green" data-toggle="modal" href="#draggable">Add new template
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <table class="table table-striped table-bordered table-hover table-checkable order-column" id="all_permissions">
                            <thead>
                            <tr>
                                <th> Id </th>
                                <th> Title </th>
                                <th> Content </th>
                                <th> Veriables </th>
                                <th> Country </th>
                                <th></td>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($templates as $template)
                                    <tr class="odd gradeX">
                                        <td> {{ $template["id"] }} </td>
                                        <td> {{ $template["title"] }} </td>
                                        <td> {{ $template["content"] }} </td>
                                        <td> {{ $template["veriables"] }} </td>
                                        <td> {{ $template["country"] }} </td>
                                        <td>
                                            <a href="javascript:void(0);" data-id="{{ $template['id'] }}" class="edit">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="javascript:void(0);" data-id="{{ $template['id'] }}" class="delete">
                                                <i class="fa fa-trash-o"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade" id="add_new_template">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Add new Template</h4>
                </div>
                <form class="form-horizontal" id="add_new_template_form">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                               
                                    <div class="form-group">
                                        <label for="title" class="col-sm-2 control-label">Title</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="title" placeholder="Title">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="content" class="col-sm-2 control-label">Content</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="content" placeholder="Content">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="variables" class="col-sm-2 control-label">Variables</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="variables" placeholder="Variables">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="hook" class="col-sm-2 control-label">Hook</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="hook" placeholder="Hook">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <textarea class="form-control" name="description"></textarea>
                                        </div>
                                    </div>
                                
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Add</button>
                    </div>
                </form>
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
        var FormValidation = function () {
            var handleValidation = function() 
            {
                var form = $('#add_new_template_form');
                var error = $('.alert-danger', form);
                var success = $('.alert-success', form);

                form.validate({
                    errorElement: 'span',
                    errorClass: 'help-block help-block-error',
                    focusInvalid: false,
                    ignore: "",
                    rules: {
                        title: {
                            minlength: 5,
                            required: true
                        },
                        content: {
                            minlength: 5,
                            required: true
                        },
                        hook: {
                            required: true
                        },
                        description: {
                            minlength: 20,
                            required: true
                        }
                    },

                    invalidHandler: function (event, validator) {
                        success.hide();
                        error.show();
                    },

                    errorPlacement: function (error, element) {
                        var icon = $(element).parent('.input-icon').children('i');
                        icon.removeClass('fa-check').addClass("fa-warning");
                        icon.attr("data-original-title", error.text()).tooltip({'container': 'body'});
                    },

                    highlight: function (element) {
                        $(element)
                            .closest('.form-group').removeClass("has-success").addClass('has-error');
                    },

                    success: function (label, element) {
                        var icon = $(element).parent('.input-icon').children('i');
                        $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
                        icon.removeClass("fa-warning").addClass("fa-check");
                    },

                    submitHandler: function (form) {
                        success.show();
                        error.hide();
                        add_new_template();
                    }
                });
            }

            return {
                init: function () {
                    handleValidation();
                }
            };
        }();

        $(document).ready(function(){
            FormValidation.init();
            
            $("textarea[name=description]").summernote({height:300});

            $(".edit").click(function(){
                $.ajax({
                    url: '{{ route('admin/templates_email/edit') }}',
                    type: "post",
                    data: {
                        'id' : $(this).data("id")
                    },
                    success: function(data){
                        clear_modal();
                        $("#add_new_template").modal("show");
                        $("[name=title]").val(data.title);
                        $("[name=content]").val(data.content);
                        $("[name=variables]").val(data.variables);
                        $("[name=hook]").val(data.hook);
                        $("[textarea=description]").val(data.description);
                    }
                });

            });

            $(".delete").click(function(){
                $.ajax({
                    url: '{{ route('admin/templates_email/delete') }}',
                    type: "post",
                    data: {
                        'id' : $(this).data("id")
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

        function clear_modal()
        {
            $("#add_new_template").modal("show");
            $("[name=title]").val('');
            $("[name=content]").val('');
            $("[name=variables]").val('');
            $("[name=hook]").val('');
            $("[textarea=description]").val('');
        }

        function add_new_template()
        {
            $.ajax({
                url: '{{ route('admin/templates_email/create') }}',
                type: "post",
                data: {
                    'title':         $('input[name=title]').val(),
                    'content':         $('input[name=content]').val(),
                    'variables':            $('input[name=variables]').val(),
                    'hook':         $('input[name=hook]').val(),
                    'description':  $('textarea[name=description]').val()
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
        }
    </script>
@endsection