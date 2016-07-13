@extends('admin.layouts.main')

@section('pageLevelPlugins')
    <link href="{{ asset('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('themeGlobalStyle')
    <link href="{{ asset('assets/global/css/components-rounded.min.css') }}" rel="stylesheet" id="style_components" type="text/css" />
    <link href="{{ asset('assets/global/css/plugins.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/jquery-notific8/jquery.notific8.min.css') }}" rel="stylesheet" type="text/css" />
    @endsection

    @section('themeLayoutStyle')
            <!-- BEGIN PAGE LEVEL STYLES -->
    <link href="{{ asset('assets/pages/css/profile.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL STYLES -->
    <link href="{{ asset('assets/layouts/layout4/css/layout.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/layouts/layout4/css/themes/light.min.css') }}" rel="stylesheet" type="text/css" id="style_color" />
    <link href="{{ asset('assets/layouts/layout4/css/custom.min.css') }}" rel="stylesheet" type="text/css" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection

@section('title', 'Back-end users - User Details')
@section('pageBodyClass','page-container-bg-solid page-header-fixed page-sidebar-closed-hide-logo')

@section('pageContentBody')
    <div class="page-content fix_padding_top_0">
        <!-- BEGIN PAGE BASE CONTENT -->
        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN PROFILE SIDEBAR -->
                <div class="profile-sidebar">
                    <!-- PORTLET MAIN -->
                    <div class="portlet light profile-sidebar-portlet bordered">
                        <!-- SIDEBAR USERPIC -->
                        <div class="profile-userpic">
                            <img src="../assets/pages/media/profile/profile_user.jpg" class="img-responsive" alt=""> </div>
                        <!-- END SIDEBAR USERPIC -->
                        <!-- SIDEBAR USER TITLE -->
                        <div class="profile-usertitle">
                            <div class="profile-usertitle-name"> Marcus Doe </div>
                            <div class="profile-usertitle-job"> Developer </div>
                        </div>
                        <!-- END SIDEBAR USER TITLE -->
                        <!-- SIDEBAR BUTTONS -->
                        <div class="profile-userbuttons">
                            <button type="button" class="btn btn-circle green btn-sm">Follow</button>
                            <button type="button" class="btn btn-circle red btn-sm">Message</button>
                        </div>
                        <!-- END SIDEBAR BUTTONS -->
                        <!-- SIDEBAR MENU -->
                        <div class="profile-usermenu">
                            <ul class="nav">
                                <li>
                                    <a href="{{route("admin/front_users/view_user", $user->id)}}">
                                        <i class="icon-home"></i> Overview </a>
                                </li>
                                <li>
                                    <a href="{{route("admin/front_users/view_account_settings", $user->id)}}">
                                        <i class="icon-settings"></i> Account Settings </a>
                                </li>
                                <li>
                                    <a href="{{route("admin/front_users/view_bookings", $user->id)}}">
                                        <i class="fa fa-calendar"></i> Bookings </a>
                                </li>
                                <li class="active">
                                    <a href="{{route("admin/front_users/view_finance", $user->id)}}">
                                        <i class="fa fa-money"></i> Finance </a>
                                </li>
                            </ul>
                        </div>
                        <!-- END MENU -->
                    </div>
                    <!-- END PORTLET MAIN -->
                </div>
                <!-- END BEGIN PROFILE SIDEBAR -->
                <!-- BEGIN PROFILE CONTENT -->
                <div class="profile-content">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="portlet light bordered">
                                <div class="portlet-title tabbable-line">
                                    <div class="caption caption-md">
                                        <i class="icon-globe theme-font hide"></i>
                                        <span class="caption-subject font-blue-madison bold uppercase">Member Bookings</span>
                                    </div>
                                    <ul class="nav nav-tabs">
                                        <li class="active">
                                            <a href="#tab_1_1" data-toggle="tab">Last 10 bookings</a>
                                        </li>
                                        <li>
                                            <a href="#tab_1_2" data-toggle="tab">Archive</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="portlet-body">
                                    <div class="tab-content">
                                        <!-- PERSONAL INFO TAB -->
                                        <div class="tab-pane active" id="tab_1_1">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <!-- BEGIN BORDERED TABLE PORTLET-->
                                                    <div class="portlet light portlet-fit bordered">
                                                        <div class="portlet-title">
                                                            <div class="caption">
                                                                <i class="icon-bubble font-dark"></i>
                                                                <span class="caption-subject font-dark bold uppercase">Bordered Table</span>
                                                            </div>
                                                        </div>
                                                        <div class="portlet-body">
                                                            <div class="table-scrollable">
                                                                <table class="table table-bordered table-hover">
                                                                    <thead>
                                                                    <tr>
                                                                        <th> # </th>
                                                                        <th> First Name </th>
                                                                        <th> Last Name </th>
                                                                        <th> Username </th>
                                                                        <th> Status </th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    <tr>
                                                                        <td rowspan="2"> 1 </td>
                                                                        <td> Mark </td>
                                                                        <td> Otto </td>
                                                                        <td> makr124 </td>
                                                                        <td>
                                                                            <span class="label label-sm label-success"> Approved </span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td> Jacob </td>
                                                                        <td> Nilson </td>
                                                                        <td> jac123 </td>
                                                                        <td>
                                                                            <span class="label label-sm label-info"> Pending </span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td> 2 </td>
                                                                        <td> Larry </td>
                                                                        <td> Cooper </td>
                                                                        <td> lar </td>
                                                                        <td>
                                                                            <span class="label label-sm label-warning"> Suspended </span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td> 3 </td>
                                                                        <td> Sandy </td>
                                                                        <td> Lim </td>
                                                                        <td> sanlim </td>
                                                                        <td>
                                                                            <span class="label label-sm label-danger"> Blocked </span>
                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td rowspan="2"> 1 </td>
                                                                        <td> Mark </td>
                                                                        <td> Otto </td>
                                                                        <td> makr124 </td>
                                                                        <td>
                                                                            <span class="label label-sm label-success"> Approved </span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td> Jacob </td>
                                                                        <td> Nilson </td>
                                                                        <td> jac123 </td>
                                                                        <td>
                                                                            <span class="label label-sm label-info"> Pending </span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td> 2 </td>
                                                                        <td> Larry </td>
                                                                        <td> Cooper </td>
                                                                        <td> lar </td>
                                                                        <td>
                                                                            <span class="label label-sm label-warning"> Suspended </span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td> 3 </td>
                                                                        <td> Sandy </td>
                                                                        <td> Lim </td>
                                                                        <td> sanlim </td>
                                                                        <td>
                                                                            <span class="label label-sm label-danger"> Blocked </span>
                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td rowspan="2"> 1 </td>
                                                                        <td> Mark </td>
                                                                        <td> Otto </td>
                                                                        <td> makr124 </td>
                                                                        <td>
                                                                            <span class="label label-sm label-success"> Approved </span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td> Jacob </td>
                                                                        <td> Nilson </td>
                                                                        <td> jac123 </td>
                                                                        <td>
                                                                            <span class="label label-sm label-info"> Pending </span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td> 2 </td>
                                                                        <td> Larry </td>
                                                                        <td> Cooper </td>
                                                                        <td> lar </td>
                                                                        <td>
                                                                            <span class="label label-sm label-warning"> Suspended </span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td> 3 </td>
                                                                        <td> Sandy </td>
                                                                        <td> Lim </td>
                                                                        <td> sanlim </td>
                                                                        <td>
                                                                            <span class="label label-sm label-danger"> Blocked </span>
                                                                        </td>
                                                                    </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- END BORDERED TABLE PORTLET-->
                                                </div>
                                            </div>
                                        </div>
                                        <!-- END PERSONAL INFO TAB -->
                                        <!-- CHANGE AVATAR TAB -->
                                        <div class="tab-pane" id="tab_1_2">
                                            <p> Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum
                                                eiusmod. </p>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <!-- BEGIN SAMPLE TABLE PORTLET-->
                                                    <div class="portlet">
                                                        <div class="portlet-title">
                                                            <div class="caption">
                                                                <i class="fa fa-bell-o"></i>Advance Table </div>
                                                            <div class="tools">
                                                                <a class="collapse" href="javascript:;" data-original-title="" title=""> </a>
                                                                <a class="config" data-toggle="modal" href="#portlet-config" data-original-title="" title=""> </a>
                                                                <a class="reload" href="javascript:;" data-original-title="" title=""> </a>
                                                                <a class="remove" href="javascript:;" data-original-title="" title=""> </a>
                                                            </div>
                                                        </div>
                                                        <div class="portlet-body">
                                                            <div class="table-scrollable">
                                                                <table class="table table-striped table-bordered table-advance table-hover">
                                                                    <thead>
                                                                    <tr>
                                                                        <th>
                                                                            <i class="fa fa-briefcase"></i> Company </th>
                                                                        <th class="hidden-xs">
                                                                            <i class="fa fa-user"></i> Contact </th>
                                                                        <th>
                                                                            <i class="fa fa-shopping-cart"></i> Total </th>
                                                                        <th> </th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    <tr>
                                                                        <td class="highlight">
                                                                            <div class="success"></div>
                                                                            <a href="javascript:;"> RedBull </a>
                                                                        </td>
                                                                        <td class="hidden-xs"> Mike Nilson </td>
                                                                        <td> 2560.60$ </td>
                                                                        <td>
                                                                            <a class="btn btn-outline btn-circle btn-sm purple" href="javascript:;">
                                                                                <i class="fa fa-edit"></i> Edit </a>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="highlight">
                                                                            <div class="info"> </div>
                                                                            <a href="javascript:;"> Google </a>
                                                                        </td>
                                                                        <td class="hidden-xs"> Adam Larson </td>
                                                                        <td> 560.60$ </td>
                                                                        <td>
                                                                            <a class="btn btn-outline btn-circle dark btn-sm black" href="javascript:;">
                                                                                <i class="fa fa-trash-o"></i> Delete </a>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="highlight">
                                                                            <div class="success"> </div>
                                                                            <a href="javascript:;"> Apple </a>
                                                                        </td>
                                                                        <td class="hidden-xs"> Daniel Kim </td>
                                                                        <td> 3460.60$ </td>
                                                                        <td>
                                                                            <a class="btn btn-outline btn-circle green btn-sm purple" href="javascript:;">
                                                                                <i class="fa fa-edit"></i> Edit </a>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="highlight">
                                                                            <div class="warning"> </div>
                                                                            <a href="javascript:;"> Microsoft </a>
                                                                        </td>
                                                                        <td class="hidden-xs"> Nick </td>
                                                                        <td> 2560.60$ </td>
                                                                        <td>
                                                                            <a class="btn btn-outline btn-circle red btn-sm blue" href="javascript:;">
                                                                                <i class="fa fa-share"></i> Share </a>
                                                                        </td>
                                                                    </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- END SAMPLE TABLE PORTLET-->
                                                </div>
                                            </div>
                                        </div>
                                        <!-- END CHANGE AVATAR TAB -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END PROFILE CONTENT -->
            </div>
        </div>
        <!-- END PAGE BASE CONTENT -->
    </div>
@endsection

@section('pageBelowLevelPlugins')
    <script src="{{ asset('assets/global/plugins/jquery-validation/js/jquery.validate.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/jquery-validation/js/additional-methods.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/jquery.sparkline.min.js') }}" type="text/javascript"></script>
@endsection

@section('pageBelowLevelScripts')
    <script src="{{ asset('assets/global/plugins/jquery-notific8/jquery.notific8.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/jquery.blockui.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/pages/scripts/profile.min.js') }}" type="text/javascript"></script>
@endsection

@section('themeBelowLayoutScripts')
    <script src="{{ asset('assets/layouts/layout4/scripts/layout.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/layouts/layout4/scripts/demo.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/layouts/global/scripts/quick-sidebar.min.js') }}" type="text/javascript"></script>
@endsection

@section('pageCustomJScripts')
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.validator.addMethod(
                "datePickerDate",
                function(value, element) {
                    // put your own logic here, this is just a (crappy) example
                    return value.match(/^\d\d?-\d\d?-\d\d\d\d$/);
                },
                "Please enter a date in the format dd/mm/yyyy."
        );

        $.validator.addMethod(
                'filesize',
                function(value, element, param) {
                    // param = size (in bytes)
                    // element = element to validate (<input>)
                    // value = value of the element (file name)
                    return this.optional(element) || (element.files[0].size <= param);
                },
                "File must be JPG, GIF or PNG, less than 1MB"
        );

        var FormValidation = function () {

            var handleValidation1 = function() {
                var form1 = $('#form_acc_personal');
                var error1 = $('.alert-danger', form1);
                var success1 = $('.alert-success', form1);

                form1.validate({
                    errorElement: 'span', //default input error message container
                    errorClass: 'help-block help-block-error', // default input error message class
                    focusInvalid: false, // do not focus the last invalid input
                    ignore: "",  // validate all fields including form hidden input
                    rules: {
                        personalFirstName: {
                            minlength: 3,
                            required: true
                        },
                        personalLastName: {
                            minlength: 3,
                            required: true
                        },
                        personalDOB: {
                            required: true,
                            datePickerDate:true
                        },
                        personalEmail: {
                            required: true,
                            email: true
                        },
                    },

                    invalidHandler: function (event, validator) { //display error alert on form submit
                        success1.hide();
                        error1.show();
                        App.scrollTo(error1, -200);
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
                        success1.show();
                        error1.hide();
                        store_account_personal(); // submit the form
                    }
                });
            }

            var handleValidation2 = function() {
                // for more info visit the official plugin documentation:
                // http://docs.jquery.com/Plugins/Validation
                var form2 = $('#form_acc_info');
                var error2 = $('.alert-danger', form2);
                var success2 = $('.alert-success', form2);

                form2.validate({
                    errorElement: 'span', //default input error message container
                    errorClass: 'help-block help-block-error', // default input error message class
                    focusInvalid: false, // do not focus the last invalid input
                    ignore: "",  // validate all fields including form hidden input
                    rules: {
                        accountUsername: {
                            minlength: 3,
                            required: true
                        },
                        accountEmail: {
                            required: true,
                            email: true
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
                        store_account_info(); // submit the form
                    }
                });
            }

            var handleValidation3 = function() {
                var form3 = $('#form_personal_address');
                var error3 = $('.alert-danger', form3);
                var success3 = $('.alert-success', form3);

                form3.validate({
                    errorElement: 'span', //default input error message container
                    errorClass: 'help-block help-block-error', // default input error message class
                    focusInvalid: false, // do not focus the last invalid input
                    ignore: "",  // validate all fields including form hidden input
                    rules: {
                        personal_addr1: {
                            minlength: 5,
                            required: true
                        },
                        personal_addr_city: {
                            minlength: 3,
                            required: true
                        },
                        personal_addr_region: {
                            minlength:2,
                            required: true
                        },
                        personal_addr_pcode: {
                            minlength: 2,
                            required: true
                        },
                    },

                    invalidHandler: function (event, validator) { //display error alert on form submit
                        success3.hide();
                        error3.show();
                        App.scrollTo(error3, -200);
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
                        success3.show();
                        error3.hide();
                        update_personal_address(); // submit the form
                    }
                });
            }

            var handleValidation4 = function() {
                var form4 = $('#form_password_update');
                var error4 = $('.alert-danger', form4);
                var success4 = $('.alert-success', form4);

                form4.validate({
                    errorElement: 'span', //default input error message container
                    errorClass: 'help-block help-block-error', // default input error message class
                    focusInvalid: false, // do not focus the last invalid input
                    ignore: "",  // validate all fields including form hidden input
                    rules: {
                        old_password: {
                            minlength: 8,
                            required: true,
                        },
                        new_password1: {
                            minlength: 8,
                            required: true,
                        },
                        new_password2: {
                            minlength: 8,
                            required: true,
                            equalTo: '#new_password1',
                        },
                    },

                    invalidHandler: function (event, validator) { //display error alert on form submit
                        success4.hide();
                        error4.show();
                        App.scrollTo(error4, -200);
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
                        success4.show();
                        error4.hide();
                        update_passwd(); // submit the form
                    }
                });
            }

            var handleValidation5 = function() {
                var form5 = $('#user_picture_upload1');
                var error5 = $('.alert-danger', form5);
                var success5 = $('.alert-success', form5);

                form5.validate({
                    errorElement: 'span', //default input error message container
                    errorClass: 'help-block help-block-error', // default input error message class
                    focusInvalid: false, // do not focus the last invalid input
                    ignore: "",  // validate all fields including form hidden input
                    rules: {
                        user_avatar: {
                            required: true,
                            accept: "image/*",
                            filesize: 1048576,
                        },
                    },
                    messages: {
                        user_avatar: {
                            required: "We need your avatar before submitting the form",
                            accept: "The uploaded file must be an image",
                            filesize: "File must be JPG, GIF or PNG, less than 1MB",
                        }
                    },

                    invalidHandler: function (event, validator) { //display error alert on form submit
                        success5.hide();
                        error5.show();
                        App.scrollTo(error5, -200);
                    },

                    highlight: function (element) { // hightlight error inputs
                        $(element)
                                .closest('.form-group').addClass('has-error'); // set error class to the control group
                    },

                    unhighlight: function (element) { // revert the change done by hightlight
                        $(element)
                                .closest('.form-group').removeClass('has-error'); // set error class to the control group
                    },

                    success: function (label) {
                        label
                                .closest('.form-group').removeClass('has-error'); // set success class to the control group
                    },

                    submitHandler: function (form) {
                        success5.show();
                        error5.hide();
                        form.submit();
                    }
                });
            }

            var handleValidation6 = function() {
                var form6 = $('#user_picture_upload2');
                var error6 = $('.alert-danger', form6);
                var success6 = $('.alert-success', form6);

                form5.validate({
                    errorElement: 'span', //default input error message container
                    errorClass: 'help-block help-block-error', // default input error message class
                    focusInvalid: false, // do not focus the last invalid input
                    ignore: "",  // validate all fields including form hidden input
                    rules: {
                        user_avatar: {
                            required: true,
                            accept: "image/*",
                            filesize: 1048576,
                        },
                    },
                    messages: {
                        user_avatar: {
                            required: "We need your avatar before submitting the form",
                            accept: "The uploaded file must be an image",
                            filesize: "File must be JPG, GIF or PNG, less than 1MB",
                        }
                    },

                    invalidHandler: function (event, validator) { //display error alert on form submit
                        success6.hide();
                        error6.show();
                        App.scrollTo(error6, -200);
                    },

                    highlight: function (element) { // hightlight error inputs
                        $(element)
                                .closest('.form-group').addClass('has-error'); // set error class to the control group
                    },

                    unhighlight: function (element) { // revert the change done by hightlight
                        $(element)
                                .closest('.form-group').removeClass('has-error'); // set error class to the control group
                    },

                    success: function (label) {
                        label
                                .closest('.form-group').removeClass('has-error'); // set success class to the control group
                    },

                    submitHandler: function (form) {
                        success6.show();
                        error6.hide();
                        form.submit();
                    }
                });
            }

            return {
                //main function to initiate the module
                init: function () {
                    handleValidation1();
                    handleValidation2();
                    handleValidation3();
                    handleValidation4();
                    handleValidation5();
                }
            };
        }();

        $(document).ready(function(){
            FormValidation.init();
        });

        function store_account_info(){
            $.ajax({
                url: '{{route('admin/back_users/view_user/acc_info', ['id'=>$user->id])}}',
                type: "post",
                data: {
                    'accountUsername': $('input[name=accountUsername]').val(),
                    'accountEmail': $('input[name=accountEmail]').val(),
                    'accountJobTitle': $('input[name=accountJobTitle]').val(),
                    'accountProfession': $('input[name=accountProfession]').val(),
                    'accountDescription': $('textarea[name=accountDescription]').val(),
                    'employeeRole': $('select[name=employeeRole]').val(),
                    '_method': 'post',
                },
                success: function(data){
                    alert(data);
                }
            });
        }

        function store_account_personal(){
            $.ajax({
                url: '{{route('admin/back_users/view_user/personal_info', ['id'=>$user->id])}}',
                type: "post",
                data: {
                    'first_name':       $('input[name=personalFirstName]').val(),
                    'middle_name':      $('input[name=personalMiddleName]').val(),
                    'last_name':        $('input[name=personalLastName]').val(),
                    'date_of_birth':    $('input[name=personalDOB]').val(),
                    'personal_email':   $('input[name=personalEmail]').val(),
                    'mobile_number':    $('input[name=personalPhone]').val(),
                    'bank_acc_no':      $('input[name=personalBankAcc]').val(),
                    'social_sec_no':    $('input[name=personalSSN]').val(),
                    'about_info':       $('textarea[name=personalAbout]').val(),
                    'country_id':       $('select[name=personalCountry]').val(),
                    '_method': 'post',
                },
                success: function(data){
                    alert(data);
                }
            });
        }

        function update_personal_address(){
            $.ajax({
                url: '{{route('admin/back_users/view_user/personal_address', ['id'=>$user->id])}}',
                type: "post",
                data: {
                    'address1':     $('input[name=personal_addr1]').val(),
                    'address2':     $('input[name=personal_addr2]').val(),
                    'city':         $('input[name=personal_addr_city]').val(),
                    'region':       $('input[name=personal_addr_region]').val(),
                    'postal_code':  $('input[name=personal_addr_pcode]').val(),
                    'country_id':   $('select[name=personal_addr_country]').val(),
                    '_method': 'post',
                },
                success: function(data){
                    alert(data);
                }
            });
        }

        function update_passwd(){
            $.ajax({
                url: '{{route('admin/back_users/view_user/password_update', ['id'=>$user->id])}}',
                type: "post",
                data: {
                    'old_password': $('input[name=old_password]').val(),
                    'password1':    $('input[name=new_password1]').val(),
                    'password2':    $('input[name=new_password2]').val(),
                    '_method': 'post',
                },
                success: function(data){
                    alert(data);
                }
            });
        }

        $(".user_avatar_select_btn1").on("click", function(){
            App.blockUI({
                target: '#user_picture_upload1',
                boxed: true
            });
        });

        $(".user_avatar_select_btn1").on("change", function(){
            App.unblockUI('#user_picture_upload1');
        });

        $(".user_avatar_select_btn2").on("click", function(){
            App.blockUI({
                target: '#user_picture_upload2',
                boxed: true
            });
        });

        $(".user_avatar_select_btn2").on("change", function(){
            App.unblockUI('#user_picture_upload2');
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