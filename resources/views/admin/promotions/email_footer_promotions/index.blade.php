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
                    <!--<th>Media</th>-->
                    <th>City</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
               
                </tbody>
            </table>
        </div>
    </section>

@stop