@extends('admin.layouts.federation.main')

@section('pageLevelPlugins')
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
    <div class="page-content fix_padding_top_0">
        <!-- BEGIN PAGE BASE CONTENT -->
        <div class="row">
            <div class="col-md-12">
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-equalizer font-red-sunglo"></i>
                            <span class="caption-subject font-red-sunglo bold uppercase">Financial profile</span>
                            <span class="caption-helper">add details...</span>
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <!-- BEGIN FORM-->
                        <form action="#" id="new_financial_profile" class="form-horizontal">
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label col-md-3"> Profile Name </label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control input-xlarge" readonly disabled value="{{ $profile->profile_name }}">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3"> Company Name </label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control input-xlarge" readonly disabled value="{{ $profile->company_name }}">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3 inline"> Company Registration No. </label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control inline-block input-xlarge input-inline" readonly disabled value="{{ $profile->organisation_number }}">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-3 inline"> Bank Name </label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control inline-block input-xlarge input-inline" readonly disabled value="{{ $profile->bank_name }}">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3 inline"> Bank Acc No. </label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control inline-block input-xlarge input-inline" readonly disabled value="{{ $profile->bank_account }}">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-3 inline"> Address Line 1 </label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control inline-block input-inline input-xlarge" readonly disabled value="{{ $profile->address1 }}">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3 inline"> Address Line 2 </label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control inline-block input-inline input-xlarge" readonly disabled value="{{ $profile->address2 }}">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3 inline"> Postal Code </label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control inline-block input-small input-inline" readonly disabled value="{{ $profile->postal_code }}">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3 inline"> City </label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control inline-block input-xlarge input-inline" readonly disabled value="{{ $profile->city }}">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3 inline"> Region </label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control inline-block input-large input-inline" value="{{ $profile->region }}" readonly disabled>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3 inline"> Country </label>
                                            <div class="col-md-9">
                                                <select class="form-control input-large" readonly disabled>
                                                    @foreach ($countries as $country)
                                                        <option value="{{ $country->id }}" {!! @$profile->country_id==$country->id?'selected':'' !!}>{{ $country->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <!--/span-->
                                </div>
                                <!--/row-->
                            </div>
                            <div class="form-actions">
                            </div>
                        </form>
                        <!-- END FORM-->
                    </div>
                </div>
            </div>
        </div>
        <!-- END PAGE BASE CONTENT -->
    </div>
@endsection

@section('pageBelowLevelPlugins')
    <script src="{{ asset('assets/global/plugins/jquery-validation/js/jquery.validate.min.js') }}" type="text/javascript"></script>
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