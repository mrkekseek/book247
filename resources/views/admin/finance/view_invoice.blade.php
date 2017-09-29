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
                            <div class="row invoice-logo wrap">
                                <div class="col-md-6 invoice-logo-space left">
                                    <img src="{{ \App\Http\Controllers\AppSettings::get_setting_value_by_name('globalWebsite_account_logo_image')?\App\Http\Controllers\AppSettings::get_setting_value_by_name('globalWebsite_account_logo_image'):asset('assets/global/img/logo.png') }}" class="img-responsive" alt="" />
                                </div>
                                <div class="col-md-6 right">
                                    <p class="md-text-center"> #{{ $invoice->invoice_number }} / {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $invoice->created_at)->format('d M Y') }}
                                        <span class="muted"> {{ $invoice->invoice_type }} </span>
                                    </p>
                                </div>
                            </div>
                            <hr/>
                            <div class="row wrap clearfix">
                                <div class="col-md-4 left">
                                    <h3>Client:</h3>
                                    <ul class="list-unstyled">
                                        <li> {{ $member['full_name'] }} </li>
                                        <li> {{ $member['email_address'] }} </li>
                                        <li> {{ $member['country'] }} </li>
                                    </ul>
                                </div>
                                <div class="col-md-4  hidden-print">
                                </div>
                                <div class="col-md-4 invoice-payment right">
                                    <h3>Payment Details:</h3>
                                    <ul class="list-unstyled">
                                        <li>
                                            <strong>Company name:</strong> {{ isset($financial_profile->company_name) ? $financial_profile->company_name : 'None available'}} </li>
                                        <li>
                                            <strong>Registration number:</strong> {{ isset($financial_profile->organisation_number) ? $financial_profile->organisation_number : 'None available'}} </li>
                                        <li>
                                            <strong>Bank name:</strong> {{ isset($financial_profile->bank_name) ? $financial_profile->bank_name : 'None available' }} </li>
                                        <li>
                                            <strong>Bank account:</strong> {{ isset($financial_profile->bank_account) ? $financial_profile->bank_account : 'None available' }} </li>
                                        <li>
                                            <strong>Invoice number:</strong> {{ isset($invoice->invoice_number) ? $invoice->invoice_number : 'None available' }} </li>
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
                                                <td class="hidden-xs"> {{ $item->price  }} </td>
                                                <td class="hidden-xs"> {{ $item->discount }}% </td>
                                                <td class="hidden-xs"> {{ $item->vat }}% </td>
                                                <td> {{ $item->total_price }} </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row wrap">
                                <div class="col-md-4 left">
                                    <div class="well">
                                        <address>
                                            <strong>{{ !isset($financial_profile->address1) ? (isset($financial_profile->address2) ? $financial_profile->address2 : '') : $financial_profile->address1 }}</strong>
                                            <br/> {{ isset($financial_profile->city) ? $financial_profile->city : ''}}
                                            <abbr title="Postal Code">P:</abbr> {{ isset($financial_profile->postal_code) ? $financial_profile->postal_code : '' }}
                                            <br/> {{ isset($financial_profile->region) ? $financial_profile->region : ''}}
                                            <br/> {{ isset($country) ? $country : ''}}
                                        </address>
                                        <address>
                                            <strong>{{ $member['full_name'] }}</strong>
                                            <br/>
                                            <a href="mailto:{{$member['email_address']}}"> {{ $member['email_address'] }} </a></br>
                                            @if(isset($member['address']) && $member['address'])
                                                {{
                                                    $member['address']->address1.' '.$member['address']->postal_code.' '.$member['address']->city.' '.$member['address']->region
                                                }}
                                            @endif
                                            {{ ' '.$member['country'] }}
                                        </address>
                                    </div>
                                </div>
                                <div class="col-md-8 invoice-block right">
                                    <ul class="list-unstyled amounts">
                                        <li>
                                            <strong>Sub - Total amount:</strong> {{$sub_total.' '.$currency}} </li>
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
                                    <a href="javascript:;" class="btn btn blue hidden-print margin-bottom-5 download">
                                        Download PDF
                                        <i class="fa fa-download"></i>
                                    </a>
                                    @if ($invoice->status=='pending')
                                    <a class="btn green hidden-print margin-bottom-5" hidden data-toggle="confirmation-custom" data-key="{{$invoice->invoice_number}}" data-original-title="How would you pay?" data-singleton="true">
                                        Make Manual Payment <i class="fa fa-check"></i>
                                    </a>
                                    @elseif ($invoice->status=='processing')
                                        <a class="btn green hidden-print margin-bottom-5" hidden style="cursor:default;">
                                            Payment is processing <i class="fa fa-check"></i>
                                        </a>
                                    @elseif ($invoice->status=='cancelled')
                                        <a class="btn green hidden-print margin-bottom-5" hidden style="cursor:default;"> Invoice Cancelled
                                            <i class="fa fa-check"></i>
                                        </a>
                                    @elseif ($invoice->status=='declined')
                                        <a class="btn green hidden-print margin-bottom-5" hidden data-toggle="confirmation-custom" data-key="{{$invoice->invoice_number}}" data-original-title="How would you pay?" data-singleton="true"> Payment is declined
                                            <i class="fa fa-check"></i>
                                        </a>
                                    @elseif ($invoice->status=='incomplete')
                                        <a class="btn green hidden-print margin-bottom-5" hidden data-toggle="confirmation-custom" data-key="{{$invoice->invoice_number}}" data-original-title="How would you pay?" data-singleton="true"> Partially paid - pay remaining
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
            <div class="col-md-12">
                <div class="portlet light portlet-fit">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-bubble font-dark"></i>
                            <span class="caption-subject font-dark bold uppercase">Financial Transactions</span>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="table-scrollable">
                            <table class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th> # </th>
                                    <th> Invoice Item </th>
                                    <th> Amount </th>
                                    <th> Transaction Type </th>
                                    <th> Transaction Date </th>
                                    <th> other details </th>
                                    <th> Status </th>
                                </tr>
                                </thead>
                                <tbody>
                                @if (sizeof($financialTransactions)>0)
                                    @foreach($financialTransactions as $single)
                                        <tr>
                                            <td> {{ $key }} </td>
                                            <td>@foreach ($single->names as $itemNames)
                                                    {{$itemNames}}<br />
                                                @endforeach
                                            </td>
                                            <td> {{$single->transaction_amount}} {{$single->transaction_currency}} </td>
                                            <td> {{$single->transaction_type}} </td>
                                            <td> {{$single->transaction_date}} </td>
                                            <td> {{$single->other_details}} </td>
                                            <td>
                                                @if ($single->status=='pending')
                                                    <span class="label label-sm label-info"> Pending </span>
                                                @elseif ($single->status=='processing')
                                                    <span class="label label-sm label-info"> Processing </span>
                                                @elseif($single->status=='completed')
                                                    <span class="label label-sm label-success"> Completed </span>
                                                @elseif($single->status=='cancelled')
                                                    <span class="label label-sm label-warning"> Cancelled </span>
                                                @elseif($single->status=='declined')
                                                    <span class="label label-sm label-danger"> Declined </span>
                                                @elseif($single->status=='incomplete')
                                                    <span class="label label-sm label-warning"> Incomplete </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td></td>
                                        <td colspan="6">There are no financial transactions associated with this invoice</td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END PAGE BASE CONTENT -->
    </div>
@endsection

@section('pageBelowLevelPlugins')
    <script src="{{ asset('assets/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/jquery-validation/js/jquery.validate.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>

    <script src="{{ asset('assets/global/plugins/bootstrap-confirmation-2-4/bootstrap-confirmation.min.js') }}" type="text/javascript"></script>
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
                
                $.ajax({
                    url : "{{route('ajax/download_pdf')}}",
                    method : "post",
                    data : {
                        html : $('.bordered').first().html()
                    },
                    success: function(response){
                        
                        var href = 'data:application/pdf;base64,' + response.pdf;
                        

                        var link = document.createElement('a');
                        link.href = href;
                        link.download = "pdf_" + (new Date() / 1000) + ".pdf";
                        link.target = "blank";
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                    }
                })
            });
            
        });

        function mark_invoice_as_paid(invoice_number, payment_type){
            var returnStatus = $.ajax({
                url: '{{route('ajax/finance_action_invoice_paid')}}',
                type: "post",
                async: false,
                cache: false,
                data: {
                    'invoice_number': invoice_number,
                    'method': payment_type
                },
                success: function (data) {
                    if(data.success){
                        show_notification('Booking Paid', data.message, 'lime', 3500, 0);
                        return '1';
                    }
                    else{
                        show_notification('Error marking Paiment', data.errors, 'ruby', 3500, 0);
                        return '2';
                    }
                }
            }).responseJSON.success;

            return returnStatus;
        }

        $('[data-toggle=confirmation-custom]').confirmation({
            copyAttributes: ['data-key'],
            buttons: [
                {
                    label: 'Cash',
                    class: 'btn btn-sm blue',

                    onClick: function() {
                        var abutton = $('a[data-key="' + $(this).attr('data-key') + '"]');

                        var pay_answer = mark_invoice_as_paid($(this).attr('data-key'), 'cash');
                        if ( pay_answer == true ){
                            abutton.remove();
                            location.reload();
                        }
                        else{
                            abutton.confirmation('toggle');
                        }
                    }
                },
                {
                    label: 'Card',
                    class: 'btn btn-sm purple-seance',

                    onClick: function() {
                        var abutton = $('a[data-key="' + $(this).attr('data-key') + '"]');

                        var pay_answer = mark_invoice_as_paid($(this).attr('data-key'), 'card');
                        if ( pay_answer == true ){
                            abutton.remove();
                            location.reload();
                        }
                        else{
                            abutton.confirmation('toggle');
                        }
                    }
                },
                {
                    label: 'Credit',
                    class: 'btn btn-sm green-seagreen',

                    onClick: function() {
                        var abutton = $('a[data-key="' + $(this).attr('data-key') + '"]');

                        var pay_answer = mark_invoice_as_paid($(this).attr('data-key'), 'credit');
                        if ( pay_answer == true ){
                            abutton.remove();
                            location.reload();

                            //abutton.confirmation('destroy');
                            //abutton.unbind('click');
                            //abutton.css('cursor','default');
                        }
                        else{
                            abutton.confirmation('toggle');
                        }
                    }
                }
            ]
        });
    </script>
@endsection