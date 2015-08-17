jQuery(function($) {'use strict';

	// Navigation Scroll
	$(window).scroll(function(event) {
		//Scroll();
	});

	$('.navbar-collapse ul li a').on('click', function() {  
		$('html, body').animate({scrollTop: $(this.hash).offset().top - 5}, 1000);
		return false;
	});

	/* User define function
	function Scroll() {
		var contentTop      =   [];
		var contentBottom   =   [];
		var winTop      =   $(window).scrollTop();
		var rangeTop    =   200;
		var rangeBottom =   500;
		$('.navbar-collapse').find('.scroll a').each(function(){
			contentTop.push( $( $(this).attr('href') ).offset().top);
			contentBottom.push( $( $(this).attr('href') ).offset().top + $( $(this).attr('href') ).height() );
		})
		$.each( contentTop, function(i){
			if ( winTop > contentTop[i] - rangeTop ){
				$('.navbar-collapse li.scroll')
				.removeClass('active')
				.eq(i).addClass('active');			
			}
		})
	};

	$('#tohash').on('click', function(){
		$('html, body').animate({scrollTop: $(this.hash).offset().top - 5}, 1000);
		return false;
	});

	// accordian
	$('.accordion-toggle').on('click', function(){
		$(this).closest('.panel-group').children().each(function(){
		$(this).find('>.panel-heading').removeClass('active');
		 });

	 	$(this).closest('.panel-heading').toggleClass('active');
	});

	//Slider
	/*$(document).ready(function() {
		var time = 7; // time in seconds

	 	var $progressBar,
	      $bar, 
	      $elem, 
	      isPause, 
	      tick,
	      percentTime;
	 
	    //Init the carousel
	    $("#main-slider").find('.owl-carousel').owlCarousel({
	      slideSpeed : 500,
	      paginationSpeed : 500,
	      singleItem : true,
	      navigation : true,
			navigationText: [
			"<i class='fa fa-angle-left'></i>",
			"<i class='fa fa-angle-right'></i>"
			],
	      afterInit : progressBar,
	      afterMove : moved,
	      startDragging : pauseOnDragging,
	      //autoHeight : true,
	      transitionStyle : "fadeUp"
	    });
	 
	    //Init progressBar where elem is $("#owl-demo")
	    function progressBar(elem){
	      $elem = elem;
	      //build progress bar elements
	      buildProgressBar();
	      //start counting
	      start();
	    }
	 
	    //create div#progressBar and div#bar then append to $(".owl-carousel")
	    function buildProgressBar(){
	      $progressBar = $("<div>",{
	        id:"progressBar"
	      });
	      $bar = $("<div>",{
	        id:"bar"
	      });
	      $progressBar.append($bar).appendTo($elem);
	    }
	 
	    function start() {
	      //reset timer
	      percentTime = 0;
	      isPause = false;
	      //run interval every 0.01 second
	      tick = setInterval(interval, 10);
	    };
	 
	    function interval() {
	      if(isPause === false){
	        percentTime += 1 / time;
	        $bar.css({
	           width: percentTime+"%"
	         });
	        //if percentTime is equal or greater than 100
	        if(percentTime >= 100){
	          //slide to next item 
	          $elem.trigger('owl.next')
	        }
	      }
	    }
	 
	    //pause while dragging 
	    function pauseOnDragging(){
	      isPause = true;
	    }
	 
	    //moved callback
	    function moved(){
	      //clear interval
	      clearTimeout(tick);
	      //start again
	      start();
	    }
	});*/

	//Initiat WOW JS
	new WOW().init();
	//smoothScroll
	smoothScroll.init();

	// portfolio filter
	$(window).load(function(){'use strict';
		var $portfolio_selectors = $('.portfolio-filter >li>a');
		var $portfolio = $('.portfolio-items');
		$portfolio.isotope({
			itemSelector : '.portfolio-item',
			layoutMode : 'fitRows'
		});
		
		$portfolio_selectors.on('click', function(){
			$portfolio_selectors.removeClass('active');
			$(this).addClass('active');
			var selector = $(this).attr('data-filter');
			$portfolio.isotope({ filter: selector });
			return false;
		});
	});

	$(document).ready(function() {
		//Animated Progress
		$('.progress-bar').bind('inview', function(event, visible, visiblePartX, visiblePartY) {
			if (visible) {
				$(this).css('width', $(this).data('width') + '%');
				$(this).unbind('inview');
			}
		});

		//Animated Number
		$.fn.animateNumbers = function(stop, commas, duration, ease) {
			return this.each(function() {
				var $this = $(this);
				var start = parseInt($this.text().replace(/,/g, ""));
				commas = (commas === undefined) ? true : commas;
				$({value: start}).animate({value: stop}, {
					duration: duration == undefined ? 1000 : duration,
					easing: ease == undefined ? "swing" : ease,
					step: function() {
						$this.text(Math.floor(this.value));
						if (commas) { $this.text($this.text().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,")); }
					},
					complete: function() {
						if (parseInt($this.text()) !== stop) {
							$this.text(stop);
							if (commas) { $this.text($this.text().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,")); }
						}
					}
				});
			});
		};

		$('.animated-number').bind('inview', function(event, visible, visiblePartX, visiblePartY) {
			var $this = $(this);
			if (visible) {
				$this.animateNumbers($this.data('digit'), false, $this.data('duration')); 
				$this.unbind('inview');
			}
		});
	});

	// Contact form
	/*var form = $('#main-contact-form');
	form.submit(function(event){
		event.preventDefault();
		var form_status = $('<div class="form_status"></div>');
		$.ajax({
			url: $(this).attr('action'),
			beforeSend: function(){
				form.prepend( form_status.html('<p><i class="fa fa-spinner fa-spin"></i> Email is sending...</p>').fadeIn() );
			}
		}).done(function(data){
			form_status.html('<p class="text-success">Thank you for contact us. As early as possible  we will contact you</p>').delay(3000).fadeOut();
		});
	});*/

	//Pretty Photo
	$("a[rel^='prettyPhoto']").prettyPhoto({
		social_tools: false
	});

	//Google Map
	/*var latitude = $('#google-map').data('latitude');
	var longitude = $('#google-map').data('longitude');
	function initialize_map() {
		var myLatlng = new google.maps.LatLng(latitude,longitude);
		var mapOptions = {
			zoom: 14,
			scrollwheel: false,
			center: myLatlng
		};
		var map = new google.maps.Map(document.getElementById('google-map'), mapOptions);
		var marker = new google.maps.Marker({
			position: myLatlng,
			map: map
		});
	}
	google.maps.event.addDomListener(window, 'load', initialize_map);*/


    //birthday new js

    $("#howitworks").on('click',function(){
        $("#portfolio").show();
        $(".city").show();
    });

    $("#wowtbales_mumbai_menu").on('click',function(){
        $(".options-display-mumbai").show();
        $(".options-display-delhi").hide();
        $(".options-display-bangalore").hide();
        $(".options-display-pune").hide();
        $("#contact").show();
        $("#city_sel").val("mumbai");
        /*$("#lunch-option").append($("<option>").attr("value", "Hakkasan,BKC").attr("selected", "selected").text("Hakkasan,BKC"));
        $('#lunch-option').selectmenu('refresh');
        $('#lunch-option').prop("readonly",true);*/
        //this is for dynamic lunch option
        $(".lunch-sel").empty();
        $(".lunch-sel").html("<input type='hidden' id='lunch_option' name='lunch_option' value='Hakkasan, Linking Road, Bandra'>");

    });

    $("#wowtbales_delhi_menu").on('click',function(){
        $(".options-display-mumbai").hide();
        $(".options-display-delhi").show();
        $(".options-display-bangalore").hide();
        $(".options-display-pune").hide();
        $("#contact").show();
        $("#city_sel").val("delhi");
        $(".lunch-sel").empty();
        var arr = [
            {val : "", text: "Preferred Lunch Option"},
            {val : "Caffe Tonino, Connaught Place", text: "Caffe Tonino, Connaught Place"},
            {val : "Thai High, Mehrauli", text: "Thai High, Mehrauli"}
        ];

        var sel = $('<select class="form-control" id="lunch_option" name="lunch_option">').appendTo('#lunch-sel');
        $(arr).each(function() {
            sel.append($("<option>").attr('value',this.val).text(this.text));
        });

    });

    $("#wowtbales_banglore_menu").on('click',function(){
        $(".options-display-mumbai").hide();
        $(".options-display-delhi").hide();
        $(".options-display-bangalore").show();
        $(".options-display-pune").hide();
        $("#contact").show();
        $("#city_sel").val("bangalore");
        $(".lunch-sel").empty();
        var arr = [
            {val : "", text: "Preferred Lunch Option"},
            {val : "Om Made Cafe, Koramangala", text: "Om Made Cafe, Koramangala"},
            {val : "100 Ft Bar Boutique Restaurant, Indiranagar", text: "100 Ft Bar Boutique Restaurant, Indiranagar"},
            {val : "Bluefrog, 3 Church Street", text: "BlueFROG, 3 Church Street"}
        ];

        var sel = $('<select class="form-control" id="lunch_option" name="lunch_option">').appendTo('#lunch-sel');
        $(arr).each(function() {
            sel.append($("<option>").attr('value',this.val).text(this.text));
        });

    });

    $("#wowtbales_pune_menu").on('click',function(){
        $(".options-display-mumbai").hide();
        $(".options-display-delhi").hide();
        $(".options-display-bangalore").hide();
        $(".options-display-pune").show();
        $("#contact").show();
        $("#city_sel").val("pune");
        $(".lunch-sel").empty();
        $(".lunch-sel").html("<input type='hidden' id='lunch_option' name='lunch_option' value='Cafe 1730, Koregaon Park'>");

    });

    $("#main-contact-form").validate({
        rules: {
            name: {required: true},
            email: {required: true,email: true},
            phone_no: {required: true,number: true,minlength:10,maxlength:10},
            lunch_option:{required: true}
        },
        submitHandler: function(form) {
            /*$.ajax({
                type: "POST",
                url: "<?php bloginfo("template_directory"); ?>/contact/process.php",
                data: $(form).serialize(),
                timeout: 3000,
                success: function() {alert('works');},
                error: function() {alert('failed');}
            });*/

            return false;
        }
    });

    $(".test").on('click',function(){
        $( "#main-contact-form" ).submit(function( event ) {
            alert( "Handler for .submit() called." );
            event.preventDefault();
        });
    });



    //share on twitter


    $("#cust-details").on('click',function(){

       var status = $("#main-contact-form").valid();
        if(status) {
            $.ajax({
                type: "POST",
                url: "/promotion/birthday/save",
                data: $("#main-contact-form").serialize(),
                timeout: 3000,
                success: function(response) {
                    $.parseJSON(response);
                    if(response){
                        $(".hit").show();
                        $("#facebook-share").show();


                        $("#facebook-share").on('click',function(){


                                var product_name   = 	'The Good Life with WowTables';
                                var description	   =	'Join me in celebrating the third birthday of WowTables with a luxurious day of dining, relaxing and unwinding.';
                                var share_image	   =	'http://wowtables.com/assets/birthday/images/tgl.jpg';
                                var share_url	   =	'http://wowtables.com';
                                FB.ui({
                                    method: 'share',
                                    href: share_url,
                                    title: product_name,
                                    link: share_url,
                                    picture: share_image,
                                    description: description
                                }, function(response) {
                                    /*if(response && response.post_id){}
                                     else{}*/

                                });

                        });

                        $('#twitter-share').twitterbutton({

                            title:'I want to taste the #TheGoodLife with @Wow_Tables because  ',
                            layout:'none',
                            ontweet:function(response){

                            },
                            lang:'en'
                        });

                    } else{
                        $(".fail").show();
                    }
                },
                error: function() {
                    $(".fail").show();
                }
            });
        }



    });






    /*$(".facebook-share").on('click',function(){

        var product_name   = 	'The Good Life with WowTables';
        var description	   =	'Join me in celebrating the third birthday of WowTables with a luxurious day of dining, relaxing and unwinding.';
        var share_image	   =	'https://s3-eu-west-1.amazonaws.com/wowtables/uploads/gallery/media_55b203f4ea9e9_651x269.jpg';
        var share_url	   =	'http://wowtables.com';
        FB.ui({
            method: 'share',
            href: share_url,
            title: product_name,
            link: share_url,
            picture: share_image,
            description: description
        }, function(response) {
            if(response && response.post_id){}
            else{}
        });

    });*/






});
