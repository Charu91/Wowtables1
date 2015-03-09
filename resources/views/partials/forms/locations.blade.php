                <div class="form-group">
                    {!! Form::label('address[address]','Address',['class'=>'col-sm-3 control-label']) !!}
                    <div class="col-sm-6">
                        {!! Form::textarea('address[address]',null,['rows'=>'3','class'=>'form-control populate','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('address[pin_code]','Pin Code',['class'=>'col-sm-3 control-label']) !!}
                    <div class="col-sm-6">
                        {!! Form::text('address[pin_code]',null,['class'=>'form-control']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6 col-sm-offset-3">
                        <div class="input-group mb-md">
                            <input type="text" class="form-control" id="location_search_val" placeholder="Search Google for Location's Latitude & Longitude">
                            <span class="input-group-btn">
                                <button id="location_search" class="btn btn-danger">Go!</button>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('address[latitude]','Latitude',['class'=>'col-sm-3 control-label']) !!}
                    <div class="col-sm-6">
                        {!! Form::text('address[latitude]',null,['class'=>'form-control populate latLong','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('address[longitude]','Longitude',['class'=>'col-sm-3 control-label']) !!}
                    <div class="col-sm-6">
                        {!! Form::text('address[longitude]',null,['class'=>'form-control populate latLong','required'=>'']) !!}
                    </div>
                </div>
                <!--<div class="form-group">
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
                </div>-->
                <div class="panel-footer">
                    <div id="gmap-basic-marker" style="height: 200px; width: 100%;"></div>
                </div>
