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
    <div class="page-content">
        <div class="row">
            <div class="col-md-12">
                <div class="portlet light portlet-fit portlet-datatable bordered">
                    <div class="portlet-title">
                        <div class="pull-left back-link">
                            <a href="javascript:void(0);" data-href="/admin/templates_email/list_all" class="back">
                                <i class="fa fa-chevron-left"></i>
                            </a>
                        </div>
                        <div class="caption">
                            Add new template
                        </div>
                    </div>
                    <div class="portlet-body">
                         <form class="form-horizontal" id="add_new_template_form">
                            <div class="row">
                                <div class="col-sm-12 form-group">
                                    <label for="title" class="col-sm-2 control-label">Title</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control"  name="title" placeholder="Title" />
                                    </div>
                                </div>

                                <div class="col-sm-12 form-group">
                                    <label for="content" class="col-sm-2 control-label">Country</label>
                                    <div class="col-sm-10">
                                        <select class="form-control" name="country">
                                            <option value="">Select country</option>
                                            @foreach($country_list as $country)
                                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                                            @endforeach
                                        </select>
                                    </div> 
                                </div>

                                <div class="col-sm-12 form-group">
                                    <div class="col-sm-2 control-label">
                                        Hook
                                    </div>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" placeholder="Hook" name="hook" />
                                    </div>
                                </div>

                                <div class="col-sm-12 form-group">
                                    <div class="col-sm-2 control-label">
                                        Variables
                                    </div>
                                    <div class="col-sm-10">
                                        <select data-placeholder="Select or add variables" name="variables" class="form-control select2-multiple" multiple>
                                            @foreach ($variables as $index => $var)
                                                <option value="{{ $index }}">{{ $var }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-12 form-group">
                                    <div class="col-sm-2"></div>
                                    <div class="col-sm-4">
                                        <input type="text" id="name-var" class="form-control" placeholder="Name" />
                                    </div>
                                    <div class="col-sm-5">
                                        <input type="text" id="value-var" class="form-control" placeholder="Value" />
                                    </div>
                                    <div class="col-sm-1 text-right">
                                        <button type="button" id="add-var" class="btn btn-default btn-block">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="col-sm-12 form-group">
                                    <label for="content" class="col-sm-2 control-label">Description</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" rows="3" name="content" placeholder="Description"></textarea>
                                    </div> 
                                </div>

                                <div class="col-sm-12 form-group">
                                    <div class="col-sm-12">
                                        <textarea class="form-control"  name="description">{{ isset($template->description) ? $template->description : '' }}</textarea>
                                    </div>
                                </div>
                            
                                <div class="col-sm-12 form-group">
                                    <div class="col-sm-2 pull-right">
                                        <button type="submit" class="btn btn-block btn-primary">Add</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>  
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="confirm-exit-without-save">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Exit without save?</h4>
                </div>
                <div class="modal-body">
                    <p>This action will not save the changes you have apllied to this template</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <a href="/admin/templates_email/list_all" class="btn btn-primary">Exit</a>
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
        var FormValidation = function () {

            $.validator.addMethod("description", function(value, element) {
                return value && value.length ? true : false;
            }, "Please enter description");

             $.validator.addMethod("variables", function(value, element) {
                return $.trim($(element).text()) ? true : false;
            }, "Please enter variables");

            var handleValidation = function() 
            {
                var form = $('#add_new_template_form');
                var error = $('.alert-danger', form);
                var success = $('.alert-success', form);

                form.validate({
                    errorElement: 'span',
                    errorClass: 'help-block help-block-error',
                    focusInvalid: false,
                    ignore: [],
                    rules: {
                        title: {
                            required: true
                        },
                        content : {
                            required: false
                        },
                        country: {
                            required: true
                        },
                        variables : {
                            variables : true,
                            required : true
                        },
                        hook : {
                            required : true
                        },
                        description : {
                            required : true,
                            description : true
                        }
                    },
                    messages : {
                        title: {
                            minlength: "Your title must consist of at least 5 characters",
                            required: "Please enter title"
                        },
                        content: {
                            minlength: "Your title must consist of at least 5 characters",
                            required: "Please enter title"
                        },
                        variables: {
                            required: "Please add variables"
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


        function add_new_template()
        {
            var variables = {};
            $("select[name=variables] option:selected").each(function(k, v){
                variables[$(v).attr('value')] = $(v).text();
            });

            $.ajax({
                url: '{{ route('admin/templates_email/create') }}',
                type: "post",
                data: {
                    'title'       :  $('input[name=title]').val(),
                    'country_id'  :  $('select[name=country]').val(),
                    'content'     :  $('textarea[name=content]').val(),
                    'hook'        :  $('input[name=hook]').val(),
                    'variables'   :  variables,
                    'description' :  $('textarea[name=description]').val()
                },
                success: function(data){
                    if(data.success)
                    {
                        show_notification(data.title, data.message, 'lime', 3500, 0);
                        setTimeout(function(){
                            location.href = "/admin/templates_email/list_all";
                        },2000);
                    }
                    else
                    {
                        change = false;
                        for(var i in data.errors)
                        {
                            show_notification(data.title, data.errors[i], 'ruby', 3500, 0);
                        }
                        
                    }
                }
            });
        }

        var change = false;
        function action()
        {
            change = true;
        }

        $(document).ready(function(){
            FormValidation.init();
            $("textarea[name=description]").summernote({ height : 300 , onChange : function(e){
                
                var element = $("textarea[name=description]");
                var parent = $(element).closest(".form-group");

                if( ! $(element).summernote('isEmpty'))
                {
                    parent.removeClass("has-error").addClass("has-success");
                }
                else
                {
                   parent.removeClass("has-success").addClass("has-error");
                }

            }});

            $('select[name=variables]').select2();

            $("select[name=variables]").change(function() {
                var element = $(this);
                var parent = $(element).closest(".form-group");
                
                if($(element).val())
                {
                    parent.removeClass("has-error").addClass("has-success");
                }
                else
                {
                   parent.removeClass("has-success").addClass("has-error");
                }
            });

            $('input[name=title]').change(action);
            $('select[name=country]').change(action);
            $('textarea[name=content]').change(action);
            $('input[name=hook]').change(action);
            $('textarea[name=description]').change(action);
            
            $("#add-var").click(function(){
                var name = $("#name-var").val(),
                    value = $("#value-var").val();
                $("select[name=variables]").append(new Option(name, value, true, true)).trigger('change');
                $("#name-var").val("");
                $("#value-var").val("");
            });

            $(".back").click(function(event){
                event.preventDefault();
                if (change)
                {
                    $("#confirm-exit-without-save").modal("show");
                }
                else
                {
                    window.location.href = $(this).data("href");                
                }
            });

        });
    </script>
@endsection