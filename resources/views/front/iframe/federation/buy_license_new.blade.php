<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="Author"    content="author">
    <meta name="Keywords" content="keywords">
    <meta name="description" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ env('MY_SERVER_URL') }}</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset ('assets/iframe/css/fonts.css') }}">
    <link rel="stylesheet" href="{{ asset ('assets/iframe/libs/slick/slick.css') }}">
    <link rel="stylesheet" href="{{ asset ('assets/iframe/libs/slick/slick-theme.css') }}">
    <link rel="stylesheet" href="{{ asset ('assets/iframe/css/style.css') }}">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="body-iframe">

@if(isset($membership_active) || isset($membership_suspended))
    <div class="membership-status-container">
        @if(isset($membership_active))
            <span class="membership-status"> You have an active membership </span>
        @endif
        @if(isset($membership_suspended))
            <span class="membership-status"> Your membership is suspended </span>
        @endif
    </div>


@else
    @if(!isset($membership))
    <div class="wrapper body-iframe-step-1" id="body-iframe-step-1">
        <div class="container-iframe">
            <h3 class="slider-caption">To join the event you need to get <br>  License (Membership) from "XXXXX".  <br>You can do it right away here:</h3>
            <div class="carusel-wraper">
                <div class="carusel items-container simple-items">
                    @foreach ($membership_list as $key => $m)
                        @if ($m->status == 'active')
                            <div class="carusel-item-content carusel-item-content-{{ $m->id }}" >
                                <div class="box-item item item-{{ $key }}" style="border-top: 8px solid {{ $m->plan_calendar_color }}"  data-match-height="memberships-options">
                                    <h2 class="h2">{{ $m->name }}</h2>
                                    {{--<p style="height:96px" class="after-cap">--}}
                                        {{--{{ $m->short_description  }}--}}
                                    {{--</p>--}}
                                    <h3 class="h3" style="color: {{ $m->plan_calendar_color }}">{{ $m->get_price()->price }},-/mo</h3>
                                    <p>First month fee {{ $m->administration_fee_amount }},-</p>
                                    <ul class="list">
                                        <li>Billing: monthly</li>
                                        <li>Binding: {{ $m->binding_period }} months</li>
                                        <li>Sign out: {{ $m->sign_out_period ? $m->sign_out_period .' months' : 'none'}}</li>
                                    </ul>
                                    <p>Can not book squash</p>
                                    <div class="carousel-button-wrap">
                                        <a href="#" data-id="membership" data-value="{{ $m->id }}" class="form-choice carusel-button steps-button" style="background: {{ $m->plan_calendar_color }}">GET IT NOW</a>
                                        <div class="checkbox-chek checkbox-chek-mobile">
                                            <input type="checkbox" class="chek-page">
                                            <p class="mobile-accept">Accept our <a href="#">Terms & Conditions </a> </p>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
            <div class="checkbox-chek checkbox-chek-desktop">
                <input type="checkbox" class="chek-page">
                <p class="desktop-accept">You need to accept our <a href="#">Terms & Conditions </a> before you do any purchases.</p>
            </div>
        </div>
    </div>
    @endif
    <!--item-2-->
    <div class="wrapper body-iframe-step-2" id="body-iframe-step-2">
        <div class="container-iframe">

            <div class="step-box clearfix">
                @if (isset($membership) && is_object($membership))
                    <div class="box-item" style="border-top: 8px solid {{ $membership->plan_calendar_color }}">
                        <h2 class="h2">{{ $membership->name }}</h2>
                        {{--<p style="height:96px" class="after-cap">--}}
                            {{--{{ $membership->short_description  }}--}}
                        {{--</p>--}}
                        <h3 class="h3" style="color: {{ $membership->plan_calendar_color }}">{{ $membership->get_price()->price }},-/mo</h3>
                        <p>First month fee {{ $membership->administration_fee_amount }},-</p>
                        <ul class="list">
                            <li>Billing: monthly</li>
                            <li>Binding: {{ $membership->binding_period }} months</li>
                            <li>Sign out: {{ $membership->sign_out_period ? $membership->sign_out_period .' months' : 'none'}}</li>
                        </ul>
                        <p>Can not book squash</p>
                        <div class="button-box">
                            <a href="#" data-id="payment_method" data-value="card" class="steps-button pay-but pay-with-card form-choice" style="background: {{ $membership->plan_calendar_color }}">PAY WITH A CARD</a>
                            <a href="#" data-id="payment_method" data-value="paypal" class="steps-button pay-but pay-with-paypal form-choice">
                                <span>PAY WITH</span>
                                <img src="{{  asset('assets/iframe/img/icon-pay.png') }}" alt="PayPal">
                            </a>
                        </div>
                    </div>
                @else
                    <div class="box-item membership-replacer">

                        <div class="button-box">
                            <a href="#" data-id="payment_method" data-value="card" class="steps-button pay-but pay-with-card form-choice">PAY WITH A CARD</a>
                            <a href="#" data-id="payment_method" data-value="paypal" class="steps-button pay-but pay-with-paypal form-choice">
                                <span>PAY WITH</span>
                                <img src="{{  asset('assets/iframe/img/icon-pay.png') }}" alt="PayPal">
                            </a>
                        </div>
                    </div>
                @endif

                <div class="picture-item">
                    <img src="{{  asset('assets/iframe/img/picture.jpg') }}" alt="picture">
                </div>
            </div>
        </div>
    </div>
    @if (isset($membership) && is_object($membership))
        <script type="text/javascript">
            $('#body-iframe-step-2 .container-iframe').animate({
                opacity: 1 ,
                zIndex: 1
            }, 900, 'linear');
        </script>
    @endif
    <!--item-3-->
    <div class="wrapper body-iframe-step-3" id="body-iframe-step-3">
        <div class="container-iframe">
            <div class="order-wrapper clearfix">
                <div class="order-summary">
                    <h2 class="h2">ORDER SUMMARY</h2>
                    <div class="summary-box clearfix">
                        <div>
                            <span>Dag/Helg - Fitness</span>
                            <p class="summary-sample">2 months signing out period and no binding.</p>
                            <p class="summary-sample">Monthly billing</p>
                        </div>
                        <div>
                            <span>149,-</span>
                        </div>
                    </div>
                    <div class="summary-total clearfix">
                        <div class="summary-total-name"><span>Item total</span></div>
                        <div class="summary-total-pay"><span>149,-</span></div>
                    </div>
                    <p class="summary-total-summ">Total 149,-</p>
                </div>
                <div class="order-details">
                    <h2 class="h2">PAYMENT DETAILS</h2>
                    <form action="1.php" method="post" class="order-form">
                        <label for="CardNumber">
                            <span>Card Number</span>
                            <input type="text" id="CardNumber" name="CardNumber">
                        </label>
                        <div>
                            <p>Expiration Date</p>
                            <input type="text" placeholder="mm" class="small-input"> <span>/</span>
                            <input type="text" placeholder="yy" class="small-input">
                        </div>
                        <label for="cvv">
                            <span>CVV</span>
                            <input type="text" class="small-input" id="cvv" name="cvv">
                        </label>
                        <label for="HolderName">
                            <span>Card Holder Name (as seen on the card)</span>
                            <input type="text" id="HolderName" name="HolderName">
                        </label>
                        <a href="#" class="steps-button payment-but pay-with-card-final">PAY NOW</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="wrapper body-iframe-step-4" id="body-iframe-step-4">
        <div class="container-iframe">
            <div class="step-box clearfix">
                <span> Payment in progress </span>
                <span class="dot dot-one">.</span>
                <span class="dot dot-two">.</span>
                <span class="dot dot-three">.</span>
            </div>
        </div>
    </div>

    <form id="main-form" style="display: none">
        <input name="user_id" type="hidden" value="{{ isset($user_id) ? $user_id : '' }}"/>
        <input name="membership" type="text" id="membership" value="{{ isset($membership) ? $membership->id : '' }}"/>
        <input name="payment_method" type="text" id="payment_method"/>
        <input name="redirect_url" type="text" value="{{ $redirect_url }}"/>
    </form>

    <form id="paypal-form" action="{{ env('PAYPAL_SANDBOX') }}"  target="_parent" method="post" style="display: none;">
        <input type="hidden" name="cmd" value="_cart">
        <input type="hidden" name="business" value="{{ env('PAYPAL_EMAIL') }}">
        <input type="hidden" name="return" value="{{ env('MY_SERVER_URL') }}/membership/paypal_success">
        <input type="hidden" name="cancel_url" value="{{ env('MY_SERVER_URL') }}/membership/paypal_cancel">
        <input type="hidden" name="notify_url" value="{{ env('MY_SERVER_URL') }}/membership/ipn">
        <input type="hidden" name="rm" value="1">
        <input type="hidden" name="upload" value="1">

        <input type="hidden" name="item_name" value="">
        <input type="hidden" name="amount" value="">
        <input type="hidden" name="quantity" value="1">
        <input type="hidden" name="currency_code" value="USD">

        <!-- Set variables that override the address stored with PayPal. -->

        <input type="hidden" name="first_name" value="">
        <input type="hidden" name="last_name" value="">
        <input type="hidden" name="email" value="">

        <input type="hidden" name="custom" value="">
        <input type="hidden" name="invoice" value="">

    </form>
@endif
<div class="alert-box-container">
    <div class="alert-box">
        <span>You have to accept Terms & Conditions</span>
        <button class="btn btn-primary" id="close_alert">OK</button>
    </div>

</div>

<!--====END MODAL====-->
<script src="{{ asset ('assets/iframe/libs/JQ_1-9-1/jquery.min.js') }}"></script>
<script src="{{ asset ('assets/iframe/libs/slick/slick.min.js') }}"></script>
<script src="{{ asset ('assets/iframe/js/common.js') }}"></script>
<script src="{{ asset('assets/global/scripts/jquery.matchHeight.js') }}"></script>
<script type="text/javascript">
    function inIframe () {
        try {
            return window.self !== window.top;
        } catch (e) {
            return true;
        }
    }
    (function($){
        $(document).ready(function() {
            var $form = $('#main-form');
            var $paypal_form = $('#paypal-form');
            if (!inIframe()) {
//                $('body').text('accessed only in iframe!');
            }
            $('.form-choice').click(function(){
                if($('.chek-page').is(':checked')) {
                    var $input = $form.find('#' + $(this).data('id'));
                    if ($input.length) {
                        $input.attr('value', $(this).data('value'));
                        console.log('form updated');
                    } else {
                        if ($(this).data('id') && $(this).data('id') !== 'user_id') {
                            $form.append('<input type="text" name="' + $(this).data('id') + '" id="' + $(this).data('id') + '" value="' + $(this).data('value') + '">');
                        }
                    }
                }
            });
            $('.chek-page').click(function(){
                if ($(this).is(':checked')){
                    $('input[type=checkbox]').attr('checked', true);
                } else {
                    $('input[type=checkbox]').attr('checked', false);
                }

            });
            $('.pay-with-paypal').click(function(){

                $.ajax({
                    url: '/membership/paypal_payment',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        'user_id' : $('input[name=user_id]').val() ,
                        'membership' : $('#membership').val(),
                        'payment_method' : $('#payment_method').val(),
                        'redirect_url': $('input[name=redirect_url]').val()
                    },
                    success: function(response)
                    {
                        console.log(response);
                        $paypal_form.find('input[name=item_name]');
                        $paypal_form.find('input[name=amount]');
//                        $paypal_form.find('input[name=item_name]').attr('value',response.data.membership_name);
//                        $paypal_form.find('input[name=amount]').attr('value',response.data.price);
                        if(response.data.invoices.length > 1){
                            for (var i=1; i <= response.data.invoices.length ; i++) {
                                console.log(response.data.invoices[i]);
                                var $name_clone = $paypal_form.find('input[name=item_name]').clone();
                                $name_clone.attr('name',$name_clone.attr('name') + '_' + i).val(response.data.invoices[i-1].item_name);
                                var $amount_clone = $paypal_form.find('input[name=amount]').clone();
                                $amount_clone.attr('name',$amount_clone.attr('name') + '_' + i).val(response.data.invoices[i-1].price);
                                var $quantity_clone = $paypal_form.find('input[name=quantity]').clone();
                                $quantity_clone.attr('name',$quantity_clone.attr('name') + '_' + i).val(1);
                                $paypal_form.append($name_clone).append($amount_clone).append($quantity_clone);
                            }
                            $paypal_form.find('input[name=item_name]').remove();
                            $paypal_form.find('input[name=amount]').remove();
                            $paypal_form.find('input[name=quantity]').remove();
                        } else {
                            $paypal_form.find('input[name=item_name]').val(response.data.invoices[0].item_name);
                            $paypal_form.find('input[name=amount]').val(response.data.invoices[0].price);
                            $paypal_form.find('input[name=quantity]').val(1);
                        }
                        $paypal_form.find('input[name=invoice]').attr('value',response.data.invoices[0].invoice_id);
                        $paypal_form.find('input[name=custom]').attr('value',response.data.custom);
                        $paypal_form.find('input[name=first_name]').attr('value',response.data.user.first_name);
                        $paypal_form.find('input[name=last_name]').attr('value',response.data.user.last_name);
                        $paypal_form.find('input[name=email]').attr('value',response.data.user.email);
                        $paypal_form.submit();
                    },
                    error: function(response)
                    {
                        console.log('failure');
                    }
                });
//                $form.attr('action','/membership/payment');
//                $form.attr('method','POST');
//                $form.submit();
            });
            $('.carusel').slick({
                slidesToShow: 3,
                slidesToScroll: 1,
                arrows: true,
                prevArrow:"<button type='button' class='prev'><img src='{{asset('assets/iframe/img/left.png')}}'></button>",
                nextArrow:"<button type='button' class='next'><img src='{{asset('assets/iframe/img/right.png')}}'></button>",
                fade: false,
                autoplay:false,
                speed : 500,
                autoplaySpeed: 10000,
                infinite:false,
                dots: false,
                responsive: [
                    {
                        breakpoint: 769,
                        settings: {
                            slidesToShow: 1,
                            infinite:true,
                            dots: true,
                        }
                    }
                ]
            });
//            if (window.innerWidth < 700) {
//                $('.box-item').find('p').css({"height":"auto"});
//                $('.box-item').find('h2').css({"height":"auto"});
//            }
//            $(window).resize(function(){
//                if (window.innerWidth < 700) {
//                    $('.box-item').find('p').css({"height":"auto"});
//                    $('.box-item').find('h2').css({"height":"auto"});
//                } else {
//                    $('.box-item').find('p').css({"height":96});
//                    $('.box-item').find('h2').css({"height":52});
//                }
//            });

        });
    } )(jQuery);
</script>

</body>
</html>

