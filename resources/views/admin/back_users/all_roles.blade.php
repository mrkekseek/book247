@extends('admin.layouts.main')

@section('pageLevelPlugins')
    <link href="{{ asset('assets/global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('themeGlobalStyle')
    <link href="{{ asset('assets/global/css/components-rounded.min.css') }}" rel="stylesheet" id="style_components" type="text/css" />
    <link href="{{ asset('assets/global/css/plugins.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/jquery-notific8/jquery.notific8.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('themeLayoutStyle')
    <link href="{{ asset('assets/layouts/layout4/css/layout.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/layouts/layout4/css/themes/light.min.css') }}" rel="stylesheet" type="text/css" id="style_color" />
    <link href="{{ asset('assets/layouts/layout4/css/custom.min.css') }}" rel="stylesheet" type="text/css" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection

@section('title', 'Back-end users - All User Roles')
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
                <div class="portlet light portlet-fit bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-settings font-red"></i>
                            <span class="caption-subject font-red sbold uppercase">{!!$text_parts['table_head_text1']!!}</span>
                        </div>
                        <div class="btn-group pull-right">
                            <button id="sample_editable_1_new" class="btn green"> Add New
                                <i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <table class="table table-striped table-hover table-bordered" id="sample_editable_1">
                            <thead>
                            <tr>
                                <th> Role Name </th>
                                <th> Display Name </th>
                                <th> Description </th>
                                <th> Edit </th>
                                <th> Delete </th>
                            </tr>
                            </thead>
                            <tbody>
                        @foreach($roles as $role)
                            <tr>
                                <td> {{$role['name'] }} </td>
                                <td> {{$role['display_name']}} </td>
                                <td class="center"> {{$role['description']}} </td>
                                <td>
                                    <a class="edit" href="javascript:;" id="{{$role['id']}}"> Edit </a>
                                </td>
                                <td>
                                    <a class="delete" href="javascript:;" id="{{$role['id']}}"> Delete </a>
                                </td>
                            </tr>
                        @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- END EXAMPLE TABLE PORTLET-->
            </div>
        </div>
        <!-- END PAGE BASE CONTENT -->
    </div>
@endsection

@section('pageBelowLevelPlugins')
    <script src="{{ asset('assets/global/scripts/datatable.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/datatables/datatables.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js') }}" type="text/javascript"></script>
@endsection

@section('pageBelowLevelScripts')
    <script src="{{ asset('assets/global/plugins/jquery-notific8/jquery.notific8.min.js') }}" type="text/javascript"></script>
@endsection

@section('themeBelowLayoutScripts')
    <script src="{{ asset('assets/layouts/layout4/scripts/layout.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/layouts/layout4/scripts/demo.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/layouts/global/scripts/quick-sidebar.min.js') }}" type="text/javascript"></script>
@endsection

@section('pageCustomJScripts')
    <script type="text/javascript">
        var TableDatatablesEditable = function () {

            var handleTable = function () {

                function restoreRow(oTable, nRow) {
                    var aData = oTable.fnGetData(nRow);
                    var jqTds = $('>td', nRow);

                    for (var i = 0, iLen = jqTds.length; i < iLen; i++) {
                        oTable.fnUpdate(aData[i], nRow, i, false);
                    }

                    oTable.fnDraw();
                }

                function editRow(oTable, nRow, dataID) {
                    dataID = typeof dataID !== 'undefined' ? dataID : false;
                    var aData = oTable.fnGetData(nRow);
                    var jqTds = $('>td', nRow);

                    jqTds[0].innerHTML = '<input type="text" class="form-control" value="' + aData[0] + '">';
                    jqTds[1].innerHTML = '<input type="text" class="form-control" value="' + aData[1] + '">';
                    jqTds[2].innerHTML = '<input type="text" class="form-control" value="' + aData[2] + '">';
                    if (dataID==false){
                        jqTds[3].innerHTML = '<a class="edit" href="">Save</a>';
                    }
                    else{
                        jqTds[3].innerHTML = '<a class="edit" href="" id="'+dataID+'">Save</a>';
                    }
                    jqTds[4].innerHTML = '<a class="cancel" href="">Cancel</a>';
                }

                function saveRow(oTable, nRow) {
                    var jqInputs = $('input', nRow);

                    oTable.fnUpdate(jqInputs[0].value, nRow, 0, false);
                    oTable.fnUpdate(jqInputs[1].value, nRow, 1, false);
                    oTable.fnUpdate(jqInputs[2].value, nRow, 2, false);
                    oTable.fnUpdate('<a class="edit" href="">Edit</a>', nRow, 3, false);
                    oTable.fnUpdate('<a class="delete" href="">Delete</a>', nRow, 4, false);
                    oTable.fnDraw();
                }

                function cancelEditRow(oTable, nRow) {
                    var jqInputs = $('input', nRow);
                    oTable.fnUpdate(jqInputs[0].value, nRow, 0, false);
                    oTable.fnUpdate(jqInputs[1].value, nRow, 1, false);
                    oTable.fnUpdate(jqInputs[2].value, nRow, 2, false);
                    oTable.fnUpdate('<a class="edit" href="">Edit</a>', nRow, 3, false);
                    oTable.fnDraw();
                }

                var table = $('#sample_editable_1');

                var oTable = table.dataTable({

                    // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
                    // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js).
                    // So when dropdowns used the scrollable div should be removed.
                    //"dom": "<'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r>t<'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",

                    "lengthMenu": [
                        [5, 15, 20, -1],
                        [5, 15, 20, "All"] // change per page values here
                    ],

                    // Or you can use remote translation file
                    //"language": {
                    //   url: '//cdn.datatables.net/plug-ins/3cfcc339e89/i18n/Portuguese.json'
                    //},

                    // set the initial value
                    "pageLength": 5,

                    "language": {
                        "lengthMenu": " _MENU_ records"
                    },
                    "columnDefs": [{ // set default column settings
                        'orderable': true,
                        'targets': [0]
                    }, {
                        "searchable": true,
                        "targets": [0]
                    }],
                    "order": [
                        [0, "asc"]
                    ] // set first column as a default sort by asc
                });

                var tableWrapper = $("#sample_editable_1_wrapper");

                var nEditing = null;
                var nNew = false;

                $('#sample_editable_1_new').click(function (e) {
                    e.preventDefault();

                    if (nNew && nEditing) {
                        if (confirm("Previose row not saved. Do you want to save it ?")) {
                            saveRow(oTable, nEditing); // save
                            $(nEditing).find("td:first").html("Untitled");
                            nEditing = null;
                            nNew = false;

                        } else {
                            oTable.fnDeleteRow(nEditing); // cancel
                            nEditing = null;
                            nNew = false;

                            return;
                        }
                    }

                    var aiNew = oTable.fnAddData(['', '', '', '', '']);
                    var nRow = oTable.fnGetNodes(aiNew[0]);
                    editRow(oTable, nRow);
                    nEditing = nRow;
                    nNew = true;
                });

                table.on('click', '.delete', function (e) {
                    e.preventDefault();
                    var roleID = $(this).attr('id');

                    if (confirm("Are you sure to delete this row ?") == false) {
                        return;
                    }

                    delete_role(roleID);
                    if (returnStatus==false){
                        // here we make the fields red
                        return false;
                    }

                    var nRow = $(this).parents('tr')[0];
                    oTable.fnDeleteRow(nRow);
                    //alert("Deleted! Do not forget to do some ajax to sync with backend :)");
                });

                table.on('click', '.cancel', function (e) {
                    e.preventDefault();
                    if (nNew) {
                        oTable.fnDeleteRow(nEditing);
                        nEditing = null;
                        nNew = false;
                    } else {
                        restoreRow(oTable, nEditing);
                        nEditing = null;
                    }
                });

                table.on('click', '.edit', function (e) {
                    e.preventDefault();

                    /* Get the row as a parent of the link that was clicked on */
                    var nRow = $(this).parents('tr')[0];
                    var roleID = $(this).attr('id');

                    if (nEditing !== null && nEditing != nRow) {
                        /* Currently editing - but not this row - restore the old before continuing to edit mode */
                        restoreRow(oTable, nEditing);
                        editRow(oTable, nRow, roleID);
                        nEditing = nRow;
                    } else if (nEditing == nRow && this.innerHTML == "Save") {
                        /* Editing this row and want to save it */
                        add_role_to_database(nEditing, roleID);
                        if (returnStatus==false){
                            // here we make the fields red
                            return false;
                        }

                        saveRow(oTable, nEditing);
                        nEditing = null;
                        //alert("Updated! Do not forget to do some ajax to sync with backend :)");
                    } else {
                        /* No edit in progress - let's start one */
                        editRow(oTable, nRow, roleID);
                        nEditing = nRow;
                    }
                });
            }

            return {

                //main function to initiate the module
                init: function () {
                    handleTable();
                }

            };

        }();

        jQuery(document).ready(function() {
            TableDatatablesEditable.init();
        });

        var returnStatus;
        function statusChangeReturn(new_val){
            returnStatus = new_val;
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function add_role_to_database(nRow, dataID){
            var array_vars = $('input', nRow);
            dataID = typeof dataID !== 'undefined' ? dataID : false;
            var fieldsName = {0:"RoleName", 1:"RoleDisplayName"};

            var name = array_vars[0].value;
            var display_name = array_vars[1].value;
            var description = array_vars[2].value;
            returnStatus = true;

            if (dataID==false){
                var method = 'post';
            }
            else{
                var method = 'put';
            }

            $.ajax({
                url: '{{route('admin/back_users/user_roles')}}',
                type: "post",
                async: false,
                data: {
                    'name': name,
                    'display_name': display_name,
                    'description': description,
                    'dataID': dataID,
                    '_method': method,},
                success: function (data) {
                    if (data.success==false){
                        var notificationTitle = data.title;
                        var notificationMessage = data.message;
                        var inputFields = $('#sample_editable_1').find("input");
                        inputFields.each(function(k, v){
                            $(this).attr('name', fieldsName[k]);
                            $(this).addClass('border-green-meadow');
                        });

                        $.each(data.errors, function(k, v){
                            if (k=="name"){
                                $("input[name='RoleName']").removeClass('border-green-meadow');
                                $("input[name='RoleName']").addClass('border-red-mint');
                            }
                            else if(k=="display_name"){
                                $("input[name='RoleName']").removeClass('border-green-meadow');
                                $("input[name='RoleDisplayName']").addClass('border-red-mint');
                            }
                        });

                        show_notification(notificationTitle, notificationMessage, 'ruby', 10000, false);
                        statusChangeReturn(false);
                    }
                    else{
                        var notificationTitle = data.title;
                        var notificationMessage = data.message;
                        show_notification(notificationTitle, notificationMessage, 'lime', 10000, false);
                    }
                }
            });
        }

        function delete_role(roleID){
            returnStatus = true;
            $.ajax({
                url: '{{route('admin/back_users/user_roles')}}',
                type: "post",
                async: false,
                data: {
                    'dataID'  : roleID,
                    '_method' : 'delete',},
                success: function (data) {
                    if (data.success==false){
                        var notificationTitle = data.title;
                        var notificationMessage = data.message;

                        show_notification(notificationTitle, notificationMessage, 'ruby', 10000, false);
                        statusChangeReturn(false);
                    }
                    else{
                        var notificationTitle = data.title;
                        var notificationMessage = data.message;
                        show_notification(notificationTitle, notificationMessage, 'lime', 10000, false);
                    }
                }
            });
        }

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