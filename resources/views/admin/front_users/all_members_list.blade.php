@extends('admin.layouts.main')

@section('pageLevelPlugins')
    <link href="../assets/global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css" />
@endsection

@section('themeGlobalStyle')
    <link href="../assets/global/css/components-rounded.min.css" rel="stylesheet" id="style_components" type="text/css" />
    <link href="../assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('themeLayoutStyle')
    <link href="../assets/layouts/layout4/css/layout.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/layouts/layout4/css/themes/light.min.css" rel="stylesheet" type="text/css" id="style_color" />
    <link href="../assets/layouts/layout4/css/custom.min.css" rel="stylesheet" type="text/css" />
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
                                <tr class="odd gradeX">
                                    <td>
                                        <input type="checkbox" class="checkboxes" value="{{$user->id}}" /> </td>
                                    <td> <a href="{{route("admin/front_users/view_user", $user->id)}}">{{$user->first_name}} {{$user->middle_name}} {{$user->last_name}}</a> </td>
                                    <td> {{$user->username}} </td>
                                    <td>
                                        <a href="mailto:{{$user->email}}"> {{$user->email}} </a>
                                    </td>
                                    <td class="center"> {{$user->created_at}} </td>
                                    <td>
                                        <span class="label label-sm label-success"> Approved </span>
                                    </td>
                                </tr>
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
                            <h4 class="modal-title">Add new Front User/Customer</h4>
                        </div>
                        <div class="modal-body">
                            <form action="#" id="form_sample_2" class="form-horizontal">
                                <div class="form-body">
                                    <div class="alert alert-danger display-hide">
                                        <button class="close" data-close="alert"></button> You have some form errors. Please check below. </div>
                                    <div class="alert alert-success display-hide">
                                        <button class="close" data-close="alert"></button> Your form validation is successful! </div>
                                    <div class="form-group  margin-top-20">
                                        <label class="control-label col-md-4">First Name
                                            <span class="required"> * </span>
                                        </label>
                                        <div class="col-md-7">
                                            <div class="input-icon right">
                                                <i class="fa"></i>
                                                <input type="text" class="form-control" name="first_name" /> </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Middle Name
                                        </label>
                                        <div class="col-md-7">
                                            <div class="input-icon right">
                                                <i class="fa"></i>
                                                <input type="text" class="form-control" name="middle_name" /> </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Last Name
                                            <span class="required"> * </span>
                                        </label>
                                        <div class="col-md-7">
                                            <div class="input-icon right">
                                                <i class="fa"></i>
                                                <input type="text" class="form-control" name="last_name" /> </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Username
                                            <span class="required"> * </span>
                                        </label>
                                        <div class="col-md-7">
                                            <div class="input-icon right">
                                                <i class="fa"></i>
                                                <input type="text" class="form-control" name="username" /> </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Password
                                            <span class="required"> * </span>
                                        </label>
                                        <div class="col-md-7">
                                            <div class="input-icon right">
                                                <i class="fa"></i>
                                                <input type="text" class="form-control" name="password" /> </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Email Address
                                            <span class="required"> * </span>
                                        </label>
                                        <div class="col-md-7">
                                            <div class="input-icon right">
                                                <i class="fa"></i>
                                                <input type="text" class="form-control" name="email" /> </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="user_type" value="{{$role->id}}" />
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
    <script src="../assets/global/plugins/jquery.min.js" type="text/javascript"></script>
    <script src="../assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="../assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
    <script src="../assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
    <script src="../assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
    <script src="../assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
    <script src="../assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
    <script src="../assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
@endsection

@section('pageBelowLevelPlugins')
    <script src="../assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
    <script src="../assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
    <script src="../assets/global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>

    <script src="../assets/global/scripts/datatable.js" type="text/javascript"></script>
    <script src="../assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
    <script src="../assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
@endsection

@section('themeBelowGlobalScripts')
    <script src="../assets/global/scripts/app.min.js" type="text/javascript"></script>
@endsection

@section('pageBelowLevelScripts')
    <script src="../assets/pages/scripts/table-datatables-managed.min.js" type="text/javascript"></script>
@endsection

@section('themeBelowLayoutScripts')
    <script src="../assets/layouts/layout4/scripts/layout.min.js" type="text/javascript"></script>
    <script src="../assets/layouts/layout4/scripts/demo.min.js" type="text/javascript"></script>
    <script src="../assets/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>
@endsection

@section('pageCustomJScripts')
    <script type="text/javascript">
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
                        name: {
                            minlength: 2,
                            required: true
                        },
                        username: {
                            minlength: 3,
                            required: true
                        },
                        first_name: {
                            minlength: 2,
                            required: true
                        },
                        last_name: {
                            minlength: 2,
                            required: true
                        },
                        password: {
                            minlength: 8,
                            required: true
                        },
                        email: {
                            required: true,
                            email: true
                        },
                        url: {
                            required: true,
                            url: true
                        },
                        number: {
                            required: true,
                            number: true
                        },
                        digits: {
                            required: true,
                            digits: true
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
                    'middle_name':  $('input[name=middle_name]').val(),
                    'last_name':    $('input[name=last_name]').val(),
                    'username':     $('input[name=username]').val(),
                    'password':     $('input[name=password]').val(),
                    'email':        $('input[name=email]').val(),
                    'user_type':    $('select[name=user_type]').val(),
                    '_method':      $('input[name=_method]').val(),
                    '_token':       $('input[name=_token]').val()},
                success: function(data){
                    alert(data);
                }
            });
        }

        $(document).ready(function(){
            FormValidation.init();
        });

        /* Start - All admin scripts */
        function booking_calendar_view_redirect(selected_date){
            var calendar_book = "{{route('bookings/location_calendar_day_view',['day'=>'##day##'])}}";
            the_link = calendar_book.replace('##day##', $('#calendar_booking_top_menu').data('datepicker').getFormattedDate('dd-mm-yyyy'));
            window.location.href = the_link;
        }

        function show_notification(title_heading, message, theme, life, sticky) {
            var settings = {
                theme: theme,
                sticky: sticky,
                horizontalEdge: 'top',
                verticalEdge: 'right',
                life : life,
            };

            if ($.trim(title_heading) != '') {
                settings.heading = title_heading;
            }

            $.notific8('zindex', 11500);
            $.notific8($.trim(message), settings);
        }
        /* Stop - All admin scripts */
    </script>
@endsection