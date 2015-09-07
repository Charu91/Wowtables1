@extends('frontend.templates.details_pages')

@section('content')
    <style>
        .central_div_thankyou{
            width: 80%;
            height: 40%;
            margin:0 auto;
            padding:0 auto; text-align: center;}

        .thankyou_block{
            width: 70%;
            height: auto;
            background: none;
            color:#fff;
            padding: 0 auto;
            margin: 0 auto;

        }

        .align_block{
            margin: 0 auto;
            padding: 0 auto;

        }


        .thankyou_block h2{
            font-size: 16px;
            color:#000;
            text-align:center;
            display: inline-block;}

        .thankyou_block p{
            font-size: 14px;
            color: #453678;
            text-align: centre;
        }

        .thankyou_block h3{
            font-size: 16px;
            color:#000;
            text-align:center;
            display: inline-block;}
    </style>
    <div id="fb-root"></div>
    <script>(function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));</script>

    <div id="container-middle">
        <div class="page">

            <!--Top Navigation-->
            <div id="top-navigation">

                <!-- <div class="left-menu"><a href="/mumbai">mumbai</a></div>
                <div class="left-menu"><a href="/pune">puna</a></div> -->


            </div>
            <!--Top Navigation closed-->

            <!--Content-->

            <div class="container cms-page">

                <!--Left Contenet-->
                <div class="detail-left-content">

                    <!--TOp-->
                    <?php if (isset($orderDetails['paymentStatus']) && $orderDetails['paymentStatus'] === 'failure'): ?>
                    <div class="top-box1">
                        <p>Oops, looks like we didn't receive your payment. If you are having trouble with paying please call our concierge at 09619551387.</p>
                        <p><a href="{{URL::to('/')}}/pages/gift-cards" style="color:#D27B47">Click here</a> to go back to the gift cards page.</p>
                    </div>
                    <?php else: ?>
                    <div class="central_div_thankyou">

                        <!--TOp-->
                        <div class="thankyou_block">
                            <h2>Thank you for your order.</h2>
                            <h3>Your order number is: <?php echo $orderDetails['order_id'];?>.</h3>
                            <p>
                                <a href="{{URL::to('/')}}/pages/gift-cards" style="color:#D27B47">Click here to go back to the gift cards page.</a></p>
                        </div>
                        <!--TOp-->

                        <!--bottom-->
                        <!--bottom-->
                    </div>
                    <!--TOp-->

                    <!--bottom-->
                    <!--bottom-->
                    <?php endif;?>
                </div>
                <!--Content closed-->
            </div>
        </div>

@endsection