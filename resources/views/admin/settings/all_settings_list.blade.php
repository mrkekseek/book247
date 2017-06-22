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
                        <!-- BEGIN BUTTON ADD HEAD-->
                        <div class="row form-group">
                            <div class="col-md-12">
                                <button class="btn btn-primary" data-toggle="modal" href='#add_settings_modal'>
                                    <i class="fa fa-plus"></i>
                                    Add Setting
                                </button>
                                <div class="loader">Loading...</div>
                            </div>
                        </div>
                        <!-- END BUTTON ADD HEAD-->
                         <div class="portlet box green">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-cogs"></i>Company General Settings
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="table-scrollable">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th> # </th>
                                                <th> Name </th>
                                                <th> Desctiption </th>
                                                <th> Type </th>
                                                <th class="text-center"> Min </th>
                                                <th class="text-center"> Max </th>
                                                <th> Add values </th>
                                                <th> Edit </th>
                                                <th> Delete </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($settings as $s)
                                                <tr>
                                                    <td> {{ $s->id }} </td>
                                                    <td> {{ $s->name }} </td>
                                                    <td> {{ $s->description }} </td>
                                                    @if ( ! $s->constrained)
                                                    <td> {{ $data_types[$s->data_type] }} </td>
                                                    <td class="text-center"> {{ $s->min_value }} </td>
                                                    <td class="text-center"> {{ $s->max_value }} </td>
                                                    <td></td>
                                                    @else
                                                    <td colspan="3"></td>
                                                    <td class="text-center">
                                                        <button class="btn btn-success add-items-settings btn-sm" data-id="{{ $s->id }}">
                                                            <i class="fa fa-plus"></i>
                                                        </button>
                                                    </td>
                                                    @endif
                                                    <td> 
                                                        <button class="btn btn-primary edit-settings btn-sm" data-id="{{ $s->id }}">
                                                            <i class="fa fa-edit"></i>
                                                        </button>
                                                    </td>
                                                    <td> 
                                                        <button class="btn btn-danger remove-settings btn-sm" data-id="{{ $s->id }}">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </td>
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

    <!-- BEGIN MODALS -->
        <!-- BEGIN MODAL ADD -->
        <div class="modal" id="add_settings_modal" >
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Add Settings</h4>
                    </div>
                    <form role="form" name="add_setting_form" id="add_setting_form">
                        <div class="modal-body">
                            <div class="form-body">
                                <div class="form-group">
                                    <label>Setting Name</label>
                                    <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-envelope"></i>
                                            </span>
                                        <input type="text" class="form-control input-sm" name="setting_name" placeholder="Enter setting name here"> 
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Setting Internal Name</label>
                                    <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-envelope"></i>
                                            </span>
                                        <input type="text" class="form-control input-sm" name="setting_internal_name" placeholder="Enter setting internal name here; Ex : setting_name_with_underlines "> </div>
                                </div>
                                <div class="form-group">
                                    <label>Short Description</label>
                                    <textarea class="form-control input-sm" name="setting_description" rows="3"></textarea>
                                </div>

                                <div class="form-group select_type">
                                    <label>Setting Type</label>
                                    <select class="form-control input-sm" name="setting_type">
                                        <option value="">Select type</option>
                                       @foreach($data_types as $key => $val)

                                            <option value="{{ $key }}">{{ $val }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group select_type">
                                     <label>Min and Max values (if they exists)</label>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input class="form-control input-sm" placeholder="min value" name="setting_min_val" type="text"> </div>
                                        <div class="col-md-6">
                                            <input class="form-control input-sm" placeholder="max value" name="setting_max_val" type="text"> </div>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label> Is Constrained [has predefined values for selection] </label>
                                    <div class="mt-radio-inline">
                                        <label class="mt-radio">
                                            <input name="setting_constrained" value="yes" type="radio"> Yes
                                            <span></span>
                                        </label>
                                        <label class="mt-radio">
                                            <input name="setting_constrained" value="no" checked="checked" type="radio"> No
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn blue">Add General Setting</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- END MODAL ADD SETTINGS -->
        <!-- BEGIN MODAL EDIT SETTINGS -->
        <div class="modal fade" id="edit_settings_modal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Edit Settings</h4>
                    </div>
                    <form role="form" name="edit_setting_form" id="edit_setting_form">
                        <div class="modal-body">
                                <div class="form-body">
                                    <div class="form-group">
                                        <label>Setting Name</label>
                                        <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-envelope"></i>
                                                </span>
                                            <input type="text" class="form-control input-sm" name="setting_name" placeholder="Enter setting name here"> </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Setting Internal Name</label>
                                        <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-envelope"></i>
                                                </span>
                                            <input type="text" class="form-control input-sm" name="setting_internal_name" placeholder="Enter setting internal name here; Ex : setting_name_with_underlines "> </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Short Description</label>
                                        <textarea class="form-control input-sm" name="setting_description" rows="3"></textarea>
                                    </div>
                                    <div class="form-group select_type">
                                        <label>Setting Type</label>
                                        <select class="form-control input-sm" name="setting_type">
                                            @foreach($data_types as $key => $val)
                                                <option value="{{ $key }}">{{ $val }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group select_type">
                                        <label>Min and Max values (if they exists)</label>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <input class="form-control input-sm" placeholder="min value" name="setting_min_val" type="text"> </div>
                                            <div class="col-md-6">
                                                <input class="form-control input-sm" placeholder="max value" name="setting_max_val" type="text"> </div>
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label> Is Constrained [has predefined values for selection] </label>
                                        <div class="mt-radio-inline">
                                            <label class="mt-radio">
                                                <input name="setting_constrained" value="yes" data-type="1" type="radio" /> Yes
                                                <span></span>
                                            </label>
                                            <label class="mt-radio">
                                                <input name="setting_constrained" value="no"  data-type="0" type="radio" /> No
                                                <span></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary update-settings">Update changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- END MODAL EDIT SETTINGS -->
        <!-- BEGIN MODAL ADD ITEMS SETTINGS -->
            <div class="modal fade" id="add_items_settings">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title">Add Items</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-xs-12">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th> Name </th>
                                                <th> Caption  </th>
                                                <th> Delete </th>
                                            </tr>
                                        </thead>
                                        <tbody id="list_itmes_cation">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer clearfix">
                            <div class="row form-group">
                                <div class="col-md-5">
                                    <input type="text" class="form-control" id="settings_items_name" placeholder="Name ..." />
                                </div>
                                <div class="col-md-5">
                                    <input type="text" class="form-control" id="settings_items_cation" placeholder="Caption ... " />
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-primary btn-block" id="add_items_settings_btn">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <button class="btn btn-primary save-items-settings">Add confined values</button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        <!-- END  MODAL ADD ITEMS SETTINGS -->
    <!-- END MODALS-->

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
        $.validator.addMethod("datePickerDate",function(value, element) {
            // put your own logic here, this is just a (crappy) example
            return value.match(/^\d\d?-\d\d?-\d\d\d\d$/);
        },"Please enter a date in the format dd/mm/yyyy.");

        $.validator.addMethod('filesize',function(value, element, param) {
            // param = size (in bytes)
            // element = element to validate (<input>)
            // value = value of the element (file name)
            return this.optional(element) || (element.files[0].size <= param);
        },"File must be JPG, GIF or PNG, less than 1MB");

        $.validator.addMethod("validate_email",function(value, element) {
            if(/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/.test( value )) {
                return true;
            }
            else {
                return false;
            }
        },"Please enter a valid Email.");

        var FormValidation = function () {
            // validation using icons
            var handleValidation2 = function() {
                // for more info visit the official plugin documentation:
                // http://docs.jquery.com/Plugins/Validation

                var form2 = $('#add_setting_form');
                var error2 = $('.alert-danger', form2);
                var success2 = $('.alert-success', form2);

                form2.validate({
                    errorElement: 'span', //default input error message container
                    errorClass: 'help-block help-block-error', // default input error message class
                    focusInvalid: false, // do not focus the last invalid input
                    ignore: "",  // validate all fields including form hidden input
                    rules: {
                        setting_name: {
                            minlength: 2,
                            required: true
                        },
                        setting_internal_name: {
                            minlength: 2,
                            required: true
                        },
                        setting_description: {
                            minlength: 5,
                            required: true
                        },
                        setting_min_val: {
                            number: true
                        },
                        setting_max_val: {
                            number: true
                        },
                    },

                    invalidHandler: function (event, validator) { //display error alert on form submit
                        success2.hide();
                        error2.show();
                        App.scrollTo(error2, -200);
                    },

                    errorPlacement: function (error, element) { // render error placement for each input type
                        var icon = $(element).parent('.input-icon').children('i');
                        icon.removeClass('fa-check').addClass("fa-warning");
                        icon.attr("data-original-title", error.text()).tooltip({'container': 'body'});
                    },

                    highlight: function (element) { // hightlight error inputs
                        $(element)
                                .closest('.form-group').removeClass("has-success").addClass('has-error'); // set error class to the control group
                    },

                    unhighlight: function (element) { // revert the change done by hightlight

                    },

                    success: function (label, element) {
                        var icon = $(element).parent('.input-icon').children('i');
                        $(element).closest('.form-group').removeClass('has-error').addClass('has-success'); // set success class to the control group
                        icon.removeClass("fa-warning").addClass("fa-check");
                    },

                    submitHandler: function (form) {
                        success2.show();
                        error2.hide();
                        add_new_setting(); // submit the form
                    }
                });
            }

            return {
                //main function to initiate the module
                init: function () {
                    handleValidation2();
                }
            };
        }();

        var FormValidationEdit = function () {
            var handleValidation3 = function() {
            
                var form3 = $('#edit_setting_form');
                var error3 = $('.alert-danger', form3);
                var success3 = $('.alert-success', form3);

                form3.validate({
                    errorElement: 'span',
                    errorClass: 'help-block help-block-error',
                    focusInvalid: false,
                    ignore: "",
                    rules: {
                        setting_name: {
                            minlength: 2,
                            required: true
                        },
                        setting_internal_name: {
                            minlength: 2,
                            required: true
                        },
                        setting_description: {
                            minlength: 5,
                            required: true
                        },
                    },

                    invalidHandler: function (event, validator) {
                        success3.hide();
                        error3.show();
                        App.scrollTo(error3, -200);
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
                        success3.show();
                        error3.hide();
                        edit_setting();
                    }
                });
            }

            return {
                //main function to initiate the module
                init: function () {
                    handleValidation3();
                }
            };
        }();

        var update_id = 0;
        var adds_id = 0;

        $(document).ready(function(){
            
            FormValidation.init();
            
            $("input[name=setting_constrained]").change(function(){
                if ($(this).val() != "yes")
                {
                   $(".select_type").show();
                }
                else
                {
                    $(".select_type, .field").hide();
                    $("[name=setting_type]").val("");
                    $("[name=setting_min_val]").val("");
                    $("[name=setting_max_val]").val("");
                }
            });

            $(".edit-settings").click(function(){
                var id = $(this).data("id");
                update_id = id;
                show_wait();
                $.ajax({
                    url: '{{ route('ajax/get_settings') }}',
                    type: "post",
                    cache: false,
                    data: {
                        'settings_id' : id
                    },
                    success: function (data) {
                        clear();

                        $('#edit_setting_form input[name="setting_name"]').val(data.name);
                        $('#edit_setting_form input[name="setting_internal_name"]').val(data.system_internal_name);
                        $('#edit_setting_form textarea[name="setting_description"]').val(data.description);
                        
                        
                        $('#edit_setting_form select[name="setting_type"] option').each(function(index, value){
                            if( $(value).attr("value") == data.data_type)
                            {
                                $(value).prop("selected", true);
                            }
                        });

                        if ($("[name='setting_constrained']").val() == "yes")
                        {
                            $(".select_type").hide();
                        }

                        $('#edit_setting_form input[name="setting_constrained"]').each(function(index, value){
                            if ($(value).data("type") == data.constrained)
                            {
                                $(value).parent().addClass("checked");
                                $(value).attr('checked', 'checked');
                            }
                        });
                        
                        $('#edit_setting_form input[name="setting_min_val"]').val(data.min_value);
                        $('#edit_setting_form input[name="setting_max_val"]').val(data.max_value);
                        hide_wait();
                        $("#edit_settings_modal").modal("show");
                        FormValidationEdit.init();
                    }
                });
            });

            $("#edit_settings_modal").submit(function(e){
                e.preventDefault();
            });

            $(".remove-settings").click(function(){
                var id = $(this).data("id");
                $.ajax({
                    url   : '{{ route('ajax/delete_settings') }}',
                    type  : "post",
                    cache : false,
                    data  : {
                        'id' : id
                    },
                    success: function (data) {
                        if (data.success)
                        {
                            show_notification('Settings update', 'The details entered were correct', 'lime', 3500, 0);
                            setTimeout(function(){
                                window.location.reload(true);
                            },2500);
                        }
                        else
                        {
                            show_notification('Settings update ERROR', 'Something went wrong.', 'tangerine', 3500, 0);
                        }
                    }
                });
            });

            $(".save-items-settings").click(function(){
            
                var list_caption = [];
                $("#list_itmes_cation tr").each(function(index, value){
                    if ( ! $(value).hasClass('empty_list'))
                    {
                        var name = $(value).find("td:nth-child(1)").text();
                        var value = $(value).find("td:nth-child(2)").text();

                        list_caption.push({name : name, value : value});
                    }
                });

                $.ajax({
                    url: '{{ route('ajax/add_items_settings') }}',
                    type: "post",
                    cache: false,
                    data: {
                        'setting_id' : add_id, 
                        'list' : list_caption
                    },
                    success: function (data) {
                        if (data.success) {
                            show_notification('New settings add', 'The details entered were correct', 'lime', 3500, 0);
                            setTimeout(function(){
                                window.location.reload(true);
                            },2500);
                        }
                        else{
                            show_notification('New settings ERROR', 'Something went wrong.', 'tangerine', 3500, 0);
                        }
                    }
                });
            });

            $(".add-items-settings").click(function(){
               var id = $(this).data("id");
               add_id = id;
                show_wait();
                $.ajax({
                    url: '{{ route('ajax/get_items_settings') }}',
                    type: "post",
                    cache: false,
                    data: {
                        'settings_id' : id
                    },
                    success: function (data) {
                        
                        hide_wait();

                        var context = "";

                        for(var i in data)
                        {
                            context += "<tr>";
                            
                            
                            context += "<td>";
                            context += data[i].item_value;
                            context += "</td>";

                            context += "<td>";
                            context += data[i].caption;
                            context += "</td>";

                            context += "<td>";
                            context += "<button class='btn btn-danger btn-delete-caption'><i class='fa fa-minus'></i></button>";
                            context += "</td>";


                            context += "</tr>";
                            
                        }

                        if ( ! context)
                        {
                            context = "<tr class='empty_list'><td class='text-center' colspan='3'>Empty list</td></tr>";
                        }

                        $("#list_itmes_cation").empty();
                        $("#list_itmes_cation").append(context);

                        $("#add_items_settings").modal("show");

                        $(".btn-delete-caption").click(function(){
                            $(this).closest("tr").remove();
                            if ( ! $("#list_itmes_cation tr").size())
                            {
                                $("#list_itmes_cation").append("<tr class='empty_list'><td class='text-center' colspan='3'>Empty list</td></tr>");    
                            }
                        });

                    }
                });
            });

            $('#add_items_settings_btn').click(function(){

                if ($("#list_itmes_cation tr.empty_list").size())
                {
                    $("#list_itmes_cation").empty();
                }

                var tr = "";
                tr += "<tr>";
                tr += "<td>";
                tr += $("#settings_items_name").val();
                tr += "</td>";
                tr += "<td>";
                tr += $("#settings_items_cation").val();
                tr += "</td>";
                tr += "<td>";
                tr += "<button class='btn btn-danger btn-delete-caption'><i class='fa fa-minus'></i></button>";
                tr += "</td>";
                tr += "</tr>";
                $("#list_itmes_cation").append(tr);

                $(".btn-delete-caption").click(function(){
                    $(this).closest("tr").remove();
                    if ( ! $("#list_itmes_cation tr").size())
                    {
                        $("#list_itmes_cation").append("<tr class='empty_list'><td class='text-center' colspan='3'>Empty list</td></tr>");    
                    }
                });

            });
        });

        function show_wait()
        {
            $(".loader").css("display", "inline-block")
        }

        function hide_wait()
        {
            $(".loader").hide();
        }

        function clear()
        {
            $('#add_setting_form input[name="setting_name"]').val("");
            $('#add_setting_form input[name="setting_internal_name"]').val("");
            $('#add_setting_form textarea[name="setting_description"]').val("");
            $('#add_setting_form select[name="setting_type"]').val("");
            $('#add_setting_form input[name="setting_min_val"]').val("");
            $('#add_setting_form input[name="setting_max_val"]').val("");

            // ---

            $('#edit_setting_form input[name="setting_name"]').val("");
            $('#edit_setting_form input[name="setting_internal_name"]').val("");
            $('#edit_setting_form textarea[name="setting_description"]').val("");
            $('#edit_setting_form select[name="setting_type"]').val("");
            $('#edit_setting_form input[name="setting_min_val"]').val("");
            $('#edit_setting_form input[name="setting_max_val"]').val("");

            $('#edit_setting_form span.checked').removeClass('checked');
        }

        function edit_setting()
        {
            $.ajax({
                url: '{{ route('ajax/update_settings') }}',
                type: "post",
                cache: false,
                data: {
                    'id'                   : update_id,
                    'name'                 : $('#edit_setting_form input[name="setting_name"]').val(),
                    'system_internal_name' : $('#edit_setting_form input[name="setting_internal_name"]').val(),
                    'description'          : $('#edit_setting_form textarea[name="setting_description"]').val(),
                    'contained'            : $("#edit_setting_form input[name='setting_constrained']").prop('checked'),
                    'data_type'            : $('#edit_setting_form select[name="setting_type"]').val(),
                    'min_value'            : $('#edit_setting_form input[name="setting_min_val"]').val(),
                    'max_value'            : $('#edit_setting_form input[name="setting_max_val"]').val()
                },
                success: function (data) {
                    if (data.success) {
                        show_notification('Settings update', 'The details entered were correct', 'lime', 3500, 0);
                        setTimeout(function(){
                            window.location.reload(true);
                        },2500);
                    }
                    else{
                        show_notification('Settings update ERROR', 'Something went wrong.', 'tangerine', 3500, 0);
                    }
                }
            });
        }

        function add_new_setting()
        {
            $.ajax({
                url: '{{ route('ajax/register_new_setting') }}',
                type: "post",
                cache: false,
                data: {
                    'name':                 $('#add_setting_form input[name="setting_name"]').val(),
                    'system_internal_name': $('#add_setting_form input[name="setting_internal_name"]').val(),
                    'description':          $('#add_setting_form textarea[name="setting_description"]').val(),
                    'contained':            $("#add_setting_form input[name='setting_constrained']").prop('checked'),
                    'data_type':            $('#add_setting_form select[name="setting_type"]').val(),
                    'min_value':            $('#add_setting_form input[name="setting_min_val"]').val(),
                    'max_value':            $('#add_setting_form input[name="setting_max_val"]').val()
                },
                success: function (data) {
                    if (data.success) {
                        show_notification('New settings add', 'The details entered were correct', 'lime', 3500, 0);
                        setTimeout(function(){
                            window.location.reload(true);
                        },2500);
                    }
                    else{
                        show_notification('New settings ERROR', 'Something went wrong.', 'tangerine', 3500, 0);
                    }
                }
            });
        }
    </script>
@endsection