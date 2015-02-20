<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            {!! Form::label('full_name','Full Name',['class'=>'control-label']) !!}
            {!! Form::text('full_name',null,['class'=>'form-control']) !!}
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
            {!! Form::select('role',$roles_list,$user->role->id,['class'=>'form-control', 'data-plugin-selectTwo'=>'']) !!}
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