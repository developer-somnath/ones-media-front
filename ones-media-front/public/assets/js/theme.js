
    jQuery(document).ready(function( $ ) {
        $('.counter').counterUp({
            delay: 10,
            time: 1000
        });
    });



wow = new WOW(
  {
  animateClass: 'animated',
  offset:       100
  }
  );
wow.init();



	jQuery(document).ready(function($) {
			jQuery('.stellarnav').stellarNav({
				theme: 'light',
				breakpoint: 991,
				position: 'right',
				phoneBtn: '(780) 743-1904',
				//locationBtn: 'https://www.google.com/maps'
			});
			
});

// $(window).scroll(function () {
//             if ($(this).scrollTop() > 250) {
//                 $('.main-header').addClass("sticky");
//             }
//             else {
//                 $('.main-header').removeClass("sticky");
//             }
//         });

function openNav() {
  document.getElementById("mySidenav").style.width = "320px";
}

function closeNav() {
  document.getElementById("mySidenav").style.width = "0";
}


var cc = $('#h-slider');
cc.owlCarousel({
    loop:true,	
	margin:0,
    nav:false,
	dots:true,
	smartSpeed:3000,
	//mouseDrag:false,
    autoplay:true,
    //animateOut: 'slideOutUp',
    items: 1,
	//navText: [ '<i class="fa-solid fa-chevron-left"></i>', '<i class="fa-solid fa-chevron-right"></i>' ],
	
});

var cc = $('#p-slider');
cc.owlCarousel({
	autoplay:true,
    loop:true,	
	margin:30,
    nav:true,
	dots:false,
	smartSpeed:3000,
	//animateOut: 'fadeOut',
    items: 1,
	navText: [ '<i class="fas fa-angle-left"></i>', '<i class="fas fa-angle-right"></i>' ],
	
});

var cc = $('#t-slider');
cc.owlCarousel({
	autoplay:true,
    loop:true,	
	margin:0,
    nav:false,
	dots:false,
	smartSpeed:2000,
	//animateOut: 'fadeOut',
    items: 1,
	//navText: [ '<i class="fas fa-angle-left"></i>', '<i class="fas fa-angle-right"></i>' ],
	
});
var cc = $('#t-slider1');
cc.owlCarousel({
	autoplay:true,
    loop:true,	
	margin:0,
    nav:false,
	dots:false,
	smartSpeed:2000,
	//animateOut: 'fadeOut',
    items: 1,
	//navText: [ '<i class="fas fa-angle-left"></i>', '<i class="fas fa-angle-right"></i>' ],
	
});
var cc = $('#t-slider2');
cc.owlCarousel({
	autoplay:true,
    loop:true,	
	margin:0,
    nav:false,
	dots:false,
	smartSpeed:2000,
	//animateOut: 'fadeOut',
    items: 1,
	//navText: [ '<i class="fas fa-angle-left"></i>', '<i class="fas fa-angle-right"></i>' ],
	
});


var cc = $('#p-slider-1');
cc.owlCarousel({
	autoplay:true,
    loop:true,	
	margin:30,
    nav:true,
	dots:false,
	smartSpeed:3000,
	//animateOut: 'fadeOut',
    items: 4,
	navText: [ '<i class="fas fa-angle-left"></i>', '<i class="fas fa-angle-right"></i>' ],
	
	responsive : {
    // breakpoint from 0 up
   0:{
            items:1,
        },
    // breakpoint from 480 up
   480:{
            items:2,
        },
    // breakpoint from 768 up
    768:{
            items:3,
        },
		// breakpoint from 768 up
    992:{
            items:4,
        }
		 
}
});

var cc = $('#c-slider');
cc.owlCarousel({
	autoplay:true,
    loop:true,	
	margin:0,
    nav:false,
	dots:true,
	smartSpeed:3000,
    items: 1,
	//navText: [ '<i class="fas fa-angle-left"></i>', '<i class="fas fa-angle-right"></i>' ],

});





