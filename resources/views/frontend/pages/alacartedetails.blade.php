@extends('frontend.templates.details_pages')

@section('content')
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<div class="container deal-detail">

<style>
@media screen and (min-width: 0px) and (max-width: 600px) {
  #wowtables_alacarte_sugg {
    padding-top: 0px !important;
  }
}
@media screen and (min-width: 361px) and (max-width: 1980px) {
  #wowtables_alacarte_sugg {
    padding-top: 10px !important;
  }
}
</style>
   
    <div class="container deal-detail">
      <div class="row">
      <div class="col-md-8 col-sm-8 deal-detail-left"  itemscope itemtype="http://data-vocabulary.org/Review-aggregate">
                      <div id="breadcrumb">
                
                <ol class="breadcrumb">
                    <li><a href="/<?php echo $current_city;?>/alacarte"><?php echo ucfirst($current_city) ?></a></li>
                    <li><a href="/<?php echo $current_city ?>-all-<?php echo strtolower($arrALaCarte['data']['cuisine'][0]) ?>-alacarte-restaurants"><?php echo ucfirst($arrALaCarte['data']['cuisine'][0]) ?></a></li>
                    <li class="active"><?php echo ucfirst($arrALaCarte['data']['title']) ?></li>
                </ol>
            
            </div>
                     <div class="deal-top-box">
            <table id='gourmet_points' >
              <tr>
                <td class="deal-title">
                  <h2>
                    <span itemprop="itemreviewed">
                      <?php echo $arrALaCarte['data']['title'];?>, <span class="small-subarea"><?php echo $arrALaCarte['data']['location_address']['area'];?></span>
                    </span>
                    <span style="float: right;cursor: pointer;">
                      
                      <a data-page_loc="Email Tip Widget" data-target="#redirectAlacarteEmailModal" data-toggle="modal">
                        <img src="/assets/img/share-48.png" title="Email a Friend" />
                      </a>
                      
                    </span>
                  </h2>
                </td>           
              </tr>
           
              
            </table>

            <div id="deal-detail-carousel" class="carousel slide" data-ride="carousel">
              <!-- Wrapper for slides -->
              <div class="carousel-inner">
                
                <div class="item active">
                  <img  itemprop="photo" src="<?php echo isset($arrALaCarte['data']['image']['gallery'][0])?$arrALaCarte['data']['image']['gallery'][0]:'';?>" alt="deal1">
                </div>

                <?php $i=2;
                if(isset($arrALaCarte['data']['image']['gallery']) && is_array($arrALaCarte['data']['image']['gallery'])) 
                {
                  foreach($arrALaCarte['data']['image']['gallery'] as $key => $value) 
                  { 
                    ?>
                    <div class="item">
                        <img  itemprop="photo" src="<?php echo $value;?>" alt="<?php echo $value;?>" alt="deal<?=$i;?>">
                    </div>
                    <?php $i++;
                  } 
                }
                ?>
                                
              </div>
                <a class="left carousel-control" href="#deal-detail-carousel" data-slide="prev">
                    <img  src="{{URL::to('/')}}/images/arrow-left1.png" >
                </a>
                <a class="right carousel-control" href="#deal-detail-carousel" data-slide="next">
                    <img  src="{{URL::to('/')}}/images/arrow-right1.png" >
                </a>
                   
            </div>
            <div class="booking clearfix visible-xs">
        <?php echo $ptype = $arrALaCarte['data']['pricing'];?>
        <!--<div class="col-md-6 sm_price"><p class="lead"><?php echo $ptype;?> <span>Per Person</span></p></div>-->
        <!--<div class="col-md-6 mk_reserv"><a href="#ReservationBox" class="btn btn-warning">MAKE A RESERVATION</a></div>-->
        <div class="scrollingBox"><a href="#ReservationBox" class="btn btn-warning">MAKE A RESERVATION</a></div>
      </div>
          </div>
      <?php     //echo "<pre>"; print_r($review_data);?>

          <div class="deal-bottom-box">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs">
              <li class="active" id='alcart-detail-tab info_tab'><a href="#info" data-toggle="tab" id="details_menu_tab" class="alacata_detail_menu_tab">
                <span class="hidden-xs">Restaurant Info</span><span class="visible-xs">Details</span></a>
              </li>
              <li id='alcart-sugg-tab menu_tab' class="wowtables_alacarte_details"><a href="#menu" data-toggle="tab" id="wowtables_alacarte_sugg">Curator's Suggestions</a></li>

              <li class="review-tab alcart-review-tab" id="wowtable_alacrate_page"><a href="#review" data-toggle="tab" class="alcarta-review-tab-detal" id="review_tab">
               <?PHP 
               $average_rating = $arrALaCarte['data']['rating'];// number of full stars
               $average_rating_2=$average_rating-floor($average_rating); //number of half stars
               $average_rating_3=5-floor($average_rating); //number of white stars

               if($arrALaCarte['data']['review_count']){?>
                <span><span itemprop="votes"><?PHP echo $arrALaCarte['data']['review_count'];?></span>&nbsp;Reviews &nbsp;</span>
                 <span>(</span>

                  <span class="star-all hidden-xs">
                  <?PHP for($i=0;$i<floor($average_rating);$i++){?>
                          <span class="star-icon full large_star_icon list_star_icon">&#9733;</span>
                  <?PHP }?>
                  <?PHP if($average_rating_2>0){?>
                           <span class="star-icon half">&#9733;</span>
                  <?PHP }?>
                  <?PHP for($j=1;$j<=$average_rating_3;$j++){?>
                          <span class="star-icon">&#9733;</span>
                  <?PHP }?>
                  </span>
                  <span class="star-mob visible-xs">
                    <span itemprop="rating" itemscope itemtype="http://data-vocabulary.org/Rating">
                      <span itemprop="average"><?PHP echo $average_rating;?></span>/<span itemprop="best">5</span>
                    </span>                    
                  </span>
                    <span>)</span>  

                    <?PHP } else {?>
                <span class=""><span></span>Reviews</span><span></span>
                  <?PHP } ?>
                   <div class="clearfix"></div></a>
               </li>
            </ul>
            <input type="hidden" name="current_city" value="<?php echo $current_city;?>">

            <input type="hidden" name="cuisine" value="<?php echo $arrALaCarte['data']['cuisine'][0];?>">
            <input type="hidden" name="price" value="<?php echo $ptype;?>">
            <input type="hidden" name="areas" value="<?php echo $arrALaCarte['data']['location_address']['area'];?>">
            <input type="hidden" name="restaurant_name" value="<?php echo $arrALaCarte['data']['title'];?>">
            <!-- Tab panes -->
            <div class="tab-content">
              <div class="tab-pane fade in active" id="info">
               <p><p>
                <?php echo $arrALaCarte['data']['resturant_information']?></p>
              </p>
                <hr> 
                <div class="">
                                                            <?php echo $arrALaCarte['data']['location_address']['address_line'];?><br>
                                                    </div>   
                <div id="map-canvas" style="height:300px;margin: 5px;">
                  <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBsEnNgLLhw0AFS4JfwsE1d3oTOeaWcccU&amp;sensor=true"></script>
                      <script type="text/javascript">
                           var dealer_lat = '<?php echo $arrALaCarte["data"]["location_address"]["latitude"];?>';
                           var dealer_lng = '<?php echo$arrALaCarte["data"]["location_address"]["longitude"];?>';
                          function initialize() {
                            var  mylatlng =  new google.maps.LatLng(dealer_lat, dealer_lng);
                            var mapOptions = {
                              center: mylatlng,
                              zoom: 14
                            };
                            var map = new google.maps.Map(document.getElementById("map-canvas"),
                                mapOptions);
                                
                            var marker = new google.maps.Marker({
                              position: mylatlng,
                              map: map
                          });
                          }
                          google.maps.event.addDomListener(window, 'load', initialize);
                    </script>
                </div> 
              </div>
              <div class="tab-pane fade" id="menu">
          <?php if(isset($rows[0]['curators_picks']) && $rows[0]['curators_picks'] != ""){?>
                <div class="row" style="width:100%;padding:10px;border:1px solid black;margin-top:10px;margin-left:1px;background-color:#fefbf2;">
                  <div class="col-sm-9">
                    <strong>From Our Curator Panel</strong>
          <?php echo $rows[0]['curators_picks'];?>
                  </div>
                </div>
        <?php } ?>
        <div class="row">
          <div class="col-sm-8">
                    <?php if(isset($arrALaCarte['data']['menu_pick']) && $arrALaCarte['data']['menu_pick'] != ""){?>
          <div class="row" style="width:100%;padding:10px;border:1px solid black;margin-top:10px;margin-left:1px;background-color:#fefbf2;">
            <div class="col-sm-12">
            <strong>Menu Picks</strong>
            <?php echo $arrALaCarte['data']['menu_pick'];?>
            </div>
          </div>
          <?php } ?>
          <?php if(isset($arrALaCarte['data']['expert_tips']) && $arrALaCarte['data']['expert_tips'] != ""){?>
          <div class="row" style="width:100%;padding:10px;border:1px solid black;margin-top:10px;margin-left:1px;background-color:#fefbf2;">
            <div class="col-sm-12" style="padding-bottom: 10px;">
            <strong>Expert Tips</strong>
            <?php echo $arrALaCarte['data']['expert_tips'];?>
            </div>
      <a data-page_loc="Suggest Tip Widget" class="btn btn-warning" data-target="#redirecttipModal" data-toggle="modal"> Suggest Tip</a>
          </div>
          <?php } else {?>

      <?php //echo "<pre>"; print_r($this->session->all_userdata());
        if(Session::get('id') > 0) {?>
          <div class="row" style="width:100%;margin-top:10px;padding:10px;border:1px solid black;margin-left:1px;background-color: #fefbf2;">
            <a data-page_loc="Suggest Tip Widget" class="btn btn-warning" data-target="#redirecttipModal" data-toggle="modal"> Suggest Tip</a>  
          </div>
      <?php } 
      }
      ?>
          </div>
          <div class="col-sm-4">
          <?php if(isset($alacarte_related_tags[0]['a_type']) && $alacarte_related_tags[0]['a_type'] != ""){?>
          <div class="row" style="width:100%;padding:10px;border:1px solid black;margin-top:10px;margin-left:1px;background-color:#fefbf2;">
            <div class="col-sm-12">
            <strong>Tags</strong>
            <?php foreach($alacarte_related_tags as $tags){?>
                   <p><a target="_blank" href="{{URL::to('/')}}/alacarte/collection/<?php echo $tags['a_url'] ?>"><?php echo $tags['a_type'];?></a></p>
            <?php }?>
            </div>
          </div>
          <?php } ?>
          </div>
        </div>
              </div>
                  <!-- review start-->

              <div class="tab-pane fade" id="review">
                <div class="row">
                  <div class="review-all">                    
                    <div class="col-md-12">
                    <?PHP if($arrALaCarte['data']['review_count']){?>
                      <p><strong>The reviews below are from members that have personally tried this experience:</strong></p><?PHP }
                      else {?>
                        <p><strong>No reviews yet. Be the first one to make a reservation and review your experience!</strong></p>
                      <?PHP }?>
                    </div>
                  </div>
                               <!-- REVIEW START-->
                <ul class="comments" id="review_comments">               
                   <input type="hidden" id="ala_id" value="<?=$arrALaCarte['data']['id']?>">
      
                      <?PHP 
                      if(is_array($arrALaCarte['data']['review_detail']) && count($arrALaCarte['data']['review_detail'])>0)
                      foreach($arrALaCarte['data']['review_detail'] as $reviews): ?>
                    <li>
                      <div class="col-md-3">
                        <p class="lead name"><?php echo $reviews['name'];?></p>
                      </div>
                      <div class="col-md-9">
                        <div class="row star-info">
                          <div class="col-xs-8">
                            <ul class="list-inline">
                            <p><?php $reviews['rating'];
                              $avar = 5 - $reviews['rating'];
                            ?></p>
                           <?PHP          
                                    for($i=1; $i<=$reviews['rating'];$i++) {
                                      echo '<li><span class="glyphicon glyphicon-star"></span></li>';
                                   }
                                   for($j=0; $j<$avar; $j++){
                                    echo '<li class="inactive"><span class="glyphicon glyphicon-star"></span></li>';}
                           ?>                            
                            </ul>
                           </div>
              <p class="col-xs-4 text-right date"><?php echo date('F d, Y',strtotime($reviews['created_at']));?>
                          </p>
                        </div>
                        <p>
                        <div height='25px'></div>
                          <?PHP echo $reviews['review'];?>  
                        </p>
                      </div>
                    </li>
                      <?PHP endforeach;

                       ?>
                  </ul>
                   <?PHP 
                   if($arrALaCarte['data']['review_count']>4){?>
                  <div class="text-center more"><a href="#" class="btn btn-warning" id="view_more">View more reviews</a></div>                  
                <?PHP }?>
                </div>
              </div>
                <div><br /><br />
             <!-- review end-->
       <?php if(isset($related_experiences[0]['id']) && $related_experiences[0]['id'] != ""){?>
        <p class="col-md-12"><strong>WowTables experiences at this restaurant</strong></p>
       <?php }?>
             <div class="row">
        <?php 
   /* if(isset($arrALaCarte['data']['related_experiences']))
    {
      foreach($related_experiences as $rel_exp) { //echo "<pre>"; print_r($rows);?>
                <div class="col-sm-6 col-xs-12 col-md-6">
          <a href="<?php echo ($rel_exp['coming_soon']=='1')? 'javascript:void(0);':$base_url.$rel_exp['city'].'/experiences/'.$rel_exp['slug'];?>">
             <table class="deal-head" id="table_head">
              <tr>
                <td id="large_td">
                   <?php echo $rel_exp['exp_title'];?>
                </td>
                <td id="small_td">
                <?php if ($rel_exp['intval'] >=0 && $rel_exp['tickets_sold'] < $rel_exp['max_num_orders']  ) { 
                  if( $rel_exp['coming_soon']=='1'){ ?>
                  <span>Coming</span> Soon
                  <?php }else {?>
                  <span>View</span>details
                  <?php } //coming soon ends         
                } else { ?>
                  <span>Sold</span>Out
                <?php } ?> 
                </td>
              </tr>
             </table>
           </a>
      <div id="alacarte_details_page_small_size">
             <img data-original="<?php echo $base_url;?><?php echo $rel_exp['list_image'];?>" rel="<?php echo $base_url;?><?php echo $rel_exp['list_image'];?>" alt="" src="<?php echo $base_url;?><?php echo $rel_exp['list_image'];?>" class="img-responsive alacarte_details_page_small_size">
             <?php if(isset($rel_exp['flag_name']) && $rel_exp['flag_name'] != "") {?>
                      <div class="new_flag1" style="background:#<?php echo $rel_exp['color'];?>">
                          <?php echo $rel_exp['flag_name'];?>
                      </div>
            <?php } ?>
                      <!--<div  class="book_mark1">          
                      </div>-->
                   </div>
                   <div class="deal-desc" id="desc_tag">
                      <p id="dummy_text"><?php echo $rel_exp['exp_short_desc'];?></p>
                   </div>
                   <div class="white_background1">
                      <div class="row">
                        <div class="col-sm-7 col-xs-7">
                            <div id="big_col">
                            <span class="star-all">
              <?php if((isset($rel_exp['full_stars']) && $rel_exp['full_stars'] != "" && $rel_exp['full_stars'] != 0)) {?>
                <?php for($c=0;$c<$rel_exp['full_stars'];$c++){ ?>
                <span class="ala-star-icon full">&#9733;</span>
                <?php }
              }?>

              <?php if((isset($rel_exp['half_stars']) && $rel_exp['half_stars'] != "" && $rel_exp['half_stars'] != 0)) {?>
                <span class="ala-star-icon half">&#9733;</span>
              <?php } ?>

              <?php if((isset($rows['blank_stars']) && $rel_exp['blank_stars'] != "" && $rel_exp['blank_stars'] != 0)){?>
                <?php for($c=1;$c<=$rel_exp['blank_stars'];$c++){?>
                <span class="ala-star-icon">&#9733;</span>
                <?php }?>
              <?php } ?>
                            <!--<span class="ala-star-icon full">&#9733;</span>
                            <span class="ala-star-icon full">&#9733;</span>
                            <span class="ala-star-icon full">&#9733;</span>
                            <span class="ala-star-icon half">&#9733;</span>
                            <span class="ala-star-icon">&#9733;</span>-->
                            </span><br /><div class="rating_ala" id="rating_review"><?php echo (isset($rel_exp['review_count']) && $rel_exp['review_count'] > 0) ? "(".$rel_exp['review_count']." Reviews )" : "";?></div>
                          </div>
                        </div>
                        <div class="col-sm-5 col-xs-5">
                          <div id="small_col">
                            <span style="font-size:18px;font-weight:bold;"> Rs <?php echo number_format($rel_exp['price'],0);?></span><br />
                            <div class="rating_ala" id="rating_review rating_reviews">(<?php echo $rel_exp['price_type'];?>)</div>
                          </div>
                        </div>
                      </div>
                      
                     <!--  <div class="rewiew_statrs1">
                       
                        </div> -->
                   </div> 
               </div> 
         <?php } 
    }*/
    ?>
             </div>


            </div>
           </div>
           
          </div>
        </div>
        <div class="col-md-4 col-sm-4 deal-detail-right">
    
        <div class="widget reservation-box" id="ReservationBox">
             <h3 class="text-center">RESERVE A TABLE</h3>
             <form role='form' method="post" action="{{URL::to('/')}}/orders/restaurant_checkout" id="alacarte_booking_form">  
             <div class="panel-group reservation-accordian" id="ac_accordion">
             <div id="ac_reserv_table2">
        <input type="hidden" name="address" id='ac_locations2' value="<?php echo $arrALaCarte['data']['id'];?>">   
              <div class="panel panel-default">
                <div class="panel-heading <?php echo ($hasOrder) ? '' : 'active'?>">
                  <h4 class="panel-title">
                     <a href="javascript:" style="text-decoration: none;">
                      Select Party Size </a><a  href="javascript:" data-original-title="Select the total number of guests at the table. If a larger table size is needed, please contact the WowTables Concierge." data-placement="top" data-toggle="tooltip" class="btn tooltip1"><img src="{{URL::to('/')}}/images/question_icon_small_display.png"></a>
                      <select name="qty" id="ac_party_size2" class="pull-right space <?=($hasOrder)? 'hidden' : '';?>">
                            <option value="0">SELECT</option>
                            
                            <?php 
                            $exp_location_id = $arrALaCarte['data']['id'];                          
                            $min_num = ($reserveData[$exp_location_id]['min_people'])?$reserveData[$exp_location_id]['min_people']:0;
                            $max_num = ($reserveData[$exp_location_id]['max_people'])?$reserveData[$exp_location_id]['max_people']:0; 
                            if($max_num > 0)
                            {
                            for ($i = $min_num; $i <= $max_num;): ?>
                                <?php
                                    $selected = '';
                                    if ($hasOrder && $order['qty'] == $i)
                                        {
                                            $selected = 'selected';
                                        }
                                    $peop_name = ($i == 1) ? 'Person' : 'People';
                                ?>
                                <option value="<?php echo $i?>"<?php echo $selected;?>><?php echo $i?> <?php echo $peop_name ?></option>
                            <?php 
                            $i = $i+(int)$reserveData[$exp_location_id]['increment'];
                            endfor; 
                            }
                            ?>
                        </select>
                        <strong><a id="ac_party_edit2" href="javascript:" class="<?=($hasOrder)? '' : 'hidden';?>" style="text-decoration: none;float: right;font-size: 13px;color: #EAB703;"><span style="color: #fff;"><?=($hasOrder)? $order['qty'] : '';?></span> EDIT</a></strong>
                  </h4>
                </div>
             
              </div>
              <div class="panel panel-default">
                <div class="panel-heading" >
                  <h4 class="panel-title">
                    <a href="javascript:" style="text-decoration: none;">
                      Select Date
                    </a>
                     <strong><a id="ac_date_edit2" class="<?=($hasOrder) ?  : 'hidden'; ?>" data-toggle="collapse" data-parent="#ac_accordion" href="#ac_collapseTwo" style="text-decoration: none;float: right;font-size: 13px;color: #EAB703;"><span style="color: #fff;"><?=($hasOrder) ? $order['date'] : ''; ?></span> EDIT</a></strong>
                  </h4>
                </div>
                <div id="ac_collapseTwo" class="panel-collapse collapse">
                  <div class="panel-body">
                    <div class="input-append date" id='ac_dp1' data-date-format="dd-mm-yyyy">
                        <input type="hidden" value="<?php echo ($hasOrder) ? date('Y-n-d',strtotime($order['date'])) : ''; ?>" name="booking_date" id="ac_booking_date2">
                        <div class="options" style="margin: -10px;">
                            <div id="ac_choose_date2"></div>
                        </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="panel panel-default">
                <div class="panel-heading">
                  <h4 class="panel-title">
                    <a href="javascript:" style="text-decoration: none;">
                      Select Time 
                    </a>
                    <strong>
                      <a id="ac_time_edit2" class="<?=($hasOrder) ? '' : 'hidden';?>" data-toggle="collapse" data-parent="#ac_accordion" href="#ac_collapseThree" style="text-decoration: none;float: right;font-size: 13px;color: #EAB703;"><span style="color: #fff;"><?=($hasOrder) ? $order['time'] : ''; ?></span> EDIT</a></strong>
                  </h4>
                </div>
                <div id="ac_collapseThree" class="panel-collapse collapse">
                  <div class="panel-body">
                    <div id='alacarte_time'>
                        <?php if($hasOrder):?>
                        <div class="btn-group col-lg-10 pull-right ac_actives ">
                        <?php
                          $weeks = array('sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat');
                          $week_number = date('w',strtotime($order['date']));
                          $week = $weeks[$week_number];
                          if(isset($schedule)):
                if(isset($alacarte_schedule_times)):
                  $schedule = $alacarte_schedule_times;
                endif;
                                foreach($schedule[$week] as $time=>$hours):?>
                                  <label class="btn btn-warning btn-xs time_tab <?=(in_array($order['time'],$hours))? 'active':'';?>" id="ac_<?=$time?>" style="padding:2px"><?=strtoupper($time);?></label> 
                         <?php   
                                endforeach;
                          endif;
                        ?>
                        </div>
                        <div class="clearfix"></div>
                        <input type="hidden" name="booking_time" id="alacarte_booking_time" value="<?=$order['time']?>">
                        <?php endif;?>
                    </div>
                    <div id='alacarte_hours'>
                        <?php if($hasOrder):?>
                        <?php
                          if(isset($schedule)):
                if(isset($alacarte_schedule_times)):
                $schedule = $alacarte_schedule_times;
                endif;
                              foreach($schedule[$week] as $time=>$hours):
                              ?>
                               <div id="ac_<?=$time."_tab";?>"  class="<?=(in_array($order['time'],$hours))? '':'hidden';?>">
                               <?php foreach($hours as $hour):?>
                                    <div class="time col-lg-3 <?=($hour == $order['time'])? 'time_active' : '';?>" rel="<?=$hour;?>"><a href="javascript:"><?=$hour;?></a></div>
                               <?php endforeach;?>
                               </div>
                              <?php 
                              endforeach;
                          endif;
                        ?>
                        <?php endif;?>
                    </div>
                  </div>
                </div>
              </div>
              </div>
                <div class="panel panel-default hidden" id="ac_order_info2">
                <div id="alacarte_load_layer">
                <img src="/images/loading.gif">
                </div>
                <?php 
                $user_array = Session::all();
                $user_data = array();
                if (Session::has('logged_in'))
                {
                  $user_data=$user_array;
                }
                else{
                    $user_data['full_name']='Guest';
                }     

                ?>
                <div class="panel-heading">
                  <h4 class="panel-title">
                    <a href="javascript:" style="text-decoration: none; font-size: 13px;" id='ac_fullinfo2'>
                      info
                    </a>
                    <strong><a id="ac_info_edit2" href="javascript:" style="text-decoration: none;float: right;font-size: 13px;color: #EAB703;"><span style="color: #fff;"></span> EDIT</a></strong>
                  </h4>
                </div>
                <div id="ac_collapseFive" class="panel-collapse">
                  <div class="panel-body">
                  <p style="font-size: 12px; text-align: center;">RESERVATION INFORMATION</p> 
                   <div class="input-group">
                      <span class="input-group-addon " style="color: black;"><i class="glyphicon glyphicon-envelope"></i></span>
                      <input type="email" name="email" id="alacarte_email" class="form-control" placeholder="EMAIL" value="<?=(isset($user_data['email']))? $user_data['email'] :'';?>">
                    </div>  
                    <div class="reservation_errors" id="alacarte_error_email"></div> 
                    <div class="input-group">
                      <span class="input-group-addon " style="color: black;"><i class="glyphicon glyphicon-user"></i></span>
                      <input type="text" name="fullname" id="alacarte_fullname" class="form-control" placeholder="FULL NAME" value="<?=(isset($user_data['full_name']))? $user_data['full_name'] :'';?>">
                    </div> 
                    <div class="reservation_errors" id="alacarte_error_fullname"></div> 
                    <div class="input-group">
                      <span class="input-group-addon " style="color: black;"><i class="glyphicon glyphicon-earphone"></i></span>
                      <input type="text" name="phone" id="alacarte_phone" class="form-control" placeholder="MOBILE" value="<?=(isset($user_data['phone']))? $user_data['phone'] :'';?>">
                    </div>
                    <div class="reservation_errors" id="alacarte_error_phone"></div> 
                    <div class="input-group">
                      <span class="input-group-addon" style="color: black;"><i class="glyphicon glyphicon-plus"></i></span>
                      <input type="text" name="special" id="alacarte_special" class="form-control" placeholder="(Optional) Special Requests">
                    </div> 
                    <div class="reservation_errors"></div> 
                    <div class="text-center">
                        <button class="btn btn-warning btn-xs" type="button"  id="alacarte_make_reservation">MAKE A RESERVATION</button>
                    </div> 
                  </div>
                </div>
              </div>
            </div>
              <div class="text-center">
                <span class="hidden" id="alacarte_cant_select_table">To check for immediate availability, please call our concierge.</span> 
                <p class="hidden" id="alacarte_cant_do_reserv1">You have an existing reservation that conflicts with this one. To modify or cancel your existing reservation please click</p>
                <a class="btn btn-warning hidden" id="alacarte_brs_my_reserv" href="/users/myreservations">View My Existing Reservations</a>
                <p class="hidden" id="alacarte_cant_do_reserv2">If you have any queries please call our concierge desk.</p> 
                <div class="text-center select-all-data hidden" id="ac_select_all_data2">Please select all data</div>
                <a  class="btn btn-warning <?php //=($hasOrder)? '' : 'hidden';?>" <?=(!(Session::has('logged_in')) && isset($allow_guest) && $allow_guest == "Yes") ? 'data-target="#redirectloginModal" data-toggle="modal"':'';?> id='ac_select_table2_ala'>SELECT TABLE</a>
              </div>

        <p class="text-center" style="margin-top:-7px;">
          <a style="text-decoration:none;">
            <small><br><span style="color:#eab703;">(<?php echo $arrALaCarte['data']["reward_point"] ?> Gourmet Points)
              </span>
            </small>
          </a>
        </p>

                <input type="hidden" id="ac_slug2" value="<?php echo $arrALaCarte['data']['slug']; ?>">
                <input type="hidden" name="time" id="ac_fulltime2">
                <input type="hidden" name="amount" id="ac_amount2">
        <input type="hidden" name="restaurant_id" id="ac_restaurant_id2" value="<?=$arrALaCarte['data']['id']?>">
                <input type="hidden" name="city" value="<?php echo $current_city?>">
                 <input type="hidden" name="city_id" value="<?php echo $current_city_id;?>">
        <input type="hidden" name="alacarte_id" value="<?php echo $arrALaCarte['data']['id']?>">
        <input type="hidden" name="alacarte_reward_points" value="<?php echo $arrALaCarte['data']["reward_point"] ?>">
                <input type="hidden" name="send">
            </form>
          </div>
          
                    <div class="widget query-contact">
            <p>Got a question? <br> Call our Concierge at 09619551387</p>
          </div>
          <div class="widget terms-box">
            <p class="lead">TERMS</p>
            <ul>
        <li>WowTables Gift Cards are not valid for A la carte reservations</li>
        <?php echo $arrALaCarte['data']['terms_and_condition']?>
            </ul> 
          </div>
        </div>
      </div>
    </div>
   </div>
   <div class="modal fade" id="alacarteModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title text-center" id="myModalLabel">A la carte reservation</h4>
          </div>
          <div class="modal-body">
                <h4 class="panel-title" style="margin-bottom: 20px;">
                    A la carte reservations are currently not available. We will inform you as soon as this feature is ready.
                </h4>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
  <script type="text/javascript">
          function changeClass()
              {
                document.getElementById("menu_tab").classList.add('active');
                document.getElementById("info_tab").classList.remove('active');
                document.getElementById('menu').className = "tab-pane fade in active"; 
                document.getElementById('info').className = "tab-pane fade";
                
           }
  </script><!--==============Start Google Addwords=================-->
<!--==============End Google Addwords=================-->
   
<section class="related-experiences deal-detail">
      <div class="container">
        <div class="row">  

<p class="col-md-12"><strong>Other restaurants recommended for you</strong></p>
<ul>
    <?php $i=0;?>
  <?php 
  if(isset($arrALaCarte['data']['similar_option']) && is_array($arrALaCarte['data']['similar_option']) && count($arrALaCarte['data']['similar_option'])>0)
  {
  foreach($alacartes as $row){?>
    <li class="col-md-4 col-sm-4">
            
      <div class="deal-img">
        <img src="<?php echo base_url($row['list_image']);?>" alt="" class="img-responsive">
    <?php if(isset($row['flag_name']) && $row['flag_name'] != "") {?> 
      <div class="new_flag_listing" id="popular_listing" style="background:<?php echo "#".$row['color']?>">
        <?php echo $row['flag_name']?>
      </div>
    <?php }?>
        <!--<div class="bookmark_overlay">
          <div class="bookmark_plain" onclick="toggleClass(this)"></div>
        </div>-->
      </div>
          
          <div class="discountingclass" id="discount_class">
            <div class="col-xs-12 rest_name">
              <a style="color:black;cursor:pointer;" href="<?php echo base_url($row['city'].'/alacarte/'.$row['slug'])?>">
                <h3><?php echo $row['venue'];?></h3>
              </a>
            </div>
                     <!-- <div class="col-xs-12">
                    <div class="divider"></div>
                    </div>
                    -->
            <div class="col-xs-5 text-center">
                  <p><?php echo $row['cuisine'];?></p>
                </div>
                <div class="col-xs-2">
                  <span style="color:black;">&bull;</span>
                </div>               

                <div class="col-xs-5 text-center">
                  <?php if($row['price'] == 1){
              $price_tag = "<img src='/assets/img/ruppee_14.png' title='Low' />";
            } else if($row['price'] == 2){
              $price_tag = "<img src='/assets/img/ruppee14x2.png' title='Medium' />";
            } else if($row['price'] == 3){
              $price_tag = "<img src='/assets/img/ruppee14x3.png' title='High' />";
            }
        ?>
                  <p><?php echo $price_tag;?></p>                    
              </div>
            <div class="col-xs-12 location text-center">
              <p><?php echo $row['area'];?></p>
            </div>
            <?php if(isset($arrALaCarte['data']['review_count']) && $arrALaCarte['data']['review_count'] > 0) {?>
        <div class="col-xs-12 text-center" style="padding-bottom:10px;">
          <div>
          <span class="star-all">
            <?php for($c=0;$c<$row['full_stars'];$c++){ ?>
              <span class="ala-star-icon full">&#9733;</span>
            <?php } ?>
            <?php if(isset($row['half_stars']) && $row['half_stars'] == 1) {?>
              <span class="ala-star-icon half">&#9733;</span>
            <?php }?>
            <?php for($c=1;$c<=$row['blank_stars'];$c++){?>
              <span class="ala-star-icon">&#9733;</span>
            <?php } ?>
          </span>
            <div class="rating_ala" style="color:black;">(<?php echo $row['review_count']; ?> <?php echo (($row['review_count'] > 1) ? 'Reviews' : 'Review');?>)</div>
          </div>   
        </div>
      <?php } ?>
            <div><br></div>
                 
            <div class="col-xs-6 col-sm-12 col-md-6 text-center" id="reserve_table" style="padding-bottom:8px; width:100%;">
            <a href="{{URL::to('/')}}/<?php echo $current_city.'/alacarte/'.$arrALaCarte['data']['slug'];?>" class="btn btn-inverse">Reserve A Table</a>
            </div>
            <div class="clearfix"></div>
          </div>
          </li>
  <?php } 
  }
  ?>
  <?php $i++; ?>
    </ul>
<div class="clearfix"></div>
    
</div>
</div>
<div class="modal fade" id="redirecttipModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title text-center" id="myModalLabel">Suggest A Tip for <?php echo $arrALaCarte['data']['title'];?>, <?php echo $arrALaCarte['data']['location_address']['area'];?></h4>
    </div>
    <div class="modal-body">
    <!-- Nav tabs -->

    <!-- Tab panes -->
    <div class="tab-content">
      <div class="tab-pane fade in active">
      <div id="signinForm-wrap">
      
        <div class="form-group">
        <textarea placeholder="Enter your tip here" name="suggest_expert_tip" cols="60" rows="4" id="suggest_expert_tip"></textarea></td>
        <input type="hidden" name="user_id" id="user_id" value="<?php echo Session::get('id');?>" />
        <input type="hidden" name="user_name" id="user_name" value="<?php echo Session::get('username');?>" />
        <input type="hidden" name="user_email" id="user_email" value="<?php echo Session::get('email');?>"/>
        <input type="hidden" name="alacarte_exp_id" id="alacarte_exp_id" value="<?php echo $arrALaCarte['data']['id'];?>"/>
        </div>                      
        <label class="control-label error-code text-danger" id='email_status_message'></label>
        <center><input type="button" class="btn btn-warning" name="send_tip" value="Send" id="send_tip"/></center>
      </div>
      
      </div>
    </div>
    
    </div>
    </div>
  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
<!-- Modal for email alacarte starts here-->
  <!--Share Modal -->
    <div class="modal fade" id="redirectAlacarteEmailModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title text-center" id="myModalLabel">Share Special Offer</h4>
          </div>
          <div class="modal-body" style="min-height: 100px;">
          <div id="ala_email_form">
            <form>
              <div class="form-group">
                <label for="">Add Email Addresses</label>
                <textarea class="form-control" rows="3" id='ala_guest_emails'></textarea> 
                <div class="row">
                  <div class="col-xs-6"><small>seperate with commas (,)</small></div>
                </div>
                <div class="row">
                  <div class="col-xs-12 hidden" id="ala_error_email"><small class="error">Please enter a valid email.</small></div>
                  <div class="col-xs-12 hidden" id="ala_error_email_count"><small class="error">You cannot enter more than 10 email ids.</small></div>
                </div>
              </div>

              <div class="form-group">
                <textarea class="form-control" rows="3" placeholder="Enter a personal message here." id='ala_det_content'></textarea>
                <!--<div class="row">
                  <div class="col-xs-12 hidden" id="error_content"><small>Please enter your message.</small></div>
                </div>-->
                <div class="col-xs-12 reservation-msg">
                    <p>The email to your party will include your personal message above and link to this page and share this restaurant detail.</p>
                </div>                
              </div>
        <input type="hidden" name="email_alacarte_id" value="<?php echo $arrALaCarte['data']['id']?>">
        <input type="hidden" name="ala_user_email" value="<?php echo (Session::get('email') ? Session::get('email') : "null");?>">
              <button type="submit" class="btn btn-warning btn-block" id="share_ala_details">Share Details</button>
            </form>
            </div>
            <div id="ala_email_sent_confirmation" class="hidden">
                <div class="col-xs-12 reservation-msg">
                    <p>Your message has been sent</p>
                    <button type="button" class="btn btn-warning btn-block" id="close_ala_mail_sent" data-dismiss="modal" aria-hidden="true">Close This</button>
                </div>
            </div>
            </div>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
  

  <!-- Modal for email alacarte ends here-->
<style>
#redirecttipModal .modal-header {
    background: none repeat scroll 0 0 #000;
}
#redirecttipModal .modal-header h4 {
    color: #fff;
}
#alacarte_cant_do_reserv1, #alacarte_cant_do_reserv2, #ac_select_all_data2, #alacarte_cant_select_table{
    color: #fff;
    padding: 0 10px;
}
#redirectAlacarteEmailModal .modal-header {
    background: none repeat scroll 0 0 #000;
}
#redirectAlacarteEmailModal .modal-header h4{
    color: #fff;
}
#redirectAlacarteEmailModal .modal-content{
  margin-left: 16%;
    margin-top: 20%;
    width: 59% !important;  
}
#redirectAlacarteEmailModal .panel {
    background: none repeat scroll 0 0 transparent;
    border: medium none;
    margin-bottom: 0;
}
 /*
 body {
         margin: 0;
         padding: 0;
         font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
         font-size: 10pt;
         line-height: 150%;
         border-top: 8px solid #106870;
                 }*/
 
       .contentBox {
               max-width: 1000px;
               margin: 30px auto 0 auto;
       }
       .contentLeft {
               width: 670px;
               float: left;
               text-align: justify;
               z-index: 1;
       }
       .sidebarBox {
               width: 300px; /* Replace with your sidebar width */
           z-index: 1;
   }
   .scrollingBox {
           width: 300px; /* Replace with the same width as sidebar */
           background:none;
           z-index: 1;
           padding-left: 20% !important;
   }
</style>
</section> 
<script type="text/javascript">
       $(function() {
               var offsetPixels = 10; // change with your sidebar height

               $(window).scroll(function() {
                       if ($(window).scrollTop() > offsetPixels) {
                               $(".scrollingBox").css({
                                       "position": "fixed",
                                       "top": "92%"
                               });
                       } else {
                               $(".scrollingBox").css({
                                       "position": "relative",
                                       "top": "92%"
                               });
                       }
               });
       });  
</script> 
   <script type="text/javascript">
       $(document).ready(function(){
        //alert("here");
        var current_url = window.location.href;
        var check_menu = current_url.indexOf("#menu"); 
        //console.log("checkmenu = "+check_menu);
        if(check_menu > 0){
          $('html, body').animate({
            scrollTop: $('.deal-bottom-box').offset().top
          }, 'slow');
          $("#info_tab").removeClass("active");
          $("#menu_tab").addClass("active");
          $("#info").removeClass("in");
          $("#info").removeClass("active");
          $("#menu").addClass("in");
          $("#menu").addClass("active");
        }


          $("#send_tip").on("click",function(){
            var v = $("textarea#suggest_expert_tip").val();

            var uname = $("#user_name").val();
            var uemail = $("#user_email").val();
            var ala_id = $("#alacarte_exp_id").val();
            var u_id = $("#user_id").val();
            
            if(v.length > 0 ){
              //console.log("value == "+v);
              $.ajax({
                url: "{{URL::to('/')}}/alacarte/send_suggest_tip",
                dataType: "JSON",
                type: "post",
                data: {user_id:u_id, alacarte_id:ala_id, user_name:uname, user_email:uemail, tip:v},
                success: function(d) {
                  //console.log("response = "+d.email_send_status);
                  if(d.email_send_status == "success")
                    $("#email_status_message").html("Thank you for your suggestion. Our curators will review and add it to the list. 500 Gourmet Points have been awarded to you.").delay(9000).fadeOut(500);
                  else
                    $("#email_status_message").html("Something went wrong. Please try again");
                }
              });
              $("textarea#suggest_expert_tip").val('');
              
            }
          });

          loadDatePicker();
       });

        var disabledAllDays = <?php echo json_encode($block_dates);?>;
        var allschedule = <?php echo json_encode($schedule);  ?>;
        var reserveminmax = <?php echo json_encode($reserveData);  ?>;

        function disableAllTheseDays(date) {
            var m = date.getMonth(), d = date.getDate(), y = date.getFullYear(),mon="",day="";
            var location_id = $('#ac_locations2').val();
            var disabledDays = disabledAllDays[location_id];
            if(disabledDays != undefined)
            {
              for (i = 0; i < disabledDays.length; i++) {
                  m=m+1;
                  mon=m.toString();
                  if(mon.length <2){
                      m="0"+m;
                  }
                  day=d.toString();
                  if(day.length <2){
                      d="0"+d;
                  }
                  if ($.inArray( m + '-' + d + '-' + y, disabledDays) != -1) {
                      return [false];
                  }
              }
            }
            return [true];
        }

        

        function loadDatePicker() {
          //$("#ac_choose_date2").datepicker("destroy");
         
          $("#ac_choose_date2").datepicker({
             dateFormat: 'yy-m-dd',
             minDate: 'new Date()',
             beforeShowDay: disableAllTheseDays,
             onSelect: function(dateText, inst) 
             {
                    var d = $.datepicker.parseDate("yy-m-dd",  dateText);
                   
                    var datestrInNewFormat = $.datepicker.formatDate( "D", d).toLowerCase();
                    var txt = '<div class="btn-group col-lg-10 pull-right ac_actives ">';
                    var txt2 = '';
                    var g = 1;
                    var cur_date =  new Date('<?php echo date('d M Y H:i:s'); ?>');
                    month = parseInt(cur_date.getMonth());
                    month += 1;
                    c_date = cur_date.getFullYear() + '-' + ((month<10)?'0':'')+month +  '-'  + cur_date.getDate();
                    c_time = cur_date.getHours()+":"+((cur_date.getMinutes()<10)?'0':'')+cur_date.getMinutes()+':00';
                    
                    //console.log(c_date);
                    //console.log(dateText);
                    /*Time display container*/
                      var location_id = $('#ac_locations2').val();
                      var schedule = allschedule[location_id];
                      console.log(location_id);
                      if(schedule != undefined)
                      {
                        for(key_sch in schedule[datestrInNewFormat])
                        {   
                            
                            var obj_length = Object.keys(schedule[datestrInNewFormat]).length;
                            active_tab = (g == obj_length) ? 'active' : '' ;
                            active_blck = (g == obj_length) ? '' : 'hidden' ;  
                            txt+= '<label class="btn btn-warning btn-xs time_tab ' + active_tab + '" id="ac_'+key_sch.toLowerCase()+'">'+key_sch.toUpperCase()+'</label>';
                            txt2 +=    '<div id="ac_' + key_sch.toLowerCase() + '_tab"  class="'+active_blck+'">';
                            for(key_sch_time in schedule[datestrInNewFormat][key_sch])
                            {
                               if(c_date == dateText)
                               {
                                 if(String(c_time) < String(schedule[datestrInNewFormat][key_sch][key_sch_time])) {
                                   txt2 += '<div class="alacarte_time col-lg-3 col-xs-5" rel="' + schedule[datestrInNewFormat][key_sch][key_sch_time] + '"><a href="javascript:">' + schedule[datestrInNewFormat][key_sch][key_sch_time] + '</a></div>';
                                 } 
                               }  
                               else
                               {
                                 txt2 += '<div class="alacarte_time col-lg-3 col-xs-5" rel="' + schedule[datestrInNewFormat][key_sch][key_sch_time] + '"><a href="javascript:">' + schedule[datestrInNewFormat][key_sch][key_sch_time] + '</a></div>';
                               }                          
                                                       
                            }
                            txt2+= '</div>';    
                            g++;
                        }
                      }
                      /*Time display container*/


                    txt += '</div><div class="clearfix"></div>';
                    txt += '<input type="hidden" name="booking_time" id="alacarte_booking_time" value="">';
                            $('#alacarte_hours').html(txt2);
                            $('#alacarte_time').html(txt);

                    
                    $('#ac_booking_date2').val(dateText);
                    $('#ac_date_edit2 span').text(formatDate(dateText));
                    $('#ac_date_edit2').click();
                    timehide=0;
                    $('#ac_time_edit2').click();
                    $('#ac_date_edit2').removeClass('hidden');


              }
          });
          //$( "#ac_choose_date2" ).datepicker("refresh");
      }

  </script>
@endsection