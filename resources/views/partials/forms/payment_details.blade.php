<div class="form-group">
    {!! Form::label('commission_per_reservation','Commission / Booking',['class'=>'col-sm-3 control-label']) !!}
    <div class="col-sm-6">
        {!! Form::text('commision_per_reservation',null,['class'=>'form-control','required'=>'']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('prepayment','Prepaymet Option',['class'=>'col-sm-3 control-label']) !!}
    <div class="col-sm-6">
        {!! Form::select('prepayment',[''=>'',true=>'Yes',false=>'No'],null,['class'=>'form-control populate','data-plugin-selectTwo'=>'','required'=>'']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('reward_points_per_reservation','Reward Points / Reservation',['class'=>'col-sm-3 control-label']) !!}
    <div class="col-sm-6">
        {!! Form::text('reward_points_per_reservation',null,['class'=>'form-control','required'=>'']) !!}
    </div>
</div>
