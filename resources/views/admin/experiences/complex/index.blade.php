@extends('templates.admin_layout')

@section('content')
	<header class="page-header">
		<h2>Complex Experience</h2>
		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li>
					<a href="/admin/dashboard">
						<i class="fa fa-home"></i>
					</a>
				</li>
				<li><span>Complex Experience</span></li>
			</ol>
		</div>
	</header>

	<section class="panel panel-featured panel-featured-primary">
		<header class="panel-heading">
			<h2 class="panel-title">
				All Complex Experiences
			</h2>
		</header>
		<div class="panel-body">
			<table class="table table-striped table-responsive mb-none" id="restaurantsTable">
				<thead>
				<tr>
					<th>Id</th>
					<th>Name</th>
					<th>Name</th>
					<th>Slug</th>
					<th>Status</th>
					<th>Created At</th>
					<th>Updated At</th>
					<th>Actions</th>
				</tr>
				</thead>
				<tbody>

				</tbody>
			</table>
		</div>
	</section>

@stop