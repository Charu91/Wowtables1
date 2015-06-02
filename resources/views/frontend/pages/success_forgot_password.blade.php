@extends('frontend.templates.inner_pages')

@section('content')

<!--==============Content Section=================-->
<div class="container">
    <div class="row">

        <div class="col-lg-8">
            <h3>Your password has been changed successfully. Redirecting you to login page ....
            </h3>
        </div>

        <!--Content closed-->
    </div>
    <div style="height:270px"></div>
</div>
<script language="javascript">
    //       alert("registration");
    //         var t=setTimeout(function(){alert("Hello")},3000);
    setTimeout('go_register()',3000);
    function go_register(){
        //     alert("registration");

        window.location='../registration';
    }
</script>
<!--==============Content Section closed=================-->
@endsection