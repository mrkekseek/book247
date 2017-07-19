<div class="page-sidebar navbar-collapse collapse">
    <!-- BEGIN SIDEBAR MENU -->
    <!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
    <!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
    <!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
    <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
    <!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
    <!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
    <ul class="page-sidebar-menu {{isset($is_close_menu)?'page-sidebar-menu-closed':''}} " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
        <li class="nav-item start {{ $in_sidebar=='admin-home_dashboard'?'active open':'' }}">
            <a href="javascript:;" class="nav-link nav-toggle ">
                <i class="icon-home"></i>
                <span class="title">Quick access links</span>
                <span class="selected"></span>
                <span class="arrow open"></span>
            </a>
            <ul class="sub-menu">
                <li class="nav-item start">
                    <a href="{{route('bookings/location_calendar_day_view',['day'=>\Carbon\Carbon::now()->format('d-m-Y')])}}" class="nav-link ">
                        <i class="icon-bar-chart"></i>
                        <span class="title">Bookings Calendar View</span>
                    </a>
                </li>
                <li class="nav-item start">
                    <a href="{{route('admin/back_users')}}" class="nav-link ">
                        <i class="icon-bulb"></i>
                        <span class="title">Members list</span>
                        <span class="selected"></span>
                    </a>
                </li>
            </ul>
        </li>
        <li class="heading">
            <h3 class="uppercase"> Registered Clients </h3>
        </li>
        <li class="nav-item {{ in_array($in_sidebar, array('admin-frontend-all_members','admin-frontend-user_details_view', 'admin-frontend-add_member','admin-frontend-import_members'))?'active open':'' }}">
            <a href="javascript:;" class="nav-link nav-toggle">
                <i class="icon-users"></i>
                <span class="title"> Clients </span>
                <span class="arrow"></span>
            </a>
            <ul class="sub-menu">
                <li class="nav-item {{ $in_sidebar=='admin-frontend-add_member'?'active open':'' }} ">
                    <a href="{{ route('admin/front_users/register_new') }}" class="nav-link ">
                        <span class="title"> Add New Client </span>
                    </a>
                </li>
                @if (Auth::user()->can('view-clients-import-list'))
                <li class="nav-item {{ $in_sidebar=='admin-frontend-import_members'?'active open':'' }} ">
                    <a href="{{ route('admin/front_users/import_members') }}" class="nav-link ">
                        <span class="title"> Import Clients </span>
                    </a>
                </li>
                @endif
                @if (Auth::user()->can('view-clients-list-all-clients'))
                <li class="nav-item {{ $in_sidebar=='admin-frontend-all_members'?'active open':'' }} ">
                    <a href="{{ route('admin/front_users/view_all_members') }}" class="nav-link ">
                        <span class="title"> List All Clients </span>
                    </a>
                </li>
                @endif
                @if ($in_sidebar=='admin-frontend-user_details_view')
                <li class="nav-item {{ $in_sidebar=='admin-frontend-user_details_view'?'active open':'' }} ">
                    <a class="nav-link ">
                        <span class="title"> Client Overview </span>
                    </a>
                </li>
                @endif
            </ul>
        </li>

        <li class="heading">
            <h3 class="uppercase">Bookings</h3>
        </li>
        <li class="nav-item {{ in_array($in_sidebar, array('admin-bookings-calendar_view'))?'active open':'' }}  ">
            <a href="{{ route('bookings/location_calendar_day_view',['day'=>\Carbon\Carbon::now()->format('d-m-Y')]) }}" class="nav-link nav-toggle">
                <i class="icon-calendar "></i>
                <span class="title"> Calendar View </span>
            </a>
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
        <li class="nav-item {{ in_array($in_sidebar, array('admin-backend-add-packs', 'admin-backend-all-packs')) ? 'active open' : '' }} ">
            <a href="javascript:;" class="nav-link nav-toggle">
                <i class="icon-calculator"></i>
                <span class="title"> Store Credit </span>
                <span class="arrow"></span>
            </a>
            <ul class="sub-menu">
                <li class="nav-item {{ $in_sidebar == 'admin-backend-add-packs' ? 'active open' : '' }}">
                    <a href="{{route('admin.store_credit_products.create')}}" class="nav-link ">
                        <span class="title">Add new pack</span>
                    </a>
                </li>
                <li class="nav-item {{ $in_sidebar == 'admin-backend-all-packs' ? 'active open' : '' }}">
                    <a href="{{ route('admin.store_credit_products.index') }}" class="nav-link ">
                        <span class="title">List all packs</span>
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item {{ in_array($in_sidebar, array('admin-backend-membership_products-list_all','admin-backend-membership_products-new_product','admin-backend-membership_products-show_product','admin-backend-membership_products-edit_product'))?'active open':'' }} ">
            <a href="javascript:;" class="nav-link nav-toggle">
                <i class="icon-shield"></i>
                <span class="title"> Calendar Products </span>
                <span class="arrow"></span>
            </a>
            <ul class="sub-menu">
                @if (Auth::user()->can('view-calendar-products-add-new-products'))
                <li class="nav-item {{ $in_sidebar=='admin-backend-membership_products-new_product'?'active open':'' }}">
                    <a href="{{route('admin/membership_products/add_new')}}" class="nav-link ">
                        <span class="title">Add New Product</span>
                    </a>
                </li>
                @endif
                <li class="nav-item {{ $in_sidebar=='admin-backend-membership_products-list_all'?'active open':'' }}">
                    <a href="{{ route('admin/membership_products/list_all') }}" class="nav-link ">
                        <span class="title">List All Products</span>
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
                <li class="nav-item {{ $in_sidebar=='admin-backend-user_roles'?'active open':'' }}">
                    <a href="{{ route('admin/back_users/user_roles') }}" class="nav-link ">
                        <span class="title"> Back-end users Roles </span>
                    </a>
                </li>
                <li class="nav-item {{ $in_sidebar=='admin-backend-roles_permission'?'active open':'' }}">
                    <a href="{{ route('admin/back_users/roles_permissions') }}" class="nav-link ">
                        <span class="title"> Roles Permission </span>
                    </a>
                </li>
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

        @if (Auth::user()->can('view-shop-menu'))
        <li class="nav-item {{ in_array($in_sidebar, ['admin-backend-inventory-and-transfers','admin-backend-shop-locations-list','admin-backend-shop-products-list','admin-backend-shop-products-inventory', 'admin-backend-shop-locations-details-view', 'admin-backend-product-details-view', 'admin-backend-all-products-inventory', 'admin-backend-shops-employees-work-plan', 'admin-backend-shops-add-invoice', 'admin-backend-shop-new_order', 'admin-backend-shop-all_orders', 'admin-backend-shops-cash_terminals', 'admin-backend-locations-resource-details-view', 'admin-backend-shop-new_order'])?'active open':'' }} ">
            <a href="javascript:;" class="nav-link nav-toggle">
                <i class="icon-settings"></i>
                <span class="title">Clubs</span>
                <span class="arrow"></span>
            </a>
            <ul class="sub-menu">
                <li class="nav-item {{ $in_sidebar=='admin-backend-shop-locations-list'?'active open':'' }} ">
                    <a href="{{ route("admin/shops/locations/all") }}" class="nav-link ">
                        <span class="title"> List All Clubs </span>
                    </a>
                </li>
                @if ( $in_sidebar == "admin-backend-shop-locations-details-view")
                    <li class="nav-item {{ $in_sidebar=='admin-backend-shop-locations-details-view'?'active open':'' }}">
                        <a class="nav-link ">
                            <span class="title">Club Details</span>
                        </a>
                    </li>
                @endif
                @if ( $in_sidebar == "admin-backend-locations-resource-details-view")
                    <li class="nav-item {{ $in_sidebar=='admin-backend-locations-resource-details-view'?'active open':'' }}">
                        <a class="nav-link ">
                            <span class="title">Club Details</span>
                        </a>
                    </li>
                @endif
                <!--<li class="nav-item {{ $in_sidebar=='admin-backend-inventory-and-transfers'?'active open':'' }} ">
                    <a href="{{ route('admin/shops/inventory_and_transfer') }}" class="nav-link ">
                        <span class="title">Stock and Transfers</span>
                    </a>
                </li>-->
                <!--<li class="nav-item {{ $in_sidebar=='admin-backend-shop-products-list'?'active open':'' }} ">
                    <a href="{{ route('admin/shops/products/all') }}" class="nav-link ">
                        <span class="title">All Products</span>
                    </a>
                </li>-->
                <!--
                @if ($in_sidebar=="admin-backend-product-details-view")
                    <li class="nav-item {{ $in_sidebar=='admin-backend-product-details-view'?'active open':'' }}">
                        <a class="nav-link ">
                            <span class="title">Product Details</span>
                        </a>
                    </li>
                @endif
                -->
                <!--<li class="nav-item {{ $in_sidebar=='admin-backend-all-products-inventory'?'active open':'' }} ">
                    <a href="{{ route('admin/shops/all_inventory') }}" class="nav-link ">
                        <span class="title">Product Inventory</span>
                    </a>
                </li>-->
                <!--<li class="nav-item {{ $in_sidebar=='admin-backend-shop-new_order'?'active open':'' }} ">
                    <a href="{{ route('admin/shops/add_new_order') }}" class="nav-link ">
                        <span class="title"> New Order </span>
                    </a>
                </li>-->
                <!--<li class="nav-item {{ $in_sidebar=='admin-backend-shop-all_orders'?'active open':'' }} ">
                    <a href="{{ route('admin/shops/list_orders') }}" class="nav-link ">
                        <span class="title"> All Orders </span>
                    </a>
                </li>-->
                <!--<li class="nav-item {{ $in_sidebar=='admin-backend-shops-cash_terminals'?'active open':'' }} ">
                    <a href="{{ route('admin/shops/cash_terminals') }}" class="nav-link ">
                        <span class="title"> Cash Terminals </span>
                    </a>
                </li>-->
                <li class="nav-item {{ $in_sidebar=='admin-backend-shops-employees-work-plan'?'active open':'' }} ">
                    <a href="{{ route('admin/shops/employee_work_plan') }}" class="nav-link ">
                        <span class="title"> Working Schedule </span>
                    </a>
                </li>
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
                <li class="nav-item {{ $in_sidebar == 'admin-settings-all_list' ? 'active open' : '' }}">
                    <a href="{{route('admin/settings/list_all')}}" class="nav-link ">
                        <span class="title">Define and mentain</span>
                    </a>
                </li>
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
               <li class="nav-item {{ $in_sidebar == 'admin-templates_email-list_all' ? 'active open': '' }}">
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