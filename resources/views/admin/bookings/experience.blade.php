<div class="form-group">
    {!! Form::label("attributes[pre_tax_price]","Pre Tax Price",['class'=>'col-sm-3 control-label']) !!}
    <div class="col-sm-3">
        <input type="text" class="form-control" id="attributes[pre_tax_price]" name="attributes[pre_tax_price]" value="{!! $pricingDetails->pre_tax_price !!}" readonly="readonly" />
    </div>
    {!! Form::label("attributes[post_tax_price]","Post Tax Price",['class'=>'col-sm-3 control-label']) !!}
    <div class="col-sm-3">
        <input type="text" class="form-control" id="attributes[post_tax_price]" name="attributes[post_tax_price]" value="{!! $pricingDetails->post_tax_price !!}" readonly="readonly" />
    </div>
</div>
<div class="form-group">
    {!! Form::label("attributes[commission]","Commission",['class'=>'col-sm-3 control-label']) !!}
    <div class="col-sm-3">
        <input type="text" class="form-control" id="attributes[commission]" name="attributes[commission]" value="{!! $pricingDetails->commission !!}" readonly="readonly" />
    </div>
    {!! Form::label("attributes[commission_on]","Commission On",['class'=>'col-sm-3 control-label']) !!}
    <div class="col-sm-3">
        <input type="text" class="form-control" id="attributes[commission_on]" name="attributes[commission_on]" value="{!! $pricingDetails->commission_on !!}" readonly="readonly" />
    </div>
</div>
@if(!empty($pricingDetails->addon) && isset($pricingDetails->addon))
    @foreach($pricingDetails->addon as $addon)
        @include('admin.bookings.addon')
    @endforeach
@else
    <p><strong>No Addons</strong></p>
@endif
<br/>
