@extends('templates.admin_layout')

@section('content')
    <header class="page-header">
        <h2>Experience Review</h2>
        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="/admin/dashboard">
                        <i class="fa fa-home"></i>
                    </a>
                </li>
                <li><span>Experience Review</span></li>
            </ol>
        </div>
    </header>
	<a href="/admin/alacreviewadd"><button class="btn btn-primary mb-lg btn-lg">Add Alacarte Review</button></a>
    <section class="panel panel-featured panel-featured-primary">
        <header class="panel-heading">
            <h2 class="panel-title">
                All Experience Review
            </h2>
        </header>
        <div class="panel-body">
            <table class="table table-striped table-responsive mb-none" id="restaurantsTable">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Date of seating</th>
                    <th>restaurant - location</th>
                    <th>Customer name,email</th>
                    <th>Rating</th>
                    <th>Review paragraph</th>
                    <th>Actions</th>
                    <th>Show review</th>

                </tr>
                </thead>
                <tbody>
                      @foreach($alacartReviewDetails as $key =>$alacartReview)
                        <tr>
                            <td>{{$alacartReview['review_id']}}</td>
                            <td>{{$alacartReview['reservation_date']}}</td>
                            <td>{{$alacartReview['vendor_name']}} - {{$alacartReview['vendor_address']}}</td>
                            <td>{{$alacartReview['guest_name']}} - {{$alacartReview['guest_email']}}</td>
                            <td>{{$alacartReview['rating']}}</td>
                            <td>{{$alacartReview['review']}}</td>
                            <td>
                                <a data-experience-id="{!! $alacartReview['review_id'] !!}" href="<?php echo URL::to('/').'/alacartereview/edit/'.$alacartReview['review_id']; ?>" class="btn btn-xs btn-primary">Edit</a>
                                <a data-experience-id="{!! $alacartReview['review_id'] !!}" class="btn btn-xs btn-danger delete-experience">Delete</a>
                            </td>
                            <td>
                                <span id ="show_alacart_review_{{$alacartReview['review']}}"><span>
                <input type ="checkbox" name="show[]" value="{{$alacartReview['review_id']}}" <?php if($alacartReview['review_status'] == 'Approved') { echo 'checked';} ?> onClick="alacartReview(this.value)">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

      <script type="text/javascript">
    function alacartReview(id)
    {
        var review_id = id;
        $.ajax({
                  url: "/admin/show_alacart_review_update",
                  type: "post",
                  data: {
                      review_id :  review_id
                  },
                  beforeSend:function()
                        {
                        $("#show_alacart_review_"+review_id).html('<img src="/images/loading.gif">');
                        },
                  success: function(e) {
                     //console.log(e);
                     if(e == 1)
                     {
                        location.reload();
                     }
                  }
               });
    }
    </script>

@stop