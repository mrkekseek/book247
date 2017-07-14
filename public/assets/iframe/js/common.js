$(document).ready(function() {
 	
	//CARUSEL HEADER
   
   	//animation
   	$('.carusel-button').click(function(){
   		if ($('.chek-page').is(':checked')) {
            $('#body-iframe-step-1 .container-iframe').animate({
                marginLeft: '-1500px',
                opacity: '0.1',
                zIndex: 0
            }, 1000, 'linear');

            var membership_id = $(this).attr('data-value');
            console.log(membership_id);
            var clone = $('.carusel-item-content-'+membership_id).find('.box-item').clone();
            var color = clone.find('a').attr('style');
            clone.find('.carousel-button-wrap').remove();
            clone.css({"min-height":"516px","border":"none"});
            $('.membership-replacer').attr('style',clone.attr('style')).prepend(clone.html()).find('.pay-with-card').attr('style',color);
            $('.pay-with-card-final').attr('style',color);

            $('#body-iframe-step-2 .container-iframe').animate({
                opacity: 1 ,
                zIndex: 1
            }, 900, 'linear');
		} else {
   			$('.alert-box-container').fadeIn();
		}

   	});

   	$('#close_alert').click(function(){
   	    $('.alert-box-container').fadeOut();
    });

   	$('.pay-with-card').click(function(){
   		$('#body-iframe-step-2 .container-iframe').animate({
   			marginLeft: '-1500px',
   			opacity: '0.1',
   			zIndex: 0
   		}, 1000, 'linear');

   		$('#body-iframe-step-3 .container-iframe').animate({ 
   			opacity: 1,
   			zIndex: 1
   		}, 1300, 'linear');
   	});

    $('.pay-with-paypal').click(function(){
        $('#body-iframe-step-2 .container-iframe').animate({
            marginLeft: '-1500px',
            opacity: '0.1',
            zIndex: 0
        }, 1000, 'linear');

        $('#body-iframe-step-3 .container-iframe').animate({
            marginLeft: '-1500px',
            opacity: '0.1',
            zIndex: 0
        }, 1000, 'linear');

        $('#body-iframe-step-4 .container-iframe').animate({
            opacity: 1,
            zIndex: 1
        }, 1300, 'linear');
    });

    $('.pay-with-card-final').click(function(){

        $('#body-iframe-step-3 .container-iframe').animate({
            marginLeft: '-1500px',
            opacity: '0.1',
            zIndex: 0
        }, 1000, 'linear');

        $('#body-iframe-step-4 .container-iframe').animate({
            opacity: 1,
            zIndex: 1
        }, 1300, 'linear');
    });
    

}); 