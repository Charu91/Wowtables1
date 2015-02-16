<!DOCTYPE html>
<html class="fixed sidebar-left-xs">
	<head>
        <title>{!! $title or 'WowTables' !!}</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta name="_token" content="{!! $_token or 'null' !!}" />

        {!! Html::style('images/favicon.ico',['rel'=>'icon','type'=>'image/ico']) !!}
        {!! Html::style('http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800|Shadows+Into+Light') !!}
        {!! Html::style('vendor/font-awesome/css/font-awesome.css') !!}
        {!! Html::style( elixir("css/all.css") ) !!}
        {!! Html::script('vendor/modernizr/modernizr.js') !!}

	</head>
    <body>
        <section class="body">

            <!-- start: header -->
            <header class="header">
                <div class="logo-container">
                    <a href="../" class="logo">
                        <img src="/images/logo.png" height="35" alt="Admin" />
                    </a>
                    <div class="visible-xs toggle-sidebar-left" data-toggle-class="sidebar-left-opened" data-target="html" data-fire-event="sidebar-left-opened">
                        <i class="fa fa-bars" aria-label="Toggle sidebar"></i>
                    </div>
                </div>
                <!-- start: search & user box -->
                <div class="header-right">

                    <span class="separator"></span>

                    <div id="userbox" class="userbox">
                        <a href="#" data-toggle="dropdown">
                            <div class="profile-info" data-lock-name="John Doe" data-lock-email="johndoe@okler.com">
                                <span class="name">{{ $user->full_name }}</span>
                                <span class="role">{{ $user->role }}</span>
                            </div>
                            <i class="fa custom-caret"></i>
                        </a>

                        <div class="dropdown-menu">
                            <ul class="list-unstyled">
                                <li class="divider"></li>
                                <li>
                                    <a role="menuitem" tabindex="-1" href="javascript:void(0);"><i class="fa fa-user"></i> My Profile</a>
                                </li>
                                <li>
                                    <a role="menuitem" tabindex="-1" href="{{ route('AdminLogout') }}"><i class="fa fa-power-off"></i> Logout</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- end: search & user box -->
            </header>
            <!-- end: header -->

            <div class="inner-wrapper">

                @include('partials.menus.sidebar_left')

                <section role="main" class="content-body">
                    @yield('content')
                </section>

            </div>

            <aside id="sidebar-right" class="sidebar-right">
                @yield('sidebar-right')
            </aside>
        </section>

        @include('modals.all')

        {!! Html::script('http://maps.google.com/maps/api/js?sensor=false') !!}

        {!! Html::script( elixir("js/all.js") ) !!}
    </body>
</html>