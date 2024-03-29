<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="robots" content="noindex,nofollow" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{!! $title or 'WowTables!!' !!}</title>
    <meta name="title" content="{!! $seo_title or 'WowTables' !!}">
    <meta name="description" content="{!! $seo_meta_description or 'WowTables' !!}">
    <meta name="keywords" content="{!! $seo_meta_keywords or 'WowTables' !!}">

    {!! Html::style('https://fonts.googleapis.com/css?family=Lato:100') !!}
    {!! Html::style('vendor/font-awesome/css/font-awesome.css') !!}
    {!! Html::style( elixir("css/all.css") ) !!}
    {!! Html::script('vendor/modernizr/modernizr.js') !!}

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<body>
        @include('site.partials.topnav')

        <div class="container" style="margin-top:10px;max-width:700px;">
            @include('site.partials.notifications')
        </div>

        @yield('content')

        @if( $inform_rebranding == false )
            @include('modals.inform_rebranding')
        @endif

<!-- Scripts -->
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>

@if( $inform_rebranding == false )
    <script>
        $(document).on('ready', function(){
            $('#informRebranding').modal('show');
        });
    </script>
@endif

</body>
</html>
