@extends('templates.admin_layout')

@section('content')
    <header class="page-header">
        <h2>Locations</h2>
        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="/admin/dashboard">
                        <i class="fa fa-home"></i>
                    </a>
                </li>
                <li>
                    <a href="/admin/settings">
                        Settings
                    </a>
                </li>
                <li><span>Locations</span></li>
            </ol>
        </div>
    </header>

    <div class="row">
        <div class="col-sm-6">
            <section class="panel panel-featured panel-featured-primary">
                <header class="panel-heading">
                    <h2 class="panel-title">
                        All Locations
                    </h2>
                </header>
                <div class="panel-body">
                    <table class="table table-striped table-responsive mb-none" id="locationsTable">
                        <thead>
                            <tr>
                                <th>Location</th>
                                <th>Slug</th>
                                <th>Type</th>
                                <th>Parent Location</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </section>
        </div>
        <div class="col-sm-6">
            <form id="newLocationForm" method="post" action="#" class="form-horizontal form-bordered">
                <section class="panel panel-featured panel-featured-danger">
                    <header class="panel-heading">
                        <h2 class="panel-title">Add New Location</h2>
                    </header>
                    <div class="panel-body" id="loginFormBlock">
                        <div class="alert alert-success">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <strong>Well done!</strong> You are using a awesome template! <a href="" class="alert-link">Say Hi to Porto Admin</a>.
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Location Name:</label>
                            <div class="col-sm-9">
                                <input type="text" name="location_name" id="locationName" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Location Slug:</label>
                            <div class="col-sm-9">
                                <div class="input-group input-group-icon">
                                    <input type="text" name="location_slug" id="locationSlug" class="form-control" required>
                                    <span class="input-group-addon">
                                        <span class="icon">
                                            <i class="fa fa-spinner fa-spin"></i>
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Location Type:</label>
                            <div class="col-sm-9">
                                <select name="location_type" id="locationType" class="form-control" required>
                                    <option value="">Select</option>
                                    <option value="Country">Country</option>
                                    <option value="State">State</option>
                                    <option value="City">City</option>
                                    <option value="Area">Area</option>
                                    <option value="Locality">Locality</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Parent:</label>
                            <div class="col-sm-9">
                                <select name="location_parent" id="locationParent" disabled class="form-control"></select>
                            </div>
                        </div>
                    </div>
                    <footer class="panel-footer">
                        <button id="saveLocation" data-role="button" class="btn btn-danger" data-loading-text="Saving..." autocomplete="off">
                            Save
                        </button>
                        <button id="resetLocationForm" class="btn btn-success">
                            Reset
                        </button>
                    </footer>
                </section>
            </form>
        </div>
    </div>

@stop