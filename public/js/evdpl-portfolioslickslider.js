jQuery(document).ready( function(){
    
    /*jQuery('.desktop-slider').slick({
        cssEase: 'linear',
        slidesToShow: 3,
        slidesToScroll: 1,
        dots: true,
        arrows: true
    });*/


    jQuery(".desktop-slider").slick({
	    slidesToShow: 4, // default desktop values
	    slidesToScroll: 4,
	    /*rows: 2,*/
	    dots: true,
	    arrows: true,
	    responsive: [
	        {
	            breakpoint: 980, // tablet breakpoint
	            settings: {
	                slidesToShow: 3,
	                slidesToScroll: 3
	            }
	        },
	        {
	            breakpoint: 480, // mobile breakpoint
	            settings: {
	                slidesToShow: 1,
	                slidesToScroll: 1
	            }
	        }
	    ]
	});


});