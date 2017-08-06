@extends('admin.layouts.main')

@section('pageLevelPlugins')
    <link href="{{ asset('assets/global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('themeGlobalStyle')
    <link href="{{ asset('assets/global/plugins/jquery-notific8/jquery.notific8.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/css/components-rounded.min.css') }}" rel="stylesheet" id="style_components" type="text/css" />
    <link href="{{ asset('assets/global/css/plugins.min.css') }}" rel="stylesheet" type="text/css" />
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
                <!-- BEGIN EXAMPLE TABLE PORTLET-->
                <div class="portlet light bordered">
                    <div class="portlet-body">
                        <div class="table-toolbar">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="btn-group">
                                        <button id="sample_editable_1_new" class="btn sbold green" data-toggle="modal" href="#draggable"> Add New
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <table class="table table-striped table-bordered table-hover table-checkable order-column" id="sample_1">
                            <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /> </th>
                                <th> Full Name </th>
                                <th> Username </th>
                                <th> Email </th>
                                <th> Joined </th>
                                <th> Status </th>
                            </tr>
                            </thead>
                            <tbody>

                        @foreach($users as $user)
                            @if($user->status != 'deleted')
                                <tr class="odd gradeX">
                                    <td>
                                        <input type="checkbox" class="checkboxes" value="{{$user->id}}" /> </td>
                                    <td> <a href="{{route("admin/back_users/view_user/", $user->id)}}">{{$user->first_name}} {{$user->middle_name}} {{$user->last_name}}</a> </td>
                                    <td> {{$user->username}} </td>
                                    <td>
                                        <a href="mailto:{{$user->email}}"> {{$user->email}} </a>
                                    </td>
                                    <td class="center"> {{$user->created_at}} </td>
                                    <td>
                                        <span class="label label-sm {{ $user->status == 'active' ? 'label-success' : 'label-danger' }}"> {{ ucfirst($user->status) }} </span>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-equalizer font-blue-steel"></i>
                            <span class="caption-subject font-blue-steel bold uppercase"> Deleted Backend Users </span>
                        </div>
                        <div class="tools">
                            <a class="expand" href="" data-original-title="" title=""> </a>
                        </div>
                    </div>
                    <div class="portlet-body" style="display:none;">
                        <table class="table table-striped table-bordered table-hover table-checkable order-column" id="sample_1">
                            <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /> </th>
                                <th> Full Name </th>
                                <th> Username </th>
                                <th> Email </th>
                                <th> Action </th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($users as $user)
                                @if($user->status == 'deleted')
                                    <tr class="odd gradeX">
                                        <td>
                                            <input type="checkbox" class="checkboxes" value="{{$user->id}}" /> </td>
                                        <td> <a>{{$user->first_name}} {{$user->middle_name}} {{$user->last_name}}</a> </td>
                                        <td> {{$user->username}} </td>
                                        <td>
                                            <a href="mailto:{{$user->email}}"> {{$user->email}} </a>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-info yellow-mint" onclick="javascript: reactivate_member('{{ $user->id }}')"> Reactivate </button>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- END EXAMPLE TABLE PORTLET-->
            </div>

            <div class="modal fade draggable-modal" id="draggable" tabindex="-1" role="basic" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                            <h4 class="modal-title">Start Dragging Here</h4>
                        </div>
                        <div class="modal-body">
                            <form action="#" id="form_sample_2">
                                <div class="form-body">
                                    <div class="alert alert-danger display-hide">
                                        <button class="close" data-close="alert"></button> You have some form errors. Please check below. </div>
                                    <div class="alert alert-success display-hide">
                                        <button class="close" data-close="alert"></button> Your form validation is successful! </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label visible-ie8 visible-ie9">First Name</label>
                                                <input class="form-control placeholder-no-fix" type="text" placeholder="First Name" name="first_name" /> 
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label visible-ie8 visible-ie9">Last Name</label>
                                                <input class="form-control placeholder-no-fix" type="text" placeholder="Last Name" name="last_name" /> 
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label visible-ie8 visible-ie9">Phone Number</label>
                                                <input class="form-control placeholder-no-fix" type="text" placeholder="Phone Number" name="phone_number" /> 
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="input-group date date-picker" data-date-format="yyyy-mm-dd" data-date-end-date="-0d" data-date-start-view="decades">
                                                    <input type="text" class="form-control" name="DOB" id="DOB" placeholder="Date of Birth" value="" readonly>
                                                    <span class="input-group-btn">
                                                        <button class="btn default" type="button">
                                                            <i class="fa fa-calendar"></i>
                                                        </button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <select name="gender" class="form-control">
                                                    <option value="">Select Gender</option>
                                                    <option value="M"> Male </option>
                                                    <option value="F"> Female </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <select name="country" id="country" class="form-control">
                                                    <option value="">Select Citizenship</option>
                                                    @foreach ($countries as $country)
                                                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
                                                <label class="control-label visible-ie8 visible-ie9">Email</label>
                                                <input class="form-control placeholder-no-fix" type="text" placeholder="Email" name="email" /> 
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <select class="form-control" name="user_type">
                                                    <option value = ''>User type</option>
                                                    @foreach($all_roles as $role)
                                                        <option value="{{$role->id}}">{{$role->name}}</option>
                                                    @endforeach
                                                </select> 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {!! csrf_field() !!}
                                <input type="hidden" name="_method" value="put" />
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                            <button type="button" class="btn green submit_form_2" onCLick="javascript: $('#form_sample_2').submit();">Save changes</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
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

    <script src="{{ asset('assets/global/scripts/datatable.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/datatables/datatables.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js') }}" type="text/javascript"></script>
@endsection

@section('themeBelowGlobalScripts')
    <script src="{{ asset('assets/global/scripts/app.min.js') }}" type="text/javascript"></script>
@endsection

@section('pageBelowLevelScripts')
    <script src="{{ asset('assets/pages/scripts/table-datatables-managed.min.js') }}" type="text/javascript"></script>
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

                var form2 = $('#form_sample_2');
                var error2 = $('.alert-danger', form2);
                var success2 = $('.alert-success', form2);

                form2.validate({
                    errorElement: 'span', //default input error message container
                    errorClass: 'help-block help-block-error', // default input error message class
                    focusInvalid: false, // do not focus the last invalid input
                    ignore: "",  // validate all fields including form hidden input
                    rules: {
                        first_name: {
                            minlength: 2,
                            required: true
                        },
                        last_name: {
                            minlength: 2,
                            required: true
                        },
                        phone_number: {
                            required: true,
                            digits: true,
                            minlength: 8,
                            maxlength: 20,
                            remote: {
                                url: "{{ route('ajax/check_phone_for_member_registration') }}",
                                type: "post",
                                data: {
                                    phone_number: function() {
                                        return $( "input[name='phone_number']" ).val();
                                    }
                                }
                            }
                        },
                        email: {
                            email: true,
                            validate_email:true,
                            required: true,
                            remote: {
                                url: "{{ route('ajax/check_email_for_member_registration') }}",
                                type: "post",
                                data: {
                                    email: function() {
                                        return $( "input[name='email']" ).val();
                                    }
                                }
                            }
                        },
                        DOB: {
                            required: true,
                        },
                        gender: {
                            required: true,
                            minlength:1
                        },
                        country: {
                            required: true,
                            minlength:1
                        },
                        user_type : {
                            required: true
                        }
                        
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
                        register_back_user(); // submit the form
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

        function register_back_user(){
            $.ajax({
                url: '{{route('admin/back_users/add_user')}}',
                type: "post",
                data: { 'first_name':   $('input[name=first_name]').val(),
                        'last_name':    $('input[name=last_name]').val(),
                        'email':        $('input[name=email]').val(),
                        'phone_number':        $('input[name=phone_number]').val(),
                        'dob':        $('input[name=DOB]').val(),
                        'gender':        $('select[name=gender]').val(),
                        'country_id':        $('select[name=country]').val(),
                        'user_type':    $('select[name=user_type]').val(),
                        '_method':      $('input[name=_method]').val(),
                        '_token':       $('input[name=_token]').val()},
                success: function(data){
                    if(data.success){
                        show_notification(data.title, data.message, 'lime', 3500, 0);
                        setTimeout(function(){
                            location.reload();
                        },2000);
                    }
                    else{
                        $('.alert-success', '#form_sample_2').hide();
                        $('.alert-danger', '#form_sample_2').html(data.errors);
                        $('.alert-danger', '#form_sample_2').show();
                        show_notification(data.title, data.errors, 'ruby', 3500, 0);
                    }
                }
            });
        }

        function reactivate_member(id){
            $.ajax({
                url: '{{ route('ajax/reactivate_member') }}',
                type: "post",
                data: {
                    'user_id': id
                },
                success: function (data) {
                    if (data.success) {
                        show_notification(data.title, data.message, 'lime', 3500, 0);
                        setTimeout(function () {
                            location.reload();
                        }, 2000);
                    }
                    else {
                        show_notification(data.title, data.errors, 'ruby', 3500, 0);
                    }
                }
            });
        }
        
        var ComponentsDateTimePickers = function () {

            var handleDatePickers = function () {

                if (jQuery().datepicker) {
                    $('.date-picker').datepicker({
                        rtl: App.isRTL(),
                        orientation: "left",
                        autoclose: true,
                        daysOfWeekHighlighted: "0",
                        weekStart:1,
                    });
                    //$('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
                }

                /* Workaround to restrict daterange past date select: http://stackoverflow.com/questions/11933173/how-to-restrict-the-selectable-date-ranges-in-bootstrap-datepicker */
            }

            return {
                //main function to initiate the module
                init: function () {
                    handleDatePickers();
                }
            };

        }();

        $(document).ready(function(){
            FormValidation.init();
            ComponentsDateTimePickers.init();
        });
    </script>
@endsection