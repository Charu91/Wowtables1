@extends('templates.admin_layout')

@section('content')
	<header class="page-header">
		<h2>Curators</h2>
		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li>
					<a href="/admin/dashboard">
						<i class="fa fa-home"></i>
					</a>
				</li>
				<li>
					<a href="/admin/users">
                        Users
					</a>
				</li>
				<li><span>Curators</span></li>
			</ol>
		</div>
	</header>
<a href="/admin/user/curators/create"><button class="btn btn-primary mb-lg btn-lg">Add new curator</button></a>
    <section class="panel panel-featured panel-featured-primary">

        <header class="panel-heading">
            <h2 class="panel-title">
                All Curators
            </h2>
        </header>
        <div class="panel-body">
            <table class="table table-striped table-responsive mb-none" id="usersTable">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Image</th>
                    <th>City</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($curators as $curator)
                    <tr>
                        <th>{!! $curator->id !!}</th>
                        <th>{!! $curator->name !!}</th>
                        <th><img style="height: 56px;" class="img-thumbnail" src="{!! $media_url.$curator->media->media_resized->first()->file !!}"/></th>
                        <th>{!! $curator->location->name !!}</th>
                        <th>
                            <a href="/admin/user/curators/{!! $curator->id !!}/edit" data-curator-id="/admin/user/curators/{!! $curator->id !!}/edit" class="btn btn-xs btn-primary edit-curator-btn">Edit</a>
                            &nbsp;|&nbsp;
                            <a data-curator-id="{!! $curator->id !!}" class="btn btn-xs btn-danger delete-curator-btn">Delete</a>
                        </th>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </section>

@stop