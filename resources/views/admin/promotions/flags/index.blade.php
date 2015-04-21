@extends('templates.admin_layout')

@section('content')
	<header class="page-header">
		<h2>Flags</h2>
		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li>
					<a href="/admin/dashboard">
						<i class="fa fa-home"></i>
					</a>
				</li>
				<li>
					<a href="/admin/promotions">
                        Promotions
					</a>
				</li>
				<li><span>Flags</span></li>
			</ol>
		</div>
	</header>

	<div class="row">
		<div class="col-sm-8">
			<section class="panel panel-featured panel-featured-primary">
				<header class="panel-heading">
					<h2 class="panel-title">
						All Flags
					</h2>
				</header>
				<div class="panel-body">
					<table class="table table-striped table-responsive mb-none" id="usersTable">
						<thead>
						<tr>
							<th>Flag Id</th>
							<th>Flag Name</th>
							<th>Flag Color</th>
							<th>Action</th>
						</tr>
						</thead>
						<tbody>
						@foreach($flags as $flag)
							<tr>
								<th>{!! $flag->id !!}</th>
								<th>{!! $flag->name !!}</th>
								<th>{!! $flag->color !!}</th>
								<th>
									<a data-flag-id="{!! $flag->id !!}" class="btn btn-xs btn-primary edit-flag-btn">Edit</a>
									&nbsp;|&nbsp;
									<a data-flag-id="{!! $flag->id !!}" class="btn btn-xs btn-danger delete-flag-btn">Delete</a>
								</th>
							</tr>
						@endforeach
						</tbody>
					</table>
				</div>
			</section>
		</div>
		<div class="col-sm-4" id="editFlagHolder"></div>
		<div class="col-sm-4">

			<section class="panel">
				<header class="panel-heading">
					<h2 class="panel-title">Add New</h2>
				</header>
				<div class="panel-body">
					{!! Form::open(['route'=>'admin.promotions.flags.store','novalidate'=>'novalidate']) !!}

					<div class="form-group">
                        {!! Form::label('name','Name',['class'=>'col-sm-4 control-label']) !!}
                        <div class="col-sm-8">
                            {!! Form::text('name',null,['class'=>'form-control','required'=>'']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('color','Color',['class'=>'col-sm-4 control-label ']) !!}
                        <div class="col-sm-8">
                            {!! Form::select('color',['Red'=>'Red','Blue'=>'Blue','Green'=>'Green','Yellow'=>'Yellow','Black'=>'Black','White'=>'White'],null,['class'=>'form-control']) !!}
                        </div>
                    </div>

				</div>
				<footer class="panel-footer">
					{!! Form::submit('Add Flag',['class'=>'btn btn-primary']) !!}
				</footer>
				{!! Form::close() !!}
			</section>
		</div>
	</div>
@stop