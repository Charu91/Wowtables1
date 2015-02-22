<section class="panel">
    <header class="panel-heading">
        <h2 class="panel-title">Edit User Attribute</h2>
    </header>
    {!! Form::model($attribute,['route'=>['admin.user.attributes.update',$attribute->id],'method'=>'PUT']) !!}

    <div class="panel-body">
        @include('admin.users.partials.create_user_attributes')
    </div>
    <footer class="panel-footer">
        {!! Form::submit('Update Attribute',['class'=>'btn btn-primary']) !!}
        <a class="btn btn-primary" id="cancelUserAttributeEditBtn">Cancel</a>
    </footer>
    {!! Form::close() !!}
</section>