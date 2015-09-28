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
        <h2>Invoices List</h2>
        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="/admin/dashboard">
                        <i class="fa fa-home"></i>
                    </a>
                </li>
                <li><span>Invoices List</span></li>
            </ol>
        </div>
    </header>
@stop
