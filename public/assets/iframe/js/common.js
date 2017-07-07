$(document).ready(function() {
 	
	//CARUSEL HEADER
	$('.carusel').slick({
	    slidesToShow: 3,
		slidesToScroll: 1,
		arrows: true,
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
   
   	//animation
   	$('.carusel-button').click(function(){
   		$('#body-iframe-step-1 .container-iframe').animate({
   			marginLeft: '-1500px',
   			opacity: '0.1',
   			zIndex: 0
   		}, 1000, 'linear');

   		var membership_id = $(this).attr('data-value');
   		console.log(membership_id);
   		var clone = $('.carusel-item-content-'+membership_id).find('.box-item').clone();
   		var color = clone.find('a').attr('style');
   		clone.find('a').remove();
   		$('.membership-replacer').attr('style',clone.attr('style')).prepend(clone.html()).find('.pay-with-card').attr('style',color);
   		$('.pay-with-card-final').attr('style',color);

   		$('#body-iframe-step-2 .container-iframe').animate({ 
   			opacity: 1 ,
   			zIndex: 1
   		}, 900, 'linear');
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