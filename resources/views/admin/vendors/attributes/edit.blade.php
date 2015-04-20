<section class="panel">
    <header class="panel-heading">
        <h2 class="panel-title">Edit Vendor Attribute</h2>
    </header>
    {!! Form::model($attribute,['route'=>['admin.restaurant.attributes.update',$attribute->id],'method'=>'PUT']) !!}

    <div class="panel-body">
        @include('admin.vendors.partials.create_vendor_attributes')
    </div>
    <footer class="panel-footer">
        {!! Form::submit('Update Attribute',['class'=>'btn btn-primary']) !!}
        <a class="btn btn-primary" id="cancelVendorAttributeEditBtn">Cancel</a>
    </footer>
    {!! Form::close() !!}
</section>