<div class="form-group">
    <div class="col-sm-9 col-sm-offset-3">
        <div class="checkbox-custom checkbox-primary">
            <input type="checkbox" name="attributes[allow_gift_card_redemptions]" id="attributes[allow_gift_card_redemptions]" value="1" checked="">
            <label  for="attributes[allow_gift_card_redemptions]">Allow Gift Card Redemptions <span class="required">*</span></label>
        </div>
    </div>
</div>
<div class="form-group">
    <label for="attributes[commission_per_cover]" class="col-sm-3 control-label">Commission per Cover <span class="required">*</span></label>
    <div class="col-sm-6">
        {!! Form::text('attributes[commission_per_cover]',null,['class'=>'form-control','required'=>'']) !!}
    </div>
</div>
<div class="form-group">
    <label for="attributes[reward_points_per_reservation]" class="col-sm-3 control-label">Reward Points per Reservation <span class="required">*</span></label>
    <div class="col-sm-6">
        {!! Form::text('attributes[reward_points_per_reservation]',null,['class'=>'form-control','required'=>'']) !!}
    </div>
</div>
