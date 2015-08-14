<?php ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta property="og:url"           content="http://wowtables.com/" />
    <meta property="og:type"          content="article" />
    <meta property="og:title"         content="The Good Life with WowTables" />
    <meta property="og:description"   content="Join me in celebrating the third birthday of WowTables with a luxurious day of dining, relaxing and unwinding." />
    <title>Wowtables</title>
    <!-- core CSS -->
    <!--<link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/font-awesome.min.css" rel="stylesheet">
    <link href="assets/css/animate.min.css" rel="stylesheet">
    <link href="assets/css/prettyPhoto.css" rel="stylesheet">
    <link href="assets/css/main.css" rel="stylesheet">-->

    {!! Html::style('assets/birthday/css/bootstrap.min.css') !!}
    {!! Html::style('assets/birthday/css/font-awesome.min.css') !!}
    {!! Html::style('assets/birthday/css/animate.min.css') !!}
    {!! Html::style('assets/birthday/css/prettyPhoto.css') !!}
    {!! Html::style('assets/birthday/css/main.css') !!}

    <!--[if lt IE 9]>
    {!! Html::script('assets/birthday/js/html5shiv.js') !!}
    {!! Html::script('assets/birthday/js/respond.js') !!}
    <![endif]-->
    <!--<link rel="shortcut icon" href="images/ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="images/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="images/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="images/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="images/ico/apple-touch-icon-57-precomposed.png">-->

    <style type="text/css">
        .contact-form{
          border: 2px solid rgb(204, 204, 204);
        }
        #contact .contact-form{
          margin-bottom: 7%;
        }
    </style>

</head><!--/head-->

<body id="home" class="homepage">

    <header id="header">
        <nav id="main-menu" class="navbar navbar-default navbar-fixed-top" role="banner">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="index.html">
                        {!! HTML::image('assets/birthday/images/logo.png', $alt="logo", $attributes = array()) !!}
                    </a>
                </div>

                <div class="collapse navbar-collapse navbar-right">
                    <ul class="nav navbar-nav">
                        <li class="scroll"><a href="#features">A la carte</a></li>
                        <li class="scroll"><a href="#services">Dining Experiance</a></li>
                    </ul>
                </div>
            </div><!--/.container-->
        </nav><!--/nav-->
    </header><!--/header-->

    <section id="main-slider">
        <div class="owl-carousel">
            <div class="item" style="background-image: url('/assets/birthday/images/bg1.jpg');">
                <div class="slider-inner">
                </div>
            </div><!--/.item-->
        </div><!--/.owl-carousel-->
    </section><!--/#main-slider-->

    <!--<section>
        <div class="control-panel">
            <div class="item">
                <div class="slider-inner"></div>
            </div>
            <div class="row">
            </div>
        </div>
    </section>-->

    <section id="cta" class="wow fadeInUp">
        <div class="container">
        <div class="block text-center">
            <div class="row">
                <div class="col-sm-12">
                    <p>It's our third birthday - and there's no one we'd rather celebrate it with than you. We want to gift you, our dear diners, a little complimentary taste of a truly luxurious lifestyle. We're giving you the chance to win one glorious, all-inclusive day of feasting, relaxing and unwinding.  All you have to do to win is participate in our contest, telling us on Facebook and Twitter why you want a taste of #TheGoodLife. </p>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <p><strong>When:</strong> Mumbai, Delhi, Pune - <strong>August 22</strong></p>
                    <p>Bangalore - <strong>August 29</strong></p>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                      <p><strong>What's on the Itinerary:</strong></p>
                      <ul style="list-style: none">
                        <li>A decadent lunch</li>
                        <li>A rejuvenating massage</li>
                        <li>A happy round of drinks and appetisers</li>
                      </ul>
                </div>
            </div>
            <!--<div class="row">
                <div class="col-xs-4 ">
                    <div class="btn btn-sm btn-primary full">WHEN</div>
                </div>
                <div class="col-xs-4 ">
                    <div class="btn btn-sm btn-primary full">WHAT'S ON ITINERARY</div>
                </div>
                <div class="col-xs-4 ">
                    <div class="btn btn-sm btn-primary full">HOW TO ENTER</div>
                </div>
            </div>-->



            <!--<div class="row">
                <div class="col-sm-12">
                    <p>When: <strong>August 22</strong></p>
                </div>
                <div class="col-sm-12">
                    <p><strong>What's on the itinerary</strong></p>
                </div>
                <div class="col-sm-12" style="font-size: 1.2em;">
                    <ul style="list-style: none;">
                        <li>Lunch at one of our 3 delicious dining experiences</li>
                        <li>A relaxing massage</li>
                        <li>Refreshing drinks and a snack</li>
                    </ul>
                </div>
                <div class="col-sm-12">
                    <div class="block text-center">
                        <p>All you have to do to win is participate in our contest, telling us what makes you a WowTables Top Diner - and you're officially in the running for a taste of The Good Life with WowTables!</p>
                    </div>
                </div>
            </div>-->
        </div>
        </div>
    </section><!--/#cta-->



    <!--<section id="birthday">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="block text-center wow fadeInUp">
                        <i class="fa fa-5x fa-birthday-cake"></i>
                        <h1>August 22</h1>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="itinerary">
        <div class="container">
            <div class="row">
                <div class="col-sm-4">
                    <div class="block text-center wow fadeInUp">
                        <i class="fa fa-cutlery fa-4x"></i>
                        <h5>Lunch at one of our 3 delicious dining experiences</h5>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="block text-center wow fadeInUp">
                        <i class="fa fa-smile-o fa-4x"></i>
                        <h5>A relaxing massage</h5>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="block text-center wow fadeInUp">
                        <i class="fa fa-glass fa-4x"></i>
                        <h5>Refreshing drinks and a snack</h5>
                    </div>
                </div>
            </div>
        </div>
    </section>-->

    <section id="how">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="block text-center wow fadeInUp">
                        <div class="btn btn-lg btn-primary full" id="howitworks"><strong>Click here to enter the contest!</strong></div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section id="portfolio" style="display: none;">
        <div class="container">
            <div class="city" style="display: none;">
            <div class="row">
                                <div class="col-sm-12">
                                    <div class="block text-center">
                                        <h3>Select Your City</h3>
                                    </div>
                                </div>
                            </div>

                <div class="text-center top-buffer">
                    <ul class="portfolio-filter">
                        <li><a class="#" id="wowtbales_mumbai_menu">Mumbai</a></li>
                        <li><a href="#" id="wowtbales_delhi_menu">Delhi</a></li>
                        <li><a href="#" id="wowtbales_banglore_menu">Banglore</a></li>
                        <!--<li><a href="#" id="wowtbales_pune_menu">Pune</a></li>-->
                    </ul><!--/#portfolio-filter-->
                </div>
            </div>

            <!--mumbai-->
            <div class="options-display-mumbai" style="display: none">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="block text-center">
                            <h3>Check out what's been planned for you</h3>
                        </div>
                    </div>
                </div>
                <div class="row top-buffer lunch">
                    <div class="col-sm-4 wow fadeInUp">
                       <h5>Lunch</h5>
                       <img src="/assets/birthday/images/mumbailunch.jpg" alt="lunch1">
                       <p>A luxurious five course lunch at Hakkasan, Linking Road, Bandra</p>
                    </div>
                    <div class="col-sm-4 wow fadeInUp">
                        <h5>Spa</h5>
                        <img src="/assets/birthday/images/mumbaispa.jpg" alt="lunch2">
                        <p>Four Fountains De-Stress Spa, Pali Naka, Bandra</p>
                    </div>
                    <div class="col-sm-4 wow fadeInUp">
                        <h5>Drinks and a bite</h5>
                        <img src="/assets/birthday/images/mumbai-pbcl.jpg" alt="lunch3">
                        <p>2 drinks and an appetiser at Tilt All Day, Lower Parel</p>
                    </div>
                </div>
                <div class="row top-buffer">
                    <div class="col-sm-4">
                        <a href="https://www.uber.com/"><img src="/assets/birthday/images/mumbaiuberfinal.jpg" alt="uber"></a>
                    </div>
                    <div class="col-sm-8">
                        <p><strong>And if you would like to be chauffeured around in style, we've tied up with the inimitable Uber, providing you with a promo code that gives you Rs. 300 off on every ride you book with them during this journey. Yes, really.</strong></p>
                    </div>
                </div><!-- uber code -->
                <!--<div class="row top-buffer wow fadeInUp">
                    <div class="col-sm-4">
                        <img src="/assets/birthday/images/spa.jpg" alt="spa">
                    </div>
                    <div class="col-sm-2">
                        <h5>SPA</h5>
                        <p>This sample text.This sample text.This sample text.This sample text.</p>
                    </div>
                    <div class="col-sm-4">
                        <img src="/assets/birthday/images/pbcl.jpg" alt="pbcl">
                    </div>
                    <div class="col-sm-2">
                        <h5>PBCL</h5>
                        <p>This sample text.This sample text.This sample text.This sample text.</p>
                    </div>
                </div>-->
            </div>

            <!--delhi-->
            <div class="options-display-delhi" style="display: none">
                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="block text-center">
                                            <h3>Check out what's been planned for you</h3>
                                        </div>
                                    </div>
                                </div>
                <div class="row top-buffer">
                    <div class="col-sm-12">
                        <div class="block text-center">
                            <h5>Lunch Options</h5>
                        </div>
                    </div>
                </div>
                <div class="row top-buffer lunch">
                    <div class="col-sm-4 wow fadeInUp">
                        <img src="/assets/birthday/images/delhilunch1.jpg" alt="lunch1">
                        <p>A four course multi-cuisine feast at Market Cafe, GK -2</p>
                    </div>
                    <div class="col-sm-4 wow fadeInUp">
                        <img src="/assets/birthday/images/delhilunch2.jpg" alt="lunch2">
                        <p>A five course Italian feast at Caffe Tonino, Connaught Place</p>
                    </div>
                    <div class="col-sm-4 wow fadeInUp">
                        <img src="/assets/birthday/images/delhilunch3.jpg" alt="lunch3">
                        <p>A four course lunch at Thai High, Mehrauli</p>
                    </div>
                </div>
                <div class="row top-buffer wow fadeInUp">

                    <div class="col-sm-2">
                        <h5>SPA</h5>
                        <p>A rejuvenating massage at the Four Fountain Spa, NDSE Market</p>
                    </div>
                    <div class="col-sm-4">
                         <img src="/assets/birthday/images/delhispa.jpg" alt="spa">
                    </div>

                    <div class="col-sm-2">
                        <h5>Drinks and a bite</h5>
                        <p>Drinks with an appetiser at Pamphilos, GK -1</p>
                    </div>
                    <div class="col-sm-4">
                         <img src="/assets/birthday/images/delhipbcl.jpg" alt="pbcl">
                    </div>
                </div>
            </div>

            <!--Bangalore-->
            <div class="options-display-bangalore" style="display: none">
                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="block text-center">
                                            <h3>Check out what's been planned for you</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="row top-buffer">
                                    <div class="col-sm-12">
                                        <div class="block text-center">
                                            <h5>Lunch Options</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="row top-buffer lunch">
                                    <div class="col-sm-4 wow fadeInUp">
                                        <img src="/assets/birthday/images/bangalorelunch1.jpg" alt="lunch1">
                                        <p>A wholesome Mediterranean lunch at Om Made Cafe, Koramangala</p>
                                    </div>
                                    <div class="col-sm-4 wow fadeInUp">
                                        <img src="/assets/birthday/images/bangalorelunch2.jpg" alt="lunch2">
                                        <p>A delectable European lunch at 100 Ft Bar Boutique Restaurant, Indiranagar</p>
                                    </div>
                                    <div class="col-sm-4 wow fadeInUp">
                                        <img src="/assets/birthday/images/bangalorelunch3.jpg" alt="lunch3">
                                        <p>An eclectic lunch experience at BlueFROG, 3 Church Street</p>
                                    </div>
                                </div>
                                <div class="row top-buffer wow fadeInUp">

                                    <div class="col-sm-2">
                                        <h5>SPA</h5>
                                        <p>A rejuvenating massage at the Four Fountain Spa, Koramangala and Indiranagar</p>
                                    </div>
                                    <div class="col-sm-4">
                                        <img src="/assets/birthday/images/bangalorespa.jpg" alt="spa">
                                    </div>
                                    <div class="col-sm-2">
                                        <h5>Drinks and a bite</h5>
                                        <p>Drinks with an appetiser at Loft 38, Indiranagar</p>
                                    </div>
                                    <div class="col-sm-4">
                                         <img src="/assets/birthday/images/bangalorepbcl.jpg" alt="pbcl">
                                    </div>
                                </div>
            </div>

            <!-- Pune
            <div class="options-display-pune" style="display: none">
                <h3>2) Check out what's been planned for you</h3>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="block text-center">
                                            <h5>Lunch Options</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="row top-buffer lunch">
                                    <div class="col-sm-4 wow fadeInUp">
                                        <img src="/assets/birthday/images/lunch1.jpg" alt="lunch1">
                                        <p>This sample text.This sample text.This sample text.This sample text.</p>
                                    </div>
                                    <div class="col-sm-4 wow fadeInUp">
                                        <img src="/assets/birthday/images/lunch2.jpg" alt="lunch2">
                                        <p>This sample text.This sample text.This sample text.This sample text.</p>
                                    </div>
                                    <div class="col-sm-4 wow fadeInUp">
                                        <img src="/assets/birthday/images/lunch3.jpg" alt="lunch3">
                                        <p>This sample text.This sample text.This sample text.This sample text.</p>
                                    </div>
                                </div>
                                <div class="row top-buffer wow fadeInUp">
                                    <div class="col-sm-4">
                                        <img src="/assets/birthday/images/spa.jpg" alt="spa">
                                    </div>
                                    <div class="col-sm-2">
                                        <h5>SPA</h5>
                                        <p>This sample text.This sample text.This sample text.This sample text.</p>
                                    </div>
                                    <div class="col-sm-4">
                                        <img src="/assets/birthday/images/pbcl.jpg" alt="pbcl">
                                    </div>
                                    <div class="col-sm-2">
                                        <h5>PBCL</h5>
                                        <p>This sample text.This sample text.This sample text.This sample text.</p>
                                    </div>
                                </div>
            </div>-->
        </div>
    </section>

    <section id="contact" style="display: none;">
        <div class="row">
            <div class="col-sm-6 col-sm-offset-3">
                <div class="wowtables_contact_form">
                    <div class="contact-form wowtable_padding_form">
                        <div class="row wowtables_share_form">
                            Tell us who you are and why you should win!
                        </div>
                    </div>
                    <div class="contact-form" style="margin-top:0px !important;">
                        <div class="row">
                            <div class="text-center message hit" style="display: none;">
                                <p><strong>You have successfully entered</strong></p>
                            </div>
                            <div class="text-center message fail" style="display: none;">
                                <p><strong>Something went wrong.Try again</strong></p>
                            </div>
                        </div>
                        <form id="main-contact-form" name="contact-form" method="post" action="{{URL::to('/')}}/birthday">
                            <div class="form-group">
                                <input type="text" name="name" class="form-control" placeholder="Enter Your Name">
                            </div>
                            <div class="form-group">
                                <input type="email" name="email" class="form-control" placeholder="Enter Your Email">
                            </div>
                            <div class="form-group">
                                <input type="number" name="phone_no" class="form-control" placeholder="Enter Your Contact No">
                            </div>
                            <div class="form-group lunch-sel" id="lunch-sel">

                            </div>
                            <input type="hidden" name="promotion_type" id="promotion_type">
                            <input type="hidden" name="city_sel" id="city_sel">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="block text-center">
                                        <p><strong>Tell us why you should win a taste of #TheGoodLife.</strong></p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                                            <div class="col-sm-12">
                                                                <div class="block">
                                                                    <p>Eg; "I want a taste of #TheGoodLife with @WowTables because I love food, I love unwinding and I love WowTables."
                                                                       Make sure to use the hashtag #TheGoodLife and tag @WowTables on Facebook and @Wow_Tables on Twitter.
                                                                       Remember - there's no such thing as too many reasons. The more you post and tweet - the more you increase your chances of winning!</p>
                                                                </div>
                                                            </div>
                                                        </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <p><strong>Tell us on Facebook</strong></p>
                                </div>
                                <div class="col-sm-3">
                                    <div id='fb-root'></div>
                                    <a id="facebook-share"><img src="/assets/birthday/images/facebook-share.png" alt="facebook" width="70" height="30" /></a>
                                </div>
                            </div>
                            <div class="row">
                                                            <div class="col-sm-4">
                                                                <p><strong>Tell us on Twitter</strong></p>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <div id="twitter-share"></div>
                                                            </div>
                                                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section><!--/#bottom-->

    <footer id="footer">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    &copy; 2015 WowTables
                </div>
            </div>
        </div>
    </footer><!--/#footer-->


    {!! Html::script('assets/birthday/js/jquery.js') !!}
    {!! Html::script('assets/birthday/js/jquery.validate.min.js') !!}
    {!! Html::script('assets/birthday/js/bootstrap.min.js') !!}
    {!! Html::script('assets/birthday/js/mousescroll.js') !!}
    {!! Html::script('assets/birthday/js/smoothscroll.js') !!}
    {!! Html::script('assets/birthday/js/jquery.prettyPhoto.js') !!}
    {!! Html::script('assets/birthday/js/jquery.isotope.min.js') !!}
    {!! Html::script('assets/birthday/js/jquery.inview.min.js') !!}
    {!! Html::script('assets/birthday/js/wow.min.js') !!}
    <script src='http://connect.facebook.net/en_US/all.js'></script>
    {!! Html::script('assets/birthday/js/jquery.twitterbutton.1.1.js') !!}
    {!! Html::script('assets/birthday/js/main.js') !!}




   <script type="text/javascript">
        $(document).ready(function(){
            $(".img-design").click(function(){
                $('.img-design').removeClass('selected-data');
                $(this).addClass('selected-data');
            });

            window.fbAsyncInit = function() {
                    // init the FB JS SDK
                    FB.init({
                        appId      : '487953444640436',
                        status     : true,
                        xfbml      : true
                    });

                };

                // Load the SDK asynchronously
                (function(d, s, id){
                    var js, fjs = d.getElementsByTagName(s)[0];
                    if (d.getElementById(id)) {return;}
                    js = d.createElement(s); js.id = id;
                    js.src = "//connect.facebook.net/en_US/all.js";
                    fjs.parentNode.insertBefore(js, fjs);
                }(document, 'script', 'facebook-jssdk'));



        });
   </script>

</body>
</html>