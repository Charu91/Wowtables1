$(document).ready(function(){
	var search_var = 0;
	//search by cuisine/restaurant/area and brings the dropdown and appends it to the table which is below the search bar (Mobile resolution)
			$("#search_by_rest").on('keyup',function(){
				var e = $(this).val();
				var c = $("#uri_city").val();
				//$("#search_error").html("");
				search_var = 1;
				if(e.length > 0 ){
					$(".search-ajax-loader").show();
					$.ajax({
						url: "custom_search/new_custom_search",
						type: "POST",
						dataType: "json",
						data: {
							search: e,city: c
						},
						success: function(e) {
							$(".search-ajax-loader").hide();
								var t = "";
								var n = "";
								if (e.length == 0) {
									t = "<tr><td colspan='2'><p class='text-center'>There are no any experiences!</p></td></tr>"
								} else {
									$.each(e, function(d,e) {
										//console.log("here"+e);
										t += "<tr>";
										t += "<td class='select_dropdownvalue' data-dropdown_value='"+e+"'>" + ucfirst(e);
										t += "</td></tr>"
									});
								}
								$(".search_by_results tbody").html(t);
								//console.log("results = "+e);
						},
						timeout: 9999999
					});
				}else{
					$(".search_by_results tbody").html('');
				};
			});

			//ajax call to bring the relevant cuisine,areas,tags,and restaurant results (for site and mobile resolution)
			/*$("body").delegate(".select_dropdownvalue","click",function(){
				var rest_val = $(this).attr('data-dropdown_value');
				//var rest_val = $(this).val();
				var date_val = $("#datepicker").val();
				var time_val = $("#search_by_time").val();
				var amount_value = $("#amount").val();
				var final_amount = amount_value.split(' ');
				var start_from = final_amount[1];
				var end_with = final_amount[4];
				var c = $("#uri_city").val();
				search_var = 1;
				$("#search_by").val(rest_val);
				$("#search_by_rest").val(rest_val);
				$(".search_by_results tbody").html('');
				console.log("rest_val = "+rest_val+" , date_val = "+date_val+" , time_val = "+time_val+" , amount_val = "+amount_value);
				//ajax call beings required results and according to results bring area,cuisine and tags results if any of above values are not null  
				if(rest_val != "" || date_val != "" || time_val != "" || start_from != "" || end_with != "") {
				  $.ajax({
					//url: "custom_search/search_result",
					url: "custom_search/search_restaurant",
					dataType: "JSON",
					type: "post",
					//data: {restaurant_val : rest_val,date_value : date_val,time_value : time_val},
					data: {restaurant_val : rest_val,date_value : date_val,time_value : time_val,start_price: start_from, end_price : end_with, city: c},
					beforeSend:function(){
						$(".show_loading_img").css("display","block");
					},
					success: function(d) {
					  //console.log(d.area_count);
					  var area_replace = '';
					  $.each(d.area_count,function(index, value){
						//console.log('city' + index + ',  value: ' + value);
						area_replace += '<div class="checkbox"><label><input class="search_by_place" type="checkbox" value="'+index+'">'+index+'<span class="badge">'+value+'</span></label></div>'
					  });

					  var cuisine_replace = '';
					  $.each(d.cuisine_count,function(index, value){
						//console.log('city' + index + ',  value: ' + value);
						cuisine_replace += '<div class="checkbox"><label><input class="search_by_cuisine" type="checkbox" value="'+index+'">'+index+'<span class="badge">'+value+'</span></label></div>'
					  });

					  var tags_replace = '';
					  $.each(d.tags_count,function(index, value){
						//console.log('city' + index + ',  value: ' + value);
						tags_replace += '<label class="btn btn-warning"><input type="checkbox" class="search_by_tags" value="'+index+'"> '+value+'</label>'
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
						$("#left-content").fadeIn(500)
					},
					timeout: 9999999
				  });
				} //ajax call brings default area,cuisine and tags values, if the above variables are null,
				else {
				  $.ajax({
					//url: "custom_search/search_result",
					url: "custom_search/get_area_cuisine_tags",
					dataType: "JSON",
					type: "post",
					data: {city : c},
					//data: {restaurant_val : rest_val,date_value : date_val,time_value : time_val,start_price: start_from, end_price : end_with},
					success: function(d) {
					  //console.log(d.area_count);
					  var area_replace = '';
					  $.each(d.area_data,function(index, value){
						//console.log('city' + index + ',  value: ' + value);
						area_replace += '<div class="checkbox"><label><input class="search_by_place" type="checkbox" value="'+index+'">'+index+'<span class="badge">'+value+'</span></label></div>'
					  });

					  var cuisine_replace = '';
					  $.each(d.cuisine_data,function(index, value){
						//console.log('city' + index + ',  value: ' + value);
						cuisine_replace += '<div class="checkbox"><label><input class="search_by_cuisine" type="checkbox" value="'+index+'">'+index+'<span class="badge">'+value+'</span></label></div>'
					  });

					  var tags_replace = '';
					  $.each(d.tags_data,function(index, value){
						//console.log('city' + index + ',  value: ' + value);
						tags_replace += '<label class="btn btn-warning"><input type="checkbox" class="search_by_tags" value="'+index+'"> '+value+'</label>'
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
					timeout: 9999999
				  });
				  
				}
			});*/

			//ajax call to bring the relevant cuisine,areas,tags,and restaurant results (for site and mobile resolution)
			$("#manual_search").click(function(){
				var rest_val = $("#search_by").val();
				//var rest_val = $(this).val();
				var date_val = $("#datepicker").val();
				var time_val = $("#search_by_time").val();
				var amount_value = $("#amount").val();
				var final_amount = amount_value.split(' ');
				var start_from = final_amount[1];
				var end_with = final_amount[4];
				var c = $("#uri_city").val();
				search_var = 1;
				$("#search_by").val(rest_val);
				$("#search_by_rest").val(rest_val);
				$(".search_by_results tbody").html('');
				//console.log("rest_val = "+rest_val+" , date_val = "+date_val+" , time_val = "+time_val+" , amount_val = "+amount_value);
				//ajax call beings required results and according to results bring area,cuisine and tags results if any of above values are not null  
				if(rest_val != "") {
				  $.ajax({
					//url: "custom_search/search_result",
					url: "custom_search/manual_search",
					dataType: "JSON",
					type: "post",
					//data: {restaurant_val : rest_val,date_value : date_val,time_value : time_val},
					data: {restaurant_val : rest_val,date_value : date_val,time_value : time_val,start_price: start_from, end_price : end_with, city: c},
					beforeSend:function(){
						$(".show_loading_img").css("display","block");
					},
					success: function(d) {
					  //console.log(d.area_count);
					  var area_replace = '';
					  $.each(d.area_count,function(index, value){
						//console.log('city' + index + ',  value: ' + value);
						area_replace += '<div class="checkbox"><label><input class="search_by_place" type="checkbox" value="'+index+'">'+index+'<span class="badge">'+value+'</span></label></div>'
					  });

					  var cuisine_replace = '';
					  $.each(d.cuisine_count,function(index, value){
						//console.log('city' + index + ',  value: ' + value);
						cuisine_replace += '<div class="checkbox"><label><input class="search_by_cuisine" type="checkbox" value="'+index+'">'+index+'<span class="badge">'+value+'</span></label></div>'
					  });

					  var tags_replace = '';
					  $.each(d.tags_count,function(index, value){
						//console.log('city' + index + ',  value: ' + value);
						tags_replace += '<label class="btn btn-warning"><input type="checkbox" class="search_by_tags" value="'+index+'"> '+value+'</label>'
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
						$("#left-content").fadeIn(500)
					},
					timeout: 9999999
				  });
				}
			});

			$("#Manual_Search").click(function(){
				var rest_val = $("#search_by_rest").val();
				//var rest_val = $(this).val();
				var date_val = $("#datepicker-small").val();
				var time_val = $("#Search_By_Time").val();
				var amount_value = $("#amount-small").val();
				var final_amount = amount_value.split(' ');
				var start_from = final_amount[1];
				var end_with = final_amount[4];
				var c = $("#uri_city").val();
				search_var = 1;
				$("#search_by").val(rest_val);
				$("#search_by_rest").val(rest_val);
				$(".search_by_results tbody").html('');
				//console.log("rest_val = "+rest_val+" , date_val = "+date_val+" , time_val = "+time_val+" , amount_val = "+amount_value);
				//ajax call beings required results and according to results bring area,cuisine and tags results if any of above values are not null  
				if(rest_val != "") {
				  $.ajax({
					//url: "custom_search/search_result",
					url: "custom_search/manual_search",
					dataType: "JSON",
					type: "post",
					//data: {restaurant_val : rest_val,date_value : date_val,time_value : time_val},
					data: {restaurant_val : rest_val,date_value : date_val,time_value : time_val,start_price: start_from, end_price : end_with, city: c},
					beforeSend:function(){
						$(".show_loading_img").css("display","block");
					},
					success: function(d) {
					  //console.log(d.area_count);
					  var area_replace = '';
					  $.each(d.area_count,function(index, value){
						//console.log('city' + index + ',  value: ' + value);
						area_replace += '<div class="checkbox"><label><input class="search_by_place" type="checkbox" value="'+index+'">'+index+'<span class="badge">'+value+'</span></label></div>'
					  });

					  var cuisine_replace = '';
					  $.each(d.cuisine_count,function(index, value){
						//console.log('city' + index + ',  value: ' + value);
						cuisine_replace += '<div class="checkbox"><label><input class="search_by_cuisine" type="checkbox" value="'+index+'">'+index+'<span class="badge">'+value+'</span></label></div>'
					  });

					  var tags_replace = '';
					  $.each(d.tags_count,function(index, value){
						//console.log('city' + index + ',  value: ' + value);
						tags_replace += '<label class="btn btn-warning"><input type="checkbox" class="search_by_tags" value="'+index+'"> '+value+'</label>'
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
						$("#left-content").fadeIn(500)
					},
					timeout: 9999999
				  });
				}
			});


			//ajax call for getting results according to the date selected in the search 
			$('#datepicker').on('change', function() {
				  $("#date_error").css("display","none");
				  $('#datepicker').css("border","");
				  var selectedDate = $('#datepicker').datepicker('getDate');
				  var date_val = $(this).val();
				  var rest_val = $("#search_by").val();
				  var time_val = $("#search_by_time").val();
				  var amount_value = $("#amount").val();
				  var final_amount = amount_value.split(' ');
				  var start_from = final_amount[1];
				  var end_with = final_amount[4];
				  var c = $("#uri_city").val();
				  var today = new Date();
				  today.setHours(0);
				  today.setMinutes(0);
				  today.setSeconds(0);
				  if(today || selectedDate) {
					  //ajax call brings results according to date selected and accordingly area,cuisine and tags results is selected date is future date
					  if (Date.parse(today) < Date.parse(selectedDate)) {
							
							$("#date_error").css("display","none");
							$('#datepicker').css("border","");
							
							$.ajax({
								//url: "custom_search/search_future_date_restaurant",
								url: "custom_search/search_restaurant",
								dataType: "JSON",
								type: "post",
								//data: {restaurant_val : rest_val,date_value : date_val,time_value : time_val},
								data: {restaurant_val : rest_val,date_value : date_val,time_value : time_val,start_price: start_from, end_price : end_with,city: c},
								beforeSend:function(){
									$(".show_loading_img").css("display","block");
								},
								success: function(d) {
									//$("#results").append(d);
									//console.log(d);
									var area_replace = '';
									$.each(d.area_count,function(index, value){
										//console.log('city' + index + ',  value: ' + value);
										area_replace += '<div class="checkbox"><label><input class="search_by_place" type="checkbox" value="'+index+'">'+index+'<span class="badge">'+value+'</span></label></div>'
									});

									var cuisine_replace = '';
									$.each(d.cuisine_count,function(index, value){
										//console.log('city' + index + ',  value: ' + value);
										cuisine_replace += '<div class="checkbox"><label><input class="search_by_cuisine" type="checkbox" value="'+index+'">'+index+'<span class="badge">'+value+'</span></label></div>'
									});

									var tags_replace = '';
									$.each(d.tags_count,function(index, value){
										//console.log('city' + index + ',  value: ' + value);
										tags_replace += '<label class="btn btn-warning"><input type="checkbox" class="search_by_tags" value="'+index+'"> '+value+'</label>'
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
									$("#left-content").fadeIn(500)
								},
								timeout: 9999999
							});
						
					  } //ajax call brings todays details and accordingly area,cuisine and tags results is selected date is todays date
					  else if(Date.parse(today) == Date.parse(selectedDate)){
							$("#date_error").css("display","none");
							$('#datepicker').css("border","");
							
							$.ajax({
								//url: "custom_search/search_todays_date_restaurant",
								url: "custom_search/search_restaurant",
								dataType: "JSON",
								type: "post",
								//data: {restaurant_val : rest_val,date_value : date_val,time_value : time_val},
								data: {restaurant_val : rest_val,date_value : date_val,time_value : time_val,start_price: start_from, end_price : end_with,city: c},
								beforeSend:function(){
									$(".show_loading_img").css("display","block");
								},
								success: function(d) {
									//$("#results").append(d);
									//console.log(d);
									var area_replace = '';
									$.each(d.area_count,function(index, value){
										//console.log('city' + index + ',  value: ' + value);
										area_replace += '<div class="checkbox"><label><input class="search_by_place" type="checkbox" value="'+index+'">'+index+'<span class="badge">'+value+'</span></label></div>'
									});

									var cuisine_replace = '';
									$.each(d.cuisine_count,function(index, value){
										//console.log('city' + index + ',  value: ' + value);
										cuisine_replace += '<div class="checkbox"><label><input class="search_by_cuisine" type="checkbox" value="'+index+'">'+index+'<span class="badge">'+value+'</span></label></div>'
									});

									var tags_replace = '';
									$.each(d.tags_count,function(index, value){
										//console.log('city' + index + ',  value: ' + value);
										tags_replace += '<label class="btn btn-warning"><input type="checkbox" class="search_by_tags" value="'+index+'"> '+value+'</label>'
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
									$("#left-content").fadeIn(500)
								},
								timeout: 9999999
							});
					  } 
					  //show error if selected date is less than todays date
					  else if (Date.parse(today) > Date.parse(selectedDate)) {

						$("#date_error").css("display","block");
						$('#datepicker').css("border","1px solid red");
					  }
				  }
			});
			
			//ajax call for getting results according to the date selected in the search 
			$('#datepicker-small').on('change', function() {
				  $("#date_error_small").css("display","none");
				  $('#datepicker-small').css("border","");
				  var selectedDate = $('#datepicker-small').datepicker('getDate');
				  var date_val = $(this).val();
				  var rest_val = $("#search_by_rest").val();
				  var time_val = $("#Search_By_Time").val();
				  var amount_value = $("#amount-small").val();
				  var final_amount = amount_value.split(' ');
				  var start_from = final_amount[1];
				  var end_with = final_amount[4];
				  var today = new Date();
				  var c = $("#uri_city").val();
				  today.setHours(0);
				  today.setMinutes(0);
				  today.setSeconds(0);
				  if(today || selectedDate) {
					  //ajax call brings results according to date selected and accordingly area,cuisine and tags results is selected date is future date
					  if (Date.parse(today) < Date.parse(selectedDate)) {
							
							$("#date_error_small").css("display","none");
							$('#datepicker-small').css("border","");
							
							$.ajax({
								//url: "custom_search/search_future_date_restaurant",
								url: "custom_search/search_restaurant",
								dataType: "JSON",
								type: "post",
								//data: {restaurant_val : rest_val,date_value : date_val,time_value : time_val},
								data: {restaurant_val : rest_val,date_value : date_val,time_value : time_val,start_price: start_from, end_price : end_with,city: c},
								beforeSend:function(){
									$(".show_loading_img").css("display","block");
								},
								success: function(d) {
									//$("#results").append(d);
									//console.log(d);
									var area_replace = '';
									$.each(d.area_count,function(index, value){
										//console.log('city' + index + ',  value: ' + value);
										area_replace += '<div class="checkbox"><label><input class="search_by_place" type="checkbox" value="'+index+'">'+index+'<span class="badge">'+value+'</span></label></div>'
									});

									var cuisine_replace = '';
									$.each(d.cuisine_count,function(index, value){
										//console.log('city' + index + ',  value: ' + value);
										cuisine_replace += '<div class="checkbox"><label><input class="search_by_cuisine" type="checkbox" value="'+index+'">'+index+'<span class="badge">'+value+'</span></label></div>'
									});

									var tags_replace = '';
									$.each(d.tags_count,function(index, value){
										//console.log('city' + index + ',  value: ' + value);
										tags_replace += '<label class="btn btn-warning"><input type="checkbox" class="search_by_tags" value="'+index+'"> '+value+'</label>'
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
									$("#left-content").fadeIn(500)
								},
								timeout: 9999999
							});
						
					  } //ajax call brings todays details and accordingly area,cuisine and tags results is selected date is todays date
					  else if(Date.parse(today) == Date.parse(selectedDate)){
							$("#date_error_small").css("display","none");
							$('#datepicker-small').css("border","");
							
							$.ajax({
								//url: "custom_search/search_todays_date_restaurant",
								url: "custom_search/search_restaurant",
								dataType: "JSON",
								type: "post",
								//data: {restaurant_val : rest_val,date_value : date_val,time_value : time_val},
								data: {restaurant_val : rest_val,date_value : date_val,time_value : time_val,start_price: start_from, end_price : end_with,city: c},
								beforeSend:function(){
									$(".show_loading_img").css("display","block");
								},
								success: function(d) {
									//$("#results").append(d);
									//console.log(d);
									var area_replace = '';
									$.each(d.area_count,function(index, value){
										//console.log('city' + index + ',  value: ' + value);
										area_replace += '<div class="checkbox"><label><input class="search_by_place" type="checkbox" value="'+index+'">'+index+'<span class="badge">'+value+'</span></label></div>'
									});

									var cuisine_replace = '';
									$.each(d.cuisine_count,function(index, value){
										//console.log('city' + index + ',  value: ' + value);
										cuisine_replace += '<div class="checkbox"><label><input class="search_by_cuisine" type="checkbox" value="'+index+'">'+index+'<span class="badge">'+value+'</span></label></div>'
									});

									var tags_replace = '';
									$.each(d.tags_count,function(index, value){
										//console.log('city' + index + ',  value: ' + value);
										tags_replace += '<label class="btn btn-warning"><input type="checkbox" class="search_by_tags" value="'+index+'"> '+value+'</label>'
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
									$("#left-content").fadeIn(500)
								},
								timeout: 9999999
							});
					  } 
					  //show error if selected date is less than todays date
					  else if (Date.parse(today) > Date.parse(selectedDate)) {

						$("#date_error_small").css("display","block");
						$('#datepicker-small').css("border","1px solid red");
					  }
				  }
			});
			

			//ajax call for getting results according to the time selected in the search 
			$("#search_by_time").on('change',function(){ //console.log("called");
				var time_val = $(this).val();
				var date_val = $("#datepicker").val();
				var rest_val = $("#search_by").val();
				var amount_value = $("#amount").val();
				var final_amount = amount_value.split(' ');
				var start_from = final_amount[1];
				var end_with = final_amount[4];
				var c = $("#uri_city").val();
				//console.log('final amount split = '+final_amount);
				//console.log(" first amount =="+final_amount[1]+" , second amount == "+final_amount[4]);
				//console.log("time value == "+time_val+" , date value = "+date_val+" , rest val = "+rest_val+" , start_from = "+start_from+" , end_with = "+end_with);
				if(time_val != "") {
					$.ajax({
						url: "custom_search/search_restaurant",
						dataType: "JSON",
						type: "post",
						//data: {restaurant_val : rest_val,date_value : date_val,time_value : time_val},
						data: {restaurant_val : rest_val,date_value : date_val,time_value : time_val,start_price: start_from, end_price : end_with,city: c},
						beforeSend:function(){
							$(".show_loading_img").css("display","block");
						},
						success: function(d) {
							//$("#results").append(d);
							//console.log(d);
							var area_replace = '';
							$.each(d.area_count,function(index, value){
								//console.log('city' + index + ',  value: ' + value);
								area_replace += '<div class="checkbox"><label><input class="search_by_place" type="checkbox" value="'+index+'">'+index+'<span class="badge">'+value+'</span></label></div>'
							});

							var cuisine_replace = '';
							$.each(d.cuisine_count,function(index, value){
								//console.log('city' + index + ',  value: ' + value);
								cuisine_replace += '<div class="checkbox"><label><input class="search_by_cuisine" type="checkbox" value="'+index+'">'+index+'<span class="badge">'+value+'</span></label></div>'
							});

							var tags_replace = '';
							$.each(d.tags_count,function(index, value){
								//console.log('city' + index + ',  value: ' + value);
								tags_replace += '<label class="btn btn-warning"><input type="checkbox" class="search_by_tags" value="'+index+'"> '+value+'</label>'
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
							$("#left-content").fadeIn(500)
						},
						timeout: 9999999
					});
				} 
			});
			
			//ajax call for getting results according to the time selected in the search 
			$("#Search_By_Time").on('change',function(){
				var time_val = $(this).val();
				var date_val = $("#datepicker-small").val();
				var rest_val = $("#search_by_rest").val();
				var amount_value = $("#amount-small").val();
				var final_amount = amount_value.split(' ');
				var start_from = final_amount[1];
				var end_with = final_amount[4];
				var c = $("#uri_city").val();
				//console.log('final amount split = '+final_amount);
				//console.log(" first amount =="+final_amount[1]+" , second amount == "+final_amount[4]);
				//console.log("time value == "+time_val+" , date value = "+date_val+" , rest val = "+rest_val+" , start_from = "+start_from+" , end_with = "+end_with);

				$.ajax({
					url: "custom_search/search_restaurant",
					dataType: "JSON",
					type: "post",
					//data: {restaurant_val : rest_val,date_value : date_val,time_value : time_val},
					data: {restaurant_val : rest_val,date_value : date_val,time_value : time_val,start_price: start_from, end_price : end_with, city: c},
					beforeSend:function(){
						$(".show_loading_img").css("display","block");
					},
					success: function(d) {
						//$("#results").append(d);
						//console.log(d);
						var area_replace = '';
						$.each(d.area_count,function(index, value){
							//console.log('city' + index + ',  value: ' + value);
							area_replace += '<div class="checkbox"><label><input class="search_by_place" type="checkbox" value="'+index+'">'+index+'<span class="badge">'+value+'</span></label></div>'
						});

						var cuisine_replace = '';
						$.each(d.cuisine_count,function(index, value){
							//console.log('city' + index + ',  value: ' + value);
							cuisine_replace += '<div class="checkbox"><label><input class="search_by_cuisine" type="checkbox" value="'+index+'">'+index+'<span class="badge">'+value+'</span></label></div>'
						});

						var tags_replace = '';
						$.each(d.tags_count,function(index, value){
							//console.log('city' + index + ',  value: ' + value);
							tags_replace += '<label class="btn btn-warning"><input type="checkbox" class="search_by_tags" value="'+index+'"> '+value+'</label>'
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
						$("#left-content").fadeIn(500)
					},
					timeout: 9999999
				});
			});

			//ajax call for getting results according to the area selected from more options
			$("body").delegate(".search_by_place","change",function(){
				//$(this).attr("checked","checked");
				var time_val = $("#search_by_time").val();
				var date_val = $("#datepicker").val();
				var rest_val = $("#search_by").val();
				var amount_value = $("#amount").val();
				var final_amount = amount_value.split(' ');
				var start_from = final_amount[1];
				var end_with = final_amount[4];
				var sList = "";
				var c = $("#uri_city").val();

				$( ".search_by_place" ).each(function() {
					var sThisVal = (this.checked ? $(this).val() : "nullvalue");
					//if($(this).attr('checked')) {
						sList += (sList=="" ? "'"+sThisVal+"'" : ",'" + sThisVal+"'");
					//}
				});
				//console.log (sList);
				//console.log("time value == "+time_val+" , date value = "+date_val+" , rest val = "+rest_val+" , start_from = "+start_from+" , end_with = "+end_with+" , sList = "+sList);
				$.ajax({
					url: "custom_search/search_by_area",
					dataType: "JSON",
					type: "post",
					//data: {restaurant_val : rest_val,date_value : date_val,time_value : time_val},
					data: {restaurant_val : rest_val,date_value : date_val,time_value : time_val,start_price: start_from, end_price : end_with,area_values : sList,city: c},
					beforeSend:function(){
						$(".show_loading_img").css("display","block");
					},
					success: function(d) {
						//$("#results").append(d);
						//console.log(d);
						/*var area_replace = '';
						$.each(d.area_count,function(index, value){
							//console.log('city' + index + ',  value: ' + value);
							area_replace += '<div class="checkbox"><label><input class="search_by_place" type="checkbox" value="'+index+'">'+index+'<span class="badge">'+value+'</span></label></div>'
						});*/
						//console.log("asd = "+d.cuisine_count);

						var cuisine_replace = '';
						$.each(d.cuisine_count,function(index, value){
							//console.log('city' + index + ',  value: ' + value);
							cuisine_replace += '<div class="checkbox"><label><input class="search_by_cuisine" type="checkbox" value="'+index+'">'+index+'<span class="badge">'+value+'</span></label></div>'
						});

						var tags_replace = '';
						$.each(d.tags_count,function(index, value){
							//console.log('city' + index + ',  value: ' + value);
							tags_replace += '<label class="btn btn-warning"><input type="checkbox" class="search_by_tags" value="'+index+'"> '+value+'</label>'
						});

						$("#left-content").fadeOut(500, function() {
							$("#left-content").empty();
							$("#left-content").html(d.restaurant_data);
						});
						//console.log(text);
						//$(".dynamic_areas").html(area_replace);
						
						if(cuisine_replace == "") {
							cuisine_replace = "No Cuisine found";
						}
						if(tags_replace == "") {
							tags_replace = "No Tags found";
						}
						$(".dynamic_cuisine").html(cuisine_replace);
						$(".dynamic_tags").html(tags_replace);
					},
					complete: function() {
						$(".show_loading_img").css("display","none");
						$("#left-content").fadeIn(500)
					},
					timeout: 9999999
				});
			});

			//ajax call for getting results according to the cuisine selected in the search bar
			$("body").delegate(".search_by_cuisine","change",function(){
				//$(this).attr("checked","checked");
				var time_val = $("#search_by_time").val();
				var date_val = $("#datepicker").val();
				var rest_val = $("#search_by").val();
				var amount_value = $("#amount").val();
				var final_amount = amount_value.split(' ');
				var start_from = final_amount[1];
				var end_with = final_amount[4];
				var sList1 = "";
				var sList = "";
				var c = $("#uri_city").val();

				$( ".search_by_place" ).each(function() {
					var sThisVal1 = (this.checked ? $(this).val() : "nullvalue");
					//if($(this).attr('checked')) {
						sList1 += (sList1=="" ? "'"+sThisVal1+"'" : ",'" + sThisVal1+"'");
					//}
				});

				$( ".search_by_cuisine" ).each(function() {
					var sThisVal = (this.checked ? $(this).val() : "nullvalue");
					//if($(this).attr('checked')) {
						sList += (sList=="" ? "'"+sThisVal+"'" : ",'" + sThisVal+"'");
					//}
				});
				//console.log (sList);
				$.ajax({
					url: "custom_search/search_by_cuisine",
					//url: "custom_search/refine_search",
					dataType: "JSON",
					type: "post",
					//data: {restaurant_val : rest_val,date_value : date_val,time_value : time_val},
					data: {restaurant_val : rest_val,date_value : date_val,time_value : time_val,start_price: start_from, end_price : end_with,area_values : sList1,cuisine_values : sList,city: c},
					beforeSend:function(){
						$(".show_loading_img").css("display","block");
					},
					//data: {cuisine_values : sList},
					success: function(d) {
						//$("#results").append(d);
						//console.log("here = "+d);
						//console.log(d.tags_count+" = tags count")
						var tags_replace = '';
						$.each(d.tags_count,function(index, value){
							//console.log('city' + index + ',  value: ' + value);
							tags_replace += '<label class="btn btn-warning"><input type="checkbox" class="search_by_tags" value="'+index+'"> '+value+'</label>'
						});
						
						$("#left-content").fadeOut(500, function() {
							$("#left-content").empty();
							$("#left-content").html(d.restaurant_data);
						});

						if(tags_replace == "") {
							tags_replace = "No Tags found";
						}
						$(".dynamic_tags").html(tags_replace);
					},
					complete: function() {
						$(".show_loading_img").css("display","none");
						$("#left-content").fadeIn(500)
					},
					timeout: 9999999
				});
			});

			//ajax call for getting results according to the tags selected in the search bar
			
			  $("body").delegate(".search_by_tags","change",function(){
				var time_val = $("#search_by_time").val();
				var date_val = $("#datepicker").val();
				var rest_val = $("#search_by").val();
				var amount_value = $("#amount").val();
				var final_amount = amount_value.split(' ');
				var start_from = final_amount[1];
				var end_with = final_amount[4];
				var sList1 = "";
				var sList2 = "";
				var sList = "";
				var c = $("#uri_city").val();

				$( ".search_by_place" ).each(function() {
					var sThisVal1 = (this.checked ? $(this).val() : "nullvalue");
					//if($(this).attr('checked')) {
						sList1 += (sList1=="" ? "'"+sThisVal1+"'" : ",'" + sThisVal1+"'");
					//}
				});

				$( ".search_by_cuisine" ).each(function() {
					var sThisVal2 = (this.checked ? $(this).val() : "nullvalue");
					//if($(this).attr('checked')) {
						sList2 += (sList2=="" ? "'"+sThisVal2+"'" : ",'" + sThisVal2+"'");
					//}
				});
				
				
				$( ".search_by_tags" ).each(function() {
					var sThisVal = (this.checked ? $(this).val() : "0");
					//if($(this).attr('checked')) {
						sList += (sList=="" ? "'"+sThisVal+"'" : ",'" + sThisVal+"'");
					//}
				});
				//console.log (sList);
				$.ajax({
					url: "custom_search/search_by_tags",
					dataType: "JSON",
					type: "post",
					//data: {restaurant_val : rest_val,date_value : date_val,time_value : time_val},
					//data: {tags_values : sList},
					data: {restaurant_val : rest_val,date_value : date_val,time_value : time_val,start_price: start_from, end_price : end_with,area_values : sList1,cuisine_values : sList2,tags_values : sList, city : c},
					beforeSend:function(){
						$(".show_loading_img").css("display","block");
					},
					success: function(d) {
						//$("#results").append(d);
						$("#left-content").fadeOut(500, function() {
							$("#left-content").empty();
							$("#left-content").html(d.restaurant_data);
						});
					},
					complete: function() {
						$(".show_loading_img").css("display","none");
						$("#left-content").fadeIn(500)
					},
					timeout: 9999999
				});
			});

			$("#search_by, #search_by_rest").on("blur",function(){
				var val = $(this).val();
				var c = $("#uri_city").val();
				//console.log("val = "+val+" , val.length = "+val.length);
				if(val.length == 0 && search_var == 1) {
					search_var = 0;
					$.ajax({
							url: "custom_search/show_default_experiences",
							type: "POST",
							dataType: "json",
							data:{city: c},
							beforeSend:function(){
								$(".show_loading_img").css("display","block");
							},
							success: function(d) {
						  //console.log(d.area_count);
						  var area_replace = '';
						  $.each(d.area_count,function(index, value){
							//console.log('city' + index + ',  value: ' + value);
							area_replace += '<div class="checkbox"><label><input class="search_by_place" type="checkbox" value="'+index+'">'+index+'<span class="badge">'+value+'</span></label></div>'
						  });

						  var cuisine_replace = '';
						  $.each(d.cuisine_count,function(index, value){
							//console.log('city' + index + ',  value: ' + value);
							cuisine_replace += '<div class="checkbox"><label><input class="search_by_cuisine" type="checkbox" value="'+index+'">'+index+'<span class="badge">'+value+'</span></label></div>'
						  });

						  var tags_replace = '';
						  $.each(d.tags_count,function(index, value){
							//console.log('city' + index + ',  value: ' + value);
							tags_replace += '<label class="btn btn-warning"><input type="checkbox" class="search_by_tags" value="'+index+'"> '+value+'</label>'
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
						  
						},complete: function() {
							$(".show_loading_img").css("display","none");
							$("#left-content").fadeIn(500)
						},
						timeout: 9999999
						
					});
				}
			});

			$("#reset_form").on("click", function(){
				document.getElementById("custom_refine_search").reset();
				//location.reload();
				 $("#slider-range").slider("values", 0, 300);
				  $("#slider-range").slider("values", 1, 2500);
				  $( "#amount" ).val( "Rs " + $( "#slider-range" ).slider( "values", 0 ) + " - Rs " + $( "#slider-range" ).slider( "values", 1 ) );
				$("#slider-range-small").slider("values", 0, 300);
				  $("#slider-range-small").slider("values", 1, 2500);
				  $( "#amount-small" ).val( "Rs " + $( "#slider-range-small" ).slider( "values", 0 ) + " - Rs " + $( "#slider-range-small" ).slider( "values", 1 ) );
				var c = $("#uri_city").val();				
				$.ajax({
						url: "custom_search/show_default_experiences",
						type: "POST",
						dataType: "json",
						data:{city: c},
						beforeSend:function(){
							$(".show_loading_img").css("display","block");
						},
						success: function(d) {
					  //console.log(d.area_count);
					  var area_replace = '';
					  $.each(d.area_count,function(index, value){
						//console.log('city' + index + ',  value: ' + value);
						area_replace += '<div class="checkbox"><label><input class="search_by_place" type="checkbox" value="'+index+'">'+index+'<span class="badge">'+value+'</span></label></div>'
					  });

					  var cuisine_replace = '';
					  $.each(d.cuisine_count,function(index, value){
						//console.log('city' + index + ',  value: ' + value);
						cuisine_replace += '<div class="checkbox"><label><input class="search_by_cuisine" type="checkbox" value="'+index+'">'+index+'<span class="badge">'+value+'</span></label></div>'
					  });

					  var tags_replace = '';
					  $.each(d.tags_count,function(index, value){
						//console.log('city' + index + ',  value: ' + value);
						tags_replace += '<label class="btn btn-warning"><input type="checkbox" class="search_by_tags" value="'+index+'"> '+value+'</label>'
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
					  
					},complete: function() {
						$(".show_loading_img").css("display","none");
						$("#left-content").fadeIn(500)
					},
					timeout: 9999999
					
				});
			});

			$("#reset_filters").on("click", function(){
				//console.log("function callaed");
				document.getElementById("custom_refine_search_form").reset();
				$("#slider-range").slider("values", 0, 300);
				  $("#slider-range").slider("values", 1, 2500);
				  $( "#amount" ).val( "Rs " + $( "#slider-range" ).slider( "values", 0 ) + " - Rs " + $( "#slider-range" ).slider( "values", 1 ) );
				$("#slider-range-small").slider("values", 0, 300);
				  $("#slider-range-small").slider("values", 1, 2500);
				  $( "#amount-small" ).val( "Rs " + $( "#slider-range-small" ).slider( "values", 0 ) + " - Rs " + $( "#slider-range-small" ).slider( "values", 1 ) );
				var c = $("#uri_city").val();				
				$.ajax({
						url: "custom_search/show_default_experiences",
						type: "POST",
						dataType: "json",
						data:{city: c},
						beforeSend:function(){
							$(".show_loading_img").css("display","block");
						},
						success: function(d) {
					  //console.log(d.area_count);
					  var area_replace = '';
					  $.each(d.area_count,function(index, value){
						//console.log('city' + index + ',  value: ' + value);
						area_replace += '<div class="checkbox"><label><input class="search_by_place" type="checkbox" value="'+index+'">'+index+'<span class="badge">'+value+'</span></label></div>'
					  });

					  var cuisine_replace = '';
					  $.each(d.cuisine_count,function(index, value){
						//console.log('city' + index + ',  value: ' + value);
						cuisine_replace += '<div class="checkbox"><label><input class="search_by_cuisine" type="checkbox" value="'+index+'">'+index+'<span class="badge">'+value+'</span></label></div>'
					  });

					  var tags_replace = '';
					  $.each(d.tags_count,function(index, value){
						//console.log('city' + index + ',  value: ' + value);
						tags_replace += '<label class="btn btn-warning"><input type="checkbox" class="search_by_tags" value="'+index+'"> '+value+'</label>'
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
					  
					},complete: function() {
						$(".show_loading_img").css("display","none");
						$("#left-content").fadeIn(500)
					},
					timeout: 9999999
					
				});
			});

			$("body").delegate(".clear_filters","click",function(){

				$("#slider-range").slider("values", 0, 300);
				  $("#slider-range").slider("values", 1, 2500);
				  $( "#amount" ).val( "Rs " + $( "#slider-range" ).slider( "values", 0 ) + " - Rs " + $( "#slider-range" ).slider( "values", 1 ) );
				$("#slider-range-small").slider("values", 0, 300);
				  $("#slider-range-small").slider("values", 1, 2500);
				  $( "#amount-small" ).val( "Rs " + $( "#slider-range-small" ).slider( "values", 0 ) + " - Rs " + $( "#slider-range-small" ).slider( "values", 1 ) );
				//console.log("clieck");
				//window.location.reload();
				var c = $("#uri_city").val();				
				$.ajax({
						url: "custom_search/show_default_experiences",
						type: "POST",
						dataType: "json",
						data:{city: c},
						beforeSend:function(){
							$(".show_loading_img").css("display","block");
						},
						success: function(d) {
					  //console.log(d.area_count);
					  var area_replace = '';
					  $.each(d.area_count,function(index, value){
						//console.log('city' + index + ',  value: ' + value);
						area_replace += '<div class="checkbox"><label><input class="search_by_place" type="checkbox" value="'+index+'">'+index+'<span class="badge">'+value+'</span></label></div>'
					  });

					  var cuisine_replace = '';
					  $.each(d.cuisine_count,function(index, value){
						//console.log('city' + index + ',  value: ' + value);
						cuisine_replace += '<div class="checkbox"><label><input class="search_by_cuisine" type="checkbox" value="'+index+'">'+index+'<span class="badge">'+value+'</span></label></div>'
					  });

					  var tags_replace = '';
					  $.each(d.tags_count,function(index, value){
						//console.log('city' + index + ',  value: ' + value);
						tags_replace += '<label class="btn btn-warning"><input type="checkbox" class="search_by_tags" value="'+index+'"> '+value+'</label>'
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
					  
					},complete: function() {
						$(".show_loading_img").css("display","none");
						$("#left-content").fadeIn(500)
					},
					timeout: 9999999
					
				});
			});
});