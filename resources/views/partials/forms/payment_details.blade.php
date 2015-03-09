<div class="form-group">
    <div class="col-sm-9 col-sm-offset-3">
        <div class="checkbox-custom checkbox-primary">
            <input type="checkbox" name="attributes[allow_gift_card_redemptions]" id="attributes[allow_gift_card_redemptions]" value="1" checked="">
            <label  for="attributes[allow_gift_card_redemptions]">Allow Gift Card Redemptions</label>
        </div>
    </div>
</div>
<div class="form-group">
    {!! Form::label('attributes[commission_per_cover]','Commission per Cover',['class'=>'col-sm-3 control-label']) !!}
    <div class="col-sm-6">
        {!! Form::text('attributes[commission_per_cover]',null,['class'=>'form-control','required'=>'']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('prepayment','Pre-Payment Option',['class'=>'col-sm-3 control-label']) !!}
    <div class="col-sm-6">
        {!! Form::select('prepayment',[''=>'',true=>'Yes',false=>'No'],null,['class'=>'form-control populate','data-plugin-selectTwo'=>'','required'=>'']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('attributes[reward_points_per_reservation]','Reward Points per Reservation',['class'=>'col-sm-3 control-label']) !!}
    <div class="col-sm-6">
        {!! Form::text('attributes[reward_points_per_reservation]',null,['class'=>'form-control','required'=>'']) !!}
    </div>
</div>
