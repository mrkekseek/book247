@extends('admin.layouts.main')


@section('pageLevelPlugins')
    <link href="{{ asset('assets/global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css') }}" rel="stylesheet" type="text/css" />

    <link href="{{ asset('assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/global/plugins/bootstrap-markdown/css/bootstrap-markdown.min.css') }}">
    <link href="{{ asset('assets/global/plugins/bootstrap-summernote/summernote.css') }}" rel="stylesheet" type="text/css" />
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
                <!-- BEGIN SAMPLE FORM PORTLET-->
                <div class="portlet light bordered">
                    <div class="portlet-body">
                         <div class="portlet box green">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-cogs"></i>Manage Settings
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="table-scrollable">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th> # </th>
                                                <th> Name </th>
                                                <th>  </th>
                                                <th> Save </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($settings as $s)
                                                <tr>
                                                    <td> {{ $s->id }} </td>
                                                    <td> {{ $s->name }} </td>
                                                    @if ($s->constrained)
                                                        @if ($s->allowed)
                                                        <td>   
                                                            <select class="form-control">
                                                                @foreach($s->allowed as $row)
                                                                    <option value="{{ $row->id }}">{{ $row->item_value }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        @endif
                                                    @else
                                                        @if ($s->data_type == 'numeric')
                                                            <td>
                                                                <div class="row">
                                                                    <div class="col-sm-6">
                                                                        <input type="text" class="form-control" placeholder="Min"  />
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <input type="text" class="form-control" placeholder="Max" />
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        @elseif ($s->data_type == 'string')
                                                            <td>
                                                                <div class="row">
                                                                    <div class="col-sm-12">
                                                                        <input type="text" class="form-control" placeholder="Text"  />
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        @elseif ($s->data_type == 'date')
                                                            <td>
                                                                <div class="row">
                                                                    <div class="col-sm-12">
                                                                        <input type="text" class="form-control" placeholder="Date"  />
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        @endif
                                                     

                                                    @endif
                                                    <td> 
                                                        <button class="btn btn-primary edit-settings btn-sm" data-id="{{ $s->id }}">
                                                            <i class="fa fa-save"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            @if( ! count($settings))
                                                <tr>
                                                    <td class="text-center" colspan="8">
                                                        Empty list
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END SAMPLE FORM PORTLET-->
            </div>
        </div>
        <!-- END PAGE BASE CONTENT -->
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
    <script src="{{ asset('assets/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/jquery-validation/js/jquery.validate.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/jquery-validation/js/additional-methods.min.js') }}" type="text/javascript"></script>
@endsection

@section('themeBelowGlobalScripts')
    <script src="{{ asset('assets/global/scripts/app.min.js') }}" type="text/javascript"></script>
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

    </script>
@endsection