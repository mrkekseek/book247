<?php
    $all_locations = \App\Http\Controllers\ShopController::list_all_locations();
?>
<div class="page-header-inner ">
    <!-- BEGIN LOGO -->
    <div class="page-logo">
        <a href="{{ route('admin') }}">
            <img height="55" style="height: 55px;margin-bottom: 4px;margin-top: 11px;"  src="{{ asset('assets/global/img/sqf-logo.png') }}" alt="logo" class="logo-default" /> </a>
        <div class="menu-toggler sidebar-toggler">
            <!-- DOC: Remove the above "hide" to enable the sidebar toggler button on header -->
        </div>
    </div>
    <!-- END LOGO -->
    <!-- BEGIN RESPONSIVE MENU TOGGLER -->
    <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse"> </a>
    <!-- END RESPONSIVE MENU TOGGLER -->
    <!-- BEGIN PAGE ACTIONS -->
    <!-- DOC: Remove "hide" class to enable the page header actions -->
    <div class="page-actions">
        <div class="btn-group">
            <!--<button type="button" class="btn red-haze btn-sm dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                <span class="hidden-sm hidden-xs">Shop Locations&nbsp;</span>
                <i class="fa fa-angle-down"></i>
            </button>
            <ul class="dropdown-menu" role="menu">
                @foreach ($all_locations as $location)
                    <li>
                        <a href="javascript:;" class="set_default_shop" data-id="{{$location->id}}">
                            <i class="icon-docs"></i> {{ $location->name }} </a>
                    </li>
                @endforeach
                <li class="divider"> </li>
                <li>
                    <a href="javascript:;" class="set_default_shop" data-id="-1">
                        <i class="icon-flag"></i> All Locations
                        <span class="badge badge-success">4</span>
                    </a>
                </li>
            </ul>-->
        </div>
    </div>
    <!-- END PAGE ACTIONS -->
    <!-- BEGIN PAGE TOP -->
    <div class="page-top">
        <!-- BEGIN HEADER SEARCH BOX -->
        <!-- DOC: Apply "search-form-expanded" right after the "search-form" class to have half expanded search box -->
        <div class="input-group" style="width:320px; display:inline-block; float:left; margin-top:21px; margin-right:5px;">
            <select id="header_search_member" name="header_search_member" class="form-control js-data-users-ajax">
                <option value="" selected="selected">Select...</option>
            </select>
        </div>
        <!-- END HEADER SEARCH BOX -->
        <!-- BEGIN TOP NAVIGATION MENU -->
        <div class="top-menu">
            <ul class="nav navbar-nav pull-right">
                <li class="separator hide"> </li>
                <!-- BEGIN NOTIFICATION DROPDOWN -->
                @if (isset($notifications_on))
                <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                <li class="dropdown dropdown-extended dropdown-notification dropdown-dark" id="header_notification_bar">
                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                        <i class="icon-bell"></i>
                        <span class="badge badge-success"> 7 </span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="external">
                            <h3>
                                <span class="bold">12 pending</span> notifications</h3>
                            <a href="page_user_profile_1.html">view all</a>
                        </li>
                        <li>
                            <ul class="dropdown-menu-list scroller" style="height: 250px;" data-handle-color="#637283">
                                <li>
                                    <a href="javascript:;">
                                        <span class="time">just now</span>
                                                    <span class="details">
                                                        <span class="label label-sm label-icon label-success">
                                                            <i class="fa fa-plus"></i>
                                                        </span> New user registered. </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:;">
                                        <span class="time">3 mins</span>
                                                    <span class="details">
                                                        <span class="label label-sm label-icon label-danger">
                                                            <i class="fa fa-bolt"></i>
                                                        </span> Server #12 overloaded. </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:;">
                                        <span class="time">10 mins</span>
                                                    <span class="details">
                                                        <span class="label label-sm label-icon label-warning">
                                                            <i class="fa fa-bell-o"></i>
                                                        </span> Server #2 not responding. </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:;">
                                        <span class="time">14 hrs</span>
                                                    <span class="details">
                                                        <span class="label label-sm label-icon label-info">
                                                            <i class="fa fa-bullhorn"></i>
                                                        </span> Application error. </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:;">
                                        <span class="time">2 days</span>
                                                    <span class="details">
                                                        <span class="label label-sm label-icon label-danger">
                                                            <i class="fa fa-bolt"></i>
                                                        </span> Database overloaded 68%. </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:;">
                                        <span class="time">3 days</span>
                                                    <span class="details">
                                                        <span class="label label-sm label-icon label-danger">
                                                            <i class="fa fa-bolt"></i>
                                                        </span> A user IP blocked. </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:;">
                                        <span class="time">4 days</span>
                                                    <span class="details">
                                                        <span class="label label-sm label-icon label-warning">
                                                            <i class="fa fa-bell-o"></i>
                                                        </span> Storage Server #4 not responding dfdfdfd. </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:;">
                                        <span class="time">5 days</span>
                                                    <span class="details">
                                                        <span class="label label-sm label-icon label-info">
                                                            <i class="fa fa-bullhorn"></i>
                                                        </span> System Error. </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:;">
                                        <span class="time">9 days</span>
                                                    <span class="details">
                                                        <span class="label label-sm label-icon label-danger">
                                                            <i class="fa fa-bolt"></i>
                                                        </span> Storage server failed. </span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                @endif
                <!-- END NOTIFICATION DROPDOWN -->
                <li class="separator hide"> </li>
                <!-- BEGIN INBOX DROPDOWN -->
                @if (isset($inbox_dropdown_on))
                <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                <li class="dropdown dropdown-extended dropdown-inbox dropdown-dark" id="header_inbox_bar">
                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                        <i class="icon-envelope-open"></i>
                        <span class="badge badge-danger"> 4 </span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="external">
                            <h3>You have
                                <span class="bold">7 New</span> Messages</h3>
                            <a href="app_inbox.html">view all</a>
                        </li>
                        <li>
                            <ul class="dropdown-menu-list scroller" style="height: 275px;" data-handle-color="#637283">
                                <li>
                                    <a href="#">
                                                    <span class="photo">
                                                        <img src="{{ asset('assets/layouts/layout3/img/avatar2.jpg') }}" class="img-circle" alt=""> </span>
                                                    <span class="subject">
                                                        <span class="from"> Lisa Wong </span>
                                                        <span class="time">Just Now </span>
                                                    </span>
                                        <span class="message"> Vivamus sed auctor nibh congue nibh. auctor nibh auctor nibh... </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                                    <span class="photo">
                                                        <img src="{{ asset('assets/layouts/layout3/img/avatar3.jpg') }}" class="img-circle" alt=""> </span>
                                                    <span class="subject">
                                                        <span class="from"> Richard Doe </span>
                                                        <span class="time">16 mins </span>
                                                    </span>
                                        <span class="message"> Vivamus sed congue nibh auctor nibh congue nibh. auctor nibh auctor nibh... </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                                    <span class="photo">
                                                        <img src="{{ asset('assets/layouts/layout3/img/avatar1.jpg') }}" class="img-circle" alt=""> </span>
                                                    <span class="subject">
                                                        <span class="from"> Bob Nilson </span>
                                                        <span class="time">2 hrs </span>
                                                    </span>
                                        <span class="message"> Vivamus sed nibh auctor nibh congue nibh. auctor nibh auctor nibh... </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                                    <span class="photo">
                                                        <img src="{{ asset('assets/layouts/layout3/img/avatar1.jpg') }}" class="img-circle" alt=""> </span>
                                                    <span class="subject">
                                                        <span class="from"> Lisa Wong </span>
                                                        <span class="time">40 mins </span>
                                                    </span>
                                        <span class="message"> Vivamus sed auctor 40% nibh congue nibh... </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                                    <span class="photo">
                                                        <img src="{{ asset('assets/layouts/layout3/img/avatar3.jpg') }}" class="img-circle" alt=""> </span>
                                                    <span class="subject">
                                                        <span class="from"> Richard Doe </span>
                                                        <span class="time">46 mins </span>
                                                    </span>
                                        <span class="message"> Vivamus sed congue nibh auctor nibh congue nibh. auctor nibh auctor nibh... </span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                @endif
                <!-- END INBOX DROPDOWN -->
                <li class="separator hide"> </li>
                <!-- BEGIN TODO DROPDOWN -->
                <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                <li class="dropdown dropdown-extended dropdown-tasks dropdown-dark" id="header_task_bar">
                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                        <i class="icon-calendar"></i>
                        <span class="badge badge-primary"> bookings calendar </span>
                    </a>
                    <ul class="dropdown-menu extended tasks" style="width:240px;">
                        <li>
                            <ul class="dropdown-menu-list scroller" style="height: 270px; background-color:#ffffff;" data-handle-color="#637283">
                                <li>
                                    <div id="calendar_booking_top_menu" data-date="{{ \Carbon\Carbon::today()->format('d-m-Y') }}" data-date-format="mm/dd/yyyy" style="margin-left:10px;"> </div>
                                </li>
                                <li class="text-center bg-blue-madison bg-font-blue-madison"> <b>{{ Auth::user()->get_preferred_location_name() }}</b> </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <!-- END TODO DROPDOWN -->
                <!-- BEGIN USER LOGIN DROPDOWN -->
                <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                <li class="dropdown dropdown-user dropdown-dark">
                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                        <span class="username username-hide-on-mobile"> {{ Auth::user()->name }} </span>
                        <!-- DOC: Do not remove below empty space(&nbsp;) as its purposely used -->
                        <img alt="" class="img-circle" src="{{ Auth::user()->get_avatar_image(true) }}" /> </a>
                    <ul class="dropdown-menu dropdown-menu-default">
                        <li>
                            <a href="{{ route('admin/back_users/view_user/', ['id' => Auth::user()->id ]) }}">
                                <i class="icon-user"></i> My Profile </a>
                        </li>
                        <li>
                            <a href="{{ route('bookings/location_calendar_day_view',['day'=>\Carbon\Carbon::now()->format('d-m-Y')]) }}">
                                <i class="icon-calendar"></i> Calendar View </a>
                        </li>
                        <li class="divider"> </li>
                        <li>
                            <a href="{{ route('admin/logout') }}">
                                <i class="icon-key"></i> Log Out </a>
                        </li>
                    </ul>
                </li>
                <!-- END USER LOGIN DROPDOWN -->
                <!-- BEGIN QUICK SIDEBAR TOGGLER -->
                @if (isset($right_sidebar_on))
                <li class="dropdown dropdown-extended quick-sidebar-toggler">
                    <span class="sr-only">Toggle Quick Sidebar</span>
                    <i class="icon-logout"></i>
                </li>
                @endif
                <!-- END QUICK SIDEBAR TOGGLER -->
            </ul>
        </div>
        <!-- END TOP NAVIGATION MENU -->
    </div>
    <!-- END PAGE TOP -->
</div>