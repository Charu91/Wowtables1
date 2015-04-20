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
				<li><span>Variant Type</span></li>
			</ol>
		</div>
	</header>

	<div class="row">
		<div class="col-sm-8">
			<section class="panel panel-featured panel-featured-primary">
				<header class="panel-heading">
					<h2 class="panel-title">
						All Variants Types
					</h2>
				</header>
				<div class="panel-body">
					<table class="table table-striped table-responsive mb-none" id="usersTable">
						<thead>
						<tr>
							<th>Variant Id</th>
							<th>Variant Name</th>
							<th>Variant Alias</th>
							<th>Action</th>
						</tr>
						</thead>
						<tbody>
						@foreach($variant_types as $variant_type)
							<tr>
								<th>{!! $variant_type->id !!}</th>
								<th>{!! $variant_type->variation_name !!}</th>
								<th>{!! $variant_type->variant_alias !!}</th>
								<th>
									<a data-variant-id="{!! $variant_type->id !!}" class="btn btn-xs btn-primary edit-variant-btn">Edit</a>
									&nbsp;|&nbsp;
									<a data-variant-id="{!! $variant_type->id !!}" class="btn btn-xs btn-danger delete-variant-btn">Delete</a>
								</th>
							</tr>
						@endforeach
						</tbody>
					</table>
				</div>
			</section>
		</div>
		<div class="col-sm-4" id="editVariantTypeHolder"></div>
		<div class="col-sm-4">

			<section class="panel">
				<header class="panel-heading">
					<h2 class="panel-title">Add New</h2>
				</header>
				<div class="panel-body">
					{!! Form::open(['route'=>'admin.promotions.variant_type.store','novalidate'=>'novalidate']) !!}

					<div class="form-group">
                        {!! Form::label('variation_name','Variant Name',['class'=>'col-sm-4 control-label']) !!}
                        <div class="col-sm-8">
                            {!! Form::text('variation_name',null,['class'=>'form-control','required'=>'']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('variant_alias','Variant Alias',['class'=>'col-sm-4 control-label']) !!}
                        <div class="col-sm-8">
                            {!! Form::text('variant_alias',null,['class'=>'form-control','required'=>'']) !!}
                        </div>
                    </div>
				</div>
				<footer class="panel-footer">
					{!! Form::submit('Add Variant',['class'=>'btn btn-primary']) !!}
				</footer>
				{!! Form::close() !!}
			</section>
		</div>
	</div>
@stop