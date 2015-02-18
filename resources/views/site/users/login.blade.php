@extends('site.templates.static_pages')


@section('content')

    <section class="body-sign">
        <div style="padding-top: 0;" class="center-sign">
            <a class="logo pull-left" href="/">
                <img height="54" alt="Porto Admin" src="{!! asset('images/logo.png') !!}">
            </a>
            <div class="panel panel-sign">
                <div class="panel-title-sign mt-xl text-right">
                    <h2 class="title text-uppercase text-bold m-none"><i class="fa fa-user mr-xs"></i>  Log in To WowTables!!! </h2>
                </div>
                <div class="panel-body">
                    {!! Form::open(['route'=>'login_path']) !!}
                        <div class="form-group mb-lg">
                            <label>Username</label>
                            <div class="input-group input-group-icon">
                                <input type="text" class="form-control input-lg" name="email">
									<span class="input-group-addon">
										<span class="icon icon-lg">
											<i class="fa fa-user"></i>
										</span>
									</span>
                            </div>
                        </div>

                        <div class="form-group mb-lg">
                            <div class="clearfix">
                                <label class="pull-left">Password</label>
                                <a class="pull-right" href="pages-recover-password.html">Lost Password?</a>
                            </div>
                            <div class="input-group input-group-icon">
                                <input type="password" class="form-control input-lg" name="password">
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
                                    <input type="checkbox" name="remember_me" id="RememberMe">
                                    <label for="RememberMe">Remember Me</label>
                                </div>
                            </div>
                            <div class="col-sm-4 text-right">
                                <button class="btn btn-primary hidden-xs" type="submit">Sign In</button>
                                <button class="btn btn-primary btn-block btn-lg visible-xs mt-lg" type="submit">Sign In</button>
                            </div>
                        </div>

							<span class="mt-lg mb-lg line-thru text-center text-uppercase">
								<span>or</span>
							</span>

                        <div class="mb-xs text-center">
                            <a class="btn btn-facebook mb-md ml-xs mr-xs">Connect with <i class="fa fa-facebook"></i></a>
                            <a class="btn btn-twitter mb-md ml-xs mr-xs">Connect with <i class="fa fa-twitter"></i></a>
                        </div>

                        <p class="text-center">Don't have an account yet? <a href="pages-signup.html">Sign Up!</a>

                        </p>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </section>

@endsection