@extends('.........templates.admin_layout')

@section('content')
	<header class="page-header">
		<h2>Collections</h2>
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
				<li><span>Collections</span></li>
			</ol>
		</div>
	</header>
<a href="/admin/promotions/collections/create"><button class="btn btn-primary mb-lg btn-lg">Add new collection</button></a>
    <section class="panel panel-featured panel-featured-primary">

        <header class="panel-heading">
            <h2 class="panel-title">
                All Collections
            </h2>
        </header>
        <div class="panel-body">
            <table class="table table-striped table-responsive mb-none" id="usersTable">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Description</th>
                    <th>Banner Image</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($collections as $collection)
                    <tr>
                        <th>{!! $collection->id !!}</th>
                        <th>{!! $collection->name !!}</th>
                        <th>{!! $collection->slug !!}</th>
                        <th>{!! $collection->description !!}</th>
                        <th><img style="height: 56px;" class="img-thumbnail" src="{!! $media_url.$collection->media->media_resized->first()->file !!}"/></th>
                        <th>{!! $collection->status !!}</th>
                        <th>
                            <a href="/admin/promotions/collections/{!! $collection->id !!}/edit" data-collection-id="{!! $collection->id !!}" class="btn btn-xs btn-primary edit-collection-btn">Edit</a>
                            &nbsp;|&nbsp;
                            <a data-collection-id="{!! $collection->id !!}" class="btn btn-xs btn-danger delete-collection-btn">Delete</a>
                        </th>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </section>

@stop