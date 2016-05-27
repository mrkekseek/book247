<div class="page-sidebar navbar-collapse collapse">
    <!-- BEGIN SIDEBAR MENU -->
    <!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
    <!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
    <!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
    <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
    <!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
    <!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
    <ul class="page-sidebar-menu" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
        <li class="nav-item start {{ $in_sidebar=='admin-home_dashboard'?'active open':'' }}">
            <a href="javascript:;" class="nav-link nav-toggle ">
                <i class="icon-home"></i>
                <span class="title">Quick access links</span>
                <span class="selected"></span>
                <span class="arrow open"></span>
            </a>
            <ul class="sub-menu">
                <li class="nav-item start {{ $in_sidebar=='admin-home_dashboard'?'active open':'' }}">
                    <a href="{{route('admin')}}" class="nav-link ">
                        <i class="icon-bar-chart"></i>
                        <span class="title">Home Screen</span>
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
            <h3 class="uppercase">Registered Clients</h3>
        </li>
        <li class="nav-item {{ in_array($in_sidebar, array('admin-frontend-all_members','admin-frontend-user_details_view'))?'active open':'' }}  ">
            <a href="javascript:;" class="nav-link nav-toggle">
                <i class="icon-layers"></i>
                <span class="title">Front Users/Members</span>
                <span class="arrow"></span>
            </a>
            <ul class="sub-menu">
                <li class="nav-item {{ $in_sidebar=='admin-frontend-all_members'?'active open':'' }} ">
                    <a href="{{ route('admin/front_users/view_all_members') }}" class="nav-link ">
                        <span class="title">View All Members</span>
                    </a>
                </li>
                @if ($in_sidebar=='admin-frontend-user_details_view')
                <li class="nav-item {{ $in_sidebar=='admin-frontend-user_details_view'?'active open':'' }} ">
                    <a class="nav-link ">
                        <span class="title">Member Overview</span>
                    </a>
                </li>
                @endif
                <li class="nav-item  ">
                    <a href="layout_language_bar.html" class="nav-link ">
                        <span class="title">Header Language Bar</span>
                    </a>
                </li>
                <li class="nav-item  ">
                    <a href="layout_footer_fixed.html" class="nav-link ">
                        <span class="title">Fixed Footer</span>
                    </a>
                </li>
                <li class="nav-item  ">
                    <a href="layout_boxed_page.html" class="nav-link ">
                        <span class="title">Boxed Page</span>
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item  ">
            <a href="javascript:;" class="nav-link nav-toggle">
                <i class="icon-feed"></i>
                <span class="title">Bookings</span>
                <span class="arrow"></span>
            </a>
            <ul class="sub-menu">
                <li class="nav-item  ">
                    <a href="layout_sidebar_menu_hover.html" class="nav-link ">
                        <span class="title">Hover Sidebar Menu</span>
                    </a>
                </li>
                <li class="nav-item  ">
                    <a href="layout_sidebar_reversed.html" class="nav-link ">
                        <span class="title">Reversed Sidebar Page</span>
                    </a>
                </li>
                <li class="nav-item  ">
                    <a href="layout_sidebar_fixed.html" class="nav-link ">
                        <span class="title">Fixed Sidebar Layout</span>
                    </a>
                </li>
                <li class="nav-item  ">
                    <a href="layout_sidebar_closed.html" class="nav-link ">
                        <span class="title">Closed Sidebar Layout</span>
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item  ">
            <a href="javascript:;" class="nav-link nav-toggle">
                <i class=" icon-wrench"></i>
                <span class="title">Custom Layouts</span>
                <span class="arrow"></span>
            </a>
            <ul class="sub-menu">
                <li class="nav-item  ">
                    <a href="layout_disabled_menu.html" class="nav-link ">
                        <span class="title">Disabled Menu Links</span>
                    </a>
                </li>
            </ul>
        </li>
        <li class="heading">
            <h3 class="uppercase">Finance</h3>
        </li>
        <li class="nav-item  ">
            <a href="javascript:;" class="nav-link nav-toggle">
                <i class="icon-basket"></i>
                <span class="title">eCommerce</span>
                <span class="arrow"></span>
            </a>
            <ul class="sub-menu">
                <li class="nav-item  ">
                    <a href="ecommerce_index.html" class="nav-link ">
                        <i class="icon-home"></i>
                        <span class="title">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item  ">
                    <a href="ecommerce_orders.html" class="nav-link ">
                        <i class="icon-basket"></i>
                        <span class="title">Orders</span>
                    </a>
                </li>
                <li class="nav-item  ">
                    <a href="ecommerce_orders_view.html" class="nav-link ">
                        <i class="icon-tag"></i>
                        <span class="title">Order View</span>
                    </a>
                </li>
                <li class="nav-item  ">
                    <a href="ecommerce_products.html" class="nav-link ">
                        <i class="icon-graph"></i>
                        <span class="title">Products</span>
                    </a>
                </li>
                <li class="nav-item  ">
                    <a href="ecommerce_products_edit.html" class="nav-link ">
                        <i class="icon-graph"></i>
                        <span class="title">Product Edit</span>
                    </a>
                </li>
            </ul>
        </li>
        <li class="heading">
            <h3 class="uppercase">Administration</h3>
        </li>
        <li class="nav-item {{ in_array($in_sidebar, array('admin-backend-user_roles', 'admin-backend-all_users', 'admin-backend-roles_permission', 'admin-backend-user_details_view', 'admin-backend-permission_details_view'))?'active open':'' }} ">
            <a href="javascript:;" class="nav-link nav-toggle">
                <i class="icon-diamond"></i>
                <span class="title">Back End Users</span>
                <span class="arrow"></span>
            </a>
            <ul class="sub-menu">
                <li class="nav-item {{ $in_sidebar=='admin-backend-all_users'?'active open':'' }}">
                    <a href="{{route('admin/back_users')}}" class="nav-link ">
                        <span class="title">View All Users</span>
                    </a>
                </li>
                @if ($in_sidebar=="admin-backend-user_details_view")
                <li class="nav-item {{ $in_sidebar=='admin-backend-user_details_view'?'active open':'' }}">
                    <a class="nav-link ">
                        <span class="title">User Details</span>
                    </a>
                </li>
                @endif
                <li class="nav-item {{ $in_sidebar=='admin-backend-user_roles'?'active open':'' }}">
                    <a href="{{ route('admin/back_users/user_roles') }}" class="nav-link ">
                        <span class="title">User Roles</span>
                    </a>
                </li>
                <li class="nav-item {{ $in_sidebar=='admin-backend-roles_permission'?'active open':'' }}">
                    <a href="{{ route('admin/back_users/roles_permissions') }}" class="nav-link ">
                        <span class="title">Permissions</span>
                    </a>
                </li>
                @if ($in_sidebar=="admin-backend-permission_details_view")
                    <li class="nav-item {{ $in_sidebar=='admin-backend-permission_details_view'?'active open':'' }}">
                        <a class="nav-link ">
                            <span class="title">Permission Details</span>
                        </a>
                    </li>
                @endif
            </ul>
        </li>
        <li class="nav-item {{ in_array($in_sidebar, ['admin-backend-inventory-and-transfers','admin-backend-shop-locations-list','admin-backend-shop-products-list','admin-backend-shop-products-inventory', 'admin-backend-shop-locations-details-view', 'admin-backend-product-details-view', 'admin-backend-all-products-inventory', 'admin-backend-shops-employees-work-plan', 'admin-backend-shops-add-invoice', 'admin-backend-shop-new_order', 'admin-backend-shop-all_orders', 'admin-backend-shops-cash_terminals'])?'active open':'' }} ">
            <a href="javascript:;" class="nav-link nav-toggle">
                <i class="icon-settings"></i>
                <span class="title">Shops</span>
                <span class="arrow"></span>
            </a>
            <ul class="sub-menu">
                <li class="nav-item {{ $in_sidebar=='admin-backend-shop-locations-list'?'active open':'' }} ">
                    <a href="{{ route("admin/shops/locations/all") }}" class="nav-link ">
                        <span class="title">All Locations</span>
                    </a>
                </li>
                @if ($in_sidebar=="admin-backend-shop-locations-details-view")
                    <li class="nav-item {{ $in_sidebar=='admin-backend-shop-locations-details-view'?'active open':'' }}">
                        <a class="nav-link ">
                            <span class="title">Location Details</span>
                        </a>
                    </li>
                @endif
                <li class="nav-item {{ $in_sidebar=='admin-backend-inventory-and-transfers'?'active open':'' }} ">
                    <a href="{{ route('admin/shops/inventory_and_transfer') }}" class="nav-link ">
                        <span class="title">Stock and Transfers</span>
                    </a>
                </li>
                <li class="nav-item {{ $in_sidebar=='admin-backend-shop-products-list'?'active open':'' }} ">
                    <a href="{{ route('admin/shops/products/all') }}" class="nav-link ">
                        <span class="title">All Products</span>
                    </a>
                </li>
                @if ($in_sidebar=="admin-backend-product-details-view")
                    <li class="nav-item {{ $in_sidebar=='admin-backend-product-details-view'?'active open':'' }}">
                        <a class="nav-link ">
                            <span class="title">Product Details</span>
                        </a>
                    </li>
                @endif
                <li class="nav-item {{ $in_sidebar=='admin-backend-all-products-inventory'?'active open':'' }} ">
                    <a href="{{ route('admin/shops/all_inventory') }}" class="nav-link ">
                        <span class="title">Product Inventory</span>
                    </a>
                </li>
                <li class="nav-item {{ $in_sidebar=='admin-backend-shop-new_order'?'active open':'' }} ">
                    <a href="{{ route('admin/shops/add_new_order') }}" class="nav-link ">
                        <span class="title"> New Order </span>
                    </a>
                </li>
                <li class="nav-item {{ $in_sidebar=='admin-backend-shop-all_orders'?'active open':'' }} ">
                    <a href="{{ route('admin/shops/list_orders') }}" class="nav-link ">
                        <span class="title"> All Orders </span>
                    </a>
                </li>
                <li class="nav-item {{ $in_sidebar=='admin-backend-shops-cash_terminals'?'active open':'' }} ">
                    <a href="{{ route('admin/shops/cash_terminals') }}" class="nav-link ">
                        <span class="title"> Cash Terminals </span>
                    </a>
                </li>
                <li class="nav-item {{ $in_sidebar=='admin-backend-shops-employees-work-plan'?'active open':'' }} ">
                    <a href="{{ route('admin/shops/employee_work_plan') }}" class="nav-link ">
                        <span class="title"> Working Schedule </span>
                    </a>
                </li>
            </ul>
        </li>
    </ul>
    <!-- END SIDEBAR MENU -->
</div>