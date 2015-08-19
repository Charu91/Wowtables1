@extends('templates.admin_layout')

@section('content')
    <header class="page-header">
        <h2>Careers</h2>
        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="/admin/dashboard">
                        <i class="fa fa-home"></i>
                    </a>
                </li>
                <li><span>Careers</span></li>
            </ol>
        </div>
    </header>
    <section class="panel col-lg-8 col-lg-offset-2">
        <header class="panel-heading">
            <h2 class="panel-title">Edit</h2>
        </header>
        {!! Form::model($career,['route'=>['admin.careers.update',$career->id],'method'=>'PUT','novalidate'=>'novalidate']) !!}
        <div class="panel-body">
            <div class="form-group">
                {!! Form::label("job_tile","Job Tile",['class'=>'col-sm-4 control-label']) !!}
                <div class="col-sm-8">
                    {!! Form::text('job_title',$career->job_title,['class'=>'form-control','required'=>'']) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('job_location','Job Location',['class'=>'col-sm-4 control-label']) !!}
                <div class="col-sm-8">
                    {!! Form::text('job_location',$career->location,['class'=>'form-control','required'=>'']) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('job_description','Job Description',['class'=>'col-sm-4 control-label']) !!}
                <div class="col-sm-8">
                    {!! Form::textarea('job_description',$career->job_desc,['class'=>'form-control redactor-text','required'=>'']) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('job_qualification','Job Qualification',['class'=>'col-sm-4 control-label']) !!}
                <div class="col-sm-8">
                    {!! Form::textarea('job_qualification',$career->job_qualification,['class'=>'form-control redactor-text','required'=>'']) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('status','Show in Frontend',['class'=>'col-sm-4 control-label']) !!}
                <div class="col-sm-8">
                    {!! Form::checkbox('status',null,null,null) !!}
                </div>
            </div>
        </div>
        <footer class="panel-footer">
            {!! Form::submit('Update',['class'=>'btn btn-primary']) !!}
        </footer>
        {!! Form::close() !!}
    </section>
@stop