<div class="row">
    <div class="col-lg-6">
        <div class="panel">
            <div class="form-group">
                <label for="attributes[min_people_per_reservation]" class="col-sm-6 control-label">Minimum People Per Reservation <span class="required">*</span></label>
                <div class="col-sm-6">
                    {!! Form::text('attributes[min_people_per_reservation]',null,['class'=>'form-control','required'=>'']) !!}
                </div>
            </div>
            <div class="form-group">
                <label for="attributes[max_people_per_reservation]" class="col-sm-6 control-label">Maximum People Per Reservation <span class="required">*</span></label>
                <div class="col-sm-6">
                    {!! Form::text('attributes[max_people_per_reservation]',null,['class'=>'form-control','required'=>'']) !!}
                </div>
            </div>
            <div class="form-group">
                <label for="attributes[max_reservations_per_time_slot]" class="col-sm-6 control-label">Maximum Reservation Per Time Slot <span class="required">*</span></label>
                <div class="col-sm-6">
                    {!! Form::text('attributes[max_reservations_per_time_slot]',null,['class'=>'form-control','required'=>'']) !!}
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="panel">
            <div class="form-group">
                <label for="attributes[max_reservations_per_day]" class="col-sm-6 control-label">Maximum Reservation Per Day <span class="required">*</span></label>
                <div class="col-sm-6">
                    {!! Form::text('attributes[max_reservations_per_day]',null,['class'=>'form-control','required'=>'']) !!}
                </div>
            </div>
            <div class="form-group">
                <label for="attributes[minimum_reservation_time_buffer]" class="col-sm-6 control-label">Minimum Reservation Time Buffer <span class="required">*</span></label>
                <div class="col-sm-6">
                    {!! Form::text('attributes[minimum_reservation_time_buffer]',null,['class'=>'form-control','required'=>'']) !!}
                </div>
            </div>
            <div class="form-group">
                <label for="attributes[maximum_reservation_time_buffer]" class="col-sm-6 control-label">Maximum Reservation Time Buffer <span class="required">*</span></label>
                <div class="col-sm-6">
                    {!! Form::text('attributes[maximum_reservation_time_buffer]',null,['class'=>'form-control','required'=>'']) !!}
                </div>
            </div>
        </div>
    </div>
</div>