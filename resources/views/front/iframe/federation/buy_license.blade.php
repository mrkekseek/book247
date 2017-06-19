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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
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
<body class="body-iframe body-iframe-step-1">
<div class="wrapper">
    <div class="container-iframe">
        <div class="tab-content">
            <div id="step-1" class="tab-pane fade in active">
                <div class="carusel-wraper">
                    <div class="carusel">
                        <div class="carusel-item-content carusel-item-content-1">
                            <div class="box-item">
                                <h2 class="h2">Dag/Helg - Fitness</h2>
                                <p class="after-cap"> No sigining out period and <br>
                                    12 months binding.
                                </p>
                                <h3 class="h3">119,-/mo</h3>
                                <p>First month fee 169,-</p>
                                <ul class="list">
                                    <li>Billing: monthly</li>
                                    <li>Binding: 12 months</li>
                                    <li>Sign out: none</li>
                                </ul>
                                <p>Can not book squash</p>
                                <a data-toggle="tab" href="#step-2" data-id="membership" data-value="119" class="carusel-button steps-button form-choice">GET IT NOW</a>
                            </div>
                        </div>
                        <div class="carusel-item-content carusel-item-content-2 ">
                            <div class="box-item">
                                <h2 class="h2">Dag/Helg - Fitness</h2>
                                <p class="after-cap"> 2 months signing out period<br> and no binding.
                                </p>
                                <h3 class="h3">149,-/mo</h3>
                                <p>First month fee 199,-</p>
                                <ul class="list">
                                    <li>Billing: monthly</li>
                                    <li>Binding: none</li>
                                    <li>Sign out: 2 months</li>
                                </ul>
                                <p>Can not book squash</p>
                                <a data-toggle="tab" href="#step-2" data-id="membership" data-value="149" class="carusel-button steps-button form-choice">GET IT NOW</a>
                            </div>
                        </div>
                        <div class="carusel-item-content carusel-item-content-3">
                            <div class="box-item">
                                <h2 class="h2">Dag/Helg - Fitness</h2>
                                <p class="after-cap"> No signing out period and no<br> binding.
                                </p>
                                <h3 class="h3">229,-/mo</h3>
                                <p>First month fee 279,-</p>
                                <ul class="list">
                                    <li>Billing: monthly</li>
                                    <li>Binding: none</li>
                                    <li>Sign out: none</li>
                                </ul>
                                <p>Can not book squash</p>
                                <a data-toggle="tab" href="#step-2" data-id="membership" data-value="229" class="carusel-button steps-button form-choice">GET IT NOW</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="step-2" class="tab-pane fade">
                <div class="step-box clearfix">
                    <div class="box-item">
                        <h2 class="h2">Dag/Helg - Fitness</h2>
                        <p class="after-cap"> 2 months signing out period and no binding.</p>
                        <h3 class="h3">149,-/mo</h3>
                        <p>First month fee 199,-</p>
                        <ul class="list">
                            <li>Billing: monthly</li>
                            <li>Binding: none</li>
                            <li>Sign out: 2 months</li>
                        </ul>
                        <p>Can not book squash</p>
                        <div class="button-box">
                            <a data-toggle="tab" href="#step-3" class="steps-button pay-but form-choice" data-id="payment-method" data-value="card">PAY WITH A CARD</a>
                            <a data-toggle="tab" href="#step-3" class="steps-button pay-but form-choice deploy-paypal" data-id="payment-method" data-value="paypal">
                                <span>PAY WITH</span>
                                <img src="{{  asset('assets/iframe/img/icon-pay.png') }}" alt="PayPal">
                            </a>
                        </div>
                    </div>
                    <div class="picture-item">
                        <img src="{{  asset('assets/iframe/img/picture.jpg') }}" alt="picture">
                    </div>
                </div>
            </div>
            <div id="step-3" class="tab-pane fade">
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
                        <form action="" method="post" class="order-form">
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
                            <a data-toggle="tab" href="" class="steps-button payment-but">PAY NOW</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<form id="main-form" style="display: none">
    <input name="user_id" type="hidden" value="{{ $user_id }}"/>
    <input name="membership" type="text" id="membership"/>
    <input name="payment-method" type="text" id="payment-method"/>
</form>
<!--====END MODAL====-->
<script src="{{ asset ('assets/iframe/libs/JQ_1-9-1/jquery.min.js') }}"></script>
<script src="{{ asset ('assets/iframe/libs/slick/slick.min.js') }}"></script>
<script src="{{ asset ('assets/iframe/js/common.js') }}"></script>
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
            if (!inIframe()) {
                $('body').text('accessed only in iframe!');
            }
            $('.form-choice').click(function(){
                var $input = $form.find('#'+$(this).data('id'));
                if($input.length) {
                    $input.attr('value',$(this).data('value'));
                } else {
                    if($(this).data('id') && $(this).data('id') !== 'user_id') {
                        $form.append('<input type="text" name="' + $(this).data('id') + '" id="' + $(this).data('id') + '" value="' + $(this).data('value') + '">');
                    }
                }
            });
            $('.deploy-paypal').click(function(){
                $form.attr('action','membership/paypal_payment');
                $form.attr('method','POST');
                $form.submit();
            });
        });
    } )(jQuery);
</script>
</body>
</html>

