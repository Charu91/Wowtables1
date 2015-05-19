@extends('.........templates.admin_layout')

@section('content')
	<header class="page-header">
		<h2>Listpage Sidebar</h2>
		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li>
					<a href="/admin/dashboard">
						<i class="fa fa-home"></i>
					</a>
				</li>
				<li>
					<span>
                        Promotions
					</span>
				</li>
				<li><span>Listpage Sidebar</span></li>
			</ol>
		</div>
	</header>
<a href="/admin/promotions/listpage_sidebar/create"><button class="btn btn-primary mb-lg btn-lg">Add new sidebar</button></a>
    <section class="panel panel-featured panel-featured-primary">

        <header class="panel-heading">
            <h2 class="panel-title">
                Sidebar Listing
            </h2>
        </header>
        <div class="panel-body">
            <table class="table table-striped table-responsive mb-none" id="usersTable">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>Link</th>
                    <th>Title</th>
                    <th>Promotion Title</th>
                    <th>City</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
               @foreach($sidebars as $sidebar)
                    <tr>
                        <th>{!! $sidebar->id !!}</th>
                        <th>{!! $sidebar->link !!}</th>
                        <th>{!! $sidebar->title !!}</th>
                        <th>{!! $sidebar->promotion_title !!}</th>
                        <th>{!! $sidebar->location->name !!}</th>
                        <th>
                            <a href="/admin/promotions/listpage_sidebar/{!! $sidebar->id !!}/edit" data-sidebar-id="{!! $sidebar->id !!}" class="btn btn-xs btn-primary edit-sidebar-btn">Edit</a>
                            &nbsp;|&nbsp;
                            <a data-sidebar-id="{!! $sidebar->id !!}" class="btn btn-xs btn-danger delete-sidebar-btn">Delete</a>
                        </th>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </section>

@stop