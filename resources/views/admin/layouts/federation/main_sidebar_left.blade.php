<div class="page-sidebar navbar-collapse collapse">
    <!-- BEGIN SIDEBAR MENU -->
    <!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
    <!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
    <!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
    <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
    <!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
    <!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
    <ul class="page-sidebar-menu {{isset($is_close_menu)?'page-sidebar-menu-closed':''}} " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
        <li class="heading">
            <h3 class="uppercase"> Registered Members </h3>
        </li>
        <li class="nav-item {{ in_array($in_sidebar, array('admin-frontend-all_members','admin-frontend-user_details_view', 'admin-frontend-add_member','admin-frontend-import_members'))?'active open':'' }}">
            <a href="javascript:;" class="nav-link nav-toggle">
                <i class="icon-users"></i>
                <span class="title"> Members </span>
                <span class="arrow"></span>
            </a>
            <ul class="sub-menu">
                <li class="nav-item {{ $in_sidebar=='admin-frontend-add_member'?'active open':'' }} ">
                    <a href="{{ route('admin/front_users/register_new') }}" class="nav-link ">
                        <span class="title"> Add New Member </span>
                    </a>
                </li>
                @if (Auth::user()->can('view-clients-import-list'))
                    <li class="nav-item {{ $in_sidebar=='admin-frontend-import_members'?'active open':'' }} ">
                        <a href="{{ route('admin/front_users/import_members') }}" class="nav-link ">
                            <span class="title"> Import Members </span>
                        </a>
                    </li>
                @endif
                @if (Auth::user()->can('view-clients-list-all-clients'))
                    <li class="nav-item {{ $in_sidebar=='admin-frontend-all_members'?'active open':'' }} ">
                        <a href="{{ route('admin/front_users/view_all_members') }}" class="nav-link ">
                            <span class="title"> List All Members </span>
                        </a>
                    </li>
                @endif
                @if ($in_sidebar=='admin-frontend-user_details_view')
                    <li class="nav-item {{ $in_sidebar=='admin-frontend-user_details_view'?'active open':'' }} ">
                        <a class="nav-link ">
                            <span class="title"> Member Overview </span>
                        </a>
                    </li>
                @endif
            </ul>
        </li>

        <li class="heading">
            <h3 class="uppercase">Administration</h3>
        </li>
        <li class="nav-item {{ in_array($in_sidebar, array('admin-backend-memberships-all_plans','admin-backend-memberships-new_plans'))?'active open':'' }} ">
            <a href="javascript:;" class="nav-link nav-toggle">
                <i class="icon-shield"></i>
                <span class="title"> Membership Plans </span>
                <span class="arrow"></span>
            </a>
            <ul class="sub-menu">
                @if (Auth::user()->can('view-membership-plans-add-new-plan'))
                    <li class="nav-item {{ $in_sidebar=='admin-backend-memberships-new_plans'?'active open':'' }}">
                        <a href="{{route('admin.membership_plan.create')}}" class="nav-link ">
                            <span class="title">Add New Plan</span>
                        </a>
                    </li>
                @endif
                <li class="nav-item {{ $in_sidebar=='admin-backend-memberships-all_plans'?'active open':'' }}">
                    <a href="{{ route('admin.membership_plan.index') }}" class="nav-link ">
                        <span class="title">List All Plans</span>
                    </a>
                </li>
            </ul>
        </li>

        @if (Auth::user()->can('view-employees-menu'))
            <li class="nav-item {{ in_array($in_sidebar, array('admin-backend-user_roles', 'admin-backend-all_users', 'admin-backend-roles_permission', 'admin-backend-user_details_view', 'admin-backend-permission_details_view'))?'active open':'' }} ">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="icon-calculator"></i>
                    <span class="title"> Back-end users </span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item {{ $in_sidebar=='admin-backend-all_users'?'active open':'' }}">
                        <a href="{{route('admin/back_users')}}" class="nav-link ">
                            <span class="title"> List All Back-end users </span>
                        </a>
                    </li>
                    @if ($in_sidebar=="admin-backend-user_details_view")
                        <li class="nav-item {{ $in_sidebar=='admin-backend-user_details_view'?'active open':'' }}">
                            <a class="nav-link ">
                                <span class="title"> Back-end user Details </span>
                            </a>
                        </li>
                    @endif
                    @if ($in_sidebar=="admin-backend-permission_details_view")
                        <li class="nav-item {{ $in_sidebar=='admin-backend-permission_details_view'?'active open':'' }}">
                            <a class="nav-link ">
                                <span class="title"> Permission Details </span>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif

        @if (Auth::user()->can('view-general-settings-menu'))
            <li class="nav-item {{ in_array($in_sidebar, ['admin-settings-all_list','admin-settings-financial_profiles-add_new','admin-settings-financial_profiles-list_all','admin-settings-financial_profiles-view_edit', 'admin-templates_email-list_all', 'admin-settings-manage_settings', 'admin-settings-rankedin_integration_app_key'])?'active open':'' }} ">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="icon-shield"></i>
                    <span class="title"> General Settings </span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item {{ $in_sidebar == 'admin-settings-manage_settings' ? 'active open' : '' }}">
                        <a href="{{route('admin/settings/manage_settings')}}" class="nav-link ">
                            <span class="title">Manage settings</span>
                        </a>
                    </li>
                    <li class="nav-item {{ $in_sidebar == 'admin-settings-rankedin_integration_app_key' ? 'active open' : '' }}">
                        <a href="{{route('admin/settings/account_key')}}" class="nav-link ">
                            <span class="title">RankedIn Integration</span>
                        </a>
                    </li>
                    <li class="nav-item {{ $in_sidebar=='admin-settings-financial_profiles-add_new'?'active open':'' }}">
                        <a href="{{route('admin/settings_financial_profiles/add')}}" class="nav-link ">
                            <span class="title">Add Financial Profile</span>
                        </a>
                    </li>
                    <li class="nav-item {{ $in_sidebar=='admin-settings-financial_profiles-list_all'?'active open':'' }}">
                        <a href="{{route('admin/settings_financial_profiles/list_all')}}" class="nav-link ">
                            <span class="title">All Financial Profiles</span>
                        </a>
                    </li>
                    <li class="nav-item {{ $in_sidebar=='admin-settings-financial_profiles-list_all'?'active open':'' }}">
                        <a href="{{ route('admin/templates_email/list_all') }}" class="nav-link ">
                            <span class="title">Email templates</span>
                        </a>
                    </li>
                    @if ($in_sidebar=='admin-settings-financial_profiles-view_edit')
                        <li class="nav-item {{ $in_sidebar=='admin-settings-financial_profiles-view_edit'?'active open':'' }}">
                            <a href="" class="nav-link ">
                                <span class="title">Financial Profile Details</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif
    </ul>
    <!-- END SIDEBAR MENU -->
</div>