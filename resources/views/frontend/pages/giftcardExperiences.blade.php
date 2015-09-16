@extends('frontend.templates.details_pages')

@section('content')

    <?php
    if(Session::has('id'))
        $set_user_id = Session::get('id');
    else
        $set_user_id = 0;
    ?>

    <?php

    /*print_r($arrData);
    foreach ($arrData['data'] as $key=>$data)
                    {
                      echo $data['cityname'];
                      echo '<br>';
                      };*/
    /*print_r($alaCartaArData);
    exit;*/
    ?>

    <!--==============Top Section closed=================-->
    <div class="container deal-listing variant featured-variant">
        <div class="row">

            <div class="clearfix"></div>

            <div class="col-md-12 listing-cover">

                <img src="/assets/img/gift_header.png" class="img-responsive">
            </div>

            <div class="col-md-12 listing-cover-desc">
                <div class="row">
                    <div class="col-md-4 col-sm-4"><?php //print_r($exclusiveExperiences);?>
                        <p class="lead intro-tag text-center">Gift Card Valid Experiences</p>
                    </div>
                    <div class="col-md-8 col-sm-8">
                        <p>Gift your loved ones an exclusive WowTables experience at some of the best fine dine restaurants in {{ucfirst($relatedExperiences['city_name'])}} and make their special occasion that much more special. Call our concierge to buy a WowTables Gift Card for any of the following experiences.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <select id="giftcardCities">
                    <?php foreach($cities as $city_id => $city){?>
                        <option <?php echo (($city_name == strtolower($city)) ? "selected" : "");?> value="<?php echo strtolower($city)?>"><?php echo $city?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="clearfix"></div>
            <ul class="experience-grid">
                <?php
                foreach ($relatedExperiences['gcExperiences'] as $key=>$data)
                {

                ?>

                <li class="col-md-4 col-sm-4">
                    <a href="{{URL::to('/')}}/{{$data['cityname']}}/experiences/{{$data['slug']}}">
                        <table class="deal-head deal-head-exp" id="table_head_exp">
                            <tr>
                                <td id="large_td"><?php //echo Config::get('media.base_s3_url_listing');?>
                                    <?php echo $data['productname']; ?></td>
                                <td id="small_td">
                                    <span>View</span> Details
                                </td>
                            </tr>
                        </table>
                    </a>
                    <div id="background_image_exp" style="background:url('https://s3-eu-west-1.amazonaws.com/wowtables/uploads/listing/{{$data['file']}}');padding-top: 2px;">
                        <div  class="book_mark1">
                        </div>
                        <?php
                        if(isset($data['flagname']) && $data['flagname'] !="") {?>
                        <div class="flag new valign" id="top_paddin"style="margin-top: 52px;background-color:{{$data['color']}}"><?php echo $data['flagname'];?></div>
                        <?php }?>

                    </div>
                    <div class="deal-desc" id="desc_tag_exp">
                        <p id="dummy_text">{{$data['short_description']}} </p>
                    </div>
                    <div class="white_background1_exp">
                        <div class="row">
                            <div class="col-sm-7 col-xs-7" id="bottom_padding_footer">
                                <div id="big_col">
					<span class="star-all">
						<?php if((isset($data['full_stars']) && $data['full_stars'] != "" && $data['full_stars'] != 0)) {?>
                        <?php for($c=0;$c<floor($data['full_stars']);$c++){ ?>
                        <span class="star-icon full star_icon_exper">&#9733;</span>
                        <?php }
                        }?>
                        <?php if((isset($data['half_stars']) && $data['half_stars'] != "" && $data['half_stars'] != 0)) {?>
                        <span class="star-icon half">&#9733;</span>
                        <?php } ?>
                        <?php if((isset($data['blank_stars']) && $data['blank_stars'] != "" && $data['blank_stars'] != 0)){?>
                        <?php for($c=1;$c<=$data['blank_stars'];$c++){?>
                        <span class="star-icon">&#9733;</span>
                        <?php }?>
                        <?php } ?>
					</span>
                                    <br />
                                    <span class="rating text-center"><?php echo (isset($data['total_reviews']) && $data['total_reviews'] > 0) ? "(".$data['total_reviews']." Reviews)" : "";?></span>

                                </div>
                            </div>
                            <div class="col-sm-5 col-xs-5">
                                <div id="small_col">
                                    <span style="font-size:18px;font-weight:bold;"> Rs {{$data['price']}}</span><br />
                                    <div class="rating_ala" id="rating_review rating_reviews">{{$data['type_name']}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <?php }?>
            </ul>
        </div>
    </div>
    <script type="text/javascript">
        $("#giftcardCities").change(function(){
           var v = $("#giftcardCities option:selected").val();
            //console.log(v);
            location.replace("{{URL::to('/')}}/"+v+"/giftcard-experiences");
        });
    </script>
    <!--==============close Experiences code=================-->

@endsection