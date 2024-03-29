<nav style="margin-bottom: 0;" class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">WowTables</a>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li>{!! link_to_route('SiteHomePage','Home') !!}</li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                @if (Auth::guest())
                    <li>{!! link_to_route('login_path','Login') !!}</li>
                    <li>{!! link_to_route('register_path','Register') !!}</li>
                @else

                        <li class="dropdown">
                            <a href="/admin/dashboard" class="">Admin Panel</a>
                        </li>

                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Full Name<span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li>{!! link_to_route('logout_path','Logout') !!}</li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>