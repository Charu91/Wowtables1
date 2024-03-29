@extends('frontend.templates.inner_pages')

@section('content')

<?php 
if(Session::has('id'))
	$set_user_id = Session::get('id');
else
	$set_user_id = 0;
?>
<?php //error_reporting(0);?>
<script type="text/javascript" src="{{URL::to('/')}}/assets/js/jquery.lazyload.js"></script>
<!--<script type="text/javascript" src="{{URL::to('/')}}/js/jquery-ui-1.10.0.custom.min.js"></script>-->
<!--<script type="text/javascript" src="{{URL::to('/')}}/assets/js/search.js"></script>-->
<link rel="stylesheet" href="{{URL::to('/')}}/assets/css/jquery-ui.css">
<style>
	.lazy{
		height:40% !important;
		width:100% !important;
		background: #ebebeb repeat;
	}
	#ui-datepicker-div{
		z-index:2 !important;
	}
	.ui-autocomplete { max-height: 200px; max-width:300px;overflow-y: scroll; overflow-x: hidden;}
</style>

<?php 
	function list_page_url() {
		 $pageURL = 'http';
		 $pageURL .= "://";
		 if ($_SERVER["SERVER_PORT"] != "80") {
		  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		 } else {
		  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		 }
		 return $pageURL;
		}
		$list_page_url =  list_page_url();
		$session_user_status = Session::get('new_user_status');
?>
<script type="text/javascript">
	$(document).ready(function(){
		var sess_usr_status = '<?php echo $session_user_status;?>';
		if(sess_usr_status == 'false'){
			mixpanel.register({"New User":'False'});
		}
		var curr_url = '<?php echo $list_page_url;?>';
		mixpanel.track("Landing Page",{"Page Type":'List Page','Url':curr_url});
	});
</script>


<script type="text/javascript">
function toggleClass(el,exp_id,user_id){ 
	//var exp_id = $(this).attr('data-exp_id');
	//var user_id = $(this).attr('data-user_id');

	if(el.className == "bookmark_plain"){ 
		//mark
		el.className = "bookmark_marked"; 

		if(user_id != 0){
			$.ajax({
				url: "{{URL::to('/')}}/custom_search/set_bookmark",
				type:"post",
				dataType: "JSON",
				data: {expid:exp_id,userid:user_id},
				success: function( data ) {
				}
			});
		}

		//console.log("exp id = "+exp_id+" , user id = "+user_id);

	} else { 
		//unmark
		el.className = "bookmark_plain"; 

		if(user_id != 0){
			if(user_id != 0){
				$.ajax({
					url: "{{URL::to('/')}}/custom_search/unset_bookmark",
					type:"post",
					dataType: "JSON",
					data: {expid:exp_id,userid:user_id},
					success: function( data ) {
					}
				});
			}
		}

		//console.log("exp id = "+exp_id+" , user id = "+user_id);

	} 
}

$(function() {
     $("img.lazy").lazyload({
         effect : "fadeIn"
     });

  });
/*function isOnscreen(elem){
    var window_height = $(window).scrollTop()+screen.height;
    var el = elem.offset();
    if(window_height >= el.top){
        return true;
    } else {
        return false;
    }
}

function load_images() {
    $('.deal-img > img').each(function(i){
        if (isOnscreen($(this))) {
            $(this).attr('src', $(this).attr('rel'));
        }
    });
}

$(document).ready(function(){
    load_images();
})

$(window).resize(function(){
    load_images();
});
$(window).scroll(function(){
    load_images();
});*/
$(document).ready(function(){

	$( "#datepicker" ).datepicker({
		showOtherMonths: true,
		selectOtherMonths: true,
		minDate: 0
	});

	$( "#datepicker-small" ).datepicker({
		showOtherMonths: true,
		selectOtherMonths: true,
		minDate: 0
	});
});

</script>
<input type="hidden" name="current_city" value="<?php echo $current_city;?>">
<input type="hidden" id="slug" value="listing">
		<div class="col-md-8 col-sm-8 deal-listing-left">
			<div style="padding-bottom:10px;">
				<a href="http://go.onelink.me/1433356199?pid=Website&c=Listing_Banner" target="_blank"><img src="/assets/img/app.jpg" class="img-responsive"/></a>
					<?php //if($current_city == "mumbai"){?>
						<!--<a href="http://wowtables.com/collection/the-buzz" target="_blank"><img src="/assets/img/app.jpg" class="img-responsive"/></a>-->
					<?php //} else {?>
						<!--<a href="http://go.onelink.me/1433356199?pid=Website&c=Listing_Banner" target="_blank"><img src="/assets/img/app.jpg" class="img-responsive"/></a>-->
					<?php //}?>
			</div>
			<div class="row">
            <!-- filter for small screen -->
            <div class="col-md-12 visible-xs visible-sm">
              <div class="widget filter-widget-wrap small">
                <h3 class="text-center">Refine your search</h3>
				<span id="date_error_small" style="color:red;display:none;padding: 10px;text-align: justify;">Selected date should be greater than or equal to todays date!</span>
                <div class="filter-widget">
                  <form role="form" id="custom_refine_search_form">
                    <div class="form-group">
                      <label for="">Search</label> 
                      <div class="input-group ">
                        <input type="text" id="search_by_rest" data-items="4" data-provide="typeahead" class="form-control" placeholder="Restaurant, Cuisine or Suburb"><span class="input-group-addon" id="Manual_Search" style="cursor:pointer;"><i class="glyphicon glyphicon-search"></i></span>
                      </div>
                    </div>                                             
					<span class="search-ajax-loader"></span>
				  <table style="color:white;cursor:pointer;background-color:grey;" class="search_by_results">
					<tbody></tbody>
				  </table>
                  <div class="more-filter">
                    <div class="panel-group" id="accordion-small">
                      <div class="panel panel-default">
                        <div class="panel-heading">
                          <p class="panel-title text-center">
                            <a data-toggle="collapse" data-parent="#accordion-small" href="#collapseOneSmall">
                              More Search Options <span class="caret"></span>
                            </a>
                          </p>
                        </div>
                        <div id="collapseOneSmall" class="panel-collapse collapse">
                          <div class="panel-body">
  
                          <div class="form-group date-group">
                      <label for="">Select a Date</label>
                      <div class="input-group">
                        <input type="text" id="datepicker-small" class="form-control" placeholder="">
                      </div>
                    </div>
                    <div class="form-group time-group">
                      <label for="">Select a Time</label>
                        <select class="form-control" id="Search_By_Time" placeholder="Choose a time">
                          <option value="">--Select--</option>  
						  <option value="lunch">Lunch</option>
						  <option value="dinner">Dinner</option>
						  <?php 
						 $time = array("12:00"=>"12 pm", "13:00"=>"1 pm", "14:00"=>"2 pm", "15:00"=>"3 pm", "16:00"=>"4 pm"
						 , "17:00"=>"2 pm", "18:00"=>"6 pm", "19:00"=>"7 pm", "20:00"=>"8 pm", "21:00"=>"9 pm", "22:00"=>"10 pm", "23:00"=>"11 pm" 
						 );
						  
						  ?>
						  <option value="">----</option>
						  <?php foreach($time as $key => $value ){
                            echo "<option value=$key>$value</option>";
												}					
												?>
						  
                        </select>
                    </div>
                    <div class="form-group">
                      <label for="amount">Price range:</label>
                      <input type="text" id="amount-small" readonly>
                      <div id="slider-range-small"></div>             
                    </div>
                            <div class="area option">
                              <p class="lead">Area</p>
                              <div class="dynamic_areas">
								<?php  
								if(isset($filters['locations']))
								{
									foreach($filters['locations'] as $key => $allAreasData){  ?>
									<div class="checkbox">
									  <label>
										<input class="search_by_place" type="checkbox" value="<?php echo $allAreasData['id'];?>">
										<?php echo $allAreasData['name'];?> <span class="badge"><?php echo $allAreasData['count'];?></span>
									  </label>
									  </div>
									<?php }
								}
								?>
							  </div>
                            </div>
                            <div class="cuisine option">
                              <p class="lead">Cuisine</p>
                              <div class="dynamic_cuisine">
								<?php  
								if(isset($filters['cuisines']))
								{
									foreach($filters['cuisines'] as $key => $allCuisinesData)
									{  
										?>
										<div class="checkbox">
										  <label>
											<input class="search_by_cuisine" type="checkbox" value="<?php echo $allCuisinesData['id'];?>">
											<?php echo $allCuisinesData['name'];?> <span class="badge"><?php echo $allCuisinesData['count'];?></span>
										  </label>
										  </div>
										<?php 
									}
								}
								?>
							  </div>
                            </div>
                            <div class="tags option">
                              <p class="lead leads_tag">Tags</p>
                              <div class="btn-group dynamic_tags" data-toggle="buttons">
								<?php  
								if(isset($filters['tags']))
								{
									foreach($filters['tags'] as $key => $alltagsData)
									{  
										?>
										<label class="btn btn-warning">
											<input type="checkbox" class="search_by_tags" value="<?php echo $alltagsData['id'];?>"> <?php echo $alltagsData['name']?>
										</label>  
										<?php 
									}
								}
								?>
							  </div>
                            </div>
                          </div>

                        </div>
                      </div>
                    </div>
                  </div>  
                  <p class="text-center" style="margin-top: 15px;"><button class="btn btn-warning text" type="button" id="reset_filters">Reset Filters</button></p>
				  <!--<p class="small text-center"><a href="#" id="reset_filters">Reset Filters</a></p>-->
                  </form>          
                </div>
              </div>
            </div> <!-- end filter widget -->

			<div id="left-content" style="clear:both;">
				<div id="exp_list_load_layer" class="hidden">
					<img src="{{URL::to('/')}}/images/Loading-Image.gif">
				</div>
			<div class="col-sm-6">
				<?php $set_exp_name = (($resultCount == 1) ? "experience" : "experiences")?>
				<p class="sort-info"><?php echo ((($resultCount)>0) ? $resultCount." ".$set_exp_name." match your search criteria" : "No experiences match your search criteria"); ?></p>
			</div>
			<div class="col-sm-6">
				<span style="display:none;margin-top: -30px;position: absolute" class="show_loading_img"><img src="<?php echo URL::asset('assets/img/loading.gif');?>" title='Loading' /></span>
			</div>
				<div class="col-sm-6">
					<div class="row">
						<div class="col-sm-3">
							<p class="sort-info">Sort by</p>
						</div>
						<div class="col-sm-9 text-center">
							<div>
								<select name="sort_results" class="form-control sort_results" >
								  <option value="popular">Popularity</option>
								  <option value="new">New</option>
								</select>
							  </div>
						</div>
					</div>
				</div>
			            <div class="clearfix"></div>
			<div class="col-sm-12">
              <div class="divider"></div>
            </div>

				<?php if(empty($resultCount)):?>
					<p class="lead">
						<em>No experiences match your current search, please refine or expand your current search.<a href="javascript:void(0);" title="Clear All Filters" class="clear_filters">Clear All Filters</a></em>
					</p>
				<?php endif;?>
				<?php if($current_city == 'mumbai'){ ?>
					
				<?php } else if ($current_city == 'delhi'){ ?>
					
					
				<?php } else if($current_city == 'pune'){ ?>
					
					
				<?php 
				}
				?>
				
				<div class="widget conc-mobile col-md-12 visible-xs text-center">
					<a href="#">
						<span class="orange">MAKE A RESERVATION ONLINE</span><br/>OR<br/>CALL OUR CONCIERGE AT <span class="orange">9619551387</span>
					</a>
				</div>
				<?php
					$total_rows = $resultCount; ?>
                <ul class="experience-grid">   
				<?php                                                       
                    for($j_count=0;$j_count<$total_rows;$j_count++){
						//echo "<pre>"; print_r($rows);
				?>
						
							<li class="col-md-6 col-sm-12 col-xs-12 col-lg-6">              
									<div>
										<a href="{{URL::to('/')}}/<?php echo $current_city;?>/experiences/<?php echo $data[$j_count]['slug'];?>">
											<table class="deal-head">
												<tr>
													<td>
														<?php echo $data[$j_count]['name'];?>
													</td>
													<td>
														<span> View</span> Details
														<?php 
															/*if ( $data[$j_count]['intval'] && $data[$j_count]['tickets_sold'] < $data[$j_count]['max_num_orders']  ) {
																if( $data[$j_count]['coming_soon']=='1'){?>
																	<span>Coming</span> Soon
																<?php }else{ ?>
																	<span> View</span> Details
																<?php }
															   } else { ?>
																<span>Sold</span> out
															<?php };
															*/ ?>
													</td>
												</tr>
											</table>
										</a>

										<div class="deal-img">
											<img src="<?php echo isset($data[$j_count]['image']['listing'])?$data[$j_count]['image']['listing']:'';?>" alt="image1" class="img-responsive"/>							
												<?php
												if(isset($data[$j_count]['flag']) && $data[$j_count]['flag'] != "") {?>
												<div class="flag new valign" id="top_paddin"style="background:<?php echo $data[$j_count]['color'];?>"><?php echo $data[$j_count]['flag'];?></div>
											<?php } 
											/* 
											<div class="bookmark valign balign" id="top_alignmen">
												<div class="<?php echo ((isset($data[$j_count]['bookmark_status']) && $data[$j_count]['bookmark_status'] == 1 && (isset($data[$j_count]['bookmark_userid']) && $data[$j_count]['bookmark_userid'] == $set_user_id))? "bookmark_marked" : "bookmark_plain")?>" onclick="toggleClass(this,<?php echo $data[$j_count]['id']?>,<?php echo $set_user_id?>)"></div>
											</div>
											*/?>
											<div class="deal-desc" >
												<p><?php echo $data[$j_count]['short_description'];?></p>
											</div>
										</div>		

										<div class="discount">
											<div class="col-xs-7">
												<div>
													<span class="star-all"> <?php ?>
														<?php if((isset($data[$j_count]['full_stars']) && $data[$j_count]['full_stars'] != "" && $data[$j_count]['full_stars'] != 0)) {?>
														<?php for($c=0;$c<floor($data[$j_count]['full_stars']);$c++){ ?>
															<span class="star-icon full star_icon_exper">&#9733;</span>
														<?php }
														}?>
														<?php if((isset($data[$j_count]['half_stars']) && $data[$j_count]['half_stars'] != "" && $data[$j_count]['half_stars'] != 0)) {?>
															<span class="star-icon half">&#9733;</span>
														<?php } ?>
														<?php if((isset($data[$j_count]['blank_stars']) && $data[$j_count]['blank_stars'] != "" && $data[$j_count]['blank_stars'] != 0)){?>
														<?php for($c=1;$c<=$data[$j_count]['blank_stars'];$c++){?>
															<span class="star-icon">&#9733;</span>
														<?php }?>
														<?php } ?>
													</span>
													<span class="rating text-center"><?php echo (isset($data[$j_count]['total_reviews']) && $data[$j_count]['total_reviews'] > 0) ? "(".$data[$j_count]['total_reviews']." Reviews)" : "";?></span>
												</div>                  
											</div>
											<div class="col-xs-5 desc-price text-center">
												<?php if(isset($data[$j_count]['price']) && $data[$j_count]['price'] > 0 ) {?>
													<p>Rs <?php echo number_format($data[$j_count]['price'],0);?><span class="small">(<?php echo $data[$j_count]['price_type'];?>)</span></p>
												<?php } else {?> <p>&nbsp;<span class="small">&nbsp;</span></p><?php } ?>
											</div>
											<div class="clearfix"></div>
										</div>
									</div>
								  </li>
						
					<?php } //end for ?> 
                </ul>
			</div>
		</div>  

		</div>
		
		<div class="col-md-4 col-sm-4 deal-listing-right">
		
			<div class="widget filter-widget-wrap hidden-xs hidden-sm">
          <h3 class="text-center">Refine your search</h3>
		 <!-- <span id="date_error" style="color:red;display:none;padding: 10px;text-align: justify;">Selected date should be greater than or equal to todays date!</span>-->
            <div class="filter-widget">
              <form role="form" id="custom_refine_search">
                <div class="form-group">
                  <label for="">Search</label> 
                  <div class="input-group ">
                    <input type="text" data-items="4" data-provide="typeahead" class="form-control" id="search_by" placeholder="Restaurant, Cuisine or Suburb" ><span style="cursor:pointer;"class="input-group-addon" id="manual_search"><i class="glyphicon glyphicon-search"></i></span>
                  </div>
				  <span class="search-ajax-loader"></span>
				  <input type="hidden" name="id-holder" id="id-holder"> 
				  <table style="color:white;cursor:pointer;background-color:grey;" class="search_by_results">
					<tbody></tbody>
				  </table>
                </div>
                <div class="form-group date-group">
                  <label for="">Select a Date</label>
                  <div class="input-group">
                    <input type="text" id="datepicker" class="form-control" placeholder=""><!--<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>-->
                  </div>
                </div>
                <div class="form-group time-group">
                  <label for="">Select a Time</label>
                    <select class="form-control1" id="search_by_time" placeholder="Choose a time">
                      <option value="">--Select--</option>  
                      <option value="lunch">Lunch</option>
                      <option value="dinner">Dinner</option>
                      <option value="">----</option>
                      <option value="12:00">12 pm</option>
					  <option value="13:00">1 pm</option>
					  <option value="14:00">2 pm</option>
					  <option value="15:00">3 pm</option>
					  <option value="16:00">4 pm</option>
					  <option value="17:00">5 pm</option>
					  <option value="18:00">6 pm</option>
					  <option value="19:00">7 pm</option>
					  <option value="20:00">8 pm</option>
					  <option value="21:00">9 pm</option>
					  <option value="22:00">10 pm</option>
					  <option value="23:00">11 pm</option>
                    </select>
                </div>
                <div class="form-group">
                  <label for="amount">Price range:</label>
                  <input type="text" id="amount" readonly>
                  <div id="slider-range"></div>             
                </div>                               
              
              <div class="more-filter">
                <div class="panel-group" id="accordion">
                  <div class="panel panel-default">
                    <div class="panel-heading">
                      <p class="panel-title text-center">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                          More Search Options <span class="caret"></span>
                        </a>
                      </p>
                    </div>
                    <div id="collapseOne" class="panel-collapse collapse">
                      <div class="panel-body">                        
                        <div class="area option">
                          <p class="lead">Area</p>
						  <div class="dynamic_areas">
							<?php  
								if(isset($filters['locations']))
								{
									foreach($filters['locations'] as $key => $allAreasData){  ?>

									<div class="checkbox">
									  <label>
										<input class="search_by_place" type="checkbox" value="<?php echo $allAreasData['id'];?>">
										<?php echo $allAreasData['name'];?> <span class="badge"><?php echo $allAreasData['count'];?></span>
									  </label>
									  </div>
									<?php }
								}
								?>
                          </div>
                        </div>
                        <div class="cuisine option">
                          <p class="lead">Cuisine</p>
                          <div class="dynamic_cuisine">
							<?php  
								if(isset($filters['cuisines']))
								{
									foreach($filters['cuisines'] as $key => $allCuisinesData)
									{  
										?>
										<div class="checkbox">
										  <label>
											<input class="search_by_cuisine" type="checkbox" value="<?php echo $allCuisinesData['id'];?>">
											<?php echo $allCuisinesData['name'];?> <span class="badge"><?php echo $allCuisinesData['count'];?></span>
										  </label>
										  </div>
										<?php 
									}
								}
								?>
                          </div>
                        </div>
                        <div class="tags option">
                          <p class="lead leads_tag">Tags</p>
                          <div class="btn-group dynamic_tags" data-toggle="buttons">
                            <?php  
								if(isset($filters['tags']))
								{
									foreach($filters['tags'] as $key => $alltagsData)
									{  
										?>
										<label class="btn btn-warning">
											<input type="checkbox" class="search_by_tags" value="<?php echo $alltagsData['id'];?>"> <?php echo $alltagsData['name']?>
										</label>  
										<?php 
									}
								}
								?>
                          </div>
                        </div>
                      </div>

                    </div>
                  </div>
                </div>
              </div>  
              <p class="text-center" style="margin-top: 15px;"><button class="btn btn-warning text" type="button" id="reset_form">Reset Filters</button></p>
			  <!--<p class="small text-center"><a href="javascript:void(0);" id="reset_form">Reset Filters</a></p>-->
			  </form>
            </div>
          </div>

			<?php foreach($ListpageSidebars as $sidebars){ ?>
				<div class="widget">

					<?php if(isset($sidebars->link) && $sidebars->link != ''){ ?>
						<a href="<?php echo $sidebars->link;?>">
					<?php } ?>
						<img src="<?php echo $listpage_sidebar_url.$sidebars->imagename;?>" class="img-responsive" alt="WowTables Concierge">
					<?php if(isset($sidebars->link) && $sidebars->link != ''){ ?>
						</a>
					<?php } ?>
					<?php if((isset($sidebars->promotion_title) && $sidebars->promotion_title != '') && (isset($sidebars->description) && $sidebars->description != '')){ ?>
						<div class="desc">
							<p><?php echo $sidebars->promotion_title;?></p>
							<p class="small"> <?php echo strip_tags($sidebars->description);?></p>
						</div>
					<?php } ?>

				</div>
			<?php } ?>


        </div>
	</div>
</div>
<input type='hidden' name='hdn_search_type' id='hdn_search_type' value=""/>
<input type='hidden' name='hdn_search_id' id='hdn_search_id' value=""/>
<script> // Jquery slider 
      $(document).ready(function(){
        $( "#slider-range" ).slider({
          range: true,
          min: 0,
          max: 15000,
          values: [ 0, 15000 ],
        slide: function( event, ui ) {
        $( "#amount" ).val( "Rs " + ui.values[ 0 ] + " - Rs " + ui.values[ 1 ] );
         },
			stop:function(event, ui){
			var time_val = $("#search_by_time").val();
			var date_val = $("#datepicker").val();
			var rest_val = $("#search_by").val();
			var start_from = ui.values[0];
			var end_with = ui.values[1];
			var c = $("#uri_city_id").val();
			//console.log("ajax call for according to price "+ui.values[ 1 ]+" , "+ui.values[ 0 ]);
			//console.log("time value = "+time_val+" , date value = "+date_val+" , rest value = "+rest_val);
			$.ajax({
				url: "/custom_search/search_filter",
				dataType: "JSON",
				type: "post",
				data: {restaurant_val : rest_val,date_value : date_val,time_value : time_val,start_price: start_from, end_price : end_with,city: c},
				beforeSend:function(){
					//$(".show_loading_img").css("display","block");
					$('#exp_list_load_layer').removeClass('hidden');
				},
				success: function(d) {
					//$("#results").append(d);
					//console.log(d);
					var area_replace = '';
		            $.each(d.area_count,function(index, valueData){
		              area_replace += '<div class="checkbox"><label><input class="search_by_place" type="checkbox" value="'+valueData.id+'">'+valueData.name+'<span class="badge">'+valueData.count+'</span></label></div>'
		            });


					var cuisine_replace = '';
		            $.each(d.cuisine_count,function(index, valueData){
		              cuisine_replace += '<div class="checkbox"><label><input class="search_by_cuisine" type="checkbox" value="'+valueData.id+'">'+valueData.name+'<span class="badge">'+valueData.count+'</span></label></div>'
		            });

		            var tags_replace = '';
		            $.each(d.tags_count,function(index, valueData){
		              tags_replace += '<label class="btn btn-warning"><input type="checkbox" class="search_by_tags" value="'+valueData.id+'"> '+valueData.name+'</label>'
		            });

					$("#left-content").fadeOut(500, function() {
						$("#left-content").empty();
						$("#left-content").html(d.restaurant_data);
					});
					//console.log(text);
					if(area_replace == "") {
						area_replace = "No Areas found";
					}
					if(cuisine_replace == "") {
						cuisine_replace = "No Cuisine found";
					}
					if(tags_replace == "") {
						tags_replace = "No Tags found";
					}
					$(".dynamic_areas").html(area_replace);
					$(".dynamic_cuisine").html(cuisine_replace);
					$(".dynamic_tags").html(tags_replace);
				},
				complete: function() {
					$(".show_loading_img").css("display","none");
					$("#left-content").fadeIn(500);
					$('#exp_list_load_layer').addClass('hidden');
					$('html, body').animate({
						scrollTop: $('#left-content').offset().top
					}, 'slow');
				},
				timeout: 9999999
			});
		  }
        });
        $( "#amount" ).val( "Rs " + $( "#slider-range" ).slider( "values", 0 ) +
        " - Rs " + $( "#slider-range" ).slider( "values", 1 ) );

            $('.input-group.date').datepicker({
          });
      });
      $(document).ready(function(){
        $( "#slider-range-small" ).slider({
          range: true,
          min: 0,
          max: 15000,
          values: [ 0, 15000 ],
        slide: function( event, ui ) {
        $( "#amount-small" ).val( "Rs " + ui.values[ 0 ] + " - Rs " + ui.values[ 1 ] );
          },stop:function(event, ui){
			var time_val = $("#search_by_time").val();
			var date_val = $("#datepicker").val();
			var rest_val = $("#search_by").val();
			var start_from = ui.values[0];
			var end_with = ui.values[1];
			var c = $("#uri_city_id").val();
			//console.log("ajax call for according to price "+ui.values[ 1 ]+" , "+ui.values[ 0 ]);
			//console.log("time value = "+time_val+" , date value = "+date_val+" , rest value = "+rest_val);
			$.ajax({
				url: "/custom_search/search_filter",
				dataType: "JSON",
				type: "post",
				data: {restaurant_val : rest_val,date_value : date_val,time_value : time_val,start_price: start_from, end_price : end_with,city: c},
				beforeSend:function(){
					//$(".show_loading_img").css("display","block");
					$('#exp_list_load_layer').removeClass('hidden');
				},
				success: function(d) {
					//$("#results").append(d);
					//console.log(d);
					var area_replace = '';
		            $.each(d.area_count,function(index, valueData){
		              area_replace += '<div class="checkbox"><label><input class="search_by_place" type="checkbox" value="'+valueData.id+'">'+valueData.name+'<span class="badge">'+valueData.count+'</span></label></div>'
		            });

					var cuisine_replace = '';
		            $.each(d.cuisine_count,function(index, valueData){
		              cuisine_replace += '<div class="checkbox"><label><input class="search_by_cuisine" type="checkbox" value="'+valueData.id+'">'+valueData.name+'<span class="badge">'+valueData.count+'</span></label></div>'
		            });

		            var tags_replace = '';
		            $.each(d.tags_count,function(index, valueData){
		              tags_replace += '<label class="btn btn-warning"><input type="checkbox" class="search_by_tags" value="'+valueData.id+'"> '+valueData.name+'</label>'
		            });

					$("#left-content").fadeOut(500, function() {
						$("#left-content").empty();
						$("#left-content").html(d.restaurant_data);
					});
					//console.log(text);
					if(area_replace == "") {
						area_replace = "No Areas found";
					}
					if(cuisine_replace == "") {
						cuisine_replace = "No Cuisine found";
					}
					if(tags_replace == "") {
						tags_replace = "No Tags found";
					}
					$(".dynamic_areas").html(area_replace);
					$(".dynamic_cuisine").html(cuisine_replace);
					$(".dynamic_tags").html(tags_replace);
				},
				complete: function() {
					$(".show_loading_img").css("display","none");
					$("#left-content").fadeIn(500);
					$('#exp_list_load_layer').addClass('hidden');
					$('html, body').animate({
						scrollTop: $('#left-content').offset().top
					}, 'slow');
					
				},
				timeout: 9999999
			});
		  }
        });
        $( "#amount-small" ).val( "Rs " + $( "#slider-range-small" ).slider( "values", 0 ) +
        " - Rs " + $( "#slider-range-small" ).slider( "values", 1 ) );

            $('.input-group.date').datepicker({
          });
		  //search by cuisine/restaurant/area and brings the dropdown and appends it to the table which is below the search bar (Full resolution)
		  var search_var = 0;
		 
			var c = $("#uri_city_id").val();


			$('#search_by, #search_by_rest').autocomplete({			
				
				source: function( request, response ) {

					$.ajax({
						url: "/custom_search/new_custom_search",
						dataType: "JSON",
						data: {
							term: request.term,city : c
						},
						success: function( data ) {
                            //console.log('response for all== '+data);
							response( data );
						}
					});
				},
				focus: function( event, ui ) { //console.log('ui = '+ui.item.label);
					var itemArr = ui.item.label.split('~~~');
					$( this ).val( itemArr[0] );
					return false;
				},
				select: function(event,ui){
					event.preventDefault();
					var itemArr = ui.item.value.split('~~~');
					var rest_val = itemArr[0];
					var date_val = $("#datepicker").val();
					var time_val = $("#search_by_time").val();
					var amount_value = $("#amount").val();
					var final_amount = amount_value.split(' ');
					var start_from = final_amount[1];
					var end_with = final_amount[4];
					var c = $("#uri_city_id").val();
					var sList1        = "";
					var sList2        = "";
					var sList         = "";

					if(itemArr[2] == 'location' || itemArr[2] == 'no_data')
					{
						sList1	= 	itemArr[1]
					}

					if(itemArr[2] == 'cuisine')
					{
						sList2	= 	itemArr[1]
					}

					if(itemArr[2] == 'vendor')
					{
						sList	= 	itemArr[1]
					}

					$( "#search_by_rest" ).val( rest_val);
					$( "#search_by" ).val( rest_val );

					$( "#hdn_search_id" ).val( itemArr[1]);
					$( "#hdn_search_type" ).val(itemArr[2]);

					search_var = 1;
					//console.log("rest_val = "+rest_val+" , date_val = "+date_val+" , time_val = "+time_val+" , amount_val = "+amount_value);
					$.ajax({

						url: "{{URL::to('/')}}/custom_search/search_filter",
						dataType: "JSON",
						type: "post",
						//data: {restaurant_val : rest_val,date_value : date_val,time_value : time_val},
						data: {restaurant_val : rest_val,date_value : date_val,time_value : time_val,start_price: start_from, end_price : end_with, city: c,area_values : sList1,cuisine_values : sList2,vendor_value : sList},
						beforeSend:function(){
							//$(".show_loading_img").css("display","block");
							$('#exp_list_load_layer').removeClass('hidden');
						},
						success: function(d) {
						  //console.log(d.area_count);
						  var area_replace = '';
				            $.each(d.area_count,function(index, valueData){
				              area_replace += '<div class="checkbox"><label><input class="search_by_place" type="checkbox" value="'+valueData.id+'">'+valueData.name+'<span class="badge">'+valueData.count+'</span></label></div>'
				            });

						  var cuisine_replace = '';
				            $.each(d.cuisine_count,function(index, valueData){
				              cuisine_replace += '<div class="checkbox"><label><input class="search_by_cuisine" type="checkbox" value="'+valueData.id+'">'+valueData.name+'<span class="badge">'+valueData.count+'</span></label></div>'
				            });

				            var tags_replace = '';
				            $.each(d.tags_count,function(index, valueData){
				              tags_replace += '<label class="btn btn-warning"><input type="checkbox" class="search_by_tags" value="'+valueData.id+'"> '+valueData.name+'</label>'
				            });
						  $("#left-content").fadeOut(500, function() {
								$("#left-content").empty();
								$("#left-content").html(d.restaurant_data);
							});
						if(area_replace == "") {
							area_replace = "No Areas found";
						}
						if(cuisine_replace == "") {
							cuisine_replace = "No Cuisine found";
						}
						if(tags_replace == "") {
							tags_replace = "No Tags found";
						}
						  $(".dynamic_areas").html(area_replace);
						  $(".dynamic_cuisine").html(cuisine_replace);
						  $(".dynamic_tags").html(tags_replace);
						  
						},
						complete: function() {
							$(".show_loading_img").css("display","none");
							$("#left-content").fadeIn(500);
							$('#exp_list_load_layer').addClass('hidden');
							$('html, body').animate({
								scrollTop: $('#left-content').offset().top
							}, 'slow');
						},
						timeout: 9999999
					  });
				},
				create: function () {
		          $(this).data('ui-autocomplete')._renderItem = function (ul, item) {
		               itemArr = item.value.split('~~~');
						return $( "<li>" )
						.append( "<a data-id='"+itemArr[1]+"' data-type='"+itemArr[2]+"'>"+itemArr[0] + "</a>" )
						.appendTo( ul );
		            };
		        },	
				minLength: 1
			})

			var search_var = 0;
			var search_var = 0;
	
	

      });      
    </script>
<script>
inpage=0;
</script>

@endsection