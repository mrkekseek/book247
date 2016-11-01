@extends('admin.layouts.main')

@section('pageLevelPlugins')
@endsection

@section('themeGlobalStyle')
    <link href="../assets/global/css/components-rounded.min.css" rel="stylesheet" id="style_components" type="text/css" />
    <link href="../assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('themeLayoutStyle')
    <link href="../assets/layouts/layout4/css/layout.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/layouts/layout4/css/themes/light.min.css" rel="stylesheet" type="text/css" id="style_color" />
    <link href="../assets/layouts/layout4/css/custom.min.css" rel="stylesheet" type="text/css" />

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
                    <div class="portlet-title">
                        <div class="caption font-red-sunglo">
                            <i class="icon-settings font-red-sunglo"></i>
                            <span class="caption-subject bold uppercase"> Company General Settings </span>
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <form role="form" name="add_setting_form" id="add_setting_form">
                            <div class="form-body">
                                <div class="form-group">
                                    <label>Setting Name</label>
                                    <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-envelope"></i>
                                            </span>
                                        <input type="text" class="form-control input-sm" name="setting_name" placeholder="Enter setting name here"> </div>
                                </div>
                                <div class="form-group">
                                    <label>Setting Internal Name</label>
                                    <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-envelope"></i>
                                            </span>
                                        <input type="text" class="form-control input-sm" name="setting_internal_name" placeholder="Enter setting internal name here; Ex : setting_name_with_underlines "> </div>
                                </div>
                                <div class="form-group">
                                    <label>Short Description</label>
                                    <textarea class="form-control input-sm" name="setting_description" rows="3"></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Setting Type</label>
                                    <select class="form-control input-sm" name="setting_type">
                                        <option value="string"> String / Alphanumeric Values</option>
                                        <option value="text"> Text </option>
                                        <option value="numeric"> Numeric Only </option>
                                        <option value="date"> Date / DateTime Values </option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Min and Max values (if they exists)</label>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input class="form-control input-sm" placeholder="min value" name="setting_min_val" type="text"> </div>
                                        <div class="col-md-6">
                                            <input class="form-control input-sm" placeholder="max value" name="setting_max_val" type="text"> </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label> Is Constrained [has predefined values for selection] </label>
                                    <div class="mt-radio-inline">
                                        <label class="mt-radio">
                                            <input name="setting_constrained" id="setting_constrained1" value="yes" type="radio"> Yes
                                            <span></span>
                                        </label>
                                        <label class="mt-radio">
                                            <input name="setting_constrained" id="setting_constrained2" value="no" checked="" type="radio"> No
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn blue">Add General Setting</button>
                                <button type="button" class="btn default">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- END SAMPLE FORM PORTLET-->
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 ">
                <!-- BEGIN SAMPLE FORM PORTLET-->
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-settings font-dark"></i>
                            <span class="caption-subject font-dark sbold uppercase"> Shop General Settings </span>
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <form class="form-horizontal" role="form">
                            <div class="form-body">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Block Help</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" placeholder="Enter text">
                                        <span class="help-block"> A block of help text. </span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Inline Help</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control input-inline input-medium" placeholder="Enter text">
                                        <span class="help-inline"> Inline help. </span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Input Group</label>
                                    <div class="col-md-9">
                                        <div class="input-inline input-medium">
                                            <div class="input-group">
                                                            <span class="input-group-addon">
                                                                <i class="fa fa-user"></i>
                                                            </span>
                                                <input type="email" class="form-control" placeholder="Email Address"> </div>
                                        </div>
                                        <span class="help-inline"> Inline help. </span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Email Address</label>
                                    <div class="col-md-9">
                                        <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <i class="fa fa-envelope"></i>
                                                        </span>
                                            <input type="email" class="form-control" placeholder="Email Address"> </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Password</label>
                                    <div class="col-md-9">
                                        <div class="input-group">
                                            <input type="password" class="form-control" placeholder="Password">
                                                        <span class="input-group-addon">
                                                            <i class="fa fa-user"></i>
                                                        </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Left Icon</label>
                                    <div class="col-md-9">
                                        <div class="input-icon">
                                            <i class="fa fa-bell-o"></i>
                                            <input type="text" class="form-control" placeholder="Left icon"> </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Right Icon</label>
                                    <div class="col-md-9">
                                        <div class="input-icon right">
                                            <i class="fa fa-microphone"></i>
                                            <input type="text" class="form-control" placeholder="Right icon"> </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Icon Input in Group Input</label>
                                    <div class="col-md-9">
                                        <div class="input-group">
                                            <div class="input-icon">
                                                <i class="fa fa-lock fa-fw"></i>
                                                <input id="newpassword" class="form-control" type="text" name="password" placeholder="password" /> </div>
                                                        <span class="input-group-btn">
                                                            <button id="genpassword" class="btn btn-success" type="button">
                                                                <i class="fa fa-arrow-left fa-fw" /></i> Random</button>
                                                        </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Input With Spinner</label>
                                    <div class="col-md-9">
                                        <input type="password" class="form-control spinner" placeholder="Password"> </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Static Control</label>
                                    <div class="col-md-9">
                                        <p class="form-control-static"> email@example.com </p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Disabled</label>
                                    <div class="col-md-9">
                                        <input type="password" class="form-control" placeholder="Disabled" disabled> </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Readonly</label>
                                    <div class="col-md-9">
                                        <input type="password" class="form-control" placeholder="Readonly" readonly> </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Dropdown</label>
                                    <div class="col-md-9">
                                        <select class="form-control">
                                            <option>Option 1</option>
                                            <option>Option 2</option>
                                            <option>Option 3</option>
                                            <option>Option 4</option>
                                            <option>Option 5</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Multiple Select</label>
                                    <div class="col-md-9">
                                        <select multiple class="form-control">
                                            <option>Option 1</option>
                                            <option>Option 2</option>
                                            <option>Option 3</option>
                                            <option>Option 4</option>
                                            <option>Option 5</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Textarea</label>
                                    <div class="col-md-9">
                                        <textarea class="form-control" rows="3"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputFile" class="col-md-3 control-label">File input</label>
                                    <div class="col-md-9">
                                        <input type="file" id="exampleInputFile">
                                        <p class="help-block"> some help text here. </p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Checkboxes</label>
                                    <div class="col-md-9">
                                        <div class="checkbox-list">
                                            <label>
                                                <input type="checkbox"> Checkbox 1 </label>
                                            <label>
                                                <input type="checkbox"> Checkbox 1 </label>
                                            <label>
                                                <input type="checkbox" disabled> Disabled </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Inline Checkboxes</label>
                                    <div class="col-md-9">
                                        <div class="checkbox-list">
                                            <label class="checkbox-inline">
                                                <input type="checkbox" id="inlineCheckbox21" value="option1"> Checkbox 1 </label>
                                            <label class="checkbox-inline">
                                                <input type="checkbox" id="inlineCheckbox22" value="option2"> Checkbox 2 </label>
                                            <label class="checkbox-inline">
                                                <input type="checkbox" id="inlineCheckbox23" value="option3" disabled> Disabled </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Radio</label>
                                    <div class="col-md-9">
                                        <div class="radio-list">
                                            <label>
                                                <input type="radio" name="optionsRadios" id="optionsRadios22" value="option1" checked> Option 1 </label>
                                            <label>
                                                <input type="radio" name="optionsRadios" id="optionsRadios23" value="option2" checked> Option 2 </label>
                                            <label>
                                                <input type="radio" name="optionsRadios" id="optionsRadios24" value="option2" disabled> Disabled </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Inline Radio</label>
                                    <div class="col-md-9">
                                        <div class="radio-list">
                                            <label class="radio-inline">
                                                <input type="radio" name="optionsRadios" id="optionsRadios25" value="option1" checked> Option 1 </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="optionsRadios" id="optionsRadios26" value="option2" checked> Option 2 </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="optionsRadios" id="optionsRadios27" value="option3" disabled> Disabled </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9">
                                        <button type="submit" class="btn green">Submit</button>
                                        <button type="button" class="btn default">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- END SAMPLE FORM PORTLET-->
            </div>
            <div class="col-md-6 ">
                <!-- BEGIN SAMPLE FORM PORTLET-->
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-settings font-dark"></i>
                            <span class="caption-subject font-dark sbold uppercase">Horizontal Form</span>
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <form class="form-horizontal" role="form">
                            <div class="form-body">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Block Help</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" placeholder="Enter text">
                                        <span class="help-block"> A block of help text. </span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Inline Help</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control input-inline input-medium" placeholder="Enter text">
                                        <span class="help-inline"> Inline help. </span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Input Group</label>
                                    <div class="col-md-9">
                                        <div class="input-inline input-medium">
                                            <div class="input-group">
                                                            <span class="input-group-addon">
                                                                <i class="fa fa-user"></i>
                                                            </span>
                                                <input type="email" class="form-control" placeholder="Email Address"> </div>
                                        </div>
                                        <span class="help-inline"> Inline help. </span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Email Address</label>
                                    <div class="col-md-9">
                                        <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <i class="fa fa-envelope"></i>
                                                        </span>
                                            <input type="email" class="form-control" placeholder="Email Address"> </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Password</label>
                                    <div class="col-md-9">
                                        <div class="input-group">
                                            <input type="password" class="form-control" placeholder="Password">
                                                        <span class="input-group-addon">
                                                            <i class="fa fa-user"></i>
                                                        </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Left Icon</label>
                                    <div class="col-md-9">
                                        <div class="input-icon">
                                            <i class="fa fa-bell-o"></i>
                                            <input type="text" class="form-control" placeholder="Left icon"> </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Right Icon</label>
                                    <div class="col-md-9">
                                        <div class="input-icon right">
                                            <i class="fa fa-microphone"></i>
                                            <input type="text" class="form-control" placeholder="Right icon"> </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Icon Input in Group Input</label>
                                    <div class="col-md-9">
                                        <div class="input-group">
                                            <div class="input-icon">
                                                <i class="fa fa-lock fa-fw"></i>
                                                <input id="newpassword" class="form-control" type="text" name="password" placeholder="password" /> </div>
                                                        <span class="input-group-btn">
                                                            <button id="genpassword" class="btn btn-success" type="button">
                                                                <i class="fa fa-arrow-left fa-fw" /></i> Random</button>
                                                        </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Input With Spinner</label>
                                    <div class="col-md-9">
                                        <input type="password" class="form-control spinner" placeholder="Password"> </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Static Control</label>
                                    <div class="col-md-9">
                                        <p class="form-control-static"> email@example.com </p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Disabled</label>
                                    <div class="col-md-9">
                                        <input type="password" class="form-control" placeholder="Disabled" disabled> </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Readonly</label>
                                    <div class="col-md-9">
                                        <input type="password" class="form-control" placeholder="Readonly" readonly> </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Dropdown</label>
                                    <div class="col-md-9">
                                        <select class="form-control">
                                            <option>Option 1</option>
                                            <option>Option 2</option>
                                            <option>Option 3</option>
                                            <option>Option 4</option>
                                            <option>Option 5</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Multiple Select</label>
                                    <div class="col-md-9">
                                        <select multiple class="form-control">
                                            <option>Option 1</option>
                                            <option>Option 2</option>
                                            <option>Option 3</option>
                                            <option>Option 4</option>
                                            <option>Option 5</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Textarea</label>
                                    <div class="col-md-9">
                                        <textarea class="form-control" rows="3"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputFile" class="col-md-3 control-label">File input</label>
                                    <div class="col-md-9">
                                        <input type="file" id="exampleInputFile">
                                        <p class="help-block"> some help text here. </p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Checkboxes</label>
                                    <div class="col-md-9">
                                        <div class="checkbox-list">
                                            <label>
                                                <input type="checkbox"> Checkbox 1 </label>
                                            <label>
                                                <input type="checkbox"> Checkbox 1 </label>
                                            <label>
                                                <input type="checkbox" disabled> Disabled </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Inline Checkboxes</label>
                                    <div class="col-md-9">
                                        <div class="checkbox-list">
                                            <label class="checkbox-inline">
                                                <input type="checkbox" id="inlineCheckbox21" value="option1"> Checkbox 1 </label>
                                            <label class="checkbox-inline">
                                                <input type="checkbox" id="inlineCheckbox22" value="option2"> Checkbox 2 </label>
                                            <label class="checkbox-inline">
                                                <input type="checkbox" id="inlineCheckbox23" value="option3" disabled> Disabled </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Radio</label>
                                    <div class="col-md-9">
                                        <div class="radio-list">
                                            <label>
                                                <input type="radio" name="optionsRadios" id="optionsRadios22" value="option1" checked> Option 1 </label>
                                            <label>
                                                <input type="radio" name="optionsRadios" id="optionsRadios23" value="option2" checked> Option 2 </label>
                                            <label>
                                                <input type="radio" name="optionsRadios" id="optionsRadios24" value="option2" disabled> Disabled </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Inline Radio</label>
                                    <div class="col-md-9">
                                        <div class="radio-list">
                                            <label class="radio-inline">
                                                <input type="radio" name="optionsRadios" id="optionsRadios25" value="option1" checked> Option 1 </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="optionsRadios" id="optionsRadios26" value="option2" checked> Option 2 </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="optionsRadios" id="optionsRadios27" value="option3" disabled> Disabled </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9">
                                        <button type="submit" class="btn green">Submit</button>
                                        <button type="button" class="btn default">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- END SAMPLE FORM PORTLET-->
            </div>
        </div>
        <!-- END PAGE BASE CONTENT -->
    </div>
@endsection

@section('pageBelowCorePlugins')
    <script src="../assets/global/plugins/jquery.min.js" type="text/javascript"></script>
    <script src="../assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="../assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
    <script src="../assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
    <script src="../assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
    <script src="../assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
    <script src="../assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
    <script src="../assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
@endsection

@section('pageBelowLevelPlugins')
    <script src="../assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
    <script src="../assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
    <script src="../assets/global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
@endsection

@section('themeBelowGlobalScripts')
    <script src="../assets/global/scripts/app.min.js" type="text/javascript"></script>
@endsection

@section('pageBelowLevelScripts')
@endsection

@section('themeBelowLayoutScripts')
    <script src="../assets/layouts/layout4/scripts/layout.min.js" type="text/javascript"></script>
    <script src="../assets/layouts/layout4/scripts/demo.min.js" type="text/javascript"></script>
    <script src="../assets/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>
@endsection

@section('pageCustomJScripts')
    <script type="text/javascript">
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
            // validation using icons
            var handleValidation2 = function() {
                // for more info visit the official plugin documentation:
                // http://docs.jquery.com/Plugins/Validation

                var form2 = $('#add_setting_form');
                var error2 = $('.alert-danger', form2);
                var success2 = $('.alert-success', form2);

                form2.validate({
                    errorElement: 'span', //default input error message container
                    errorClass: 'help-block help-block-error', // default input error message class
                    focusInvalid: false, // do not focus the last invalid input
                    ignore: "",  // validate all fields including form hidden input
                    rules: {
                        setting_name: {
                            minlength: 2,
                            required: true
                        },
                        setting_internal_name: {
                            minlength: 2,
                            required: true
                        },
                        setting_description: {
                            minlength: 5,
                            required: true
                        },
                        setting_type: {
                            required: true
                        },
                        setting_min_val: {
                            number: true
                        },
                        setting_max_val: {
                            number: true
                        },
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
                        add_new_setting(); // submit the form
                    }
                });


            }

            return {
                //main function to initiate the module
                init: function () {
                    handleValidation2();
                }
            };
        }();

        $(document).ready(function(){
            FormValidation.init();
        });

        function add_new_setting(){
            $.ajax({
                url: '{{route('ajax/register_new_setting')}}',
                type: "post",
                cache: false,
                data: {
                    'name':                 $('input[name="setting_name"]').val(),
                    'system_internal_name': $('input[name="setting_internal_name"]').val(),
                    'description':          $('textarea[name="setting_description"]').val(),
                    'contained':            $('input[name="setting_constrained"]').val(),
                    'data_type':            $('select[name="setting_type"]').val(),
                    'min_value':            $('input[name="setting_min_val"]').val(),
                    'max_value':            $('input[name="setting_max_val"]').val()
                },
                success: function (data) {
                    if (data.success) {
                        show_notification('New user registered', 'The details entered were correct so the user is now registered.', 'lime', 3500, 0);
                        setTimeout(function(){
                            window.location.reload(true);
                        },2500);
                    }
                    else{
                        show_notification('User registration ERROR', 'Something went wrong with the registration. Try changing the email/phone number or try reloading the page', 'tangerine', 3500, 0);
                    }
                }
            });
        }
    </script>
@endsection