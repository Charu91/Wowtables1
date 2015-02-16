@extends('templates.admin_layout')

@section('content')
    <header class="page-header">
        <h2>Experiences</h2>
        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="/admin/dashboard">
                        <i class="fa fa-home"></i>
                    </a>
                </li>
                <li><span>Experiences</span></li>
            </ol>
        </div>
    </header>

    <section class="panel panel-featured panel-featured-primary">
        <header class="panel-heading">
            <h2 class="panel-title">
                All Experiences
            </h2>
        </header>
        <div class="panel-body">
            <table class="table table-striped table-responsive mb-none" id="experiencesTable">
                <thead>
                <tr>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </section>

@stop