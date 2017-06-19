$(document).ready(function() {
 	
	//CARUSEL HEADER
	$('.carusel').slick({
	    slidesToShow: 3,
		slidesToScroll: 1,
		arrows: false,
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
   
   	//GOOGLE MAP
    

}); 