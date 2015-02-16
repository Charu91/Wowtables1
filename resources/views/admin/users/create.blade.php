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

	<section class="panel">
		<header class="panel-heading">
			<h2 class="panel-title">Add New User</h2>
		</header>
		<div class="panel-body">
			{!! Form::open(['route'=>'AdminUserStore']) !!}
			<div class="row">
				<div class="col-sm-4">
					<div class="form-group">
						{!! Form::label('fullname','Full Name',['class'=>'control-label']) !!}
						{!! Form::text('fullname',null,['class'=>'form-control']) !!}
					</div>
				</div>
				<div class="col-sm-4">
					<div class="form-group">
						{!! Form::label('email','Email Address',['class'=>'control-label']) !!}
						{!! Form::text('email',null,['class'=>'form-control']) !!}
					</div>
				</div>
				<div class="col-sm-4">
					<div class="form-group">
						{!! Form::label('password','Password',['class'=>'control-label']) !!}
						{!! Form::password('password',['class'=>'form-control']) !!}
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-4">
					<div class="form-group">
						{!! Form::label('location','Location',['class'=>'control-label']) !!}
						{!! Form::text('location',null,['class'=>'form-control']) !!}
					</div>
				</div>
				<div class="col-sm-4">
					<div class="form-group">
						{!! Form::label('mobile','Mobile Number',['class'=>'control-label']) !!}
						{!! Form::text('mobile',null,['class'=>'form-control']) !!}
					</div>
				</div>
				<div class="col-sm-4">
					<div class="form-group">
						{!! Form::label('zipcode','Zip Code',['class'=>'control-label']) !!}
						{!! Form::text('zipcode',null,['class'=>'form-control']) !!}
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-4">
					<div class="form-group">
						{!! Form::label('gender','Gender',['class'=>'control-label']) !!}
						{!! Form::select('gender',['male'=>'Male','female'=>'Female'],'Male',['class'=>'form-control']) !!}
					</div>
				</div>
				<div class="col-sm-4">
					<div class="form-group">
						{!! Form::label('role','Role',['class'=>'control-label']) !!}
						{!! Form::select('role',['administrator'=>'Administrator','user'=>'User'],'User',['class'=>'form-control']) !!}
					</div>
				</div>
				<div class="col-sm-4">
					<div class="form-group">
						{!! Form::label('email-preferences','Email Preferences',['class'=>'control-label']) !!}
						{!! Form::select('email-preferences',['weekly'=>'Weekly','never'=>'Never'],'Weekly',['class'=>'form-control']) !!}
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-4">
					<div class="form-group">
						{!! Form::label('date_of_birth','Date of Birth',['class'=>'control-label']) !!}
						{!! Form::text('date_of_birth',null,['class'=>'form-control','data-plugin-datepicker'=>'','data-plugin-skin'=>'primary']) !!}
					</div>
				</div>
				<div class="col-sm-4">
					<div class="form-group">
						{!! Form::label('telecampaign','Telecampaign',['class'=>'control-label']) !!}
						{!! Form::text('telecampaign',null,['class'=>'form-control']) !!}
					</div>
				</div>
			</div>
		</div>
		<footer class="panel-footer">
			{!! Form::submit('Add User',['class'=>'btn btn-primary']) !!}
		</footer>
		{!! Form::close() !!}
	</section>
@stop