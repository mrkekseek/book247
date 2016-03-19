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
            <h3 class="uppercase">Front End Users</h3>
        </li>
        <li class="nav-item  ">
            <a href="javascript:;" class="nav-link nav-toggle">
                <i class="icon-layers"></i>
                <span class="title">Front End Users</span>
                <span class="arrow"></span>
            </a>
            <ul class="sub-menu">
                <li class="nav-item  ">
                    <a href="layout_blank_page.html" class="nav-link ">
                        <span class="title">Blank Page</span>
                    </a>
                </li>
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
        <li class="nav-item  ">
            <a href="javascript:;" class="nav-link nav-toggle">
                <i class="icon-docs"></i>
                <span class="title">Apps</span>
                <span class="arrow"></span>
            </a>
            <ul class="sub-menu">
                <li class="nav-item  ">
                    <a href="app_todo.html" class="nav-link ">
                        <i class="icon-clock"></i>
                        <span class="title">Todo 1</span>
                    </a>
                </li>
                <li class="nav-item  ">
                    <a href="app_todo_2.html" class="nav-link ">
                        <i class="icon-check"></i>
                        <span class="title">Todo 2</span>
                    </a>
                </li>
                <li class="nav-item  ">
                    <a href="app_inbox.html" class="nav-link ">
                        <i class="icon-envelope"></i>
                        <span class="title">Inbox</span>
                    </a>
                </li>
                <li class="nav-item  ">
                    <a href="app_calendar.html" class="nav-link ">
                        <i class="icon-calendar"></i>
                        <span class="title">Calendar</span>
                    </a>
                </li>
                <li class="nav-item  ">
                    <a href="app_ticket.html" class="nav-link ">
                        <i class="icon-notebook"></i>
                        <span class="title">Support</span>
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item  ">
            <a href="javascript:;" class="nav-link nav-toggle">
                <i class="icon-user"></i>
                <span class="title">User</span>
                <span class="arrow"></span>
            </a>
            <ul class="sub-menu">
                <li class="nav-item  ">
                    <a href="page_user_profile_1.html" class="nav-link ">
                        <i class="icon-user"></i>
                        <span class="title">Profile 1</span>
                    </a>
                </li>
                <li class="nav-item  ">
                    <a href="page_user_profile_1_account.html" class="nav-link ">
                        <i class="icon-user-female"></i>
                        <span class="title">Profile 1 Account</span>
                    </a>
                </li>
                <li class="nav-item  ">
                    <a href="page_user_profile_1_help.html" class="nav-link ">
                        <i class="icon-user-following"></i>
                        <span class="title">Profile 1 Help</span>
                    </a>
                </li>
                <li class="nav-item  ">
                    <a href="page_user_profile_2.html" class="nav-link ">
                        <i class="icon-users"></i>
                        <span class="title">Profile 2</span>
                    </a>
                </li>
                <li class="nav-item  ">
                    <a href="javascript:;" class="nav-link nav-toggle">
                        <i class="icon-notebook"></i>
                        <span class="title">Login</span>
                        <span class="arrow"></span>
                    </a>
                    <ul class="sub-menu">
                        <li class="nav-item ">
                            <a href="page_user_login_1.html" class="nav-link " target="_blank"> Login Page 1 </a>
                        </li>
                        <li class="nav-item ">
                            <a href="page_user_login_2.html" class="nav-link " target="_blank"> Login Page 2 </a>
                        </li>
                        <li class="nav-item ">
                            <a href="page_user_login_3.html" class="nav-link " target="_blank"> Login Page 3 </a>
                        </li>
                        <li class="nav-item ">
                            <a href="page_user_login_4.html" class="nav-link " target="_blank"> Login Page 4 </a>
                        </li>
                        <li class="nav-item ">
                            <a href="page_user_login_5.html" class="nav-link " target="_blank"> Login Page 5 </a>
                        </li>
                        <li class="nav-item ">
                            <a href="page_user_login_6.html" class="nav-link " target="_blank"> Login Page 6 </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item  ">
                    <a href="page_user_lock_1.html" class="nav-link " target="_blank">
                        <i class="icon-lock"></i>
                        <span class="title">Lock Screen 1</span>
                    </a>
                </li>
                <li class="nav-item  ">
                    <a href="page_user_lock_2.html" class="nav-link " target="_blank">
                        <i class="icon-lock-open"></i>
                        <span class="title">Lock Screen 2</span>
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item  ">
            <a href="javascript:;" class="nav-link nav-toggle">
                <i class="icon-social-dribbble"></i>
                <span class="title">General</span>
                <span class="arrow"></span>
            </a>
            <ul class="sub-menu">
                <li class="nav-item  ">
                    <a href="page_general_about.html" class="nav-link ">
                        <i class="icon-info"></i>
                        <span class="title">About</span>
                    </a>
                </li>
                <li class="nav-item  ">
                    <a href="page_general_contact.html" class="nav-link ">
                        <i class="icon-call-end"></i>
                        <span class="title">Contact</span>
                    </a>
                </li>
                <li class="nav-item  ">
                    <a href="javascript:;" class="nav-link nav-toggle">
                        <i class="icon-notebook"></i>
                        <span class="title">Portfolio</span>
                        <span class="arrow"></span>
                    </a>
                    <ul class="sub-menu">
                        <li class="nav-item ">
                            <a href="page_general_portfolio_1.html" class="nav-link "> Portfolio 1 </a>
                        </li>
                        <li class="nav-item ">
                            <a href="page_general_portfolio_2.html" class="nav-link "> Portfolio 2 </a>
                        </li>
                        <li class="nav-item ">
                            <a href="page_general_portfolio_3.html" class="nav-link "> Portfolio 3 </a>
                        </li>
                        <li class="nav-item ">
                            <a href="page_general_portfolio_4.html" class="nav-link "> Portfolio 4 </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item  ">
                    <a href="javascript:;" class="nav-link nav-toggle">
                        <i class="icon-magnifier"></i>
                        <span class="title">Search</span>
                        <span class="arrow"></span>
                    </a>
                    <ul class="sub-menu">
                        <li class="nav-item ">
                            <a href="page_general_search.html" class="nav-link "> Search 1 </a>
                        </li>
                        <li class="nav-item ">
                            <a href="page_general_search_2.html" class="nav-link "> Search 2 </a>
                        </li>
                        <li class="nav-item ">
                            <a href="page_general_search_3.html" class="nav-link "> Search 3 </a>
                        </li>
                        <li class="nav-item ">
                            <a href="page_general_search_4.html" class="nav-link "> Search 4 </a>
                        </li>
                        <li class="nav-item ">
                            <a href="page_general_search_5.html" class="nav-link "> Search 5 </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item  ">
                    <a href="page_general_pricing.html" class="nav-link ">
                        <i class="icon-tag"></i>
                        <span class="title">Pricing</span>
                    </a>
                </li>
                <li class="nav-item  ">
                    <a href="page_general_faq.html" class="nav-link ">
                        <i class="icon-wrench"></i>
                        <span class="title">FAQ</span>
                    </a>
                </li>
                <li class="nav-item  ">
                    <a href="page_general_blog.html" class="nav-link ">
                        <i class="icon-pencil"></i>
                        <span class="title">Blog</span>
                    </a>
                </li>
                <li class="nav-item  ">
                    <a href="page_general_blog_post.html" class="nav-link ">
                        <i class="icon-note"></i>
                        <span class="title">Blog Post</span>
                    </a>
                </li>
                <li class="nav-item  ">
                    <a href="page_general_invoice.html" class="nav-link ">
                        <i class="icon-envelope"></i>
                        <span class="title">Invoice</span>
                    </a>
                </li>
                <li class="nav-item  ">
                    <a href="page_general_invoice_2.html" class="nav-link ">
                        <i class="icon-envelope"></i>
                        <span class="title">Invoice 2</span>
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item  ">
            <a href="javascript:;" class="nav-link nav-toggle">
                <i class="icon-settings"></i>
                <span class="title">System</span>
                <span class="arrow"></span>
            </a>
            <ul class="sub-menu">
                <li class="nav-item  ">
                    <a href="page_system_coming_soon.html" class="nav-link " target="_blank">
                        <span class="title">Coming Soon</span>
                    </a>
                </li>
                <li class="nav-item  ">
                    <a href="page_system_404_1.html" class="nav-link ">
                        <span class="title">404 Page 1</span>
                    </a>
                </li>
                <li class="nav-item  ">
                    <a href="page_system_404_2.html" class="nav-link " target="_blank">
                        <span class="title">404 Page 2</span>
                    </a>
                </li>
                <li class="nav-item  ">
                    <a href="page_system_404_3.html" class="nav-link " target="_blank">
                        <span class="title">404 Page 3</span>
                    </a>
                </li>
                <li class="nav-item  ">
                    <a href="page_system_500_1.html" class="nav-link ">
                        <span class="title">500 Page 1</span>
                    </a>
                </li>
                <li class="nav-item  ">
                    <a href="page_system_500_2.html" class="nav-link " target="_blank">
                        <span class="title">500 Page 2</span>
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
        <li class="nav-item {{ in_array($in_sidebar, array('admin-backend-shop-locations-list','admin-backend-shop-products-list','admin-backend-shop-products-inventory', 'admin-backend-shop-locations-details-view', 'admin-backend-product-details-view', 'admin-backend-all-products-inventory', 'admin-backend-shops-employees-work-plan'))?'active open':'' }} ">
            <a href="javascript:;" class="nav-link nav-toggle">
                <i class="icon-settings"></i>
                <span class="title">Shop</span>
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
                <li class="nav-item {{ $in_sidebar=='admin-backend-shops-employees-work-plan'?'active open':'' }} ">
                    <a href="{{ route('admin/shops/employee_work_plan') }}" class="nav-link ">
                        <span class="title"> Working Schedule </span>
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item  ">
            <a href="javascript:;" class="nav-link nav-toggle">
                <i class="icon-puzzle"></i>
                <span class="title">General Settings</span>
                <span class="arrow"></span>
            </a>
            <ul class="sub-menu">
                <li class="nav-item  ">
                    <a href="components_date_time_pickers.html" class="nav-link ">
                        <span class="title">Date & Time Pickers</span>
                    </a>
                </li>
                <li class="nav-item  ">
                    <a href="components_color_pickers.html" class="nav-link ">
                        <span class="title">Color Pickers</span>
                        <span class="badge badge-danger">2</span>
                    </a>
                </li>
                <li class="nav-item  ">
                    <a href="components_select2.html" class="nav-link ">
                        <span class="title">Select2 Dropdowns</span>
                    </a>
                </li>
                <li class="nav-item  ">
                    <a href="components_bootstrap_select.html" class="nav-link ">
                        <span class="title">Bootstrap Select</span>
                    </a>
                </li>
                <li class="nav-item  ">
                    <a href="components_multi_select.html" class="nav-link ">
                        <span class="title">Multi Select</span>
                    </a>
                </li>
                <li class="nav-item  ">
                    <a href="components_bootstrap_select_splitter.html" class="nav-link ">
                        <span class="title">Select Splitter</span>
                    </a>
                </li>
                <li class="nav-item  ">
                    <a href="components_typeahead.html" class="nav-link ">
                        <span class="title">Typeahead Autocomplete</span>
                    </a>
                </li>
                <li class="nav-item  ">
                    <a href="components_bootstrap_tagsinput.html" class="nav-link ">
                        <span class="title">Bootstrap Tagsinput</span>
                    </a>
                </li>
                <li class="nav-item  ">
                    <a href="components_bootstrap_switch.html" class="nav-link ">
                        <span class="title">Bootstrap Switch</span>
                        <span class="badge badge-success">6</span>
                    </a>
                </li>
                <li class="nav-item  ">
                    <a href="components_bootstrap_maxlength.html" class="nav-link ">
                        <span class="title">Bootstrap Maxlength</span>
                    </a>
                </li>
                <li class="nav-item  ">
                    <a href="components_bootstrap_fileinput.html" class="nav-link ">
                        <span class="title">Bootstrap File Input</span>
                    </a>
                </li>
                <li class="nav-item  ">
                    <a href="components_bootstrap_touchspin.html" class="nav-link ">
                        <span class="title">Bootstrap Touchspin</span>
                    </a>
                </li>
                <li class="nav-item  ">
                    <a href="components_form_tools.html" class="nav-link ">
                        <span class="title">Form Widgets & Tools</span>
                    </a>
                </li>
                <li class="nav-item  ">
                    <a href="components_context_menu.html" class="nav-link ">
                        <span class="title">Context Menu</span>
                    </a>
                </li>
                <li class="nav-item  ">
                    <a href="components_editors.html" class="nav-link ">
                        <span class="title">Markdown & WYSIWYG Editors</span>
                    </a>
                </li>
                <li class="nav-item  ">
                    <a href="components_code_editors.html" class="nav-link ">
                        <span class="title">Code Editors</span>
                    </a>
                </li>
                <li class="nav-item  ">
                    <a href="components_ion_sliders.html" class="nav-link ">
                        <span class="title">Ion Range Sliders</span>
                    </a>
                </li>
                <li class="nav-item  ">
                    <a href="components_noui_sliders.html" class="nav-link ">
                        <span class="title">NoUI Range Sliders</span>
                    </a>
                </li>
                <li class="nav-item  ">
                    <a href="components_knob_dials.html" class="nav-link ">
                        <span class="title">Knob Circle Dials</span>
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item  ">
            <a href="javascript:;" class="nav-link nav-toggle">
                <i class="icon-bulb"></i>
                <span class="title">Locations</span>
                <span class="arrow"></span>
            </a>
            <ul class="sub-menu">
                <li class="nav-item  ">
                    <a href="elements_steps.html" class="nav-link ">
                        <span class="title">All Locations</span>
                    </a>
                </li>
                <li class="nav-item  ">
                    <a href="elements_lists.html" class="nav-link ">
                        <span class="title">Add New Location</span>
                    </a>
                </li>
            </ul>
        </li>
    </ul>
    <!-- END SIDEBAR MENU -->
</div>