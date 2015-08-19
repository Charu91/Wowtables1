@extends('frontend.templates.details_pages')

@section('content')

<div class="container cms-page">
  <div class="sitemap_separator">&nbsp;</div>
  <div id="breadcrumb">
    <ol class="breadcrumb">
      <li><a href="/pages/careers" title="Careers">Careers</a></li>
      <li>{!! $career->job_title !!}</li>
    </ol>
  </div>
  <p class="lead intro-tag">
      {!! $career->job_title !!} - {!! $career->location !!}
  </p>
  <div class="row">
      <div class="col-md-12 entry-content">
				<div class="row" style="margin-top:10px;">
					<div class="col-md-4 col-sm-8"><h4>Job Description</h4></div>
					<div class="col-md-9 col-sm-8">
						<p>{!! $career->job_desc !!}</p>
					</div>
				  </div>
				  <div class="row">
					<div class="col-md-4 col-sm-8"><h4>Qualification</h4></div>
					<div class="col-md-9 col-sm-8">
						<p>{!! $career->job_qualification !!}</p>
					</div>
				  </div>
          <section class="panel col-lg-8 col-lg-offset-2">
            <header class="panel-heading">
                <h2 class="panel-title">Apply for {!! $career->job_title !!}</h2>
            </header>


          {!! Form::open(['url'=>'/pages/careers/apply/send_details','files' => true]) !!}
		<div class="panel-body">
            <div class="form-group">
                {!! Form::label("name","Name*",['class'=>'col-sm-4 control-label']) !!}
                <div class="col-sm-8">
                    {!! Form::text('name',null,['class'=>'form-control','required'=>'']) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label("email","Email*",['class'=>'col-sm-4 control-label']) !!}
                <div class="col-sm-8">
                    {!! Form::text('email',null,['class'=>'form-control','required'=>'']) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label("phone_no","Phone No*",['class'=>'col-sm-4 control-label']) !!}
                <div class="col-sm-8">
                    {!! Form::text('phone_no',null,['class'=>'form-control','required'=>'']) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label("cover_letter","Cover Letter (Optional)",['class'=>'col-sm-4 control-label']) !!}
                <div class="col-sm-8">
                    {!! Form::textarea('cover_letter',null,['class'=>'form-control']) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label("salary","Current CTC (Optional)",['class'=>'col-sm-4 control-label']) !!}
                <div class="col-sm-8">
                    {!! Form::text('salary',null,['class'=>'form-control']) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label("resume","Upload Resume*",['class'=>'col-sm-4 control-label']) !!}
                <div class="col-sm-8">
                    {!! Form::file('resume',null,['class'=>'form-control','required'=>'']) !!}
                </div>
                <div class="col-sm-8">
                  <p><span style="color:red">Note: Only .doc,.docx,.txt,.pdf allowed</span></p>
                </div>
            </div>
            <input type="hidden" id="job_role" name="job_role" value="{!! $career->job_title !!}">
          </div>
          <footer class="panel-footer">
              {!! Form::submit('Submit Application',['class'=>'btn btn-primary']) !!}
          </footer>
						<!--<table>
							<tr>
								<td><span style="color:red;">*</span>Name:</td>
								<td><input type="text" name="username" value="" required/></td>
							</tr>
							<tr>
								<td><span style="color:red;">*</span>Email:</td>
								<td><input type="text" name="email" id="check_email" value="" required/><span class="email_error" style="color:red;display:none;">Please enter a valid email address</span></td>
							</tr>
							<tr>
								<td><span style="color:red;">*</span>Phone Number:</td>
								<td><input type="text" name="phone" id="check_phone" value="" required/><span class="phone_error" style="color:red;display:none;">Only allows (0-9,+,-)</span></td>
							</tr>
							<tr>
								<td>Cover Letter (Optional):</td>
								<td><textarea name="cover_letter"></textarea></td>
							</tr>
							<tr>
								<td><span style="color:red;">*</span>Upload Resume:</td>
								<td><input type="file" name="resume" id="resume" required/><span style="color:red">Note: Only .doc,.docx,.txt,.pdf allowed</span></td>
							</tr>
							<tr>
								<td><span style="color:red;">*</span>Current CTC:</td>
								<td><input type="text" name="salary" value="" required/></td>
							</tr>
							<input type="hidden" name="job_role" value="{!! $career->job_title !!}" />
							<tr><td colspan=2><center><input class="btn btn-warning" type="submit" name="submit" id="send_info" value="Send"></center></td></tr>
						</table>-->
					</form>
          </section>
			</div>
    </div>
</div>


@endsection
