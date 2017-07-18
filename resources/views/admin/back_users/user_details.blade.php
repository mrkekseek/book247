@extends('admin.layouts.main')

@section('pageLevelPlugins')
    <link href="{{ asset('assets/global/plugins/dropzone/dropzone.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/dropzone/basic.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('themeGlobalStyle')
    <link href="{{ asset('assets/global/css/components-rounded.min.css') }}" rel="stylesheet" id="style_components" type="text/css" />
    <link href="{{ asset('assets/global/css/plugins.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/jquery-notific8/jquery.notific8.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('themeLayoutStyle')
    <!-- BEGIN PAGE LEVEL STYLES -->
    <link href="{{ asset('assets/pages/css/profile-2.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL STYLES -->
    <link href="{{ asset('assets/layouts/layout4/css/layout.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/layouts/layout4/css/themes/light.min.css') }}" rel="stylesheet" type="text/css" id="style_color" />
    <link href="{{ asset('assets/layouts/layout4/css/custom.min.css') }}" rel="stylesheet" type="text/css" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection

@section('title', 'Back-end users - User Details')
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
        <div class="profile">
            <div class="tabbable-line tabbable-full-width">
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a href="#tab_1_1" data-toggle="tab"> Overview </a>
                    </li>
                    <li>
                        <a href="#tab_1_3" data-toggle="tab"> Account </a>
                    </li>
                    <li>
                        <a href="#tab_1_9" data-toggle="tab"> Personal </a>
                    </li>
                    <li>
                        <a href="#tab_1_6" data-toggle="tab"> Help </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab_1_1">
                        <div class="row">
                            <div class="col-md-3">
                                <ul class="list-unstyled profile-nav">
                                    <li>
                                        <img src="{{$avatar}}" class="img-responsive pic-bordered" alt="" />
                                        <a data-toggle="modal" href="#draggable" class="profile-edit"> edit </a>
                                    </li>
                                    <li>
                                        <a href="javascript:;"> Bookings </a>
                                    </li>
                                    <li>
                                        <a href="javascript:;"> Invoices
                                            <span> 3 unpaid </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:;"> Hours Worked </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-8 profile-info">
                                        <h1 class="font-green sbold uppercase">{{$user->first_name.' '.$user->middle_name.' '.$user->last_name}}</h1>
                                        <p> Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt laoreet dolore magna aliquam tincidunt erat volutpat laoreet dolore magna aliquam tincidunt erat volutpat.
                                        </p>
                                        <p>
                                            <a href="javascript:;"> www.mywebsite.com </a>
                                        </p>
                                        <ul class="list-inline">
                                            <li>
                                                <i class="fa fa-map-marker"></i> {{ isset($countryDetails->full_name) ? $countryDetails->full_name : 'nationality not set' }} </li>
                                            <li>
                                                <i class="fa fa-calendar"></i> {{ isset($personal->dob_to_show) ? $personal->dob_to_show : 'date of birth not set' }} </li>
                                            <li>
                                                <i class="fa fa-briefcase"></i> {{ isset($professional->job_title) ? $professional->job_title : 'job title' }} </li>
                                            <li>
                                                <i class="fa fa-star"></i> {{ isset($professional->profession) ? $professional->profession : 'profession' }} </li>
                                        </ul>
                                    </div>
                                    <!--end col-md-8-->
                                    <div class="col-md-4">
                                        <div class="portlet sale-summary">
                                            <div class="portlet-title">
                                                <div class="caption font-red sbold"> Hours worked </div>
                                                <div class="tools">
                                                    <a class="reload" href="javascript:;"> </a>
                                                </div>
                                            </div>
                                            <div class="portlet-body">
                                                <ul class="list-unstyled">
                                                    <li>
                                                                    <span class="sale-info"> This Week
                                                                        <i class="fa fa-img-up"></i>
                                                                    </span>
                                                        <span class="sale-num"> 23 </span>
                                                    </li>
                                                    <li>
                                                                    <span class="sale-info"> This Month
                                                                        <i class="fa fa-img-down"></i>
                                                                    </span>
                                                        <span class="sale-num"> 87 </span>
                                                    </li>
                                                    <li>
                                                        <span class="sale-info"> Last Month </span>
                                                        <span class="sale-num"> 177 </span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end col-md-4-->
                                </div>
                                <!--end row-->
                                <div class="tabbable-line tabbable-custom-profile">
                                    <ul class="nav nav-tabs">
                                        <li class="active">
                                            <a href="#tab_1_11" data-toggle="tab"> Latest Customers </a>
                                        </li>
                                        <li>
                                            <a href="#tab_1_22" data-toggle="tab"> Feeds </a>
                                        </li>
                                    </ul>
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="tab_1_11">
                                            <div class="portlet-body">
                                                <table class="table table-striped table-bordered table-advance table-hover">
                                                    <thead>
                                                    <tr>
                                                        <th>
                                                            <i class="fa fa-briefcase"></i> Company </th>
                                                        <th class="hidden-xs">
                                                            <i class="fa fa-question"></i> Descrition </th>
                                                        <th>
                                                            <i class="fa fa-bookmark"></i> Amount </th>
                                                        <th> </th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                        <td>
                                                            <a href="javascript:;"> Pixel Ltd </a>
                                                        </td>
                                                        <td class="hidden-xs"> Server hardware purchase </td>
                                                        <td> 52560.10$
                                                            <span class="label label-success label-sm"> Paid </span>
                                                        </td>
                                                        <td>
                                                            <a class="btn btn-sm grey-salsa btn-outline" href="javascript:;"> View </a>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <a href="javascript:;"> Smart House </a>
                                                        </td>
                                                        <td class="hidden-xs"> Office furniture purchase </td>
                                                        <td> 5760.00$
                                                            <span class="label label-warning label-sm"> Pending </span>
                                                        </td>
                                                        <td>
                                                            <a class="btn btn-sm grey-salsa btn-outline" href="javascript:;"> View </a>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <a href="javascript:;"> FoodMaster Ltd </a>
                                                        </td>
                                                        <td class="hidden-xs"> Company Anual Dinner Catering </td>
                                                        <td> 12400.00$
                                                            <span class="label label-success label-sm"> Paid </span>
                                                        </td>
                                                        <td>
                                                            <a class="btn btn-sm grey-salsa btn-outline" href="javascript:;"> View </a>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <!--tab-pane-->
                                        <div class="tab-pane" id="tab_1_22">
                                            <div class="tab-pane active" id="tab_1_1_1">
                                                <div class="scroller" data-height="290px" data-always-visible="1" data-rail-visible1="1">
                                                    <ul class="feeds">
                                                        <li>
                                                            <div class="col1">
                                                                <div class="cont">
                                                                    <div class="cont-col1">
                                                                        <div class="label label-success">
                                                                            <i class="fa fa-bell-o"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="cont-col2">
                                                                        <div class="desc"> You have 4 pending tasks.
                                                                                        <span class="label label-danger label-sm"> Take action
                                                                                            <i class="fa fa-share"></i>
                                                                                        </span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col2">
                                                                <div class="date"> Just now </div>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <a href="javascript:;">
                                                                <div class="col1">
                                                                    <div class="cont">
                                                                        <div class="cont-col1">
                                                                            <div class="label label-success">
                                                                                <i class="fa fa-bell-o"></i>
                                                                            </div>
                                                                        </div>
                                                                        <div class="cont-col2">
                                                                            <div class="desc"> New version v1.4 just lunched! </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col2">
                                                                    <div class="date"> 20 mins </div>
                                                                </div>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <div class="col1">
                                                                <div class="cont">
                                                                    <div class="cont-col1">
                                                                        <div class="label label-danger">
                                                                            <i class="fa fa-bolt"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="cont-col2">
                                                                        <div class="desc"> Database server #12 overloaded. Please fix the issue. </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col2">
                                                                <div class="date"> 24 mins </div>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="col1">
                                                                <div class="cont">
                                                                    <div class="cont-col1">
                                                                        <div class="label label-info">
                                                                            <i class="fa fa-bullhorn"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="cont-col2">
                                                                        <div class="desc"> New order received. Please take care of it. </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col2">
                                                                <div class="date"> 30 mins </div>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <!--tab-pane-->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--tab_1_2-->
                    <div class="tab-pane" id="tab_1_3">
                        <div class="row profile-account">
                            <div class="col-md-3">
                                <ul class="ver-inline-menu tabbable margin-bottom-10">
                                    <li class="active">
                                        <a data-toggle="tab" href="#tab_1-1">
                                            <i class="fa fa-cog"></i> Account info </a>
                                        <span class="after"> </span>
                                    </li>
                                    <li>
                                        <a data-toggle="tab" href="#tab_2-2">
                                            <i class="fa fa-picture-o"></i> Change Avatar </a>
                                    </li>
                                    <li>
                                        <a data-toggle="tab" href="#tab_3-3">
                                            <i class="fa fa-lock"></i> Change Password </a>
                                    </li>
                                    <li>
                                        <a data-toggle="tab" href="#tab_4-4">
                                            <i class="fa fa-eye"></i> Account Permissions </a>
                                    </li>
                                    <li>
                                        <a data-toggle="tab" href="#tab_4-5">
                                            <i class="fa fa-eye"></i> Account Documents </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-9">
                                <div class="tab-content">
                                    <div id="tab_1-1" class="tab-pane active">
                                        <form role="form" action="#" id="form_acc_info">
                                            
                                            <div class="alert alert-danger display-hide">
                                                <button class="close" data-close="alert"></button> You have some form errors. Please check below. </div>
                                            <div class="alert alert-success display-hide">
                                                <button class="close" data-close="alert"></button> Your form validation is successful! </div>
                                            <div class="form-group">
                                                <label class="control-label">Full Name</label>
                                                <div class="input-icon">
                                                    <i class="fa"></i>
                                                    <input type="text" placeholder="Full Name" value="{{  $user->first_name .' '. $user->middle_name .' '. $user->last_name }}" class="form-control" readonly="readonly" /> </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label">Username</label>
                                                <div class="input-icon">
                                                    <i class="fa"></i>
                                                    <input type="text" name="accountUsername" id="accountUsername" placeholder="Username" value="{{$user->username}}" class="form-control" /> </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label">Email Address</label>
                                                <div class="input-icon">
                                                    <i class="fa"></i>
                                                    <input type="text" name="accountEmail" id="accountEmail" placeholder="Registration Email" value="{{ $user->email }}" class="form-control" /> </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label">Job Title</label>
                                                <div class="input-icon">
                                                    <i class="fa"></i>
                                                    <input type="text" name="accountJobTitle" id="accountJobTitle" placeholder="Design, Web etc." value="{{ @$professional->job_title }}" class="form-control" /> </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label">Profession</label>
                                                <div class="input-icon">
                                                    <i class="fa"></i>
                                                    <input type="text" name="accountProfession" id="accountProfession" value="{{ @$professional->profession }}" placeholder="Design, Web etc." class="form-control" /> </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label">Description</label>
                                                <div class="input-icon">
                                                    <i class="fa"></i>
                                                    <textarea name="accountDescription" id="accountDescription" class="form-control" rows="3" placeholder="We are KeenThemes!!!">{{ @$professional->description }}</textarea> </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label">User Role</label>
                                                <div class="input-icon">
                                                    <i class="fa"></i>
                                                    <select name="employeeRole" id="employeeRole" class="form-control">
                                                    @foreach ($roles as $role)
                                                        <option value="{{$role->id}}" {!! $role->id==$userRole->id?' selected="selected" ':'' !!}>{{$role->name}}</option>
                                                    @endforeach
                                                    </select> </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label">Default Location</label>
                                                <div class="input-icon">
                                                    <i class="fa"></i>
                                                    <select class="form-control" name="settings_preferred_location">
                                                        <option> Select Location </option>
                                                        @foreach ($locations as $location)
                                                            <option {!! @$settings['settings_preferred_location']==$location->id?' selected="selected" ':'' !!} value="{{$location->id}}">{{ $location->name }}</option>
                                                        @endforeach
                                                    </select> </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label">Default Activity</label>
                                                <div class="input-icon">
                                                    <i class="fa"></i>
                                                    <select class="form-control" name="settings_preferred_activity">
                                                        <option> Select Activity </option>
                                                        @foreach ($activities as $activity)
                                                            <option {!! @$settings['settings_preferred_activity']==$activity->id?' selected="selected" ':'' !!} value="{{$activity->id}}">{{ $activity->name }}</option>
                                                        @endforeach
                                                    </select> </div>
                                            </div>
                                            <div class="margiv-top-10">
                                                <a href="javascript:;" onclick="javascript: $('#form_acc_info').submit();" class="btn green"> Save Changes </a>
                                                <a href="javascript:;" class="btn default"> Cancel </a>
                                            </div>
                                        </form>
                                    </div>
                                    <div id="tab_2-2" class="tab-pane">
                                        <form action="{{ route('admin/back_users/view_user/avatar_image', ['id'=>$user->id]) }}" id="user_picture_upload2" class="form-horizontal" method="post" enctype="multipart/form-data">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <div class="form-group">
                                                <div class="fileinput fileinput-{{ (strlen($avatar)>10) ? 'exists':'new' }}" data-provides="fileinput">
                                                    <div class="fileinput-new thumbnail" style="width: 200px; height: 244px;">
                                                        <img src="{{ asset('assets/global/img/default-notext-text.png') }}" alt="" /> </div>
                                                    <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 240px; line-height: 200px;">
                                                    @if ( strlen($avatar)>10 )
                                                        <img src="{{ $avatar }}" />
                                                    @endif
                                                    </div>
                                                    <div>
                                                        <span class="btn default btn-file">
                                                            <span class="fileinput-new"> Select image </span>
                                                            <span class="fileinput-exists"> Change </span>
                                                            <input type="file" name="user_avatar" class="user_avatar_select_btn2" /> </span>

                                                        <a href="javascript:;" class="btn default fileinput-exists remove-avatar" data-dismiss="fileinput"> Remove </a>
                                                    </div>
                                                </div>
                                                <div class="clearfix margin-top-10">
                                                    <div class="note note-warning margin-bottom-5">
                                                        <p> Image preview only works in IE10+, FF3.6+, Safari6.0+, Chrome6.0+ and Opera11.1+. In older browsers the filename is shown instead. </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="margin-top-10">
                                                <a href="javascript:;" onclick="javascript: $('#user_picture_upload2').submit();" class="btn green"> Submit </a>
                                                <a href="javascript:;" class="btn default"> Cancel </a>
                                            </div>
                                        </form>
                                    </div>
                                    <div id="tab_3-3" class="tab-pane">
                                        <form action="#" id="form_password_update" role="form">
                                            <div class="alert alert-danger display-hide">
                                                <button class="close" data-close="alert"></button> You have some form errors. Please check below. </div>
                                            <div class="alert alert-success display-hide">
                                                <button class="close" data-close="alert"></button> Your form validation is successful! </div>
                                            @if (Auth::user()->id!=$user->id && !Auth::user()->can('manage-employees'))
                                            <div class="form-group">
                                                <label class="control-label">Old Password</label>
                                                <div class="input-icon">
                                                    <i class="fa"></i>
                                                    <input type="password" name="old_password" id="old_password" class="form-control" /> </div>
                                            </div>
                                            @endif
                                            <div class="form-group">
                                                <label class="control-label">New Password</label>
                                                <div class="input-icon">
                                                    <i class="fa"></i>
                                                    <input type="password" name="new_password1" id="new_password1" class="form-control" /> </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label">Re-type New Password</label>
                                                <div class="input-icon">
                                                    <i class="fa"></i>
                                                    <input type="password" name="new_password2" id="new_password2" class="form-control" /> </div>
                                            </div>
                                            <div class="margin-top-10">
                                                <a href="javascript:;" class="btn green" onClick="javascript: $('#form_password_update').submit();"> Change Password </a>
                                                <a href="javascript:;" class="btn default"> Cancel </a>
                                            </div>
                                        </form>
                                    </div>
                                    <div id="tab_4-4" class="tab-pane">
                                        <form action="#">
                                            <table class="table table-bordered table-striped">
                                                @foreach ($permissions as $permission)
                                                    <tr>
                                                        <td> {{ $permission->display_name }}
                                                            <span class="help-block"> {{ $permission->description }} </span></td>
                                                        <td>
                                                            <label class="uniform-inline">
                                                                <input type="radio" name="optionsRadios{{rand(10,1000)}}" value="option1" {{ $user->can($permission->name) ? 'checked' :''}} disabled /> Yes </label>
                                                            <label class="uniform-inline">
                                                                <input type="radio" name="optionsRadios{{rand(10,1000)}}" value="option2" {{ ! $user->can($permission->name) ? 'checked' :''}} disabled /> No </label>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </table>
                                            <!--end profile-settings-->
                                            <!--<div class="margin-top-10">
                                                <a href="javascript:;" class="btn green"> Save Changes </a>
                                                <a href="javascript:;" class="btn default"> Cancel </a>
                                            </div>-->
                                        </form>
                                    </div>
                                    <div id="tab_4-5" class="tab-pane">
                                        <div class="portlet box blue">
                                            <div class="portlet-title">
                                                <div class="caption">
                                                    <i class="fa fa-gift"></i>Upload Documents </div>
                                                <div class="tools">
                                                    <a class="expand" href="javascript:;" data-original-title="" title=""> </a>
                                                </div>
                                            </div>
                                            <div class="portlet-body" style="display: none;">
                                                <div class="m-heading-1 border-green m-bordered">
                                                    <h3>Documents Dropzone</h3>
                                                    <p> Select the documents you want to add, documents related to this specific user, and upload them once you added all of them to the dropbox area. </p>
                                                </div>
                                                <form action="{{ route('admin/back_users/view_user/add_document', ['id'=>$user->id]) }}" class="dropzone dropzone-file-area" id="my-dropzone" style="width: 500px; margin-top: 50px;">
                                                    <h3 class="sbold">Drop files here or click to upload</h3>
                                                    <p> This is just a demo dropzone. Selected files are not actually uploaded. </p>
                                                </form>
                                            </div>
                                        </div>

                                        <div class="portlet light portlet-fit bordered">
                                            <div class="portlet-title">
                                                <div class="caption">
                                                    <i class=" icon-layers font-green"></i>
                                                    <span class="caption-subject font-green bold uppercase">Uploaded documents [page needs to be reloaded for latest files to be shown]</span>
                                                    <div class="caption-desc font-grey-cascade"> hire documents, national identification card, etc. </div>
                                                </div>
                                            </div>
                                            <div class="portlet-body">
                                                <div class="mt-element-list">
                                                    <div class="mt-list-container list-simple ext-1">
                                                        <ul>
                                                            @foreach ($documents as $document)
                                                            <li class="mt-list-item">
                                                                <div class="list-icon-container">
                                                                    <i class="icon-check"></i>
                                                                </div>
                                                                <div class="list-datetime"> {{ $document->created_at->format('m/d/y') }} </div>
                                                                <div class="list-item-content">
                                                                    <h3 class="uppercase">
                                                                        <a href="{{ route('admin/back_user/get_document', [ 'id' => $user->id , 'document_name'=> $document->file_name ]) }}" target="_blank">{{ $document->file_name }}</a>
                                                                    </h3>
                                                                </div>
                                                            </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end col-md-9-->
                        </div>
                    </div>
                    <!--end tab-pane-->
                    <div class="tab-pane" id="tab_1_6">
                        <div class="row">
                            <div class="col-md-2">
                                <ul class="ver-inline-menu tabbable margin-bottom-10">
                                    <li class="active">
                                        <a data-toggle="tab" href="#tab_1">
                                            <i class="fa fa-briefcase"></i> General Questions </a>
                                        <span class="after"> </span>
                                    </li>
                                    <li>
                                        <a data-toggle="tab" href="#tab_2">
                                            <i class="fa fa-group"></i> Membership </a>
                                    </li>
                                    <li>
                                        <a data-toggle="tab" href="#tab_3">
                                            <i class="fa fa-leaf"></i> Terms Of Service </a>
                                    </li>
                                    <li>
                                        <a data-toggle="tab" href="#tab_1">
                                            <i class="fa fa-info-circle"></i> License Terms </a>
                                    </li>
                                    <li>
                                        <a data-toggle="tab" href="#tab_2">
                                            <i class="fa fa-tint"></i> Payment Rules </a>
                                    </li>
                                    <li>
                                        <a data-toggle="tab" href="#tab_3">
                                            <i class="fa fa-plus"></i> Other Questions </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-10">
                                <div class="tab-content">
                                    <div id="tab_1" class="tab-pane active">
                                        <div id="accordion1" class="panel-group">
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h4 class="panel-title">
                                                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#accordion1_1"> 1. Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry ? </a>
                                                    </h4>
                                                </div>
                                                <div id="accordion1_1" class="panel-collapse collapse in">
                                                    <div class="panel-body"> Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt
                                                        laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes
                                                        anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't
                                                        heard of them accusamus labore sustainable VHS. </div>
                                                </div>
                                            </div>
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h4 class="panel-title">
                                                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#accordion1_2"> 2. Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry ? </a>
                                                    </h4>
                                                </div>
                                                <div id="accordion1_2" class="panel-collapse collapse">
                                                    <div class="panel-body"> Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Anim pariatur cliche reprehenderit,
                                                        enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf
                                                        moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente
                                                        ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable
                                                        VHS. </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="tab_2" class="tab-pane">
                                        <div id="accordion2" class="panel-group">
                                            <div class="panel panel-warning">
                                                <div class="panel-heading">
                                                    <h4 class="panel-title">
                                                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#accordion2_1"> 1. Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry ? </a>
                                                    </h4>
                                                </div>
                                                <div id="accordion2_1" class="panel-collapse collapse in">
                                                    <div class="panel-body">
                                                        <p> Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt
                                                            laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore
                                                            wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably
                                                            haven't heard of them accusamus labore sustainable VHS. </p>
                                                        <p> Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt
                                                            laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore
                                                            wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably
                                                            haven't heard of them accusamus labore sustainable VHS. </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="panel panel-danger">
                                                <div class="panel-heading">
                                                    <h4 class="panel-title">
                                                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#accordion2_2"> 2. Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry ? </a>
                                                    </h4>
                                                </div>
                                                <div id="accordion2_2" class="panel-collapse collapse">
                                                    <div class="panel-body"> Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Anim pariatur cliche reprehenderit,
                                                        enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf
                                                        moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente
                                                        ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable
                                                        VHS. </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="tab_3" class="tab-pane">
                                        <div id="accordion3" class="panel-group">
                                            <div class="panel panel-danger">
                                                <div class="panel-heading">
                                                    <h4 class="panel-title">
                                                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion3" href="#accordion3_1"> 1. Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry ? </a>
                                                    </h4>
                                                </div>
                                                <div id="accordion3_1" class="panel-collapse collapse in">
                                                    <div class="panel-body">
                                                        <p> Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt
                                                            laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. </p>
                                                        <p> Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt
                                                            laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. </p>
                                                        <p> Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica,
                                                            craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt
                                                            you probably haven't heard of them accusamus labore sustainable VHS. </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="panel panel-success">
                                                <div class="panel-heading">
                                                    <h4 class="panel-title">
                                                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion3" href="#accordion3_2"> 2. Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry ? </a>
                                                    </h4>
                                                </div>
                                                <div id="accordion3_2" class="panel-collapse collapse">
                                                    <div class="panel-body"> Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Anim pariatur cliche reprehenderit,
                                                        enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf
                                                        moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente
                                                        ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable
                                                        VHS. </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end tab-pane-->
                    <!--tab_1_9-->
                    <div class="tab-pane" id="tab_1_9">
                        <div class="row profile-account">
                            <div class="col-md-3">
                                <ul class="ver-inline-menu tabbable margin-bottom-10">
                                    <li class="active">
                                        <a data-toggle="tab" href="#tab_5-5">
                                            <i class="fa fa-cog"></i> Personal info </a>
                                        <span class="after"> </span>
                                    </li>
                                    <li>
                                        <a data-toggle="tab" href="#tab_6-6">
                                            <i class="fa fa-eye"></i> Home Address </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-9">
                                <div class="tab-content">
                                    <div id="tab_5-5" class="tab-pane active">
                                        <form role="form" action="#" name="form_acc_personal" id="form_acc_personal">
                                           
                                            <div id="errors_list">
                                            </div>

                                            <div class="alert alert-danger display-hide">
                                                <button class="close" data-close="alert"></button> You have some form errors. Please check below. </div>
                                            <div class="alert alert-success display-hide">
                                                <button class="close" data-close="alert"></button> Your form validation is successful! </div>
                                            <div class="form-group">
                                                <label class="control-label">First Name</label>
                                                <input type="text" name="personalFirstName" id="personalFirstName" placeholder="First Name" value="{{$user->first_name}}" class="form-control" /> </div>
                                            <div class="form-group">
                                                <label class="control-label">Middle Name</label>
                                                <input type="text" name="personalMiddleName" id="personalMiddleName" placeholder="Middle Name" value="{{$user->middle_name}}" class="form-control" /> </div>
                                            <div class="form-group">
                                                <label class="control-label">Last Name</label>
                                                <input type="text" name="personalLastName" id="personalLastName" placeholder="Last Name" value="{{$user->last_name}}" class="form-control" /> </div>
                                            <div class="form-group">
                                                <label class="control-label">Gender</label>
                                                <select name="gender" class="form-control">
                                                    <option>Select Gender</option>
                                                    <option {!! $user->gender=='F'?'selected="selected"':'' !!} value="F"> Female </option>
                                                    <option {!! $user->gender=='M'?'selected="selected"':'' !!} value="M"> Male </option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label">Citizenship</label>
                                                <select name="personalCountry" id="personalCountry" class="form-control">
                                                    @foreach ($countries as $country)
                                                        <option value="{{ $country->id }}" {!! ($country->id==$user->country_id ? ' selected="selected" ' : '') !!}>{{ $country->name }}</option>
                                                    @endforeach
                                                </select></div>
                                            <div class="form-group">
                                                <label class="control-label">Date of Birth</label>
                                                <div class="control-label">
                                                    <div class="input-group input-medium date date-picker" data-date="{{ @$personal->dob_format }}" data-date-format="dd-mm-yyyy" data-date-viewmode="years" data-date-end-date="-0d">
                                                        <input type="text" class="form-control" name="personalDOB" id="personalDOB" value="{{ @$personal->dob_format }}" readonly>
                                                        <span class="input-group-btn">
                                                            <button class="btn default" type="button">
                                                                <i class="fa fa-calendar"></i>
                                                            </button>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label">Personal Email</label>
                                                <input type="text" name="personalEmail" id="personalEmail" placeholder="Personal Email Address" class="form-control" value="{{@$personal->personal_email}}" /> </div>
                                            <div class="form-group">
                                                <label class="control-label">Mobile Phone Number</label>
                                                <input type="text" name="personalPhone" id="personalPhone" placeholder="+1 234 567 8910 (6284)" class="form-control" value="{{@$personal->mobile_number}}" /> </div>
                                            <div class="form-group">
                                                <label class="control-label">Bank Account Number</label>
                                                <input type="text" name="personalBankAcc" id="personalBankAcc" placeholder="Bank account" class="form-control" value="{{@$personal->bank_acc_no}}" /> </div>
                                            <div class="form-group">
                                                <label class="control-label">Social Security Number [SSN]</label>
                                                <input type="text" name="personalSSN" id="personalSSN" placeholder="Social Security Number" class="form-control" value="{{@$personal->social_sec_no}}" /> </div>
                                            <div class="form-group">
                                                <label class="control-label">About</label>
                                                <textarea name="personalAbout" id="personalAbout" class="form-control" rows="3" placeholder="Other personal information">{{@$personal->about_info}}</textarea>
                                            </div>
                                            <div class="margiv-top-10">
                                                <a href="javascript:;" class="btn green" onClick="javascript: $('#form_acc_personal').submit();" > Save Changes </a>
                                                <a href="javascript:;" class="btn default"> Cancel </a>
                                            </div>
                                        </form>
                                    </div>
                                    <div id="tab_6-6" class="tab-pane">
                                        <form action="#" name="form_personal_address" id="form_personal_address">
                                            <div class="alert alert-danger display-hide">
                                                <button class="close" data-close="alert"></button> You have some form errors. Please check below. </div>
                                            <div class="alert alert-success display-hide">
                                                <button class="close" data-close="alert"></button> Your form validation is successful! </div>
                                            <div class="form-group">
                                                <label class="control-label">Address Line1</label>
                                                <input type="text" name="personal_addr1" id="personal_addr1" placeholder="Address1" value="{{ $personalAddress->address1 }}" class="form-control" /> </div>
                                            <div class="form-group">
                                                <label class="control-label">Address Line2</label>
                                                <input type="text" name="personal_addr2" id="personal_addr2" placeholder="Address2" value="{{ $personalAddress->address2 }}" class="form-control" /> </div>
                                            <div class="form-group">
                                                <label class="control-label">City</label>
                                                <input type="text" name="personal_addr_city" id="personal_addr_city" placeholder="City" value="{{ $personalAddress->city }}" class="form-control" /> </div>
                                            <div class="form-group">
                                                <label class="control-label">Region</label>
                                                <input type="text" name="personal_addr_region" id="personal_addr_region" placeholder="Region" value="{{ $personalAddress->region }}" class="form-control" /> </div>
                                            <div class="form-group">
                                                <label class="control-label">Postal Code</label>
                                                <input type="text" name="personal_addr_pcode" id="personal_addr_pcode" placeholder="Postal Code" value="{{ $personalAddress->postal_code }}" class="form-control" /> </div>
                                            <div class="form-group">
                                                <label class="control-label">Country</label>
                                                <select class="form-control" name="personal_addr_country" id="personal_addr_country">
                                                    @foreach ($countries as $country)
                                                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                                                    @endforeach
                                                </select></div>
                                            <div class="margiv-top-10">
                                                <a href="javascript:;" class="btn green" onclick="javascript: $('#form_personal_address').submit();"> Save Changes </a>
                                                <a href="javascript:;" class="btn default"> Cancel </a>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!--end col-md-9-->
                        </div>
                    </div>
                    <!--end tab-pane-->
                </div>
            </div>

            <div class="modal fade draggable-modal" id="draggable" tabindex="-1" role="basic" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                            <h4 class="modal-title">Update user avatar picture</h4>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('admin/back_users/view_user/avatar_image', ['id'=>$user->id]) }}" id="user_picture_upload1" class="form-horizontal" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div class="form-body">
                                    <div class="alert alert-danger display-hide">
                                        <button class="close" data-close="alert"></button> You have some form errors. Please check below. </div>
                                    <div class="alert alert-success display-hide">
                                        <button class="close" data-close="alert"></button> Your form validation is successful! </div>
                                    <div class="form-group last">
                                        <label class="control-label col-md-3">User Avatar</label>
                                        <div class="col-md-9">
                                            <div class="fileinput fileinput-{{ (strlen($avatar)>10) ? 'exists':'new' }} " data-provides="fileinput">
                                                <div class="fileinput-new thumbnail" style="width: 200px; height: 244px;">
                                                    <img src="{{ asset('assets/global/img/default-notext-text.png') }}" alt="" /> </div>
                                                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 240px;">
                                                    @if ( strlen($avatar)>10 )
                                                        <img src="{{ $avatar }}" />
                                                    @endif
                                                </div>
                                                <div>
                                                    <span class="btn default btn-file">
                                                        <span class="fileinput-new"> Select image </span>
                                                        <span class="fileinput-exists"> Change </span>
                                                        <input type="file" name="user_avatar" class="user_avatar_select_btn1" /> </span>
                                                    <a href="javascript:;" class="btn red fileinput-exists remove-avatar" data-dismiss="fileinput"> Remove </a>
                                                </div>
                                            </div>
                                            <div class="clearfix margin-top-10">
                                                <div class="note note-warning margin-bottom-5">
                                                    <p> Image preview only works in IE10+, FF3.6+, Safari6.0+, Chrome6.0+ and Opera11.1+. In older browsers the filename is shown instead. </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                            <button type="button" class="btn green submit_form_2" onclick="javascript: $('#user_picture_upload1').submit();">Save changes</button>
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

@section('pageBelowLevelPlugins')
    <script src="{{ asset('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js') }}" type="text/javascript"></script>
    <script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/gmaps/gmaps.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/jquery-validation/js/jquery.validate.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/jquery-validation/js/additional-methods.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}" type="text/javascript"></script>
@endsection

@section('pageBelowLevelScripts')
    <script src="{{ asset('assets/global/plugins/jquery-notific8/jquery.notific8.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/jquery.blockui.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/dropzone/dropzone.min.js') }}" type="text/javascript"></script>
@endsection

@section('themeBelowLayoutScripts')
    <script src="{{ asset('assets/layouts/layout4/scripts/layout.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/layouts/layout4/scripts/demo.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/layouts/global/scripts/quick-sidebar.min.js') }}" type="text/javascript"></script>
@endsection

@section('pageCustomJScripts')
    <script type="text/javascript">
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
                            email: true,
                            validate_email:true
                        },
                        gender: {
                            required:true,
                            minlength:1,
                        },
                        personalPhone : {
                            required: true,
                            digits: true,
                            minlength: 8,
                            maxlength: 20,
                            remote: {
                                url: "{{ route('ajax/check_phone_for_member_registration') }}",
                                type: "post",
                                data: {
                                    phone: function() {
                                        return $( "input[name='personalPhone']" ).val();
                                    }
                                }
                            }
                        }
                    },

                    invalidHandler: function (event, validator) { //display error alert on form submit
                        success1.hide();

                        var errors_list = "";
                        for(var i in validator.errorList)
                        {
                            errors_list  += "<div class='alert alert-danger'>";
                            errors_list  += $(validator.errorList[i].element).parent().find("label").text() + ": ";
                            errors_list  += validator.errorList[i].message + "</div>";
                        }

                        $("#errors_list").html(errors_list);
                        
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
                        $("#errors_list").empty();
                        success1.show();
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
                            email: true,
                            validate_email:true
                        },
                        settings_preferred_activity: {
                            required:true,
                            minlength: 1
                        },
                        settings_preferred_location: {
                            required:true,
                            minlength: 1
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
                        @if (Auth::user()->id!=$user->id || !$user->can('manage-employees'))
                        old_password: {
                            minlength: 8,
                            required: true,
                        },
                        @endif
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

        var FormDropzone = function () {
            return {
                //main function to initiate the module
                init: function () {

                    Dropzone.options.myDropzone = {
                        paramName: "user_doc", // The name that will be used to transfer the file
                        maxFilesize: 20, // MB
                        acceptedFiles: "image/jpeg,image/png,application/pdf,.psd,.doc,.docx,.xls,.xlsx,.JPG",
                        dictDefaultMessage: '',
                        dictResponseError: 'Error uploading file!',
                        init: function() {
                            this.on("sending", function(file, xhr, data) {
                                data.append("_token", '{{ csrf_token() }}');
                            });
                            this.on("addedfile", function(file) {
                                // Create the remove button
                                var removeButton = Dropzone.createElement("<a href='javascript:;'' class='btn red btn-sm btn-block'>Remove</a>");

                                // Capture the Dropzone instance as closure.
                                var _this = this;

                                // Listen to the click event
                                removeButton.addEventListener("click", function(e) {
                                    // Make sure the button click doesn't submit the form:
                                    e.preventDefault();
                                    e.stopPropagation();

                                    // Remove the file preview.
                                    _this.removeFile(file);
                                    // If you want to the delete the file on the server as well,
                                    // you can do the AJAX request here.
                                });

                                // Add the button to the file preview element.
                                file.previewElement.appendChild(removeButton);
                            });
                        }
                    }
                }
            };
        }();

        $(document).ready(function(){
            FormValidation.init();
            FormDropzone.init();
            ComponentsDateTimePickers.init();

            $(".remove-avatar").click(function(){
                console.log('test')
                $.ajax({
                    url : "{{route('admin/back_users/remove_avatar')}}",
                    type : "post",
                    success : function(response)
                    {
                        if (response.success)
                        {
                            window.location.reload();
                        }
                    }
                });
            });
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
                    'settings_preferred_location': $('select[name="settings_preferred_location"]').val(),
                    'settings_preferred_activity': $('select[name="settings_preferred_activity"]').val(),
                    '_method': 'post',
                },
                success: function(data){
                    if (data.success) {
                        $('#change_member_status').modal('hide');
                        show_notification(data.title, data.message, 'lemon', 3500, 0);
                    }
                    else{
                        show_notification(data.title, data.errors, 'ruby', 3500, 0);
                    }
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
                    'gender':           $('select[name=gender]').val(),
                    '_method': 'post',
                },
                success: function(data){
                    if (data.success) {
                        $('#change_member_status').modal('hide');
                        show_notification(data.title, data.message, 'lime', 3500, 0);
                    }
                    else{
                        var msg_error = '';
                        $.each(data.errors, function(index, value){
                            $.each(value, function(index1, value1){
                                msg_error = msg_error + value1 + '<br />';
                            });
                        });
                        show_notification(data.title, msg_error, 'ruby', 3500, 0);
                    }
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
                    if (data.success) {
                        $('#change_member_status').modal('hide');
                        show_notification(data.title, data.message, 'lemon', 3500, 0);
                    }
                    else{
                        show_notification(data.title, data.errors, 'ruby', 3500, 0);
                    }
                }
            });
        }

        function update_passwd(){
            $.ajax({
                url: '{{route('admin/back_users/view_user/password_update', ['id'=>$user->id])}}',
                type: "post",
                data: {
                    @if (Auth::user()->id!=$user->id && !Auth::user()->can('manage-employees'))
                    'old_password': $('input[name=old_password]').val(),
                    @endif
                    'password1':    $('input[name=new_password1]').val(),
                    'password2':    $('input[name=new_password2]').val(),
                },
                success: function(data){
                    if (data.success) {
                        $('#change_member_status').modal('hide');
                        show_notification(data.title, data.message, 'lemon', 3500, 0);
                        setTimeout(function(){
                            location.reload();
                        },2000);
                    }
                    else{
                        show_notification(data.title, data.errors, 'ruby', 3500, 0);
                    }
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
    </script>
@endsection