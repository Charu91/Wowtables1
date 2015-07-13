@extends('.........templates.admin_layout')

@section('content')
	<header class="page-header">
		<h2>Email Footer Promotions</h2>
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
				<li><span>Email Footer Promotions</span></li>
			</ol>
		</div>
	</header>
<a href="/admin/promotions/email_footer_promotions/create"><button class="btn btn-primary mb-lg btn-lg">Add new footer promotion</button></a>
    <section class="panel panel-featured panel-featured-primary">

        <header class="panel-heading">
            <h2 class="panel-title">
                Email Footer Promotion Listing
            </h2>
        </header>
        <div class="panel-body">
            <table class="table table-striped table-responsive mb-none" id="usersTable">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>Link</th>
                    <th>Media</th>
                    <th>City</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
               @foreach($email_footer_promotions as $efp)
                    <tr>
                        <th>{!! $efp->id !!}</th>
                        <th>{!! $efp->link !!}</th>
                        <th><img style="height: 56px;" class="img-thumbnail" src="https://s3-eu-west-1.amazonaws.com/wowtables/uploads/email_footer_promotions/{!! $efp->media->file !!}"/></th>
                        <th>{!! $efp->location->name !!}</th>
                        <th>
                            <a href="/admin/promotions/email_footer_promotions/{!! $efp->id !!}/edit" data-efp-id="{!! $efp->id !!}" class="btn btn-xs btn-primary edit-efp-btn">Edit</a>
                            &nbsp;|&nbsp;
                            <a data-efp-id="{!! $efp->id !!}" class="btn btn-xs btn-danger delete-efp-btn">Delete</a>
                        </th>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </section>

@stop