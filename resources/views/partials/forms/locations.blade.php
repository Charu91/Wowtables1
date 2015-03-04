<div class="row">
    <div class="col-lg-6">
        <section class="panel">
            <div class="panel-body">
                <div class="form-group">
                    {!! Form::label('address','Address',['class'=>'col-sm-2 control-label']) !!}
                    <div class="col-sm-10">
                        {!! Form::textarea('address',null,['rows'=>'3','class'=>'form-control populate','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('city','City',['class'=>'col-sm-2 control-label']) !!}
                    <div class="col-sm-10">
                        {!! Form::select('city',[''=>'','1'=>'First'],null,['class'=>'form-control populate','data-plugin-selectTwo'=>'','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('state','State',['class'=>'col-sm-2 control-label']) !!}
                    <div class="col-sm-10">
                        {!! Form::select('state',[''=>'','1'=>'First'],null,['class'=>'form-control populate','data-plugin-selectTwo'=>'','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('country','Country',['class'=>'col-sm-2 control-label']) !!}
                    <div class="col-sm-10">
                        {!! Form::select('country',[''=>'','1'=>'First'],null,['class'=>'form-control populate','data-plugin-selectTwo'=>'','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('pin_code','Pin Code',['class'=>'col-sm-2 control-label']) !!}
                    <div class="col-sm-10">
                        {!! Form::select('pin_code',[''=>'','1'=>'First'],null,['class'=>'form-control populate','data-plugin-selectTwo'=>'','required'=>'']) !!}
                    </div>
                </div>
            </div>
        <section>
    </div>
    <div class="col-lg-6">
        <section class="panel">
            <div class="panel-body">
                <div class="form-group">
                    <div class="col-sm-12">
                        <div class="input-group mb-md">
                            <input type="text" class="form-control" id="location_search_val" placeholder="Search for Location">
                            <span class="input-group-btn">
                                <button id="location_search" class="btn btn-danger">Go!</button>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('latitude','Latitude',['class'=>'col-sm-2 control-label']) !!}
                    <div class="col-sm-10">
                        {!! Form::text('latitude',null,['class'=>'form-control populate latLong','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('longitude','Longitude',['class'=>'col-sm-2 control-label']) !!}
                    <div class="col-sm-10">
                        {!! Form::text('longitude',null,['class'=>'form-control populate latLong','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('driving_locations','Driving Locations',['class'=>'col-sm-2 control-label']) !!}
                    <div class="col-sm-10">
                        {!! Form::textarea('driving_locations',null,['rows'=>'5','class'=>'form-control','id'=>'driving_locations','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('location_map','Related Locations',['class'=>'col-sm-2 control-label']) !!}
                    <div class="col-sm-10">
                        {!! Form::select('location_map[]',[''=>''],null,['class'=>'form-control','rows'=>'3','multiple'=>'','data-role'=>'tagsinput','data-tag-class'=>'label label-primary','required'=>'']) !!}
                    </div>
                </div>
                <div class="panel-footer">
                    <div id="gmap-basic-marker" style="height: 200px; width: 100%;"></div>
                </div>
            </div>
        </section>
    </div>
</div>