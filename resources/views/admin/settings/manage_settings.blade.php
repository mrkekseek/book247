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

@section('title', 'Back-end users - All list')
@section('pageBodyClass','page-container-bg-solid page-header-fixed page-sidebar-closed-hide-logo')

@section('pageContentBody')
    <div class="page-content">
        <!-- BEGIN PAGE HEAD-->
        <div class="page-head">
            <!-- BEGIN PAGE TITLE -->
            <div class="page-title">
                <h1>{!!$text_parts['title']!!}
                    <small>{!!$text_parts['subtitle']!!}</small>
                </h1>
            </div>
            <!-- END PAGE TITLE -->
        </div>
        <!-- END PAGE HEAD-->
        <!-- BEGIN PAGE BASE CONTENT -->
        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN SAMPLE FORM PORTLET-->
                <div class="portlet light bordered">
                    <div class="portlet-body">
                         <div class="portlet box green">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-cogs"></i>Manage Settings
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="table-scrollable">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th> # </th>
                                                <th> Name </th>
                                                <th> Settings type </th>
                                                <th> Min </th>
                                                <th> Max </th>
                                                <th>  </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($settings as $s)
                                                <tr>
                                                    <td> {{ $s->id }} </td>
                                                    <td> {{ $s->name }} </td>
                                                    <td> {{ $s->data_type ? $data_types[$s->data_type] : "" }} </td>
                                                    @if ($s->data_type == 'numeric')
                                                    <td> {{ $s->min_value }}</td>
                                                    <td> {{ $s->max_value }} </td>
                                                    @else
                                                    <td> </td>
                                                    <td> </td>
                                                    @endif
                                                    @if ($s->constrained)
                                                        <td>
                                                            <form role="form" class="setting_unconstrained" data-id="{{ $s->id }}">
                                                                <div class="col-sm-10">
                                                                    <select class="form-control" name="field_unconstrained">
                                                                        @if ($s->allowed) @foreach($s->allowed as $row)
                                                                            <option value="{{ $row->id }}" @if($s->value == $row->id) selected="selected" @endif>{{ $row->item_value }}</option>
                                                                        @endforeach @endif
                                                                    </select>
                                                                </div>
                                                                <div class="col-sm-2 form-group text-center">
                                                                     <button type="submit" class="btn btn-primary edit-settings btn-sm">
                                                                        <i class="fa fa-save"></i>
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </td>
                                                    @else
                                                        <td>
                                                            <form role="form" class="setting_values" data-min="{{ $s->min_value }}" data-max="{{ $s->max_value }}" data-id="{{ $s->id }}" data-type="{{ $s->data_type }}">
                                                                @if ($s->data_type == 'numeric')
                                                                    <div class="col-sm-10 form-group">
                                                                        <input data-min="{{ $s->min_value }}" data-max="{{ $s->max_value }}" type="text" class="form-control" name="field_numeric" value="{{ $s->value }}" placeholder="{{ $s->min_value }} ... {{ $s->max_value }}"  />
                                                                    </div>
                                                                @elseif ($s->data_type == 'string')
                                                               
                                                                    <div class="col-sm-10 form-group">
                                                                        <input type="text" class="form-control" value="{{ $s->value }}" name="field_string" placeholder="String"  />
                                                                    </div>
                                                               
                                                                @elseif ($s->data_type == 'text')
                                                                
                                                                    <div class="col-sm-10 form-group">
                                                                        <textarea type="text" class="form-control" name="field_text" placeholder="Text">{{ $s->value }}</textarea>
                                                                    </div>
                                                                
                                                                 @elseif ($s->data_type == 'date')
                                                               
                                                                    <div class="col-sm-10 form-group">
                                                                        <input type="text" class="form-control" value="{{ $s->value }}" name="field_date" placeholder="Date"  />
                                                                    </div>
                                                                 @endif
                                                                <div class="col-sm-2 form-group text-center">
                                                                     <button type="submit" class="btn btn-primary edit-settings btn-sm" >
                                                                        <i class="fa fa-save"></i>
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                            @if( ! count($settings))
                                                <tr>
                                                    <td class="text-center" colspan="8">
                                                        Empty list
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
                <!-- END SAMPLE FORM PORTLET-->
            </div>
        </div>
        <!-- END PAGE BASE CONTENT -->
    </div>

@endsection

@section('pageBelowCorePlugins')
    <script src="{{ asset('assets/global/plugins/jquery.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/js.cookie.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/jquery.blockui.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/uniform/jquery.uniform.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}" type="text/javascript"></script>
@endsection

@section('pageBelowLevelPlugins')
    <script src="{{ asset('assets/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/jquery-validation/js/jquery.validate.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/jquery-validation/js/additional-methods.min.js') }}" type="text/javascript"></script>
@endsection

@section('themeBelowGlobalScripts')
    <script src="{{ asset('assets/global/scripts/app.min.js') }}" type="text/javascript"></script>
@endsection

@section('pageBelowLevelScripts')
    <script src="{{ asset('assets/global/plugins/jquery-notific8/jquery.notific8.min.js') }}" type="text/javascript"></script>
@endsection

@section('themeBelowLayoutScripts')
    <script src="{{ asset('assets/layouts/layout4/scripts/layout.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/layouts/layout4/scripts/demo.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/layouts/global/scripts/quick-sidebar.min.js') }}" type="text/javascript"></script>
@endsection

@section('pageCustomJScripts')
    <script type="text/javascript">

         var FormValidationUnconstrained = function () {
            var handleValidation = function(elm) {

                var form = $(elm);
                var error = $('.alert-danger', form);
                var success = $('.alert-success', form);

                form.validate({
                    errorElement: 'span',
                    errorClass: 'help-block help-block-error',
                    focusInvalid: false,
                    ignore: "", 
                    rules: {
                        field_unconstrained : {
                            required : true
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

                    unhighlight: function (element) {

                    },

                    success: function (label, element) {
                        var icon = $(element).parent('.input-icon').children('i');
                        $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
                        icon.removeClass("fa-warning").addClass("fa-check");
                    },

                    submitHandler: function (form) {
                        success.show();
                        error.hide();
                        save_unconstrained_setting(form); 
                    }
                });
            }

            return {
                init: function () {
                    $(".setting_unconstrained").each(function(index, value){
                        handleValidation(value);
                    });
                }
            };
        }();

        $.validator.addMethod('range',function(value, element, param) {
            value = value * 1;
            return (value >= $(element).data("min")) && (value <= $(element).data("max"));
        },"Enter value from min to max");


        var FormValidation = function () {
            var handleValidation = function(elm) {

                var form = $(elm);
                var error = $('.alert-danger', form);
                var success = $('.alert-success', form);

                form.validate({
                    errorElement: 'span',
                    errorClass: 'help-block help-block-error',
                    focusInvalid: false,
                    ignore: "", 
                    rules: {
                        field_text : {
                            required : true
                        },
                        field_numeric: {
                            number : true,
                            required : true,
                            range : true
                        },
                        field_string : {
                            required : true
                        },
                        field_unconstrained : {
                            required : true
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

                    unhighlight: function (element) {

                    },

                    success: function (label, element) {
                        var icon = $(element).parent('.input-icon').children('i');
                        $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
                        icon.removeClass("fa-warning").addClass("fa-check");
                    },

                    submitHandler: function (form) {
                        success.show();
                        error.hide();
                        save_setting(form); 
                    }
                });
            }

            return {
                init: function () {
                    $(".setting_values").each(function(index, value){
                        handleValidation(value);
                    });
                }
            };
        }();


        $(document).ready(function(){
            FormValidation.init();
            FormValidationUnconstrained.init();
        })

        function save_setting(form)
        {
            var inputs = $(form).find(".form-control"),
                id     = $(form).data("id"),
                type   = $(form).data("type");

            $.ajax({
                url : "{{ route('ajax/save_setting_application') }}",
                method : "POST",
                data : {
                    setting_id : id,
                    value :  $(inputs).val()
                },
                success : function(data){
                   if (data.success)
                    {
                        show_notification('Settings update', 'The details entered were correct', 'lime', 3500, 0);
                       
                    }
                    else
                    {
                        show_notification('Settings update ERROR', 'Something went wrong.', 'tangerine', 3500, 0);
                    }
                }
            });
        }

        function save_unconstrained_setting(form)
        {

            $.ajax({
                url : "{{ route('ajax/save_allowed_setting') }}",
                method : "POST",
                data : {
                    setting_id : $(form).data("id"),
                    allowed_id :  $(form).find(".form-control").val()
                },
                success : function(data){
                   if (data.success)
                    {
                        show_notification('Settings update', 'The details entered were correct', 'lime', 3500, 0);
                       
                    }
                    else
                    {
                        show_notification('Settings update ERROR', 'Something went wrong.', 'tangerine', 3500, 0);
                    }
                }
            });
        }
    </script>
@endsection