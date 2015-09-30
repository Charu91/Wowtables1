<!-- start: sidebar -->
<aside id="sidebar-left" class="sidebar-left">

    <div class="sidebar-header">
        <div class="sidebar-title">
            Navigation
        </div>
        <div class="sidebar-toggle hidden-xs" data-toggle-class="sidebar-left-collapsed" data-target="html" data-fire-event="sidebar-left-toggle">
            <i class="fa fa-bars" aria-label="Toggle sidebar"></i>
        </div>
    </div>

    <div class="nano">
        <div class="nano-content">
            <nav id="menu" class="nav-main" role="navigation">
                <ul class="nav nav-main">
                    <li class="nav {{ (isset($uri) && $uri === 'admin/dashboard')? 'nav-active':'' }}">
                        <a href="/admin/dashboard">
                            <i class="fa fa-home" aria-hidden="true"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-parent {{ (isset($uri) && strpos($uri,'admin/experiences') !== false)? 'nav-expanded nav-active':''}}">
                        <a>
                            <i class="fa fa-list-alt" aria-hidden="true"></i>
                            <span>Experiences</span>
                        </a>
                        <ul class="nav nav-children">
                            <li class="{{ (isset($uri) && $uri === 'admin/experiences/create')? 'nav-active':'' }}">
                                <a href="/admin/experiences/create">Create Simple Experience</a>
                            </li>
                            <li class="{{ (isset($uri) && $uri === 'admin/experience/variants')? 'nav-active':'' }}">
                                <a href="/admin/experience/variants">Complex Experience Variants</a>
                            </li>
                            <li class="{{ (isset($uri) && $uri === 'admin/experience/complex/create')? 'nav-active':'' }}">
                                <a href="/admin/experience/complex/create">Create Complex Experience</a>
                            </li>
                            <li class="{{ (isset($uri) && $uri === 'admin/experience/locations/create')? 'nav-active':'' }}">
                                <a href="/admin/experience/locations/create">Create Experience Scheduling</a>
                            </li>
                            <li class="{{ (isset($uri) && $uri === 'admin/experiences')? 'nav-active':'' }}">
                                <a href="/admin/experiences">View Experiences</a>
                            </li>
                            <li class="{{ (isset($uri) && $uri === 'admin/experience/locations')? 'nav-active':'' }}">
                                <a href="/admin/experience/locations">View Experience Scheduling</a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-parent {{ (isset($uri) && strpos($uri,'admin/events') !== false)? 'nav-expanded nav-active':''}}">
                        <a>
                            <i class="fa fa-calendar" aria-hidden="true"></i>
                            <span>Events</span>
                        </a>
                        <ul class="nav nav-children">
                            <li class="{{ (isset($uri) && $uri === 'admin/events')? 'nav-active':'' }}">
                                <a href="/admin/events">View All</a>
                            </li>
                            <li class="{{ (isset($uri) && $uri === 'admin/events/create')? 'nav-active':'' }}">
                                <a href="/admin/events/create">Add New</a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-parent {{ (isset($uri) && strpos($uri,'admin/users') !== false)? 'nav-expanded nav-active':''}}">
                        <a>
                            <i class="fa fa-user" aria-hidden="true"></i>
                            <span>Users</span>
                        </a>
                        <ul class="nav nav-children">
                            <li class="{{ (isset($uri) && $uri === 'admin/users')? 'nav-active':'' }}">
                                <a href="/admin/users">View All</a>
                            </li>
                            <li class="{{ (isset($uri) && $uri === 'admin/users/create')? 'nav-active':'' }}">
                                <a href="/admin/users/create">Create New</a>
                            </li>
                            <li class="{{ (isset($uri) && $uri === 'admin/user/curators')? 'nav-active':'' }}">
                                <a href="/admin/user/curators">Create User Curators</a>
                            </li>
                            <li class="{{ (isset($uri) && $uri === 'admin/users/roles')? 'nav-active':'' }}">
                                <a href="/admin/roles">Roles and Privileges</a>
                            </li>
                            <li class="{{ (isset($uri) && $uri === 'admin/user/attributes')? 'nav-active':'' }}">
                                <a href="/admin/user/attributes">User Attributes</a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav {{ (isset($uri) && $uri === 'admin/media')? 'nav-active':'' }}">
                        <a href="/admin/media">
                            <i class="fa fa-tasks" aria-hidden="true"></i>
                            <span>Media Gallery</span>
                        </a>
                    </li>
                    <li class="nav-parent {{ (isset($uri) && strpos($uri,'admin/orders') !== false)? 'nav-expanded nav-active':''}}">
                        <a>
                            <i class="fa fa-list-alt" aria-hidden="true"></i>
                            <span>Reservations</span>
                        </a>
                        <ul class="nav nav-children">
                            <li class="{{ (isset($uri) && $uri === 'adminreservations')? 'nav-active':'' }}">
                                <a href="/admin/adminreservations ">Admin</a>
                            </li>
                            <li class="{{ (isset($uri) && $uri === 'admin/orders/create')? 'nav-active':'' }}">
                                <a href="javascript:void(0)">Create New</a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-parent {{ (isset($uri) && strpos($uri,'admin/restaurants') !== false)? 'nav-expanded nav-active':''}}">
                        <a>
                            <i class="fa fa-users" aria-hidden="true"></i>
                            <span>Restaurants</span>
                        </a>
                        <ul class="nav nav-children">
                            <li class="{{ (isset($uri) && $uri === 'admin/restaurants')? 'nav-active':'' }}">
                                <a href="/admin/restaurants">View All</a>
                            </li>
                            <li class="{{ (isset($uri) && $uri === 'admin/restaurants/create')? 'nav-active':'' }}">
                                <a href="/admin/restaurants/create">Create New</a>
                            </li>
                            <li class="{{ (isset($uri) && $uri === 'admin/restaurants/locations')? 'nav-active':'' }}">
                                <a href="/admin/restaurants/locations">View Locations</a>
                            </li>
                            <li class="{{ (isset($uri) && $uri === 'admin/restaurants/locations/create')? 'nav-active':'' }}">
                                <a href="/admin/restaurants/locations/create">Create Location</a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-parent {{ (isset($uri) && strpos($uri,'admin/pages') !== false)? 'nav-expanded nav-active':''}}">
                        <a>
                            <i class="fa fa-copy" aria-hidden="true"></i>
                            <span>Static Pages</span>
                        </a>
                        <ul class="nav nav-children">
                            <li class="{{ (isset($uri) && $uri === 'admin/pages')? 'nav-active':'' }}">
                                <a href="/admin/pages">View All</a>
                            </li>
                            <li class="{{ (isset($uri) && $uri === 'admin/pages/create')? 'nav-active':'' }}">
                                <a href="/admin/pages/create">Create New</a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-parent {{ (isset($uri) && strpos($uri,'admin/settings') !== false)? 'nav-expanded nav-active':''}}">
                        <a>
                            <i class="fa fa-gears" aria-hidden="true"></i>
                            <span>Settings</span>
                        </a>
                        <ul class="nav nav-children">
                            <li class="{{ (isset($uri) && $uri === 'admin/settings/general')? 'nav-active':'' }}">
                                <a href="javascript:void(0)">General</a>
                            </li>
                            <li class="{{ (isset($uri) && $uri === 'admin/settings/locations')? 'nav-active':'' }}">
                                <a href="/admin/settings/locations">Manage Locations</a>
                            </li>
                            <li class="{{ (isset($uri) && $uri === 'admin/restaurant/attributes')? 'nav-active':'' }}">
                                <a href="/admin/restaurant/attributes">Manage Restaurant Attributes</a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-parent {{ (isset($uri) && strpos($uri,'admin/promotions') !== false)? 'nav-expanded nav-active':''}}">
                        <a>
                            <i class="fa fa-columns" aria-hidden="true"></i>
                            <span>Promotions</span>
                        </a>
                        <ul class="nav nav-children">
                            <li class="{{ (isset($uri) && $uri === 'admin/promotions/flags')? 'nav-active':'' }}">
                                <a href="/admin/promotions/flags">Flags</a>
                            </li>
                            <li class="{{ (isset($uri) && $uri === 'admin/promotions/collections')? 'nav-active':'' }}">
                                <a href="/admin/promotions/collections">Collections</a>
                            </li>
                            <li class="{{ (isset($uri) && $uri === 'admin/promotions/listpage_sidebar')? 'nav-active':'' }}">
                                <a href="/admin/promotions/listpage_sidebar">Listpage Sidebar</a>
                            </li>
                            <li class="{{ (isset($uri) && $uri === 'admin/promotions/email_footer_promotions')? 'nav-active':'' }}">
                                <a href="/admin/promotions/email_footer_promotions">Email Footer Promotions</a>
                            </li>
                            <li class="{{ (isset($uri) && $uri === 'admin/promotions/variant_type')? 'nav-active':'' }}">
                                <a href="/admin/promotions/variant_type">Variant Type</a>
                            </li>
                            <li class="{{ (isset($uri) && $uri === 'admin/promotions/price_type')? 'nav-active':'' }}">
                                <a href="/admin/promotions/price_type">Price Types</a>
                            </li>
                        </ul>
                    </li>

                     <li class="nav-parent {{ (isset($uri) && strpos($uri,'admin/review') !== false)? 'nav-expanded nav-active':''}}">
                        <a>
                            <i class="fa fa-copy" aria-hidden="true"></i>
                            <span>Reviews</span>
                        </a>
                        <ul class="nav nav-children">
                            <li class="{{ (isset($uri) && $uri === 'admin/review')? 'nav-active':'' }}">
                                <a href="/admin/review">Experience Review</a>
                            </li>
                            <li class="{{ (isset($uri) && $uri === 'admin/reviewalacarte')? 'nav-active':'' }}">
                                <a href="/admin/reviewalacarte">Alacarte Review</a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-parent {{ (isset($uri) && strpos($uri,'admin/bookings') !== false)? 'nav-expanded nav-active':''}}">
                        <a>
                            <i class="fa fa-th-list" aria-hidden="true"></i>
                            <span>Bookings</span>
                        </a>
                        <ul class="nav nav-children">
                            <li class="{{ (isset($uri) && $uri === 'admin/bookings')? 'nav-active':'' }}">
                                <a href="/admin/bookings">Bookings List</a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-parent {{ (isset($uri) && strpos($uri,'admin/invoices') !== false)? 'nav-expanded nav-active':''}}">
                        <a>
                            <i class="fa fa-th-list" aria-hidden="true"></i>
                            <span>Invoices</span>
                        </a>
                        <ul class="nav nav-children">
                            <li class="{{ (isset($uri) && $uri === 'admin/invoices')? 'nav-active':'' }}">
                                <a href="/admin/invoices">Invoices List</a>
                            </li>
                        </ul>
                        <ul class="nav nav-children">
                            <li class="{{ (isset($uri) && $uri === 'admin/create/invoices')? 'nav-active':'' }}">
                                <a href="/admin/create/invoices">Create Invoice</a>
                            </li>
                        </ul>
                    </li>

                </ul>
            </nav>

            <hr class="separator" />

            <!-- Some Intro -->
            <hr class="separator" />
        </div>

    </div>

</aside>
<!-- end: sidebar -->