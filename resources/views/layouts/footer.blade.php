<!-- BEGIN PRE-FOOTER -->
<div class="page-prefooter">
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-sm-6 col-xs-12 footer-block">
                <h2>About</h2>
                <p> {{\App\Http\Controllers\AppSettings::get_setting_value_by_name('globalWebsite_front_about_text')!=false ? \App\Http\Controllers\AppSettings::get_setting_value_by_name('globalWebsite_front_about_text'):''}} </p>
            </div>
            @if (\App\Http\Controllers\AppSettings::get_setting_value_by_name('social_media_frontend_footer_rss')       ||
                 \App\Http\Controllers\AppSettings::get_setting_value_by_name('social_media_frontend_footer_facebook')  ||
                 \App\Http\Controllers\AppSettings::get_setting_value_by_name('social_media_frontend_footer_twitter')   ||
                 \App\Http\Controllers\AppSettings::get_setting_value_by_name('social_media_frontend_footer_google+')   ||
                 \App\Http\Controllers\AppSettings::get_setting_value_by_name('social_media_frontend_footer_linkedin')  ||
                 \App\Http\Controllers\AppSettings::get_setting_value_by_name('social_media_frontend_footer_youtube')   ||
                 \App\Http\Controllers\AppSettings::get_setting_value_by_name('social_media_frontend_footer_vimeo')
            )
            <div class="col-md-4 col-sm-6 col-xs-12 footer-block">
                <h2>Follow Us On</h2>
                <ul class="social-icons">
                    @if (\App\Http\Controllers\AppSettings::get_setting_value_by_name('social_media_frontend_footer_rss')!=false)
                    <li>
                        <a href="\App\Http\Controllers\AppSettings::get_setting_value_by_name('social_media_frontend_footer_rss')" target="_blank" data-original-title="rss" class="rss"></a>
                    </li>
                    @endif
                    @if (\App\Http\Controllers\AppSettings::get_setting_value_by_name('social_media_frontend_footer_facebook')!=false)
                    <li>
                        <a href="\App\Http\Controllers\AppSettings::get_setting_value_by_name('social_media_frontend_footer_facebook')" target="_blank" data-original-title="facebook" class="facebook"></a>
                    </li>
                    @endif
                    @if (\App\Http\Controllers\AppSettings::get_setting_value_by_name('social_media_frontend_footer_twitter')!=false)
                    <li>
                        <a href="\App\Http\Controllers\AppSettings::get_setting_value_by_name('social_media_frontend_footer_twitter')" target="_blank" data-original-title="twitter" class="twitter"></a>
                    </li>
                    @endif
                    @if (\App\Http\Controllers\AppSettings::get_setting_value_by_name('social_media_frontend_footer_google+')!=false)
                    <li>
                        <a href="\App\Http\Controllers\AppSettings::get_setting_value_by_name('social_media_frontend_footer_google+')" target="_blank" data-original-title="googleplus" class="googleplus"></a>
                    </li>
                    @endif
                    @if (\App\Http\Controllers\AppSettings::get_setting_value_by_name('social_media_frontend_footer_linkedin')!=false)
                    <li>
                        <a href="\App\Http\Controllers\AppSettings::get_setting_value_by_name('social_media_frontend_footer_linkedin')" target="_blank" data-original-title="linkedin" class="linkedin"></a>
                    </li>
                    @endif
                    @if (\App\Http\Controllers\AppSettings::get_setting_value_by_name('social_media_frontend_footer_youtube')!=false)
                    <li>
                        <a href="\App\Http\Controllers\AppSettings::get_setting_value_by_name('social_media_frontend_footer_youtube')" target="_blank" data-original-title="youtube" class="youtube"></a>
                    </li>
                    @endif
                    @if (\App\Http\Controllers\AppSettings::get_setting_value_by_name('social_media_frontend_footer_vimeo')!=false)
                    <li>
                        <a href="\App\Http\Controllers\AppSettings::get_setting_value_by_name('social_media_frontend_footer_vimeo')" target="_blank" data-original-title="vimeo" class="vimeo"></a>
                    </li>
                    @endif
                </ul>
            </div>
            @endif
            <div class="col-md-4 col-sm-6 col-xs-12 footer-block">
                <h2>Contacts</h2>
                <address class="margin-bottom-40"> Phone: {{ \App\Http\Controllers\AppSettings::get_setting_value_by_name('globalWebsite_front_phone_number')!=false ? \App\Http\Controllers\AppSettings::get_setting_value_by_name('globalWebsite_front_phone_number'):'-' }}
                    <br> Email:
                    @if (\App\Http\Controllers\AppSettings::get_setting_value_by_name('globalWebsite_front_contact_email')!=false)
                    <a href="mailto:{{\App\Http\Controllers\AppSettings::get_setting_value_by_name('globalWebsite_front_contact_email')}}">{{\App\Http\Controllers\AppSettings::get_setting_value_by_name('globalWebsite_front_contact_email')}}</a>
                    @else
                        -
                    @endif
                </address>
            </div>
        </div>
    </div>
</div>
<!-- END PRE-FOOTER -->
<!-- BEGIN INNER FOOTER -->
<div class="page-footer">
    <div class="container"> {{ \Carbon\Carbon::now()->format('Y') }} &copy; BookingSystem by
        <a href="https://www.book247.com" title="Online Booking System" target="_blank">book247.com</a>
    </div>
</div>
<div class="scroll-to-top">
    <i class="icon-arrow-up"></i>
</div>
<!-- END INNER FOOTER -->