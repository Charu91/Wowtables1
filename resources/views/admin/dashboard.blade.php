@extends('templates.admin_layout')

@section('content')
	<header class="page-header">
        <h2>Dashboard</h2>
        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="#">
                        <i class="fa fa-home"></i>
                    </a>
                </li>
            </ol>

            <a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>
        </div>
    </header>
    <div class="panel-body">
        <div class="col-md-8">
            <a data-target="#markdownmodal" data-toggle="modal" class="btn btn-default">Mark Down Editor</a>
        </div>
    </div>
    <div class="panel-body">
        <div class="col-md-8">
            <button data-media-select="1" type="button" class="btn btn-primary media-modal-btn" >Single Select Media Modal</button>
            <button data-media-select="2" type="button" class="btn btn-primary media-modal-btn" >Multi Select Media Modal</button>

            <!--<a id="mediaModalBtn" class="mb-xs mt-xs mr-xs modal-with-zoom-anim btn btn-default" href="#mediamodal">Media Modal</a>--->
        </div>
    </div>



@stop
