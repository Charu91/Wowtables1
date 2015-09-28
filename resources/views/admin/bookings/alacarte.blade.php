<div class="form-group">
    {!! Form::label("attributes[commission_per_cover]","Commission Per Cover",['class'=>'col-sm-3 control-label']) !!}
    <div class="col-sm-3">
        <input type="text" class="form-control" id="attributes[commission_per_cover]" name="attributes[commission_per_cover]" value="{!! $pricingDetails->commission !!}" readonly="readonly" />
    </div>
</div>
<br/>