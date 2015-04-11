@extends('templates.admin_layout')

@section('content')
	<header class="page-header">
		<h2>Experience Variants</h2>
		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li>
					<a href="/admin/dashboard">
						<i class="fa fa-dashboard"></i>
					</a>
				</li>
				<li>
					<a href="/admin/experiences">
						<i class="fa fa-home"></i>
					</a>
				</li>
				<li><span>Experience Variants</span></li>
			</ol>
		</div>
	</header>

	<div class="row">
		<div class="col-sm-6">
			<section class="panel panel-featured panel-featured-primary">
				<header class="panel-heading">
					<h2 class="panel-title">
						All Variants
					</h2>
				</header>
				<div class="panel-body">
					<table class="table table-striped table-responsive mb-none" id="usersTable">
						<thead>
						<tr>
							<th>Id</th>
							<th>Name</th>
							<th>Slug</th>
							<th>Action</th>
						</tr>
						</thead>
						<tbody>
							<tr>
								<th>1</th>
								<th>Name</th>
								<th>Slug</th>
								<th>
									<a  class="btn btn-xs btn-primary edit-user-attribute">Edit</a>
									&nbsp;|&nbsp;
									<a  class="btn btn-xs btn-danger delete-user-attribute">Delete</a>
								</th>
							</tr>
						</tbody>
					</table>
				</div>
			</section>
		</div>
		<div class="col-sm-6" id=""></div>
		<div class="col-sm-6">

			<section class="panel">
				<header class="panel-heading">
					<h2 class="panel-title">Add New Variant</h2>
				</header>
				{!! Form::open(['route'=>'admin.experience.variants.store','class'=>'form-horizontal','novalidate'=>'novalidate']) !!}
				<div class="panel-body">
					<div class="form-group">
						<label class="col-sm-4 control-label" for="name">Name <span class="required">*</span></label>
						<div class="col-sm-8">
							{!! Form::text('name',null,['class'=>'form-control','required'=>'','id'=>'title']) !!}
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label" for="slug">Slug <span class="required">*</span></label>
						<div class="col-sm-8">
							{!! Form::text('slug',null,['class'=>'form-control','required'=>'','id'=>'slug']) !!}
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label" for="attributes[short_description]">Short Description <span class="required">*</span></label>
						<div class="col-sm-8">
							{!! Form::textarea('attributes[short_description]',null,['class'=>'form-control','rows'=>'3','required'=>'']) !!}
						</div>
					</div>
					<div class="form-group">
						<label for="pricing[price]" class="col-sm-4 control-label">Price<span class="required">*</span></label>
						<div class="col-sm-8">
							{!! Form::text('pricing[price]',null,['class'=>'form-control','required'=>'']) !!}
						</div>
					</div>
					<div class="form-group">
						<label for="pricing[tax]" class="col-sm-4 control-label">Tax <span class="required">*</span></label>
						<div class="col-sm-8">
							{!! Form::text('pricing[tax]',null,['class'=>'form-control','required'=>'']) !!}
						</div>
					</div>
					<div class="form-group">
						<label for="pricing[post_tax_price]" class="col-sm-4 control-label">Post Tax Price <span class="required">*</span></label>
						<div class="col-sm-8">
							{!! Form::text('pricing[post_tax_price]',null,['class'=>'form-control','required'=>'']) !!}
						</div>
					</div>
					<div class="form-group">
                        <label for="pricing[commission_per_cover]" class="col-sm-4 control-label">Commissions Per Cover<span class="required">*</span></label>
                        <div class="col-sm-8">
                            {!! Form::text('pricing[commission_per_cover]',null,['class'=>'form-control','required'=>'']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="pricing[commission_on]" class="col-sm-4 control-label">Commission On <span class="required">*</span></label>
                        <div class="col-sm-8">
                            {!! Form::select('pricing[commission_on]',[''=>'Select Value','Pre-Tax'=>'Pre-Tax','Post-Tax'=>'Post-Tax'],null,['class'=>'form-control populate','data-plugin-selectTwo'=>'','required'=>'']) !!}
                        </div>
                    </div>
					<div class="form-group">
						<div class="col-sm-4">
							<a id="expMenuBtn" data-target="#markdownmodal" data-toggle="modal" class="btn btn-primary">Menu</a>
						</div>
					</div>
					<div class="form-group">
					    <div id="experienceMenuHolder">
							<label for="attributes[menu]" class="col-sm-4 control-label">Menu <span class="required">*</span></label>
							<div class="col-sm-8">
								{!! Form::textarea('attributes[menu]',null,['style'=>'overflow:auto;','rows'=>'10','class'=>'form-control','required'=>'','id'=>'expMenu']) !!}
							</div>
						</div>
                            {!! Form::hidden('attributes[menu_markdown]','',['id'=>'expMarkdownMenu']) !!}
					</div>
					<div class="form-group">
                        <label for="mapping[complex_product_id]" class="col-sm-4 control-label">Select Complex Experience <span class="required">*</span></label>
                        <div class="col-sm-8">
                            {!! Form::select('mapping[complex_product_id]',$complex_experience_list,null,['class'=>'form-control populate','data-plugin-selectTwo'=>'','required'=>'']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="mapping[variant_option_id]" class="col-sm-4 control-label">Select variant <span class="required">*</span></label>
                        <div class="col-sm-8">
                            {!! Form::select('mapping[variant_option_id]',$variant_list,null,['class'=>'form-control populate','data-plugin-selectTwo'=>'','required'=>'']) !!}
                        </div>
                    </div>
					<div class="form-group col-md-6">
						<label for="status">&nbsp;&nbsp;&nbsp;Status <span class="required">*</span>&nbsp;&nbsp;&nbsp;</label>
						<div class="radio-custom radio-success radio-inline">
							<input type="radio" id="Publish" name="status" value="Publish">
							<label for="Publish">Publish</label>
						</div>
						<div class="radio-custom radio-danger radio-inline">
							<input type="radio" id="Draft" name="status" value="Draft" checked="checked">
							<label for="Draft">Draft</label>
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