(function($) {

	var mobileMenue = 0;
	var metaFF = 0;
	
	var anzahlAnker = $('#contentnavbar ul').children().length;
	var allpage_anchor = new Array();
	var akt_anchor = "";
	var change_anchor = "";
	
	// Einsatzticker
	var einsatzticker = {
        currentImage: 1,
		itemcount: 0,
		speed: 400
    };

	for(i=0; i<anzahlAnker; i++) {	
		var anchor_elementname = $('#js_anchor_'+i).attr('href');
		var anchor_coords = $(''+anchor_elementname+'').position();
		allpage_anchor[i] = new Array();
		allpage_anchor[i][0] = anchor_elementname;
		allpage_anchor[i][1] = (anchor_coords.top)-40;
	}



	//--------------------------------------------------------------
	// Initialisierung nach dem das dokuemnt geladen wwurde (AUTO)
	//--------------------------------------------------------------
	$(document).ready(function() {

		// --- Einsatzticker initialisieren
		einsatzticker.itemcount = $('#slideshow').children().length;
		$('.einsatzticker ul li:first-child').addClass('active');

		// --- Slide-Show initialisieren
		$('.slideshow').each(function() {
			slsh = $(this).attr('data-slidehow-id');
			itemcount = $(this).children().length;
			$('.js_'+slsh).find('.actualImg').html(1);
			$('.js_'+slsh).find('.allImages').html(itemcount);
		});

	});


//-------------------------------------------------------
//  Diverses

	
	// Floating Menue Einsatzliste
	
	$(window).scroll(function (event) {
		
		var y = $(this).scrollTop();
		var top = 475;
		var vereinpos = $('#anchor_DerVerein').position();

		if($(window).width()>490) { 
			if (y >= top) {
				$('#contentnavbar').addClass('fixed');
				$('#contentnavbar').css('max-width', '100%');
				$('.filter').css('margin', '0 0 0 0');
				$('.jsplatzhalter').css('display', 'block');
			} else {
				$('#contentnavbar').removeClass('fixed');
				$('#contentnavbar').css('max-width', '980px');
				$('.filter').css('margin', '0 0 20px 0');
				$('.jsplatzhalter').css('display', 'none');
			}
		} else {
			$('#contentnavbar').removeClass('fixed');
			$('.jsplatzhalter').css('display', 'none');
			$('.filter').css('margin', '0 0 20px 0');
		}
		
		for(i=0; i<anzahlAnker; i++) {
			if(y >= allpage_anchor[i][1]) {
				akt_anchor = allpage_anchor[i][0];
			}
		}
		if(change_anchor!=akt_anchor) {	
			$('a[href="'+akt_anchor+'"]').addClass('active');
			$('a[href="'+change_anchor+'"]').removeClass('active');
			change_anchor = akt_anchor;
		}

	
	});
	
	// Animiertes Scrollen
	$('a[rel="nicescrolling"]').bind("click", function(event) {
		event.preventDefault();
		var ziel = $(this).attr("href");
		$('html,body').animate({
			scrollTop: $(ziel).offset().top
		}, 300 , function (){location.hash = ziel;});
	});


//-------------------------------------------------------
// Floating Menue
	
	$(".open-panel").click(function(){
		if(mobileMenue == 0) {		  
			mobileMenue = 1;
			$("nav").addClass("openNav");
			$("#js_modalbg").addClass("show_menubg");
			$("body").addClass("modal-open");
		} else {
			mobileMenue = 0;
			$("nav").removeClass("openNav");	
			$("#js_modalbg").removeClass("show_menubg");
			$("body").removeClass("modal-open");
		}
	});	
	
	$(".close-panel").click(function(){
		if(mobileMenue == 1) {
			mobileMenue = 0;
			$("nav").removeClass("openNav");
			$("#js_modalbg").removeClass("show_menubg");
			$("body").removeClass("modal-open");
		}
	});

//-------------------------------------------------------
// metaFF Menue
	$(document).click(function(event) {
		if(metaFF == 1) { metaFF = 0; $(".metaff").removeClass("metaffshow"); }
	});
	$(".select").click(function(event){
			if(metaFF == 0) {	
				event.preventDefault();
				metaFF = 1;
				$(".metaff").addClass("metaffshow");
				event.stopPropagation();
			} else {
				metaFF = 0;
				$(".metaff").removeClass("metaffshow");
			}
	});

//-------------------------------------------------------
// OPEN/CLOSE Archive (Einsatzliste)
	$('#js_einsatzyear_celector').click(function(e) { 
		event.preventDefault();
		if($('#archive').hasClass('hide')) {	
			$('#archive').removeClass('hide');
		} else {
			$('#archive').addClass('hide');
		}
	});

//-------------------------------------------------------
//  Slideshow

	var slideShowVars = {
        currentImage: 1,
		itemcount: 0,
		speed: 400
    };

	// Bild austauschen
	$('.nextImage').click(function() {
		
		var id = $(this).parent().attr('data-slidehow-id');
		var elements = parseInt($(this).parent().prev().children().length);
		var actualImg = parseInt($(this).prev().find('.actualImg').html());

		if(actualImg<elements) {	
			var target=actualImg+1;
		} else {
			var target=1;
		}

		changePic(target, actualImg, id);
		$(this).prev().find('.actualImg').html(target)

		return false;
	});
	
	$('.prevImage').click(function() {
		
		var id = $(this).parent().attr('data-slidehow-id');
		var elements = parseInt($(this).parent().prev().children().length);
		var actualImg = parseInt($(this).next().find('.actualImg').html());

		if(actualImg!=1) {	
			var target=actualImg-1;
		} else {
			var target=elements;
		}

		changePic(target, actualImg, id);
		$(this).next().find('.actualImg').html(target);

		return false;
	});

	function changePic(target, actualImg, id) {
		$('.slideshow_'+id+'_'+target).addClass('active');
		$('.slideshow_'+id+'_'+actualImg).removeClass('active');
	}


//-------------------------------------------------------
// Einsatzticker (Startseite)

	// Einsatz austauschen
	$('.js_nextEinsatz').click(function() {
		
		var elements = $('.einsatzticker ul li').children().length;
		if(einsatzticker.currentImage<elements) {	
			var target=einsatzticker.currentImage+1;
		} else {
			var target=1;
		}
		changeEinsatz(target);

		return false;
	});
	$('.js_lastEinsatz').click(function() {
		
		var elements = $('.einsatzticker ul li').children().length;
		if(einsatzticker.currentImage!=1) {	
			var target=einsatzticker.currentImage-1;
		} else {
			var target=elements;
		}
		changeEinsatz(target);

		return false;
	});
	function changeEinsatz(target) {
		$('.einsatzticker ul li:nth-child('+target+')').addClass('active');
		$('.einsatzticker ul li:nth-child('+einsatzticker.currentImage+')').removeClass('active');
		einsatzticker.currentImage = target;
	}

//-------------------------------------------------------
//  Diverses

	$('.js_closetermin').click(function(event) {
		event.preventDefault();
		var number = $(this).attr("terminno");

		if($('.js_termindetails_'+number+'').hasClass('hide')) {
			$('.js_termindetails_'+number).removeClass('hide');
			$(this).html('Schließen');
			$(this).parent().parent().parent().addClass('terminactive');
		} else {
			$('.js_termindetails_'+number).addClass('hide');
			$(this).html('Details');
			$(this).parent().parent().parent().removeClass('terminactive');
		}

	});


// |-----------------------------------------------------------------------------------------		
// |	STAGE ANIIMATION
// |-----------------------------------------------------------------------------------------		


	var vars = {
        currentSlide: 0,
        currentImage: '',
        totalSlides: 0,
        pause: 0,
		speed: 800
    };
	var settings = {
		pauseTime: 6000,
		pauseTimeLong: 12000,
		autoChange: 1
	};
	var timer = 0;
	var animation = '';
	
	// Anzahl der Bilder ermitteln
	var kids = $('#stage').children();
	kids.each(function() {
		var child = $(this);
		var link = '';
		vars.totalSlides++;
	});
	vars.totalSlides--;


	$(document).ready(function() {
		vars.currentSlide = 1;
		$('.js-stage-'+vars.currentSlide).fadeIn(vars.speed);
		highlight(0, 1);
		autoSlides();	
	});
	
	$('.js_callstage').click(function() {
		clearTimeout(timer);
		var next = $(this).attr('num');
		var last = vars.currentSlide;
	
		$('.js-stage-'+vars.currentSlide).fadeOut(vars.speed, function() {
			vars.currentSlide = next;	

			$('.js-stage-'+vars.currentSlide).fadeIn(vars.speed);
			highlight(last, vars.currentSlide);
		});
		timer = setTimeout (function() { nextImage(); }, settings.pauseTimeLong);
	});

	$('.js_showvideo').click(function(e) {
		e.preventDefault();
		$('#js_vidlayer').removeClass('hide');
		clearTimeout(timer);
	});
	$('.js_hidevideo').click(function(e) {
		e.preventDefault();
		$('#js_vidlayer').addClass('hide');
		clearTimeout(timer);
	});
	
	function nextImage() {
		if(vars.pause==0) {
			var last = vars.currentSlide;
			$('.js-stage-'+vars.currentSlide).fadeOut(vars.speed, function() {
				if(vars.totalSlides>vars.currentSlide) {	
					vars.currentSlide++;
				} else {
					vars.currentSlide = 1;	
				}
				$('.js-stage-'+vars.currentSlide).fadeIn(vars.speed);
				highlight(last, vars.currentSlide);
				autoSlides();	
			});
		}
	}

	function highlight(last, next) {
		if(last == 0) {
			$('.js-stage-link-'+next).addClass('active');
		} else {
			for(i=1; i<=vars.totalSlides; i++) {
				if(next!=i) {	
					$('.js-stage-link-'+last).removeClass('active');
				} else {
					$('.js-stage-link-'+next).addClass('active');
				}
			}
		}
	}
	
	function autoSlides() {
		if(settings.autoChange==1 && vars.pause == 0 && kids.length > 1){
			timer = setTimeout (function() { nextImage(); }, settings.pauseTime);
		}	
	}


})(jQuery);