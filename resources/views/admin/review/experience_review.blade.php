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
	<a href="/admin/expreviewadd"><button class="btn btn-primary mb-lg btn-lg">Add Experience Review</button></a>
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
                      @foreach($experienceReviewDetails as $key =>$experienceReview)
                        <tr>
                            <td>{{$experienceReview['review_id']}}</td>
                            <td>{{$experienceReview['reservation_date']}}</td>
                            <td>{{$experienceReview['product_name']}} - {{$experienceReview['vendor_name']}} - {{$experienceReview['product_locality']}}</td>
                            <td>{{$experienceReview['guest_name']}} - {{$experienceReview['guest_email']}}</td>
                            <td>{{$experienceReview['rating']}}</td>
							<td>{{$experienceReview['review']}}</td>
                            <td>
                                <a data-experience-id="{!! $experienceReview['review_id'] !!}" href="<?php echo URL::to('/').'/expreview/edit/'.$experienceReview['review_id']; ?>" class="btn btn-xs btn-primary">Edit</a>
                                <a data-experience-id="{!! $experienceReview['review_id'] !!}" class="btn btn-xs btn-danger delete-experience">Delete</a></td>
                            <td>
                                <span id ="show_exp_review_{{$experienceReview['review']}}"><span>
                <input type ="checkbox" name="show[]" value="{{$experienceReview['review_id']}}" <?php if($experienceReview['review_status'] == 'Approved') { echo 'checked';} ?> onClick="experienceReview(this.value)">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

    <script type="text/javascript">
    function experienceReview(id)
    {
        var review_id = id;
        $.ajax({
                  url: "/admin/show_exp_review_update",
                  type: "post",
                  data: {
                      review_id :  review_id
                  },
                  beforeSend:function()
                        {
                        $("#show_exp_review_"+review_id).html('<img src="/images/loading.gif">');
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