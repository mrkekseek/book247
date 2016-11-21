/**
 * Created by bogdan-dev on 7/13/2016.
 */

var ComponentsMenuDatePickers = function () {

    var handleDatePickers = function () {

        if (jQuery().datepicker) {
            $('#calendar_booking_top_menu').datepicker({
                    autoclose: false,
                    isRTL: App.isRTL(),
                    todayBtn: false,
                    format:"dd-mm-yyyy",
                    daysOfWeekHighlighted: "0",
                    weekStart:1,
                })
                //Listen for the change even on the input
                .on('changeDate', function(ev){
                    booking_calendar_view_redirect();
                });

            $('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
        }
    }

    return {
        //main function to initiate the module
        init: function () {
            handleDatePickers();
        }
    };

}();

jQuery(document).ready(function() {
    ComponentsMenuDatePickers.init();

    $("#calendar_booking_top_menu").click( function(event) {
        event.stopPropagation();
    });
});