<!doctype html>
<html class="fixed">
	<head>

		<!-- Basic -->
		<meta charset="UTF-8">

		<meta name="keywords" content="HTML5 Admin Template" />
		<meta name="description" content="Porto Admin - Responsive HTML5 Template">
		<meta name="author" content="okler.net">

        <meta name="_token" content="{{$_token}}" />

		<!-- Mobile Metas -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

		<!-- Web Fonts  -->
		<link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800|Shadows+Into+Light" rel="stylesheet" type="text/css">

		<!-- Vendor CSS -->
		<link rel="stylesheet" href="/vendor/bootstrap/css/bootstrap.css" />
		<link rel="stylesheet" href="/vendor/font-awesome/css/font-awesome.css" />
		<link rel="stylesheet" href="/vendor/magnific-popup/magnific-popup.css" />
		<link rel="stylesheet" href="/vendor/bootstrap-datepicker/css/datepicker3.css" />

		<!-- Theme CSS -->
		<link rel="stylesheet" href="/stylesheets/theme.css" />

		<!-- Skin CSS -->
		<link rel="stylesheet" href="/stylesheets/skins/default.css" />

		<!-- Theme Custom CSS -->
		<link rel="stylesheet" href="/stylesheets/theme-custom.css">

		<!-- Head Libs -->
		<script src="/vendor/modernizr/modernizr.js"></script>

	</head>
	<body>
		<!-- start: page -->
		<section class="body-sign">
			<div class="center-sign">
				<a href="/" class="logo pull-left">
					<img src="/images/logo.png" height="54" alt="Porto Admin" />
				</a>

				<div class="panel panel-sign">
					<div class="panel-title-sign mt-xl text-right">
						<h2 class="title text-uppercase text-bold m-none"><i class="fa fa-user mr-xs"></i> Sign In</h2>
					</div>
					<div class="panel-body">
						<form action="" method="post" id="adminSigninForm">
							<div class="form-group mb-lg">
								<label>Email Address</label>
								<div class="input-group input-group-icon">
									<input id="signinEmail" name="email" type="email" class="form-control input-lg" required />
									<span class="input-group-addon">
										<span class="icon icon-lg">
											<i class="fa fa-envelope"></i>
										</span>
									</span>
								</div>
							</div>

							<div class="form-group mb-lg">
								<div class="clearfix">
									<label class="pull-left">Password</label>
								</div>
								<div class="input-group input-group-icon">
									<input id="signinPassword" name="password" type="password" minlength="6" maxlength="15" class="form-control input-lg" required />
									<span class="input-group-addon">
										<span class="icon icon-lg">
											<i class="fa fa-lock"></i>
										</span>
									</span>
								</div>
							</div>

							<div class="row">
								<div class="col-sm-8">
									<div class="checkbox-custom checkbox-default">
										<input id="rememberMe" name="rememberme" type="checkbox"/>
										<label for="rememberMe">Remember Me</label>
									</div>
								</div>
								<div class="col-sm-4 text-right">
									<button type="submit" class="btn btn-primary hidden-xs">Sign In</button>
									<button type="submit" class="btn btn-primary btn-block btn-lg visible-xs mt-lg">Sign In</button>
								</div>
							</div>
						</form>
					</div>
				</div>
                <div id="signinPageError" class="modal-block modal-header-color modal-block-danger mfp-hide">
                    <section class="panel">
                        <header class="panel-heading">
                            <h2 class="panel-title">Houston, We have a Problem!</h2>
                        </header>
                        <div class="panel-body">
                            <div class="modal-wrapper">
                                <div class="modal-icon">
                                    <i class="fa fa-times-circle"></i>
                                </div>
                                <div class="modal-text">
                                    <p></p>
                                </div>
                            </div>
                        </div>
                        <footer class="panel-footer">
                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <button class="btn btn-danger modal-dismiss">OK</button>
                                </div>
                            </div>
                        </footer>
                    </section>
                </div>
				<p class="text-center text-muted mt-md mb-md">&copy; Copyright 2014. All Rights Reserved.</p>
			</div>
		</section>
		<!-- end: page -->

		<!-- Vendor -->
		<script src="/vendor/jquery/jquery.js"></script>
		<script src="/vendor/jquery-browser-mobile/jquery.browser.mobile.js"></script>
		<script src="/vendor/bootstrap/js/bootstrap.js"></script>
		<script src="/vendor/nanoscroller/nanoscroller.js"></script>
		<script src="/vendor/jquery-placeholder/jquery.placeholder.js"></script>
		<script src="/vendor/jquery-validation/jquery.validate.min.js"></script>
		<script src="/vendor/magnific-popup/magnific-popup.js"></script>
		
		<!-- Theme Base, Components and Settings -->
		<script src="/javascripts/admin/theme.js"></script>
		
		<!-- Theme Initialization Files -->
		<script src="/javascripts/admin/theme.init.js"></script>
		<script src="/javascripts/admin/signin.js"></script>

	</body>
</html>