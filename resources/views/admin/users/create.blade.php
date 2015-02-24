@extends('templates.admin_layout')

@section('content')
	<header class="page-header">
		<h2>Create New User</h2>
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
				<li><span>Add New User</span></li>
			</ol>
		</div>
	</header>

	{!! Form::open(['route'=>'AdminUserStore']) !!}
	<section class="panel">
		<header class="panel-heading">
			<h2 class="panel-title">Add New User</h2>
		</header>
		<div class="panel-body">
			<div class="form-group  col-lg-6">
				{!! Form::label('full_name','Full Name',['class'=>'control-label']) !!}
				{!! Form::text('full_name',null,['class'=>'form-control']) !!}
			</div>
			<div class="form-group  col-lg-6">
				{!! Form::label('email','Email Address',['class'=>'control-label']) !!}
				{!! Form::text('email',null,['class'=>'form-control']) !!}
			</div>
			<div class="form-group  col-lg-6">
				{!! Form::label('password','Password',['class'=>'control-label']) !!}
				{!! Form::password('password',['class'=>'form-control']) !!}
			</div>
			<div class="form-group  col-lg-6">
				{!! Form::label('location','Location',['class'=>'control-label']) !!}
				{!! Form::select('location_id',$locations_list,null,['class'=>'form-control', 'data-plugin-selectTwo'=>'']) !!}
			</div>
			<div class="form-group  col-lg-6">
				{!! Form::label('phone_number','Phone Number',['class'=>'control-label']) !!}
				{!! Form::text('phone_number',null,['class'=>'form-control']) !!}
			</div>
			<div class="form-group  col-lg-6">
				{!! Form::label('attributes[gender]','Gender',['class'=>'control-label']) !!}
				{!! Form::select('attributes[gender]',['male'=>'Male','female'=>'Female'],'Male',['class'=>'form-control']) !!}
			</div>
			<div class="form-group  col-lg-6">
				{!! Form::label('role_id','Role',['class'=>'control-label']) !!}
				{!! Form::select('role_id',$roles_list,null,['class'=>'form-control', 'data-plugin-selectTwo'=>'']) !!}
			</div>
			<div class="form-group  col-lg-6">
				{!! Form::label('newsletter_frequency','Email Preferences',['class'=>'control-label']) !!}
				{!! Form::select('newsletter_frequency',['Daily'=>'Daily','Weekly'=>'Weekly','Never'=>'Never'],'Daily',['class'=>'form-control']) !!}
			</div>
			<div class="form-group  col-lg-6">
				{!! Form::label('attributes[date_of_birth]','Date of Birth',['class'=>'control-label']) !!}
				{!! Form::text('attributes[date_of_birth]',null,['class'=>'form-control datepicker','data-plugin-skin'=>'primary']) !!}
			</div>
		</div>
	</section>


	{!! Form::submit('Add User',['class'=>'btn btn-primary']) !!}

	{!! Form::close() !!}
@stop