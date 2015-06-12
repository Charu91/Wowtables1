@extends('frontend.templates.details_pages')

@section('content')
<?php 
	function detail_page_url() {
		 $pageURL = 'http';
		 $pageURL .= "://";
		 if ($_SERVER["SERVER_PORT"] != "80") {
		  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		 } else {
		  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		 }
		 return $pageURL;
	}

	$detail_page_url =  detail_page_url();
	$session_user_status = Session::get('new_user_status');
?>
<script type="text/javascript">
	$(document).ready(function(){
		//alert("sad");
		var sess_usr_status = '<?php echo $session_user_status;?>';
		if(sess_usr_status == 'false'){
			mixpanel.register({"New User":'False'});
		}
		var curr_url = '<?php echo $detail_page_url;?>';
		mixpanel.track("Landing Page",{"Page Type":'Detail Page','Url':curr_url});		
	});
</script>
<?php 
$hasOrder = (bool)(isset($order) && is_array($order) && $arrExperience['data']['slug'] == $order['slug']);
//$soldOut = !($arrExperience['data']['intval'] >=0 && $arrExperience['data']['tickets_sold'] < $arrExperience['data']['max_num_orders']);
?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<div class="container deal-detail">
    <?php $city = $current_city;?>   
    <div class="container deal-detail">
    <?php 
	$footer_filter_access = array('lists','collection','filter');
	/*if($arrExperience['data']['discontinued'] == 1) { ?>
   
<?php if((isset($details) && $details['dropdowns_opt'] == 0) || in_array($this->router->fetch_method(),$footer_filter_access)):?> 
<section class="related-experiences deal-detail discontinued">
      <div class="container">
        <div class="row">  
<?php  if($this->router->fetch_method() == 'exp'):?>
<h3 class="col-md-10">Sorry, this experience is no longer available.</h3>
<p class="col-md-10"><strong>Other Experiences You Might Enjoy</strong></p>
<ul>

<?php $i=0;?>
<?php foreach($experiences as $exp):?>
<?php 

?>
    <li class="col-md-4 col-sm-4">
        <a href="<?php echo URL::to('/').$exp['city']."/experiences/".$exp['slug'];?>">
           <table class="deal-head">
                <tr>
                    <td>
                        <?php echo $exp['exp_title'];?>
                    </td>
                    <td>
                      <span>View</span> Details
                    </td>
                  </tr>
                </table>
                 <div class="deal-img">
                    <img src="<?php echo URL::to('/').$exp['list_image']; ?>" alt="" class="img-responsive">
                  <div class="deal-desc">
                    <p><?php echo trim($exp['exp_short_desc']);?></p>
                  </div>
                </div>                
              </a>
            </li>
<?php
 $i++; 
 ?>
<?php endforeach ?>
</ul>
<div class="clearfix"></div>
<?php endif ?>
<?php 
$url = $this->uri->segment(1);
$url = explode("-",$url);
$last_url_item = count($url)-1;
?>
    <div class="bottom-filters"> 
    <ul>
        <li class="col-md-4 col-sm-4">
                <div class="btn-group dropup btn-block">
                  <button type="button" class="btn btn-default btn-block dropdown-toggle" data-toggle="dropdown" id="f_cuisine">
                    Experiences by Cuisine
                    <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu" id="f_cuisine_ul">
                  <?php foreach($cousines as $cousine): ?>
                     <li ><a rel="<?php echo strtolower($cousine); ?>" href="javascript:void(0);"><?php echo $cousine; ?></a></li>
                  <?php endforeach; ?>
                  </ul>
                </div>
              </li>
               <li class="col-md-4 col-sm-4">
                <div class="btn-group dropup btn-block">
                  <button type="button" class="btn btn-default btn-block dropdown-toggle" data-toggle="dropdown" id="f_area">
                    Experiences by Location
                    <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu" id="f_area_ul">
                  <?php foreach($areas_footer[$current_city] as $area): ?>
                     <li><a rel="<?php echo strtolower($area); ?>" href="javascript:void(0);"><?php echo $area; ?></a></li>
                  <?php endforeach; ?>
                  </ul>
                </div>
              </li>
               <li class="col-md-4 col-sm-4">
                <div class="btn-group dropup btn-block">
                  <button type="button" class="btn btn-default btn-block dropdown-toggle" data-toggle="dropdown" id="f_price">
                    Experiences by Price
                    <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu" id="f_price_ul">
                  <?php foreach($prices as $key => $price): ?>  
                     <li rel="<?php echo $key; ?>"><a rel="<?php echo strtolower($key); ?>" href="javascript:void(0);"><?php echo htmlspecialchars($price); ?></a></li>
                  <?php endforeach; ?>
                  </ul>
                </div>
              </li>
          </ul>
    </div>
</div>
</div>
</section> 
<?php endif ?>
<?php } */?>

      <div class="row">
      <div class="col-md-8 col-sm-8 deal-detail-left"  itemscope itemtype="http://data-vocabulary.org/Review-aggregate">
          <?php 
          //if($details['crumb_opt'] == 0):
          ?>
            <div id="breadcrumb">
                <ol class="breadcrumb">
                    <li><a href="/<?php echo $current_city ?>"><?php echo ucfirst($current_city) ?></a></li>
                    <li><a href="/<?php echo $current_city ?>-all-<?php echo strtolower($arrExperience['data']['cuisine'][0]) ?>-experiences"><?php echo ucfirst($arrExperience['data']['cuisine'][0]) ?></a></li>
                    <li class="active"><?php echo ucfirst($arrExperience['data']['name']) ?></li>
                </ol>
            </div>
           <?php 
           //endif 
           ?>
          <div class="deal-top-box">
            <table id='gourmet_points' <?php /*echo ($details['banner_opt'] == 1)?"class='exlusive_hide'" : '';*/ ?>>
              <tr>
              	<td class="deal-title"><h1><span itemprop="itemreviewed"><?php echo $arrExperience['data']['name'];?></span>
                   <span style="float: right;cursor: pointer;" id="share-modal">
                           <a data-page_loc="Email Tip Widget" data-target="#redirectExperienceEmailModal" data-toggle="modal">
                               <img src="/assets/img/share-48.png" title="Email a Friend" id="share_modal_div" />
	                       </a>
                   </span></h1>
               </td>

               <?php 
                /*if($details['banner_opt'] == 0)
               	{
               		?>
                    <td rowspan="2" class="gourmet-label <?php echo ($details['banner_opt'] == 1)? 'banner_option_1':'';?>">
                    <span><img src="<?php echo URL::to('/'); ?>assets/img/banner-in-bg.png" class="img-responsive hidden-xs" style="padding:5px;"></span>
                    <span class="visible-xs">Exclusively for<br/>WowTables<br/>Members</span><div class="label-caret"></div> 
                    </td>  
                	<?php
                }
                commented above and taken the default*/

                ?>
                <td rowspan="2" class="gourmet-label <?php /*echo ($details['banner_opt'] == 1)? 'banner_option_1':'';*/?>">
                <span><img src="/assets/img/banner-in-bg.png" class="img-responsive hidden-xs" style="padding:5px;"></span>
                <span class="visible-xs">Exclusively for<br/>WowTables<br/>Members</span><div class="label-caret"></div> 
                </td>  
            	<?php
                ?>
                
              </tr>
           
              <tr>
                <td class="deal-desc"><h2><?php echo $arrExperience['data']['short_description'];?></h2></td>
              </tr>
            </table>

            <div id="deal-detail-carousel" class="carousel slide" data-ride="carousel">
              <!-- Wrapper for slides -->
              <div class="carousel-inner">
                
                <?php 
                $i=1;
                if(isset($arrExperience['data']['image']['gallery']) && is_array($arrExperience['data']['image']['gallery'])) 
                {
                  foreach($arrExperience['data']['image']['gallery'] as $key => $value) 
                  { 
                    ?>
                    <div class="item <?php echo ($i == 1)?'active':''?>">
                        <img  itemprop="photo" src="<?php echo $value;?>" alt="<?php echo $value;?>" alt="deal<?=$i;?>">
                    </div>
                    <?php $i++;
                  } 
                }
                ?>
                                
              </div>
                <a class="left carousel-control" href="#deal-detail-carousel" data-slide="prev">
                    <img  src="/images/arrow-left1.png" >
                </a>
                <a class="right carousel-control" href="#deal-detail-carousel" data-slide="next">
                    <img  src="/images/arrow-right1.png" >
                </a>
               <?php /*if (!empty($arrExperience['data']['multiplier']) && $arrExperience['data']['multiplier'] > 1 && !empty($arrExperience['data']['reward_points'])): ?>
                <div class="multiplier"><img class='img-responsive' src="<?php echo URL::to("images/x{$arrExperience['data']['multiplier']}.png") ?>"></div>
               <?php endif; */?>    
            </div>
            <div class="booking clearfix visible-xs">
              <div class="row">
                      <div class="col-xs-10">
                         <?php if($arrExperience['data']['price'] != 0):?>
                         <div class="col-xs-10"><p style="font-size: 16px;font-weight: 300;line-height: 1.4;margin-top: 10px;">Rs. <?php echo ceil($arrExperience['data']['price']);?> <span><?php echo $arrExperience['data']['price_type']?></span></p></div>
                         <?php endif;?>
                      </div>
                      <div class="col-xs-2">
                   <span style="float: right;cursor: pointer;margin-top: 5px;margin-right: 5px;">
                           <a data-page_loc="Email Tip Widget" data-target="#redirectExperienceEmailModal" data-toggle="modal">
                                   <img src="/assets/img/share-48.png" title="Email a Friend" />
                           </a>
                   		</span>
                   </div>
              </div>
          </div> 
          <div class="booking clearfix visible-xs">                     
                 <?php /*if (!$soldOut): ?>
                     <div class="col-md-6 scrollingBox" id="make_reservation_online"style="background: none;z-index: 1 !important;"><a href="#ReservationBox" class="btn btn-warning">MAKE A RESERVATION</a></div>
             <?php endif;*/?>
           </div>
          </div>

          <div class="deal-bottom-box">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs">
              <li class="active" id='info_tab'><a href="#info" data-toggle="tab" id="exp-details-tab">
                <span class="hidden-xs">Experience Info</span><span class="visible-xs">Details</span></a>
              </li>
              <li id='menu_tab'><a href="#menu" data-toggle="tab" class="exp-menu-tab" id="wowtables_exp_menu_tab">Menu</a></li>

              <li class="review-tab" id="review-tab-detail"><a href="#review" data-toggle="tab" id="review-menu-tab-detail">
               <?PHP 
               
               $average_rating 		= $arrExperience['data']['rating'];// number of full stars
               $average_rating_2	= $average_rating-floor($average_rating); //number of half stars
               $average_rating_3	= 5-floor($average_rating); //number of white stars

               if($arrExperience['data']['total_reviews']){?>
                <span><span itemprop="votes"><?PHP echo $arrExperience['data']['total_reviews'];?></span>&nbsp;Reviews &nbsp;</span>
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
                      <span itemprop="average"><?PHP echo $arrExperience['data']['rating'];?></span>/<span itemprop="best">5</span>
                    </span>                    
                  </span>
                    <span>)</span>  

                    <?PHP 
                	} 
                  	else 
                  	{
	                  	?>
	                	<span class=""><span></span>Reviews</span><span></span>
	                  	<?PHP 
	                 } 
	                 ?>
                   <div class="clearfix"></div></a>
               </li>
            </ul>
			<?php
			$areas_str = '';
			$addresses_str = '';
			$areas_arr = array();
      $addresses_arr  = array();
      if(is_array($arrExperience['data']['location']) && count($arrExperience['data']['location'])>0)
			{
				foreach($arrExperience['data']['location'] as $key =>$listData)
				{
					$areas_arr[]		=$listData['area'];
					$addresses_arr[]	=$listData['area'].' - '.$listData['address_line'];
				}
				$areas_str = implode(',',$areas_arr);
				$addresses_str =  implode('<br/>',$addresses_arr);
			}
            ?>
            <input type="hidden" name="current_city" value="<?=$current_city;?>">
            <input type="hidden" name="cuisine" value="<?=$arrExperience['data']['cuisine'][0];?>">
            <input type="hidden" name="price" value="<?=$arrExperience['data']['price'];?>">
            <input type="hidden" name="areas" value="<?=$areas_str;?>">
            <input type="hidden" name="restaurant_name" value="<?=$arrExperience['data']['name'];?>">
            <!-- Tab panes -->
            <div class="tab-content">
              <div class="tab-pane fade in active" id="info">
               <p><?php echo $arrExperience['data']['experience_info']; ?></p>
                <hr> 
                <div class="">
                <?php echo $addresses_str; ?>
                </div>   
                <div id="map-canvas" style="height:300px;margin: 5px;">
                  <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBsEnNgLLhw0AFS4JfwsE1d3oTOeaWcccU&sensor=true"></script>
					<script type="text/javascript">
							var multiple_latlongs = [
						  <?php
							if(is_array($arrExperience['data']['location']) && count($arrExperience['data']['location'])>0)
							{
								foreach($arrExperience['data']['location'] as $key =>$listData)
								{
									?>
									new google.maps.LatLng(<?php echo $listData['latitude']?>, <?php echo $listData['longitude']?>),
									<?php
									$arrExperience['data']['lattitude_coordinate'] = $listData['latitude'];
									$arrExperience['data']['longitude_coordinates'] = $listData['longitude'];
								}
							}
				            ?>
                           ];
						   //console.log("multiple latlongs = "+multiple_latlongs);
						   var dealer_lat = "<?php echo $arrExperience['data']['lattitude_coordinate']?>";
                           var dealer_lng = "<?php echo $arrExperience['data']['longitude_coordinates']?>";
							var markers = [];
							var iterator = 0;

							var map;

                          function initialize() {
                            var  mylatlng =  new google.maps.LatLng(dealer_lat, dealer_lng);
                            var mapOptions = {
                              center: mylatlng,
                              zoom: 10
                            };
                            map = new google.maps.Map(document.getElementById("map-canvas"),
                                mapOptions);
                                
                            /*var marker = new google.maps.Marker({
                              position: mylatlng,
                              map: map
							});*/

							for (var i = 0; i < multiple_latlongs.length; i++) {
								  //console.log("loop called = "+i);
								  addMarker();
							}

                          }


						  function addMarker() {
							  markers.push(new google.maps.Marker({
								position: multiple_latlongs[iterator],
								map: map,
								draggable: false,
								animation: google.maps.Animation.DROP
							  }));
							  iterator++;
							}

                          google.maps.event.addDomListener(window, 'load', initialize);
                    </script>
                </div> 
              </div>
              <div class="tab-pane fade" id="menu">
                <?php 

                //$arrExperience['data']['menu'] ='{"title":"A Modern 4 Course Experience at Yauatcha","menu":[{"heading":"Course 1","sub-menu":[{"heading":"Salad","description":"choose any one","items":[{"title":"Oriental Salad With Bean Curd Chips"},{"title":"Mixed Salad With Lotus Root"}]}]},{"heading":"Course 2","sub-menu":[{"heading":"Dim Sum","description":"choose any two","items":[{"title":"Sato Bean And Cashew Nut Dumpling"},{"title":"Vegetable Ying Yang Dumpling"},{"title":"Baked Vegetarian Puff"},{"title":"Crispy Asparagus, Pumpkin And Corn Roll"},{"title":"Sugarcane Chicken And Prawn Roll"},{"title":"Chicken And Prawn Sui Mai"},{"title":"Charcoal Lamb Bun"}]}]},{"heading":"Course 3","sub-menu":[{"heading":"Main Course","description":"choose any one","items":[{"title":"Szechuan Vegetable And Tofu Clay Pot"},{"title":"Stir-Fried French Beans With Shiitake Mushroom"},{"title":"Stir-Fried Chicken In Hometown Chilli Sauce"},{"title":"Steamed Indian Salmon In Spicy Black Bean Sauce"}]}]},{"heading":"Course 4","sub-menu":[{"heading":"Staple","description":"choose any one ","items":[{"title":"Stir-Fried Spicy Ramen Noodles"},{"title":"Spicy Vegetable Fried Rice With Taro And Spring Onion"},{"title":"Egg White Chicken Fried Rice"}]}]},{"heading":"Course 5","sub-menu":[{"heading":"Dessert","description":"choose any one","items":[{"title":"Chocolate Mandarin Tart"},{"title":"Mango Mascarpone Gateau"},{"title":"Raspberry Delice"}]}]}]}';
                $menuData = json_decode($arrExperience['data']['menu'],true) ;
               //echo '<pre>';
               //print_r($menuData);exit;
                if(isset($menuData['title']))
                {
                	?>
                	<ul class="menu-content">
        						<li>
        							<p class="lead"><?php echo $menuData['title'];?></p>
        						</li>
        					</ul>
        					<br/>
                	<?php
                	if(is_array($menuData['menu']) && count($menuData['menu'])>0)
                	{
                		foreach($menuData['menu'] as $menu_list_data)
                		{
                			?>
		                	<ul class="menu-content">
        								<li>
        									<p class="lead"><?php echo $menu_list_data['heading'];?><br/><small><?php echo isset($menu_list_data['description'])?$menu_list_data['description']:'';?></small></p>
        								</li>
        							</ul>
		                	<?php
                      if(isset($menu_list_data['sub-menu']) && is_array($menu_list_data['sub-menu']) && count($menu_list_data['sub-menu'])>0)
                      {
                        foreach($menu_list_data['sub-menu'] as $sub_menu_list_data)
                        {
                          ?>
                          <ul class="menu-content">
                            <li>
                              <p class="lead"><?php echo $sub_menu_list_data['heading'];?><br/><small><?php echo isset($sub_menu_list_data['description'])?$sub_menu_list_data['description']:'';?></small></p>
                            </li>
                          </ul>
                          <?php
                          if(isset($sub_menu_list_data['items']) && is_array($sub_menu_list_data['items']) && count($sub_menu_list_data['items'])>0)
                          {
                            ?>
                            <ul class="menu-content">
                              <?php
                              foreach($sub_menu_list_data['items'] as $submenu_items_data)
                              {
                                ?>                          
                                <li>
                                  <p class="lead"><?php echo $submenu_items_data['title'];?> <small><?php echo isset($submenu_items_data['tags'])?'('.implode(',',$submenu_items_data['tags']).')':'';?></small><br/> <small><?php echo isset($submenu_items_data['description'])?$submenu_items_data['description']:'';;?></small></p>
                                </li>
                              <?php                     
                              }
                              ?>
                            </ul>
                            <br/>
                            <?php
                          }
                        }
                		  }

                      if(isset($menu_list_data['items']) && is_array($menu_list_data['items']) && count($menu_list_data['items'])>0)
                      {
                        foreach($menu_list_data['items'] as $sub_menu_list_data)
                        {
                          if(isset($sub_menu_list_data['heading']))
                          {
                          ?>
                          <ul class="menu-content">
                            <li>
                              <p class="lead"><?php echo $sub_menu_list_data['heading'];?><br/><small><?php echo isset($sub_menu_list_data['description'])?$sub_menu_list_data['description']:'';?></small></p>
                            </li>
                          </ul>
                          <?php
                          }

                          if(isset($sub_menu_list_data['items']) && is_array($sub_menu_list_data['items']) && count($sub_menu_list_data['items'])>0)
                          {
                            ?>
                            <ul class="menu-content">
                              <?php
                              foreach($sub_menu_list_data['items'] as $submenu_items_data)
                              {
                                ?>                          
                                <li>
                                  <p class="lead"><?php echo $submenu_items_data['title'];?> <small><?php echo isset($submenu_items_data['tags'])?'('.implode(',',$submenu_items_data['tags']).')':'';?></small><br/> <small><?php echo isset($submenu_items_data['description'])?$submenu_items_data['description']:'';;?></small></p>
                                </li>
                              <?php                     
                              }
                              ?>
                            </ul>
                            <br/>
                            <?php
                          }
                        }
                      }
                	 }
                  }
                }
                ?>
              </div>
                  <!-- review start-->

              <div class="tab-pane fade" id="review">

                   <div class="row">
                  <div class="review-all">
                    <div class="col-md-12">
                    <?PHP if($arrExperience['data']['total_reviews']){?>
                      <p><strong>The reviews below are from members that have personally tried this experience:</strong></p><?PHP }
                      else {?>
                        <p><strong>No reviews yet. Be the first one to make a reservation and review your experience!</strong></p>
                      <?PHP }?>
                    </div>
                  </div>
                               <!-- REVIEW START-->
                <ul class="comments" id="review_comments">               
                   <input type="hidden" id="exp_id" value="<?=$arrExperience['data']['id']?>">
      
                      <?PHP 
                      if(is_array($arrExperience['data']['review_detail']) && count($arrExperience['data']['review_detail'])>0)
                      {
                      	foreach($arrExperience['data']['review_detail'] as $reviews): ?>
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
                        </div>
                        <p>
                        <div height='25px'></div>
                          <?PHP echo $reviews['review'];?>  
                        </p>
                      </div>
                    </li>
                      <?PHP endforeach;
                  	}
                       ?>
                  </ul>
                   <?PHP 
                   if($arrExperience['data']['total_reviews']>4){?>
                  <div class="text-center more"><a href="#" class="btn btn-warning" id="view_more">View more reviews</a></div>                  
                <?PHP }?>
                </div>
              </div>
             <!-- review end-->

            </div>
           
          </div>
        </div>
        <div class="col-md-4 col-sm-4 deal-detail-right">
          <div class="widget price-box">
            <?php if($arrExperience['data']['price'] != 0):?>
            <h3 class="text-center">Rs. <?php echo ceil($arrExperience['data']['price']);?> <span><?php echo $arrExperience['data']['price_type'];?></span></h3>
            <p class="text-center"><small>(<?php echo $arrExperience['data']['taxes'];?>)</small></p>
           <?php
                 if(isset($arrExperience['data']['upfront']) && $arrExperience['data']['upfront']>=1 )
                 { ?>  
                 <p class="text-center">
                  <?php echo "Pay Rs. ".$arrExperience['data']['upfront']." online &<br> Rs. ".(ceil($arrExperience['data']['price'])-$arrExperience['data']['upfront'])." at the location"; ?>
                 </p>
           <?php    }
             ?>
             <?php if(isset($arrExperience['data']['show_end_date']) && $arrExperience['data']['show_end_date']=="Yes"){ // if End date is enabled to be shown in the admin ?>
                <p class="text-center"><small><?php echo ($arrExperience['data']['intval']<=0?"0":$arrExperience['data']['intval']);?> Days to Book</small></p>
             <?php } ?>
             <?php endif;?>
            <h4>This Experience Includes:</h4>
            <ul>
             <?php echo str_replace(array('<p>','</p>'),array('<li>','</li>'),$arrExperience['data']['experience_includes']); ?>
				     <?php echo ((isset($arrExperience['data']['reward_points']) && $arrExperience['data']['reward_points'] > 0)? '<li>- '.$arrExperience['data']['reward_points'].' Gourmet Points when you make a reservation online<a  href="'.URL::to("gourmet-rewards").'" target="_blank" data-original-title="Click here to read about Gourmet Rewards" data-placement="top" data-toggle="tooltip" class="btn tooltip1"><img src="'.URL::to('images/question_icon_small_display.png').'"></a></li>': ' '  )?>
             <?php /*if ($soldOut): ?>
              <li>
                <h1 class="sold_out">SOLD OUT</h1>
              </li>
              <?php endif;*/?>
            </ul>
          </div>
      
      <?php 
      //Reservation start
      //if (!$soldOut): ?>
      <?php /*if($_GET['v'] == 2) :?>
      <div class="widget reservation-box" id="startBox">
        <div id="variationres-wrap">
         <h3 class="text-center">Reserve a table</h3>
         <div class="text-center btn-wrap version2">                
          <a class="btn btn-warning var-jump-exp">Make An Experience Reservation</a>
          <?php if($restaurant[0]['allow_reserv'] == 1) :?>
          <p style="padding-top: 13px;">OR</p>
          <?php if(isset($rest_detail_alacarte) && count($rest_detail_alacarte) == 1){ ?>
              <a class="alacarte_reservation_text" href="<?php echo URL::to();?><?php echo $rest_detail_alacarte[0]['city']."/alacarte/".$rest_detail_alacarte[0]['slug'];?>">
              <small>Make An A la carte Reservation at <br>
                <span>
                  <?php echo ucfirst($arrExperience['data']['name'])?></span><br><span>(500 Gourmet Points)
                </span>
              </small>
            </a>
          <?php } else { ?>
            <a class="alacarte_reservation_text" data-page_loc="Suggest Tip Widget" data-target="#redirectAlacarteLocationModal" data-toggle="modal">
              <small>Make An A la carte Reservation at <br>
                <span>
                  <?php echo ucfirst($arrExperience['data']['name'])?></span><br><span>(500 Gourmet Points)
                </span>
              </small>
            </a>
        <?php } ?>
          <?php endif ?>
         </div>
       </div>
      </div>
      <?php endif*/ ?>
          <div class="widget reservation-box" id="ReservationBox"<?=(isset($_GET['v']) && $_GET['v']==2) ? ' style="display:none"' : ''?>>
             <h3 class="text-center">RESERVE THIS EXPERIENCE</h3>
             <form role='form' method="post" action="<?=URL::to('orders/expcheckout')?>" id="booking_form">  
             <div class="panel-group reservation-accordian" id="accordion">
             <div id="reserv_table">
             <?php if(is_array($arrExperience['data']['location']) && count($arrExperience['data']['location'])>1): ?>
                    <div class="panel panel-default" id="address">
                        <div class="panel-heading">
                          <h4 class="panel-title">
                             <a href="javascript:" style="text-decoration: none;">
                                Location</a>  
                                <select name="address" id='locations1' class="pull-right space">
                                <?php
                                foreach($arrExperience['data']['location'] as $key =>$listData): ?>
                                    <option value="<?php echo $listData['vendor_location_id'];?>" ><?php echo $listData['area'];?></option>
                                <?php endforeach; ?>
                            </select>
                          </h4>
                     </div>
                </div>
             <?php elseif(!empty($arrExperience['data']['location']) && count($arrExperience['data']['location']) == 1): ?>    
                <input type="hidden" name="address" id='locations1' value="<?php echo $arrExperience['data']['location'][0]['vendor_location_id'];?>">
             <?php endif; ?>
              <div class="panel panel-default">
                <div class="panel-heading <?php echo ($hasOrder) ? '' : 'active'?>">
                  <h4 class="panel-title">
                     <a href="javascript:" style="text-decoration: none;">
                      Select Party Size </a><a  href="javascript:" data-original-title="Select the total number of guests at the table. If a larger table size is needed, please contact the WowTables Concierge." data-placement="top" data-toggle="tooltip" class="btn tooltip1"><img src="<?=URL::to('images/question_icon_small_display.png');?>"></a>
                      <select name="qty" id="party_size1" class="pull-right space <?=($hasOrder)? 'hidden' : '';?>">
                            <option value="0">SELECT</option>
                            
                            <?php   
                            $exp_location_id = $arrExperience['data']['location'][0]['vendor_location_id'];                          
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
                        <strong><a id="party_edit1" href="javascript:" class="<?=($hasOrder)? '' : 'hidden';?>" style="text-decoration: none;float: right;font-size: 13px;color: #EAB703;"><span style="color: #fff;"><?=($hasOrder)? $order['qty'] : '';?></span> EDIT</a></strong>
                  </h4>
                </div>
             
              </div>
              <div class="panel panel-default">
                <div class="panel-heading" >
                  <h4 class="panel-title">
                    <a href="javascript:" style="text-decoration: none;">
                      Select Date
                    </a>
                     <strong><a id="date_edit1" class="<?=($hasOrder) ?  : 'hidden'; ?>" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" style="text-decoration: none;float: right;font-size: 13px;color: #EAB703;"><span style="color: #fff;"><?=($hasOrder) ? $order['date'] : ''; ?></span> EDIT</a></strong>
                  </h4>
                </div>
                <div id="collapseTwo" class="panel-collapse collapse">
                  <div class="panel-body">
                    <div class="input-append date" id='dp1' data-date-format="dd-mm-yyyy">
                        <input type="hidden" value="" name="booking_date" id="booking_date">
                        <div class="options" style="margin: -10px;">
                            <div id="choose_date"></div>
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
                    <strong><a id="time_edit1" class="<?=($hasOrder) ? '' : 'hidden';?>" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" style="text-decoration: none;float: right;font-size: 13px;color: #EAB703;"><span style="color: #fff;"><?=($hasOrder) ? $order['time'] : ''; ?></span> EDIT</a></strong>
                  </h4>
                </div>
                <div id="collapseThree" class="panel-collapse collapse">
                  <div class="panel-body">
                    <div id='time'>
                        <?php if($hasOrder):?>
                        <div class="btn-group col-lg-10 pull-right actives ">
                        <?php
                          $weeks = array('sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat');
                          $week_number = date('w',strtotime($order['date']));
                          $week = $weeks[$week_number];
                          if(isset($schedule)):
                if(isset($schedule_times)):
                  $schedule = $schedule_times;
                endif;
                                foreach($schedule[$week] as $time=>$hours):?>
                                  <label class="btn btn-warning btn-xs time_tab <?=(in_array($order['time'],$hours))? 'active':'';?>" id="<?=$time?>" style="padding:2px">
                                      <?=strtoupper($time);?>
                                  </label>
                         <?php   
                                endforeach;
                          endif;
                        ?>
                        </div>
                        <div class="clearfix"></div>
                        <input type="hidden" name="booking_time" id="booking_time" value="<?=$order['time']?>">
                        <?php endif;?>
                    </div>
                    <div id='hours'>
                        <?php if($hasOrder):?>
                        <?php
                          if(isset($schedule)):
                if(isset($schedule_times)):
                $schedule = $schedule_times;
                endif;
                              foreach($schedule[$week] as $time=>$hours):
                              ?>
                               <div id="<?=$time."_tab";?>"  class="<?=(in_array($order['time'],$hours))? '':'hidden';?>">
                               <?php foreach($hours as $hour):?>
                                    <div class="time col-lg-3 col-xs-5 <?=($hour == $order['time'])? 'time_active' : '';?>" rel="<?=$hour;?>"><a href="javascript:"><?=$hour;?></a></div>
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
                <?php
                 $mealOptions = (is_array($arrExperience['data']['addons']) && count($arrExperience['data']['addons'])>0)?count($arrExperience['data']['addons']):0;
                 //$mealOptions = ((!empty($rows[1]['price_non_veg']) && $rows[1]['price_non_veg'] != '0.00') || (!empty($rows[1]['price_alcohol']) && $rows[1]['price_alcohol'] != '0.00'));
                 //$nonVeg = (!empty($rows[1]['price_non_veg']) && $rows[1]['price_non_veg'] != '0.00');
                 //$alcohol = (!empty($rows[1]['price_alcohol']) && $rows[1]['price_alcohol'] != '0.00');
                ?>
                <?php if ($mealOptions): ?>
              <div class="panel panel-default">
                <div class="panel-heading">
                  <h4 class="panel-title">
                    <a href="javascript:" style="text-decoration: none;">
                        Meal options<strong><a id="meal_edit1" class="hidden" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" style="text-decoration: none;float: right;font-size: 13px;color: #EAB703;"><span style="color: #fff;"></span> EDIT</a></strong>
                    </a>
                  </h4>
                </div>
                <div id="collapseFour" class="panel-collapse collapse">
                  <div class="panel-body meals">
                        <?php 
                        foreach($arrExperience['data']['addons'] as $addons)
                        {
                          ?>
                          <div style="margin-bottom: 10px;">
                                <span><?php echo $addons['reservation_title'];?></span>
                                <select name="add_ons[<?php echo $addons['prod_id'];?>]" id="non_veg">
                                    <option value="0">0</option>
                                </select>
                            </div>
                          <?php
                        }
                        ?>
                  </div>
                 </div>
              </div>
              <?php endif; ?> 
              </div>
                <div class="panel panel-default hidden" id="order_info">
                <div id="load_layer">
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
                    <a href="javascript:" style="text-decoration: none; font-size: 13px;" id='fullinfo'>
                      info
                    </a>
                    <strong><a id="info_edit1" href="javascript:" style="text-decoration: none;float: right;font-size: 13px;color: #EAB703;"><span style="color: #fff;"></span> EDIT</a></strong>
                  </h4>
                </div>
                <div id="collapseFive" class="panel-collapse">
                  <div class="panel-body">
                  <p style="font-size: 12px; text-align: center;">RESERVATION INFORMATION</p> 
                   <div class="input-group">
                      <span class="input-group-addon " style="color: black;"><i class="glyphicon glyphicon-envelope"></i></span>
                      <input type="email" name="email" id="email" class="form-control" placeholder="EMAIL" value="<?=(isset($user_data['email']))? $user_data['email'] :'';?>">
                    </div>  
                    <div class="reservation_errors" id="error_email"></div> 
                    <div class="input-group">
                      <span class="input-group-addon " style="color: black;"><i class="glyphicon glyphicon-user"></i></span>
                      <input type="text" name="fullname" id="fullname" class="form-control" placeholder="FULL NAME" value="<?=(isset($user_data['full_name']))? $user_data['full_name']:''; ?>">
                    </div> 
                    <div class="reservation_errors" id="error_fullname"></div> 
                    <div class="input-group">
                      <span class="input-group-addon " style="color: black;"><i class="glyphicon glyphicon-earphone"></i></span>
                      <input type="text" name="phone" id="phone" class="form-control" placeholder="MOBILE" value="<?=(isset($user_data['phone']))? $user_data['phone']:''; ?>">
                    </div>
                    <div class="reservation_errors" id="error_phone"></div> 
                    <div class="input-group">
                      <span class="input-group-addon" style="color: black;"><i class="glyphicon glyphicon-plus"></i></span>
                      <input type="text" name="special" id="special" class="form-control" placeholder="(Optional) Special Requests">
                    </div> 
                    <div class="reservation_errors"></div>
                    <div class="reservation_errors" id="error_people"></div> 
                    <div class="text-center">
                        <button class="btn btn-warning btn-xs" type="button"  id="make_reservation">MAKE A RESERVATION</button>
                    </div> 
                  </div>
                </div>
              </div>
            </div>
              <div class="text-center">
                <span class="hidden" id="cant_select_table">To check for immediate availability, please call our concierge.</span> 
                <p class="hidden" id="cant_do_reserv1">You have an existing reservation that conflicts with this one. To modify or cancel your existing reservation please click</p>
                <a class="btn btn-warning hidden" id="brs_my_reserv" href="<?=URL::to('/users/myreservations');?>">View My Existing Reservations</a>
                <p class="hidden" id="cant_do_reserv2">If you have any queries please call our concierge desk.</p> 
                <div class="text-center select-all-data hidden" id="select_all_data">Please select all data</div>
                <a  data-page_loc="Reservation Widget" class="btn btn-warning <?php //=($hasOrder)? '' : 'hidden';?>" <?=(!(Session::has('logged_in')) && (isset($allow_guest) && $allow_guest == "Yes")) ? 'data-target="#redirectloginModal" data-toggle="modal"':'';?> id='select_table_exp'>SELECT TABLE</a>
                <?php //if($restaurant[0]['allow_reserv'] == 1): ?>
                  <p class="text-center or-reservation <?php //=($hasOrder)? '' : 'hidden';?>" id="or_reservation">OR</p>
                  <!--<p class="text-center"><a id="jump2-alacarte"><small>Make An A la carte Reservation at <br><span><?php //echo ucfirst($arrExperience['data']['name'])?></span><br><span>(<?php //echo $restaurant[0]["reward_points"] ?> Gourmet Points)</span></small></a></p>-->
               <p class="text-center">
              <?php if(isset($rest_detail_alacarte) && count($rest_detail_alacarte) == 1){ ?>
                <a class="alacarte_reservation_text" href="<?php echo URL::to();?><?php echo $rest_detail_alacarte[0]['city']."/alacarte/".$rest_detail_alacarte[0]['slug'];?>">
                <small>Make An A la carte Reservation at <br>
                  <span>
                    <?php echo ucfirst($arrExperience['data']['name'])?></span><br><span>(500 Gourmet Points)
                  </span>
                </small>
              </a>
              <?php } else { ?>
                <a class="alacarte_reservation_text" data-page_loc="Suggest Tip Widget" data-target="#redirectAlacarteLocationModal" data-toggle="modal">
                  <small>Make An A la carte Reservation at <br>
                    <span>
                      <?php echo ucfirst($arrExperience['data']['name'])?></span><br><span>(500 Gourmet Points)
                    </span>
                  </small>
                </a>
              <?php } ?>
            </p>
                <?php //endif ?>
              </div>
                <input type="hidden" id="slug" value="<?php echo $arrExperience['data']['slug']; ?>">
                <input type="hidden" name="time" id="fulltime">
                <input type="hidden" name="amount" id="amount">
                <input type="hidden" name="post_amount" id="post_amount">
                <input type="hidden" name="description" value="<?php echo $arrExperience['data']['short_description']?>">
                <input type="hidden" name="experience_id" id="experience_id" value="<?php echo $arrExperience['data']['id']?>">
                <input type="hidden" name="city" value="<?php echo $current_city_id;?>">
                <input type="hidden" name="send">
            </form>
          </div>
          <?php //endif;?>


          <div class="widget query-contact">
            <p>Got a question? <br> Call our Concierge at <a href="tel:09619551387">09619551387</a></p>
          </div>
          <div class="widget terms-box">
            <p class="lead">TERMS</p>
            <ul>   
            <?php echo str_replace(array('<p>','</p>'),array('<li>','</li>'),$arrExperience['data']['terms_and_condition']);?>
            <?php if(isset($arrExperience['data']['gift_card']) && $arrExperience['data']['gift_card']==1){?>
            <li>- Can be redeemed with a WowTables Gift Card
            <a  href="<?php echo URL::to('gift-cards')?>" target="_blank" data-original-title="Click here to find out more about WowTables Gift Cards"
             data-placement="top" data-toggle="tooltip" class="btn tooltip1"><img src="/images/question_icon_small_display.png"></a>
            </li>
            <?php } else {?>
      			{{--<li>- WowTables Gift Cards cannot be used for this experience</li>--}}
      			<?php } ?>
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

	<div class="modal fade" id="redirectAlacarteLocationModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width: 400px;">
	<div class="modal-content">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h4 class="modal-title text-center" id="myModalLabel">Which <?php echo $arrExperience['data']['name'];?> would you like a reservation at?</h4>
	  </div>
		<div class="modal-body">               
            <div class="panel panel-default">

              <!-- List group -->
              <ul class="a-list-group">
				<?php /*foreach($rest_detail_alacarte as $cities){ //echo "<pre>"; print_r($cities);?>
					<li class="a-list-group-item" data-alacarte_link="<?php echo $cities['city']."/alacarte/".$cities['slug'];?>">
						<center><a href="" data-dismiss="modal"><?php echo $cities['area'];?></a></center>
					</li>
				<?php }*/?>
              </ul>
            </div>
          </div>
	  </div>
	</div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
  <!-- Modal for email experience starts here-->
  <!--Share Modal -->
    <div class="modal fade" id="redirectExperienceEmailModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title text-center" id="myModalLabel">Share This Experience</h4>
          </div>
          <div class="modal-body" style="min-height: 100px;">
          <div id="exp_email_form">
            <form>
              <div class="form-group">
                <label for="">Add Email Addresses</label>
                <textarea class="form-control" rows="3" id='exp_guest_emails'></textarea> 
                <div class="row">
                  <div class="col-xs-6"><small>seperate with commas (,)</small></div>
                </div>
                <div class="row">
                  <div class="col-xs-12 hidden" id="exp_error_email"><small class="error">Please enter a valid email.</small></div>
                  <div class="col-xs-12 hidden" id="exp_error_email_count"><small class="error">You cannot enter more than 10 email ids.</small></div>
                </div>
              </div>

              <div class="form-group">
                <textarea class="form-control" rows="3" placeholder="Enter a personal message here." id='exp_det_content'></textarea>
                <!--<div class="row">
                  <div class="col-xs-12 hidden" id="error_content"><small>Please enter your message.</small></div>
                </div>-->
                <div class="col-xs-12 reservation-msg">
                    <p>The email to your party will include your personal message above and link to this page.</p>
                </div>                
              </div>
			  <input type="hidden" name="email_experience_id" value="<?php echo $arrExperience['data']['id']?>">
			  <input type="hidden" name="user_email" value="<?php echo (Session::has('email')) ? Session::get('email') : "null";?>">
              <button type="submit" class="btn btn-warning btn-block" id="share_exp_details">Share Details</button>
            </form>
            </div>
            <div id="exp_email_sent_confirmation" class="hidden">
                <div class="col-xs-12 reservation-msg">
                    <p>Your message has been sent</p>
                    <button type="button" class="btn btn-warning btn-block" id="close_exp_mail_sent" data-dismiss="modal" aria-hidden="true">Close This</button>
                </div>
            </div>
            </div>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
  

  <!-- Modal for email experience ends here-->
  <style>
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
   }
#redirectAlacarteLocationModal .modal-header, #redirectExperienceEmailModal .modal-header {
    background: none repeat scroll 0 0 #000;
}
#redirectAlacarteLocationModal .modal-header h4, #redirectExperienceEmailModal .modal-header h4{
    color: #fff;
}
#redirectExperienceEmailModal .modal-content{
	margin-left: 16%;
    margin-top: 20%;
    width: 59% !important;  
}
#redirectAlacarteLocationModal .panel {
    background: none repeat scroll 0 0 transparent;
    border: medium none;
    margin-bottom: 0;
}
.panel {
    background-color: #fff;
    border: 1px solid transparent;
    border-radius: 4px;
    box-shadow: 0 1px 1px rgba(0, 0, 0, 0.05);
    margin-bottom: 20px;
}
.panel-default {
    border-color: #ddd;
}
#redirectAlacarteLocationModal .a-list-group, #redirectAlacarteLocationModal .a-list-group li {
    background: none repeat scroll 0 0 transparent;
    color: #fff;
}
.panel > .a-list-group {
    margin-bottom: 0;
}
.a-list-group {
    margin-bottom: 20px;
    padding-left: 0;
}
#redirectAlacarteLocationModal .a-list-group li {
    border-bottom: 1px solid #444242;
    border-top: medium none;
}
#redirectAlacarteLocationModal .a-list-group li a {
    color: #000;
    font-size: 120%;
    outline: medium none;
    position: relative;
    text-decoration: none;
}
.a-list-group-item {
    background-color: #fff;
    border: 1px solid #ddd;
    display: block;
    margin-bottom: -1px;
    padding: 10px 15px;
    position: relative;
}
.alacarte_reservation_text{
	color: #eab803;
    cursor: pointer;
    font-size: 110% !important;
    text-decoration: none;
    text-transform: capitalize;
}
	
</style>
  <!--Modal for selecting alacarte location from experiences reservation modal-->
  <script type="text/javascript">
      //code for floating reservation button
	    $(function() {
             var offsetPixels = 50; // change with your sidebar height

             $(window).scroll(function() {
                     if ($(window).scrollTop() > offsetPixels) {
                             $(".scrollingBox").css({
                                     "position": "fixed",
                                     "top": "88%"
                             });
                     } else {
                             $(".scrollingBox").css({
                                     "position": "relative",
                                     "top": "88%"
                             });
                     }
             });
      }); 
       
      function changeClass()
      {
            document.getElementById("menu_tab").classList.add('active');
            document.getElementById("info_tab").classList.remove('active');
            document.getElementById('menu').className = "tab-pane fade in active"; 
            document.getElementById('info').className = "tab-pane fade";
            
       }

       
  	    var disabledAllDays = <?php echo json_encode($block_dates);?>;
        var allschedule = <?php echo json_encode($schedule);  ?>;
        var reserveminmax = <?php echo json_encode($reserveData);  ?>;

        function disableAllTheseDays(date) {
            var m = date.getMonth(), d = date.getDate(), y = date.getFullYear(),mon="",day="";
            var location_id = $('#locations1').val();
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

    				$(".a-list-group-item").on('click',function(){
    					var v = $(this).attr('data-alacarte_link');
    					window.location.href=v;
    				});

            /*reservation strat*/
            loadDatePicker();

            $('#locations1').change(function(){
              $('#party_edit1').trigger('click'); 
              
               loadPartySelect();
               loadDatePicker();
            });


             /*reservation over*/
		   });

       function loadPartySelect()
       {
          var location_id = $('#locations1').val();
          var jsondata = reserveminmax[location_id];
          //console.log(jsondata);
          var selectList = $("#party_size1");
          selectList.find("option:gt(0)").remove();
          
          var min_people = jsondata.min_people;
          var max_people = jsondata.max_people;
          if(parseInt(max_people)>0)
          {
            for(var j = min_people;j <max_people;)
            {
               var optiontext = (j == 1) ? ' Person' : ' People';
               selectList.append('<option value="'+j+'">'+j+optiontext+'</option>')

               j = j+ parseInt(jsondata['increment']);
            }
          }

        }

       function loadDatePicker() {
          $("#choose_date").datepicker("destroy");
         
          $("#choose_date").datepicker({
             dateFormat: 'yy-mm-dd',
             minDate: 'new Date()',
             beforeShowDay: disableAllTheseDays,
             onSelect: function(dateText, inst) 
             {
                    var d = $.datepicker.parseDate("yy-m-dd",  dateText);
                   
                    var datestrInNewFormat = $.datepicker.formatDate( "D", d).toLowerCase();
                    var txt = '<div class="btn-group col-lg-10 pull-right actives ">';
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
                      var location_id = $('#locations1').val();
                      var schedule = allschedule[location_id];
                      if(schedule != undefined)
                      {
                        for(key_sch in schedule[datestrInNewFormat])
                        {   
                            
                            var obj_length = Object.keys(schedule[datestrInNewFormat]).length;
                            active_tab = (g == obj_length) ? 'active' : '' ;
                            active_blck = (g == obj_length) ? '' : 'hidden' ;  
                            txt+= '<label class="btn btn-warning btn-xs time_tab ' + active_tab + '" id="'+key_sch.toLowerCase()+'">'+key_sch.toUpperCase()+'</label>';
                            txt2 +=    '<div id="' + key_sch.toLowerCase() + '_tab"  class="'+active_blck+'">';
                            for(key_sch_time in schedule[datestrInNewFormat][key_sch])
                            {
                               if(c_date == dateText)
                               {
                                 if(String(c_time) < String(schedule[datestrInNewFormat][key_sch][key_sch_time])) {
                                   txt2 += '<div class="time col-lg-3 col-xs-5" rel="' + schedule[datestrInNewFormat][key_sch][key_sch_time] + '"><a href="javascript:">' + schedule[datestrInNewFormat][key_sch][key_sch_time] + '</a></div>';
                                 } 
                               }  
                               else
                               {
                                 txt2 += '<div class="time col-lg-3 col-xs-5" rel="' + schedule[datestrInNewFormat][key_sch][key_sch_time] + '"><a href="javascript:">' + schedule[datestrInNewFormat][key_sch][key_sch_time] + '</a></div>';
                               }                          
                                                       
                            }
                            txt2+= '</div>';    
                            g++;
                        }
                      }
                      /*Time display container*/


                    txt += '</div><div class="clearfix"></div>';
                    txt += '<input type="hidden" name="booking_time" id="booking_time" value="">';
                            $('#hours').html(txt2);
                            $('#time').html(txt);

                    $('#booking_date').val(dateText);


                    
                    $('#date_edit1 span').text(formatDate(dateText));
                    $('#date_edit1').click();
                    timehide=0;
                    $('#time_edit1').click(); 
              }
          });
        $( "#choose_date" ).datepicker("refresh");
      }


  </script>
@endsection