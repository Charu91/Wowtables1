@extends('templates.admin_layout')

@section('content')
	<header class="page-header">
		<h2>Pages</h2>
		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li>
					<a href="/admin/dashboard">
						<i class="fa fa-home"></i>
					</a>
				</li>
				<li><span>Pages</span></li>
			</ol>
		</div>
	</header>

	<section class="panel panel-featured panel-featured-primary">
		<header class="panel-heading">
			<h2 class="panel-title">
				All Pages
			</h2>
		</header>
		<div class="panel-body">
			<table class="table table-striped table-responsive mb-none" id="usersTable">
				<thead>
				<tr>
					<th>Page Id</th>
					<th>Page Slug</th>
					<th>Main Content</th>
					<th>Status</th>
					<th>Actions</th>
				</tr>
				</thead>
				<tbody>
					@foreach($pages as $page)
						<tr>
							<th>{!! $page->id !!}</th>
							<th>{!! $page->title !!}</th>
							<th>{!! $page->slug !!}</th>
							<th>{!! $page->status !!}</th>
							<th>
								{!! link_to_route('AdminPagesEdit','Edit',$page->id,['target'=>'_blank','class'=>'btn btn-xs btn-primary','data-page-id'=>$page->id]) !!}

								&nbsp;|&nbsp;

								<a data-page-id="{!! $page->id !!}" class="btn btn-xs btn-danger delete-page-btn">Delete</a>

								&nbsp;|&nbsp;

								{!! link_to_route('AdminPagesPreview','Preview',$page->id,['target'=>'_blank','class'=>'btn btn-xs btn-success','data-page-id'=>$page->id]) !!}

							</th>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</section>

@stop