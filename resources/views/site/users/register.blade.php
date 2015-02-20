@extends('site.templates.static_pages')


@section('content')


    <section class="body-sign">
        <div style="padding-top: 0;" class="center-sign">
            <a class="logo pull-left" href="/">
                <img height="54" alt="WowTables" src="{!! asset('images/logo.png') !!}">
            </a>
            <div class="panel panel-sign">
                <div class="panel-title-sign mt-xl text-right">
                    <h2 class="title text-uppercase text-bold m-none"><i class="fa fa-user mr-xs"></i>Sign up for WowTables!!! </h2>
                </div>
                <div class="panel-body">
                    @include('site.partials.errors')

                    {!! Form::open(['route'=>'register_path']) !!}
                        <div class="form-group mb-lg">
                            <label>Full Name</label>
                            <input type="text" class="form-control input-lg" name="full_name">
                        </div>

                        <div class="form-group mb-lg">
                            <label>E-mail Address</label>
                            <input type="email" class="form-control input-lg" name="email">
                        </div>

                        <div class="form-group mb-none">
                            <div class="row">
                                <div class="col-sm-6 mb-lg">
                                    <label>Password</label>
                                    <input type="password" class="form-control input-lg" name="password">
                                </div>
                                <div class="col-sm-6 mb-lg">
                                    <label>Password Confirmation</label>
                                    <input type="password" class="form-control input-lg" name="password_confirmation">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-8">
                                <div class="checkbox-custom checkbox-default">
                                    <input type="checkbox" name="terms_of_use" id="AgreeTerms">
                                    <label for="AgreeTerms">I agree with <a href="#">terms of use</a></label>
                                </div>
                            </div>
                            <div class="col-sm-4 text-right">
                                <button class="btn btn-primary hidden-xs" type="submit">Sign Up</button>
                                <button class="btn btn-primary btn-block btn-lg visible-xs mt-lg" type="submit">Sign Up</button>
                            </div>
                        </div>

							<span class="mt-lg mb-lg line-thru text-center text-uppercase">
								<span>or</span>
							</span>

                        <div class="mb-xs text-center">
                            <a class="btn btn-facebook mb-md ml-xs mr-xs">Connect with <i class="fa fa-facebook"></i></a>
                            <a class="btn btn-twitter mb-md ml-xs mr-xs">Connect with <i class="fa fa-twitter"></i></a>
                        </div>

                        <p class="text-center">Already have an account? {!! link_to_route('login_path','Sign In!!') !!}

                        </p>

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </section>

@endsection