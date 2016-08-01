@extends('layouts.main')

@section('pageLevelPlugins')
    <link href="{{ asset('assets/global/plugins/jquery-notific8/jquery.notific8.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/pages/css/contact.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('themeGlobalStyle')
    <link href="{{ asset('assets/global/css/components-rounded.min.css') }}" rel="stylesheet" id="style_components" type="text/css" />
    <link href="{{ asset('assets/global/css/plugins.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('themeLayoutStyle')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection

@section('title', 'Main Page')
@section('pageBodyClass','page-container-bg-solid page-boxed')

@section('pageContentBody')
    <div class="page-content-wrapper">
        <!-- BEGIN CONTENT BODY -->
        <!-- BEGIN PAGE CONTENT BODY -->
        <div class="page-content">
            <div class="container">
                <!-- BEGIN PAGE CONTENT INNER -->
                <div class="page-content-inner">
                    <div class="c-content-contact-1 c-opt-1">
                        <div class="row" data-auto-height=".c-height">
                            <div class="col-lg-8 col-md-6 c-desktop"></div>
                            <div class="col-lg-4 col-md-6" style="min-height:600px;">
                                <!--<div class="c-body">
                                    <div class="c-section">
                                        <h3>Metronic Inc.</h3>
                                    </div>
                                    <div class="c-section">
                                        <div class="c-content-label uppercase bg-blue">Address</div>
                                        <p>25, Lorem Lis Street,
                                            <br/>Orange C, California,
                                            <br/>United States of America</p>
                                    </div>
                                    <div class="c-section">
                                        <div class="c-content-label uppercase bg-blue">Contacts</div>
                                        <p>
                                            <strong>T</strong> 800 123 0000
                                            <br/>
                                            <strong>F</strong> 800 123 8888</p>
                                    </div>
                                    <div class="c-section">
                                        <div class="c-content-label uppercase bg-blue">Social</div>
                                        <br/>
                                        <ul class="c-content-iconlist-1 ">
                                            <li>
                                                <a href="#">
                                                    <i class="fa fa-twitter"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <i class="fa fa-facebook"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <i class="fa fa-youtube-play"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <i class="fa fa-linkedin"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>-->
                            </div>
                        </div>
                        <div id="gmapbg" class="c-content-contact-1-gmap" style="height: 615px;"></div>
                    </div>
                    <div class="c-content-feedback-1 c-option-1">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="c-container bg-green">
                                    <div class="c-content-title-1 c-inverse">
                                        <h3 class="uppercase">Need to know more?</h3>
                                        <div class="c-line-left"></div>
                                        <p class="c-font-lowercase">Try visiting our FAQ page to learn more about our greatest ever expanding theme, Metronic.</p>
                                        <button class="btn grey-cararra font-dark">Learn More</button>
                                    </div>
                                </div>
                                <div class="c-container bg-grey-steel">
                                    <div class="c-content-title-1">
                                        <h3 class="uppercase">Have a question?</h3>
                                        <div class="c-line-left bg-dark"></div>
                                        <form action="#">
                                            <div class="input-group input-group-lg c-square">
                                                <input type="text" class="form-control c-square" placeholder="Ask a question" />
                                                        <span class="input-group-btn">
                                                            <button class="btn uppercase" type="button">Go!</button>
                                                        </span>
                                            </div>
                                        </form>
                                        <p>Ask your questions away and let our dedicated customer service help you look through our FAQs to get your questions answered!</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="c-contact">
                                    <div class="c-content-title-1">
                                        <h3 class="uppercase">Keep in touch</h3>
                                        <div class="c-line-left bg-dark"></div>
                                        <p class="c-font-lowercase">Our helpline is always open to receive any inquiry or feedback. Please feel free to drop us an email from the form below and we will get back to you as soon as we can.</p>
                                    </div>
                                    <form action="#">
                                        <div class="form-group">
                                            <input type="text" placeholder="Your Name" class="form-control input-md"> </div>
                                        <div class="form-group">
                                            <input type="text" placeholder="Your Email" class="form-control input-md"> </div>
                                        <div class="form-group">
                                            <input type="text" placeholder="Contact Phone" class="form-control input-md"> </div>
                                        <div class="form-group">
                                            <textarea rows="8" name="message" placeholder="Write comment here ..." class="form-control input-md"></textarea>
                                        </div>
                                        <button type="submit" class="btn grey">Submit</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END PAGE CONTENT INNER -->
            </div>

        </div>
        <!-- END PAGE CONTENT BODY -->
        <!-- END CONTENT BODY -->
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
    <script src="{{ asset('assets/global/plugins/jquery-validation/js/jquery.validate.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/jquery-validation/js/additional-methods.min.js') }}" type="text/javascript"></script>
    <script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/gmaps/gmaps.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/jquery-notific8/jquery.notific8.min.js') }}" type="text/javascript"></script>
@endsection

@section('themeBelowGlobalScripts')
    <script src="{{ asset('assets/global/scripts/app.min.js') }}" type="text/javascript"></script>
@endsection

@section('pageBelowLevelScripts')
    <script src="{{ asset('assets/pages/scripts/ui-notific8.min.js') }}" type="text/javascript"></script>
@endsection

@section('themeBelowLayoutScripts')
    <script src="{{ asset('assets/layouts/layout3/scripts/layout.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/layouts/global/scripts/quick-sidebar.min.js') }}" type="text/javascript"></script>
@endsection

@section('pageCustomJScripts')
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var Contact = function () {

            return {
                //main function to initiate the module
                // use : http://www.latlong.net/ - to get lat, lng information
                init: function () {
                    var map;
                    $(document).ready(function(){
                        map = new GMaps({
                            div: '#gmapbg',
                            lat: 59.926029,
                            lng: 10.700865,
                            zoom: 13
                        });
                        map.addMarker({
                            lat: 59.739949,
                            lng: 10.212961,
                            title: 'Drammen Squash (NYHET!)',
                            infoWindow: {
                                content: "Adresse: Dr. Hansteins Gate 26<br />3044 Drammen"
                            }
                        });
                        map.addMarker({
                            lat: 59.911891,
                            lng: 10.637080,
                            title: 'Lysaker Squash',
                            infoWindow: {
                                content: "Adresse: Lysaker Torg 8<br />1366 Lysaker<br />Telefon: 67591420<br />Epost: lysaker@sqf.no"
                            }
                        });
                        map.addMarker({
                            lat: 59.919937,
                            lng: 10.746661,
                            title: 'Sentrum Squash & Fitness',
                            infoWindow: {
                                content: "Adresse: Thor Olsens gate 5<br />0177 Oslo<br />Telefon: 22207060<br />Epost: sentrum@sqf.no"
                            }
                        });
                        map.addMarker({
                            lat: 59.913869,
                            lng: 10.752245,
                            title: 'Bærum Squash & Fitness',
                            infoWindow: {
                                content: "Adresse: Rudsletta 81 <br />1351 Rud<br />Telefon: 67135650<br />Epost: baerum@sqf.no"
                            }
                        });
                        map.addMarker({
                            lat: 59.930144,
                            lng: 10.755846,
                            title: 'Sagene Squashsenter',
                            infoWindow: {
                                content: "Adresse: Sagveien 21 <br />0459 Oslo<br />Telefon: 22355511<br />Epost: sagene@sqf.no"
                            }
                        });
                        var marker_new =  map.addMarker({
                            lat: 59.914624,
                            lng: 10.749050,
                            title: 'Skippern Squash',
                            infoWindow: {
                                content: "Adresse: Torggata 16 <br />0181 Oslo<br />Telefon: 22355511 (Før kl.15 i juli tlfnr. 22993140)<br />Epost: skippern@sqf.no"
                            }
                        });
                        map.addMarker({
                            lat: 59.920614,
                            lng: 10.751460,
                            title: 'Vulkan Squash',
                            infoWindow: {
                                content: "Adresse: Maridalsveien 17<br />0178 Oslo <br />Telefon: 22355511<br />Epost: vulkan@sqf.no"
                            }
                        });

                        marker.infoWindow.open(map, marker_new);
                    });
                }
            };

        }();

        jQuery(document).ready(function() {
            Contact.init();
        });

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
    </script>
@endsection