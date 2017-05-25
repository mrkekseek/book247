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
                        <div class="caption">
                             <a href="/admin/templates_email/list_all" class="back">
                                <i class="fa fa-chevron-left"></i>
                            </a>
                            Edit template
                        </div>
                    </div>
                    <div class="portlet-body">
                         <form class="form-horizontal" id="add_new_template_form">
                            <div class="row">
                                <input type="hidden" name="isset_default" value="{{ $isset_default }}" />
                                <div class="col-sm-12 form-group">
                                    <label for="title" class="col-sm-2 control-label">Title</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" value="{{ isset($template->title) ? $template->title : '' }}" name="title" placeholder="Title">
                                    </div>
                                </div>

                                <div class="col-sm-12 form-group">
                                    <label for="content" class="col-sm-2 control-label">Country</label>
                                    <div class="col-sm-10">
                                        <select class="form-control" name="country">
                                            <option value="">Select country</option>
                                            @foreach($country_list as $country)
                                                <option @if ($country->id == $template->country_id) selected="selected" @endif value="{{ $country->id }}">{{ $country->name }}</option>
                                            @endforeach
                                        </select>
                                    </div> 
                                </div>

                               <!-- <div class="col-sm-12 form-group">        
                                    <label for="hook" class="col-sm-2 control-label">Hook</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" value="{{ isset($template->hook) ? $template->hook : '' }}" name="hook" placeholder="Hook">
                                    </div>
                                </div>-->


                                <div class="col-sm-12 form-group">
                                    <label for="content" class="col-sm-2 control-label">Description</label>
                                    <div class="col-sm-10">
                                        <textarea name="content" placeholder="Description" rows="3" class="form-control">{{ isset($template->content) ? $template->content : '' }}</textarea>
                                    </div> 
                                </div>

                                @if( ! $isset_default)

                                <div class="col-sm-12 form-group">
                                    <div class="col-sm-2 control-label">
                                        Variables
                                    </div>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" placeholder="var1, var2, var3 ..." name="variables" value="{{ isset($template->variables) ? implode(json_decode($template->variables), ', ')  : '' }}" />
                                    </div>
                                </div>
                                
                                @endif

                                <div class="col-sm-12 form-group">
                                    <div class="col-sm-12">
                                        <textarea class="form-control"  name="description">{{ isset($template->description) ? $template->description : '' }}</textarea>
                                    </div>
                                </div>

                                @if($isset_default and count($variables = json_decode($template->variables)))
                                    <div class="col-sm-12 form-group">
                                        <label class="col-sm-2 control-label">Variables:</label>
                                        <div class="col-sm-10">
                                            @foreach($variables as $var)
                                                <button type="button" class="btn btn-sm btn-default btn-vars" data-value="{{ $var }}">{{ $var }}</button>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            
                                <div class="col-sm-12 form-group">
                                    <div class="col-sm-2 pull-right">
                                        <button type="submit" class="btn btn-block btn-success">Update</button>
                                    </div>
                                    @if ($isset_default)
                                    <div class="col-sm-2 pull-right">
                                        <button type="button" class="btn btn-block btn-default" data-toggle="modal" data-target='#confirm-reset-default'>Reset to default</button>
                                    </div>
                                    @else
                                    <div class="col-sm-2 pull-right">
                                        <button type="button" class="btn btn-block btn-info" data-toggle="modal" data-target='#confirm-make-default'>Make default</button>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>  
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="confirm-reset-default">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Reset to default</h4>
                </div>
                <div class="modal-body">
                    <p>This action will reset the editted template to default and all the content will be replaced</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary btn-reset-default">Ok</button>
                </div>
            </div>
        </div>
    </div>

     <div class="modal fade" id="confirm-make-default">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Make default</h4>
                </div>
                <div class="modal-body">
                    <p>This action will make this template like default</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary btn-make-default">Ok</button>
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
            var handleValidation = function() 
            {
                var form = $('#add_new_template_form');
                var error = $('.alert-danger', form);
                var success = $('.alert-success', form);

                $.validator.addMethod("description", function(value, element) {
                    return value && value.length ? true : false;
                }, "Please enter description");

                form.validate({
                    errorElement: 'span',
                    errorClass: 'help-block help-block-error',
                    focusInvalid: false,
                    ignore: "",
                    rules: {
                        title: {
                            required: true
                        },
                        country : 
                        {
                            required: true
                        },
                        content: {
                            required: false
                        },
                        hook: {
                            required: true
                        },
                        description: {
                            required: true,
                            description : true
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
                        update_template();
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

            $(".btn-vars").click(function(){
               $("textarea[name=description]").summernote('editor.saveRange');
               $("textarea[name=description]").summernote('editor.restoreRange');
               $("textarea[name=description]").summernote('editor.focus');
               $("textarea[name=description]").summernote('editor.insertText', '[[' + $(this).data("value") + ']]');
            });


            $(".btn-make-default").click(function(){
                 $.ajax({
                    url: '/admin/templates_email/make_default/{{ $template_id }}',
                    type: "get",
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
            })

            $(".btn-reset-default").click(function(){
                $.ajax({
                    url: '/admin/templates_email/reset_default/{{ $template_id }}',
                    type: "get",
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

        function update_template()
        {
            $.ajax({
                url: '/admin/templates_email/update/{{ $template_id }}',
                type: "post",
                data: {
                    'title'         :  $('input[name=title]').val(),
                    'content'       :  $('textarea[name=content]').val(),
                    'country_id'    :  $('select[name=country]').val(),
                    //'hook'          :  $('input[name=hook]').val(),
                    'variables'     :  $('input[name=variables]').val(),
                    'description'   :  $('textarea[name=description]').val(),
                    'isset_default' :  $('input[name=isset_default]').val()
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
                        for(var i in data.errors)
                        {
                            show_notification(data.title, data.errors[i], 'ruby', 3500, 0);
                        }
                    }
                }
            });
        }

    </script>
@endsection