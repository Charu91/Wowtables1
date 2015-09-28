<h6><strong>{!! $addon->title !!}</strong></h6>
<div class="form-group">
    {!! Form::label("attributes[pre_tax_price_addon]","Pre Tax Price Addon",['class'=>'col-sm-3 control-label']) !!}
    <div class="col-sm-3">
        <input type="text" class="form-control" id="attributes[pre_tax_price_addon]" name="attributes[pre_tax_price_addon]" value="{!! $addon->pre_tax_price !!}" readonly="readonly" />
    </div>
    {!! Form::label("attributes[post_tax_price_addon]","Post Tax Price Addon",['class'=>'col-sm-3 control-label']) !!}
    <div class="col-sm-3">
        <input type="text" class="form-control" id="attributes[post_tax_price_addon]" name="attributes[post_tax_price_addon]" value="{!! $addon->post_tax_price !!}" readonly="readonly" />
    </div>
</div>
<div class="form-group">
    {!! Form::label("attributes[commission_addon]","Commission Addon",['class'=>'col-sm-3 control-label']) !!}
    <div class="col-sm-3">
        <input type="text" class="form-control" id="attributes[commission_addon]" name="attributes[commission_addon]" value="{!! $addon->commission !!}" readonly="readonly" />
    </div>
    {!! Form::label("attributes[commission_on_addon]","Commission On Addon",['class'=>'col-sm-3 control-label']) !!}
    <div class="col-sm-3">
        <input type="text" class="form-control" id="attributes[commission_on_addon]" name="attributes[commission_on_addon]" value="{!! $addon->commission_on !!}" readonly="readonly" />
    </div>
</div>