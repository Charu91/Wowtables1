<div class="row">
    <div class="col-lg-6">
        <div class="panel">
            <div class="form-group">
                {!! Form::label('attributes[min_people_per_reservation]','Minimum People / Reservation',['class'=>'col-sm-6 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::text('attributes[min_people_per_reservation]',null,['class'=>'form-control','required'=>'']) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('attributes[max_people_per_reservation]','Maximum People / Reservation',['class'=>'col-sm-6 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::text('attributes[max_people_per_reservation]',null,['class'=>'form-control','required'=>'']) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('attributes[max_reservations_per_time_slot]','Maximum Reservation / Time Slot',['class'=>'col-sm-6 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::text('attributes[max_reservations_per_time_slot]',null,['class'=>'form-control','required'=>'']) !!}
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="panel">
            <div class="form-group">
                {!! Form::label('attributes[max_reservations_per_day]','Maximum Reservation / Day',['class'=>'col-sm-6 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::text('attributes[max_reservations_per_day]',null,['class'=>'form-control','required'=>'']) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('attributes[minimum_reservation_time_buffer]','Minimum Reservation Time Buffer',['class'=>'col-sm-6 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::text('attributes[minimum_reservation_time_buffer]',null,['class'=>'form-control','required'=>'']) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('attributes[maximum_reservation_time_buffer]','Maximum Reservation Time Buffer',['class'=>'col-sm-6 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::text('attributes[maximum_reservation_time_buffer]',null,['class'=>'form-control','required'=>'']) !!}
                </div>
            </div>
        </div>
    </div>
</div>