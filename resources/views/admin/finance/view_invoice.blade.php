@extends('admin.layouts.main')

@section('pageLevelPlugins')
    <link href="{{ asset('assets/global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css') }}" rel="stylesheet" type="text/css" />

    <link href="{{ asset('assets/global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/select2/css/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('themeGlobalStyle')
    <link href="{{ asset('assets/global/css/components-rounded.min.css') }}" rel="stylesheet" id="style_components" type="text/css" />
    <link href="{{ asset('assets/global/css/plugins.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/jquery-notific8/jquery.notific8.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/pages/css/invoice.min.css') }}" rel="stylesheet" type="text/css" />
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
        <!-- BEGIN PAGE BASE CONTENT -->
        <div class="row">
            <div class="col-md-12">
                <!-- Begin: life time stats -->
                <div class="portlet light portlet-fit portlet-datatable bordered">
                    <div class="portlet-body">
                        <div class="invoice">
                            <div class="row invoice-logo">
                                <div class="col-xs-6 invoice-logo-space">
                                    <img src="{{ asset('assets/global/img/sqf-logo.png') }}" class="img-responsive" alt="" /> </div>
                                <div class="col-xs-6">
                                    <p> #{{ $invoice->invoice_number }} / {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $invoice->created_at)->format('d M Y') }}
                                        <span class="muted"> {{ $invoice->invoice_type }} </span>
                                    </p>
                                </div>
                            </div>
                            <hr/>
                            <div class="row">
                                <div class="col-xs-4">
                                    <h3>Client:</h3>
                                    <ul class="list-unstyled">
                                        <li> {{ $member['full_name'] }} </li>
                                        <li> {{ $member['email_address'] }} </li>
                                        <li> {{ $member['country'] }} </li>
                                    </ul>
                                </div>
                                <div class="col-xs-4  hidden-print">
                                </div>
                                <div class="col-xs-4 invoice-payment">
                                    <h3>Payment Details:</h3>
                                    <ul class="list-unstyled">
                                        <li>
                                            <strong>V.A.T Reg #:</strong> 542554(DEMO)78 </li>
                                        <li>
                                            <strong>Account Name:</strong> FoodMaster Ltd </li>
                                        <li>
                                            <strong>SWIFT code:</strong> 45454DEMO545DEMO </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="row" style="min-height:300px;">
                                <div class="col-xs-12">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                        <tr>
                                            <th> # </th>
                                            <th> Item </th>
                                            <th class="hidden-xs"> Quantity </th>
                                            <th class="hidden-xs"> Unit Cost </th>
                                            <th class="hidden-xs"> Discount </th>
                                            <th class="hidden-xs"> VAT </th>
                                            <th> Total </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($invoice_items as $key=>$item)
                                            <tr>
                                                <td> {{$key+1}} </td>
                                                <td> {{ $item->item_name }} </td>
                                                <td class="hidden-xs"> {{ $item->quantity }} </td>
                                                <td class="hidden-xs"> {{ $item->price }} </td>
                                                <td class="hidden-xs"> {{ $item->discount }}% </td>
                                                <td class="hidden-xs"> {{ $item->vat }}% </td>
                                                <td> {{ $item->total_price }} </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-4">
                                    <div class="well">
                                        <address>
                                            <strong>Rud Squash AS</strong>
                                            <br/> Postbox 60
                                            <br/> N-1309 Rud
                                            <br/>
                                            <abbr title="Phone">P:</abbr> (234) 145-1810 </address>
                                        <address>
                                            <strong>{{ $member['full_name'] }}</strong>
                                            <br/>
                                            <a href="mailto:{{$member['email_address']}}"> {{ $member['email_address'] }} </a>
                                        </address>
                                    </div>
                                </div>
                                <div class="col-xs-8 invoice-block">
                                    <ul class="list-unstyled amounts">
                                        <li>
                                            <strong>Sub - Total amount:</strong> {{$sub_total}} </li>
                                        <li>
                                            <strong>Discount:</strong> {{$discount}} </li>
                                        @foreach($vat as $key_vat=>$item_vat)
                                        <li>
                                            <strong>VAT {{$key_vat}}%:</strong> {{$item_vat==0?'----':$item_vat}} </li>
                                        @endforeach
                                        <li>
                                            <strong>Total:</strong> {{$grand_total}} </li>
                                    </ul>
                                    <br/>
                                    <a class="btn btn-lg blue hidden-print margin-bottom-5" hidden onclick="javascript:window.print();"> Print
                                        <i class="fa fa-print"></i>
                                    </a>
                                    <a class="btn btn-lg blue hidden-print margin-bottom-5 download" hidden >
                                        Download PDF
                                        <i class="fa fa-download"></i>
                                    </a>
                                    @if ($invoice->status=='pending')
                                    <a class="btn btn-lg green hidden-print margin-bottom-5" hidden > Make Payment
                                        <i class="fa fa-check"></i>
                                    </a>
                                    @elseif ($invoice->status=='processing')
                                        <a class="btn btn-lg green hidden-print margin-bottom-5"> Payment is processing
                                            <i class="fa fa-check"></i>
                                        </a>
                                    @elseif ($invoice->status=='cancelled')
                                        <a class="btn btn-lg green hidden-print margin-bottom-5"> Invoice Cancelled
                                            <i class="fa fa-check"></i>
                                        </a>
                                    @elseif ($invoice->status=='declined')
                                        <a class="btn btn-lg green hidden-print margin-bottom-5"> Payment is declined
                                            <i class="fa fa-check"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End: life time stats -->
            </div>
        </div>
        <!-- END PAGE BASE CONTENT -->
    </div>
@endsection

@section('pageBelowLevelPlugins')
    <script src="{{ asset('assets/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/jquery-validation/js/jquery.validate.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
@endsection

@section('pageBelowLevelScripts')
    <script src="{{ asset('assets/global/plugins/jquery-notific8/jquery.notific8.min.js') }}" type="text/javascript"></script>
@endsection

@section('themeBelowLayoutScripts')
    <script src="{{ asset('assets/layouts/layout4/scripts/layout.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/layouts/layout4/scripts/demo.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/layouts/global/scripts/quick-sidebar.min.js') }}" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.4/jspdf.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
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
            var handleValidation1 = function() {
                var form1 = $('#all_order_lines');
                var error1 = $('.alert-danger', form1);
                var success1 = $('.alert-success', form1);

                form1.validate({
                    errorElement: 'span', //default input error message container
                    errorClass: 'help-block help-block-error', // default input error message class
                    focusInvalid: false, // do not focus the last invalid input
                    ignore: "",  // validate all fields including form hidden input
                    rules: {
                        shop_name: {
                            minlength: 3,
                            required: true
                        },
                        shop_email: {
                            email: true,
                            required: true,
                            validate_email: true
                        },
                    },

                    invalidHandler: function (event, validator) { //display error alert on form submit
                        success1.hide();
                        error1.show();
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
                        success1.show();
                        error1.hide();
                        form.submit(); // submit the form
                    }
                });
            }

            return {
                //main function to initiate the module
                init: function () {
                    handleValidation1();
                }

            };
        }();

        $(document).ready(function(){
            FormValidation.init();

            $(".download").click(function(){
                $(".bordered").find("[hidden]").hide();
                html2canvas($('.bordered')[0], {
                    onrendered: function(canvas) {
                        $(".bordered").find("[hidden]").show();

                        var doc = new jsPDF("p", "mm", "a4");
                        doc.addImage(canvas, 'PNG', 10, 20, 190, 180);
                        doc.save('export.pdf');
                         
                    }
                });
            });
            
        });
    </script>
@endsection