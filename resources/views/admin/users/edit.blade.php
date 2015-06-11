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

	<form method="POST" action="{{URL::to('/')}}/admin/users/{{$data['data']['user_id']}}" accept-charset="UTF-8">

	<section class="panel">
		<header class="panel-heading">
			<h2 class="panel-title">Edit User</h2>
		
		</header>
		<div class="panel-body">
			<div class="form-group  col-lg-6">
				<label for="full_name" class="control-label">Full Name</label>
				<input class="form-control" name="full_name" type="text" value="{{$data['data']['full_name']}}" id="full_name">
			</div>
			<div class="form-group  col-lg-6">
				<label for="email" class="control-label">Email Address</label>
				<input type="email" name="email" class="form-control" id="inputEmail3" placeholder="Email" disabled="disabled" value="{{$data['data']['email']}}">
			</div>
			<div class="form-group  col-lg-6">
				<label for="location" class="control-label">City</label>
				<select name="location_id" class="form-control" required>
                <?php
                foreach ($cities as $key => $city) 
                {

                    echo '<option value="'.$key.'">'.$city.'</option>'; 
                } 
                ?>
                </select>
			</div>
			<div class="form-group  col-lg-6">
				<label for="phone_number" class="control-label">Phone Number</label>
				<input type="text" name="phone_number" id="phone" class="form-control" value="{{$data['data']['phone_number']}}" value="" required>
			</div>
			<div class="form-group  col-lg-6">
				<label for="role_id" class="control-label">Role</label>
				<select class="form-control" id="role_id" name="role_id">
					<?php foreach ($role as $roleData) 
					{
			           	
			           	echo '<option value="'.$roleData->id.'">'.$roleData->name.'</option>';
			        } ?>
				</select>
			</div>
			<div class="form-group  col-lg-6">
				<label for="newsletter_frequency" class="control-label">Email Preferences</label>
				<select class="form-control" id="newsletter_frequency" name="newsletter_frequency">
					<option value="Daily" selected="selected">Daily</option>
					<option value="Weekly">Weekly</option><option value="Never">Never</option></select>
			</div>
		</div>
	</section>

	<!-- @/*if( $user['attributes'] )*/ -->
	<section class="panel">
		<header class="panel-heading">
			<h2 class="panel-title">User's Attributes</h2>
		</header>
		<div class="panel-body">
		
			<div class="form-group  col-lg-6">
				<label for="attributes[date_of_birth]" class="control-label">Date Of Birth</label>
				<input class="form-control datepicker" name="date_of_birth" type="text" value="{{$data['data']['dob']}}" id="attributes[date_of_birth]">
			</div>
			<div class="form-group  col-lg-6">
			<label for="attributes[gender]" class="control-label">Gender</label>
			<br>
			<input type="radio" name="gender" id="gender1" value="Male" checked="checked" required>
                Male
            <input type="radio" name="gender" id="gender2" value="Female" required>
            Female
			</div>
			<div id="addUserAttributeHolder"></div>
		</div>
		<footer class="panel-footer">
			<!-- <a class="btn btn-primary" id="newUserAttributeBtn">Add New Attribute</a> -->
		</footer>
	</section>
	<!-- @/*else*/ -->
	<!-- <section class="panel">
		<header class="panel-heading">
			<a class="btn btn-primary" id="newUserAttributeBtn">Add New Attribute</a>
			<h2 class="panel-title pull-right">User's Attributes</h2>
		</header>
		<div class="panel-body">
			<div id="addUserAttributeHolder"></div>
		</div>
	</section> -->

	<!-- @/*endif*/ -->

	<input class="btn btn-primary" type="submit" value="Update User">
	{!! Form::close() !!}
	</section>

	@include('modals.add_user_attribute')

@stop
