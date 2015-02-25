@extends('templates.admin_layout')

@section('content')
	<header class="page-header">
		<h2>Users</h2>
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

	{!! Form::model($user['user'],['route' => ['AdminUserUpdate',$user['user']['id']],'method'=>'PUT']) !!}

	<section class="panel">
		<header class="panel-heading">
			<h2 class="panel-title">Edit User</h2>
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
				{!! Form::label('location','Location',['class'=>'control-label']) !!}
				{!! Form::select('location_id',$locations_list,$user['user']['location_id'],['class'=>'form-control', 'data-plugin-selectTwo'=>'']) !!}
			</div>
			<div class="form-group  col-lg-6">
				{!! Form::label('phone_number','Phone Number',['class'=>'control-label']) !!}
				{!! Form::text('phone_number',null,['class'=>'form-control']) !!}
			</div>
			<div class="form-group  col-lg-6">
				{!! Form::label('role_id','Role',['class'=>'control-label']) !!}
				{!! Form::select('role_id',$roles_list,$user['user']['role_id'],['class'=>'form-control', 'data-plugin-selectTwo'=>'']) !!}
			</div>
			<div class="form-group  col-lg-6">
				{!! Form::label('newsletter_frequency','Email Preferences',['class'=>'control-label']) !!}
				{!! Form::select('newsletter_frequency',['Daily'=>'Daily','Weekly'=>'Weekly','Never'=>'Never'],$user['user']['newsletter_frequency'],['class'=>'form-control']) !!}
			</div>
		</div>
	</section>

	@if( $user['attributes'] )
	<section class="panel">
		<header class="panel-heading">
			<h2 class="panel-title">User's Attributes</h2>
		</header>
		<div class="panel-body">
			@foreach($user['attributes'] as $name=>$value)
				<div class="form-group  col-lg-6">
					@if($value instanceof \Carbon\Carbon)
						{!! Form::label('attributes['.$name.']',ucwords(str_replace('_',' ',$name)),['class'=>'control-label']) !!}
						{!! Form::text('attributes['.$name.']',\Carbon\Carbon::parse($value)->format('Y-m-d'),['class'=>'form-control datepicker']) !!}
					@endif
					@if(is_bool($value))
						<div class="checkbox-custom checkbox-primary">
							{!! Form::checkbox('attributes['.$name.']',$value,$value) !!}
							{!! Form::label('attributes['.$name.']',ucwords(str_replace('_',' ',$name)),['class'=>'control-label']) !!}
						</div>
					@endif
					@if(is_integer($value) || is_int($value) || is_float($value) || is_string($value))
						{!! Form::label('attributes['.$name.']',ucwords(str_replace('_',' ',$name)),['class'=>'control-label']) !!}
						{!! Form::text('attributes['.$name.']',$value,['class'=>'form-control']) !!}
					@endif
					@if(is_array($value))
						{!! Form::label('attributes['.$name.']',ucwords(str_replace('_',' ',$name)),['class'=>'control-label']) !!}
						{!! Form::select('attributes['.$name.']',$value,$value,['class'=>'form-control', 'data-plugin-selectTwo'=>'','multiple'=>'']) !!}
					@endif
				</div>
			@endforeach
			<div id="addUserAttributeHolder"></div>
		</div>
		<footer class="panel-footer">
			<a class="btn btn-primary" id="newUserAttributeBtn">Add New Attribute</a>
		</footer>
	</section>
	@else
	<section class="panel">
		<header class="panel-heading">
			<a class="btn btn-primary" id="newUserAttributeBtn">Add New Attribute</a>
			<h2 class="panel-title pull-right">User's Attributes</h2>
		</header>
		<div class="panel-body">
			<div id="addUserAttributeHolder"></div>
		</div>
	</section>

	@endif

	{!! Form::submit('Update User',['class'=>'btn btn-primary']) !!}
	{!! Form::close() !!}
	</section>

	@include('modals.add_user_attribute')

@stop
