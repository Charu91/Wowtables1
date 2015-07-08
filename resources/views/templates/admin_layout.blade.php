<!DOCTYPE html>
<html class="fixed sidebar-left-xs">
	<head>
        <meta name="robots" content="noindex,nofollow" />
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
                                <span class="name">{{ $currentUser->full_name }}</span>
                                <span class="role">{{ $currentUser->role }}</span>
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
                    @include('errors.errors')
                    @include('errors.notifications')
                    @yield('content')
                </section>

            </div>

            <aside id="sidebar-right" class="sidebar-right">
                @yield('sidebar-right')
            </aside>
        </section>

        @include('modals.all')
        @include('partials.js.variables')

        {!! Html::script('http://maps.google.com/maps/api/js?sensor=false') !!}

        {!! Html::script( elixir("js/all.js") ) !!}
            <script type="text/javascript">

                    function move_table_fields(){
                        var order_list = new Array();
                        $("#experiences_table tr[rel]").each(function(i){
                            order_list[i] = $(this).attr('rel');
                        });

                        $("#experiences_table").tableDnD({
                            onDragClass: "myDragClass",
                            onDrop: function(table, row) {
                                $("#search_loading").css('display','inline');
                                var rows = table.tBodies[0].rows;
                                start = row.id;

                                var prev_id = $('#experiences_table #' + start).prev().attr('id');
                                var next_id = $('#experiences_table #' + start).next().attr('id');
                                var new_id;
                                if(start < next_id){
                                    new_id = next_id;
                                } else{
                                    new_id = prev_id;
                                }
                                $.ajax({
                                    type: "POST",
                                    data: {start : start, end: new_id,order_list : order_list},
                                    url: "/admin/experience/location/ajax_sort",
                                    dataType: "json",
                                    success: function(data){
                                        $("#search_loading").css('display','none');
                                        var tr;
                                        for(tr in data)
                                        {
                                            $("#experiences_table tr[rel=" + data[tr]['id'] + "]").attr('id',data[tr]['order_status']);
                                        }
                                    }
                                });

                            },
                            onDragStart: function(table, row) {}
                        });
                    }
                    move_table_fields();

            </script>
    </body>
</html>