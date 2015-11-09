@extends('templates.admin_layout')

@section('content')
    <style type="text/css">
        .scrollable-menu {
            height: auto;
            max-height: 270px;
            overflow-x: hidden;
        }
    </style>
    <header class="page-header">
        <h2>Create Invoices</h2>
        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="/admin/dashboard">
                        <i class="fa fa-home"></i>
                    </a>
                </li>
                <li><span>Create Invoices</span></li>
            </ol>
        </div>
    </header>
    <div class="row">
        {!! Form::open(['route'=>'GenerateInvoices','class'=>'form-horizontal','novalidate'=>'novalidate']) !!}
        {!! Form::label("from_date","From Date",['class'=>'col-sm-2 control-label']) !!}
        <div class="col-lg-2">

            <div class="input-group">
                <span class="input-group-addon">
											<i class="fa fa-calendar"></i>
										</span>
                <input type="text" name="from_date" id="from_date" data-plugin-datepicker
                       data-plugin-options='{ "multidate": true }' class="form-control">
            </div><!-- /input-group -->
        </div><!-- /.col-lg-6 -->
        {!! Form::label('to_date','To Date',['class'=>'col-sm-2 control-label']) !!}
        <div class="col-lg-2">
            <div class="input-group">
            <span class="input-group-addon">
											<i class="fa fa-calendar"></i>
										</span>
                <input type="text" name="to_date" id="to_date" data-plugin-datepicker
                       data-plugin-options='{ "multidate": true }' class="form-control">
            </div><!-- /input-group -->
        </div><!-- /.col-lg-6 -->
        {!! Form::submit('Generate',['class'=>'btn btn-primary']) !!}
        {!! Form::close() !!}
    </div>
@stop
