@extends('layouts.main')

@section('globalMandatoryStyle')
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/simple-line-icons/simple-line-icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/uniform/css/uniform.default.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('pageLevelPlugins')
    <link href="{{ asset('assets/global/plugins/jquery-notific8/jquery.notific8.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('themeGlobalStyle')
    <link href="{{ asset('assets/global/css/components-rounded.min.css') }}" rel="stylesheet" id="style_components" type="text/css" />
    <link href="{{ asset('assets/global/css/plugins.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('themeLayoutStyle')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection

@section('title', 'Main Page')
@section('pageBodyClass','page-container-bg-solid page-boxed')

@section('pageContentBody')
    <div class="page-content-wrapper">
        <!-- BEGIN CONTENT BODY -->
        <!-- BEGIN PAGE CONTENT BODY -->
        <div class="page-content">
            <div class="container">
                <!-- BEGIN PAGE CONTENT INNER -->
                <div class="page-content-inner">
                    <!-- BEGIN EXAMPLE TABLE PORTLET-->
                    <div class="portlet light margin-top-10">
                        <div class="portlet-body">
                        @if (sizeof($list_of_friends)>0)
                            <div class="note note-info" style="margin-bottom:5px;">
                                <div class="row">
                                    <div class="col-sm-9">
                                    <h4 class="block">Add more friends</h4>
                                    <p>
                                        Use the bottom button to add a new friend, friend that will be located using his/her phone number.
                                    </p>
                                    </div>
                                    <div class="form col-sm-3">
                                        <div class="form-actions right" style="border-top:none; padding:10px 0 0;">
                                            <button class="btn green" type="submit"  onclick="javascript:add_new_friend_popup();">Add a new friend</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="table-scrollable">
                                <table class="table table-striped table-bordered table-advance table-hover">
                                    <thead>
                                    <tr>
                                        <th>
                                            <i class="fa fa-briefcase"></i> Friend Name </th>
                                        <th>
                                            <i class="fa fa-user"></i> Email Address </th>
                                        <th>
                                            <i class="fa fa-user"></i> Phone Number </th>
                                        <th class="hidden-xs">
                                            <i class="fa fa-shopping-cart"></i> Preferred Gym </th>
                                        <th class="hidden-xs">
                                            <i class="fa fa-shopping-cart"></i> Friend Since </th>
                                        <th> </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                @foreach($list_of_friends as $friend)
                                    <tr>
                                        <td class="highlight">
                                            <div class="success"></div>
                                            <a href="javascript:;"> {{$friend['full_name']}} </a>
                                        </td>
                                        <td> <a target="_blank" href="mailto:{{$friend['email_address']}}">{{$friend['email_address']}}</a> </td>
                                        <td class="hidden-xs"> {{$friend['phone_number']}} </td>
                                        <td> Lysake squash </td>
                                        <td> {{$friend['since']}} </td>
                                        <td>
                                            <a href="javascript:;" data-id="{{$friend['ref_nr']}}" class="btn btn-sm btn-outline red-haze remove_friend">
                                                <i class="fa fa-edit"></i> Remove </a>
                                        </td>
                                    </tr>
                                @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="note note-info" style="margin-bottom:5px;">
                                <h4 class="block">You have no friends! Add some...</h4>
                                <p>
                                    Adding friends, makes it easier for you and your friend to make bookings and play your favorite games together.
                                    The rules are simple : you and your friend need to have a player membership for using the booking friend feature
                                    and that's it.<br /><br />
                                    Use the bottom button to add a new friend, friend that will be located using his/her phone number.
                                </p>
                                <div class="form">
                                    <div class="form-actions right" style="border-top:none; padding:10px 0 0;">
                                        <button class="btn green" type="submit"  onclick="javascript:add_new_friend_popup();">Add Friend</button>
                                    </div>
                                </div>
                            </div>
                        @endif
                        </div>
                    </div>
                    <!-- END EXAMPLE TABLE PORTLET-->

                    <div class="modal fade" id="small_remove_friend" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                    <h4 class="modal-title"> Remove Friend</h4>
                                </div>
                                <div class="modal-body">
                                    <h5>You are about to remove one of your friends from your friend list : </h5>
                                    <div class="friend_name_cancel_place" style="padding:0px 15px;"></div>
                                    <input type="hidden" name="to_remove_friend" value="-1" />
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn dark btn-outline" data-dismiss="modal">No - return</button>
                                    <button type="button" class="btn green" onclick="javascript:remove_friend();">Yes - remove</button>
                                </div>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>

                    <div class="modal fade draggable-modal" id="new_friend_modal" tabindex="-1" role="basic" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-body form-horizontal">
                                    <div class="portlet light " style="padding-bottom:0px;margin-bottom:0px;">
                                        <div class="portlet-title form-group">
                                            <div class="caption">
                                                <i class="icon-social-dribbble font-green"></i>
                                                <span class="caption-subject font-green bold uppercase">Enter friend's phone number</span>
                                            </div>
                                        </div>
                                        <div class="portlet-body form">
                                            <form action="#" id="friend_search_form" role="form" name="friend_search_form">
                                                <div class="form-body" style="padding-top:0px; padding-bottom:0px;">
                                                    <div class="form-group note note-info margin-bottom-10">
                                                        <div class="input-group">
                                                            <input type="text" placeholder="Phone number..." name="friend_phone_no" class="form-control">
                                                    <span class="input-group-btn">
                                                        <button type="submit" class="btn red">Get Friend!</button>
                                                    </span>
                                                        </div>
                                                        <small class="help-block"> numeric field between 8 and 10 digits </small>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn dark btn-outline submit_form_2" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                </div>
                <!-- END PAGE CONTENT INNER -->
            </div>

        </div>
        <!-- END PAGE CONTENT BODY -->
        <!-- END CONTENT BODY -->
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
    <script src="{{ asset('assets/global/plugins/jquery-validation/js/jquery.validate.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/jquery-validation/js/additional-methods.min.js') }}" type="text/javascript"></script>

    <script src="{{ asset('assets/global/plugins/jquery-notific8/jquery.notific8.min.js') }}" type="text/javascript"></script>
@endsection

@section('themeBelowGlobalScripts')
    <script src="{{ asset('assets/global/scripts/app.min.js') }}" type="text/javascript"></script>
@endsection

@section('pageBelowLevelScripts')
    <script src="{{ asset('assets/pages/scripts/ui-notific8.min.js') }}" type="text/javascript"></script>
@endsection

@section('themeBelowLayoutScripts')
    <script src="{{ asset('assets/layouts/layout3/scripts/layout.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/layouts/global/scripts/quick-sidebar.min.js') }}" type="text/javascript"></script>
@endsection

@section('pageCustomJScripts')
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var FormValidation = function () {

            var handleValidation1 = function() {
                var form1 = $('#friend_search_form');
                var error1 = $('.alert-danger', form1);
                var success1 = $('.alert-success', form1);

                form1.validate({
                    errorElement: 'span', //default input error message container
                    errorClass: 'help-block help-block-error', // default input error message class
                    focusInvalid: false, // do not focus the last invalid input
                    ignore: "",  // validate all fields including form hidden input
                    rules: {
                        friend_phone_no: {
                            minlength: 8,
                            maxlength: 10,
                            number:true,
                            required: true
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
                        search_friend_by_phone($('input[name=friend_phone_no]').val()); // submit the form
                    }
                });
            }

            return {
                //main function to initiate the module
                init: function () {
                    handleValidation1();
                }
            };

        }();

        jQuery(document).ready(function() {
            // initialize the forms validation part
            FormValidation.init();
        });

        function search_friend_by_phone(input_nr){
            $.ajax({
                url: '{{route('ajax/add_friend_by_phone')}}',
                type: "post",
                cache: false,
                data: {
                    'phone_no':    input_nr,
                },
                success: function(data){
                    if (data.success=='true') {
                        show_notification('Friend Added', 'Your have added ' + data.full_name + ' as a friend. You can now book an activity and include him.', 'lemon', 3500, 0);
                        setTimeout(function(){
                            $('#new_friend_modal').modal('hide');
                            location.reload();
                        }, 1500);
                    }
                    else{
                        show_notification(data.error.title, data.error.message, 'lemon', 3500, 0);
                    }
                }
            });
        }

        function add_new_friend_popup(){
            $('#new_friend_modal').modal('show');
        }

        $(document).on('click', '.remove_friend', function(){
            var friend_name  = $(this).parent().parent().find('td:eq(0)').find('a').html();
            var friend_email = $(this).parent().parent().find('td:eq(1)').find('a').html();
            $('.friend_name_cancel_place').html(friend_name + ' - ' + friend_email);

            $('input[name="to_remove_friend"]').val($(this).attr('data-id'));

            $('#small_remove_friend').modal('show');
        });

        function remove_friend(){
            var search_key = $('input[name="to_remove_friend"]').val();

            $.ajax({
                url: '{{route('ajax/remove_friend_from_list')}}',
                type: "post",
                cache: false,
                data: {
                    'search_key' : search_key
                },
                success: function (data) {
                    if(data.success){
                        show_notification(data.title, data.message, 'lime', 3500, 0);
                        $('a[data-id="' + search_key + '"').parent().parent().remove();
                    }
                    else{
                        show_notification(data.title, data.errors, 'ruby', 3500, 0);
                    }

                    $('#small_remove_friend').find('.friend_name_cancel_place').html('');
                    $('input[name="to_remove_friend"]').val(-1);
                    $('#small_remove_friend').modal('hide');
                }
            });
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
    </script>
@endsection