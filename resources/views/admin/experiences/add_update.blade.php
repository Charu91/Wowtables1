@extends('templates.admin_layout')


@section('content')



@stop


@section('old')
	<header class="page-header">
		<h2>Experiences</h2>
		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li>
					<a href="#">
						<i class="fa fa-home"></i>
					</a>
				</li>
				<li><span> Add/Update Experience</span></li>
			</ol>
			<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>
		</div>
	</header>
	<!-- start: page -->
	<section class="content-with-menu content-with-menu-has-toolbar form-gallery">
		<div class="row">
			<div class="col-md-12">
				<form id="form1" class="form-horizontal">
					<section class="panel">
						<header class="panel-heading">
							<div class="panel-actions">
								<a href="#" class="fa fa-caret-down"></a>
								<a href="#" class="fa fa-times"></a>
							</div>
							<h2 class="panel-title">Add New Experience</h2>
							<p class="panel-subtitle">
							</p>
						</header>
						<div class="panel-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Title: </label>
								<div class="col-sm-8">
									<input type="text" name="name" placeholder="Title" class="form-control">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">Description: </label>
								<div class="col-sm-8">
									<textarea class="form-control" name="description"
											  placeholder="Description"></textarea>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">Terms and Conditions: </label>
								<div class="col-sm-8">
									<input type="text" name="term_and_condition"
										   placeholder="Terms and Conditions" class="form-control">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">Short Description: </label>
								<div class="col-sm-8">
									<textarea class="form-control" name="short_description"
											  placeholder="Short Description"></textarea>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">Menu: </label>
								<div class="col-sm-8">
									<textarea class="form-control" name="menu"
											  placeholder="Menu"></textarea>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">
									Restaurant: </label>
								<div class="col-sm-8">
									<select multiple data-plugin-selectTwo class="form-control
									restaurant">
										<optgroup label="Alaskan/Hawaiian Time Zone">
											<option value="AK">Alaska</option>
										</optgroup>
										<optgroup label="Pacific Time Zone">
											<option value="CA">California</option>
										</optgroup>
										<optgroup label="Mountain Time Zone">
											<option value="AZ">Arizona</option>
										</optgroup>
										<optgroup label="Central Time Zone">
											<option value="AL">Alabama</option>
										</optgroup>
										<optgroup label="Eastern Time Zone">
											<option value="CT">Connecticut</option>
										</optgroup>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">
									Restaurant Franchises: </label>
								<div class="col-sm-8">
									<select multiple data-plugin-selectTwo class="form-control
									restaurant-franchise">
										<optgroup label="Alaskan/Hawaiian Time Zone">
											<option value="AK">Alaska</option>
										</optgroup>
										<optgroup label="Pacific Time Zone">
											<option value="CA">California</option>
										</optgroup>
										<optgroup label="Mountain Time Zone">
											<option value="AZ">Arizona</option>
										</optgroup>
										<optgroup label="Central Time Zone">
											<option value="AL">Alabama</option>
										</optgroup>
										<optgroup label="Eastern Time Zone">
											<option value="CT">Connecticut</option>
										</optgroup>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-4 control-label">Schedules</label>
								<div class="col-md-8">
									<div class="input-daterange input-group" data-plugin-datepicker>
										<span class="input-group-addon">
											<i class="fa fa-calendar"></i>
										</span>
										<input type="text" class="form-control" name="start">
										<span class="input-group-addon">to</span>
										<input type="text" class="form-control" name="end">
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-4 control-label">Total Available Slots</label>
								<div class="col-md-8">
									<div class="input-group">
										<span class="input-group-addon">
											<i class="fa fa-calendar"></i>
										</span>
										<input type="text" data-plugin-datepicker
											   data-plugin-options='{ "multidate": true }' class="form-control">
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">SEO Meta Tags: </label>
								<div class="col-sm-8">
									<input type="text" name="seo_meta_tag" class="form-control">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">Images: </label>
								<div class="col-sm-8">
									<input type="text" name="images" class="form-control">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">Addons: </label>
								<div class="col-sm-8">
									<input type="text" name="adons" class="form-control">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">Pricing: </label>
								<div class="col-sm-8">
									<input type="text" name="pricing" class="form-control">
								</div>
							</div>
						</div>
						<footer class="panel-footer">
							<button class="btn btn-primary">Submit </button>
							<button type="reset" class="btn btn-default">Reset</button>
						</footer>
					</section>
				</form>
			</div>
		</div>
	</section>
	<!-- end: page -->
@stop