	function formatDate(e) {
    e = e.split("-");
    year = e[0];
    switch (e[1]) {
        case "1":
            month = "Jan";
            break;
        case "2":
            month = "Feb";
            break;
        case "3":
            month = "Mar";
            break;
        case "4":
            month = "Apr";
            break;
        case "5":
            month = "May";
            break;
        case "6":
            month = "Jun";
            break;
        case "7":
            month = "Jul";
            break;
        case "8":
            month = "Aug";
            break;
        case "9":
            month = "Sep";
            break;
        case "10":
            month = "Oct";
            break;
        case "11":
            month = "Nov";
            break;
        case "12":
            month = "Dec";
            break
    }
    day = e[2];
    if (day == 1) {
        day += "st"
    } else if (day == 2) {
        day += "nd"
    } else if (day == 3) {
        day += "rd"
    } else {
        day += "th"
    }
    resultDate = day + " " + month + ", " + year;
    return resultDate
}

function ucfirst(e) {
    e += "";
    var t = e.charAt(0).toUpperCase();
    return t + e.substr(1)
}
$(document).ready(function() {
    function ifchangedparams() {
        party_size = $("#party_edit span").text();
        edit_date = $("#changed_date").val();
        edit_time = $("#time_edit span").text();
        alcohol = $("#alcoholedit").val();
        non_veg = $("#nonveg").val();
        last_reserv_date = $("#last_reserv_date").val();
        last_reserv_time = $("#last_reserv_time").val();
        last_reserv_outlet = $("#last_reserv_outlet").val();
        last_reserv_party_size = $("#last_reserv_party_size").val();
        if (last_reserv_date) {
            l_date = last_reserv_date.split("/");
            l_date = l_date[2] + "-" + l_date[0] + "-" + l_date[1];
            if (l_date == edit_date && last_reserv_time == edit_time && last_reserv_party_size == party_size) {
                $("#save_changes").addClass("hidden")
            } else {
                $("#save_changes").removeClass("hidden")
            }
        }
    }
    amount = 0;
    active = 0;
    one_price = 0;
    timehidden = 0;
    total = 0;
    email_regex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
    $("#gift_pay").click(function(e) {
        e.preventDefault();
        var t = 0;
		if($("#check_allow_guest").val() == 'Yes'){
				//
				/*var rec_email = $("#receiver_email").val();
				var rec_name = $("#receiver_name").val();
				var gift_no_people = $("#gift_no_people").val();
				var sel_gift_opt = $("input[type='radio'][name='gift_opt']:checked").val();
				var gift_choose_exp = $("#exp_sel_id").val();
				var amt = $("#amount").val();
				var oth_amt = $("#other_amount").val();
				var gift_send = $("input[type='radio'][name='gift_send']:checked").val();
				var mail_address = $("#mail_address").val();
				var spl_inst = $("#special_instructions").val();
				var exp_pri = $("#single_exp_price").val();
				//console.log("reveiver_email: "+rec_email+",receiver_name: "+rec_name+", gift_num_ppl: "+gift_no_people+",  select_gift_opt: "+sel_gift_opt+", gift_chse_exp: "+gift_choose_exp+", amount: "+amt+", other_amount: "+oth_amt+", send_gift: "+gift_send+", mailing_address: "+mail_address+", special_insts: "+spl_inst+", exp_price: "+exp_pri);
				$.ajax({
						url: "gift_cards/set_values_for_giftcard",
						type: "POST",
						dataType: "json",
						data:{receiver_email: rec_email,receiver_name: rec_name, gift_num_ppl: gift_no_people,  select_gift_opt: sel_gift_opt, gift_chse_exp: gift_choose_exp, amount : amt, other_amount: oth_amt, send_gift: gift_send, mailing_address: mail_address, special_insts: spl_inst,exp_price: exp_pri},
					});*/
				$("#gift_pay").attr("data-target","#redirectloginModal").attr("data-toggle","modal");
				
		} else {
			if ($("#receiver_name").val() == "") {
				$("#error_receiver_name").css("display", "block");
				$("#error_receiver_name").text("Please enter the Receiver's Name");
				$("#receiver_name").css("border", "2px solid #B94A39");
				t++
			}
			if ($("#receiver_email").val() == "") {
				$("#error_receiver_email").css("display", "block");
				$("#error_receiver_email").text("Please enter  valid Receiver's Email");
				$("#receiver_email").css("border", "2px solid #B94A39");
				t++
			}
			if (!email_regex.test($("#receiver_email").val())) {
				$("#error_receiver_email").css("display", "block");
				$("#error_receiver_email").text("Please enter  valid Receiver's Email");
				$("#receiver_email").css("border", "2px solid #B94A39");
				t++
			}
			if ($('input[name="gift_opt"]:checked').val() == 1) {
				//amount = $("#amount").val(); console.log(amount);
				//if (amount == 0) {
				if ($("#amount").val() == 0 || $("#amount").val() == '') {
					$("#other_amount_error").text("Please enter a cash value amount here");
					t++
				} else {
					$("#other_amount_error").text("")
				}
			}
			if ($('input[name="gift_opt"]:checked').val() == 2) {
				//if (total == 0) {
				if ($("#amount").val() == 0 || $("#amount").val() == '') {
					$("#total_amount_error").text("Please choose a particular dining experience");
					t++
				} else {
					$("#total_amount_error").text("")
				}
			}
			if ($('input[name="gift_send"]:checked').val() == 2) {
				if ($('input[name="mailing_address"]').val() == "") {
					$("#mailing_address_error").css("display", "block");
					t++
				} else $("#mailing_address_error").css("display", "none")
			}
			if (t == 0) {
				if (amount == 0) {
					amount = total
				}
				$("#amount").val(amount);
				$("#gift_form").submit();
			}
		}
    });
    $("#receiver_name").focus(function() {
        $("#receiver_name").css("border", "1px solid #66afe9");
        $("#error_receiver_name").css("display", "none")
    });
    $("#receiver_name").blur(function() {
        $("#receiver_name").css("border", "1px solid #ccc")
    });
    $("#receiver_email").focus(function() {
        $("#receiver_email").css("border", "1px solid #66afe9");
        $("#error_receiver_email").css("display", "none")
    });
    $("#receiver_email").blur(function() {
        $("#receiver_email").css("border", "1px solid #ccc")
    });
    $("input[name=gift_opt]").change(function(e) {
        var t = $("input[name='gift_opt']:checked").val();
        if (t == 2) {
            $("#about_gourmet li").each(function(e) {
                $(this).css("display", "")
            })
        }
        if (t == 1) {
            $("#about_gourmet li").each(function(e) {
                if ($(this).attr("rel") == 2) $(this).css("display", "none")
            })
        }
    });
    $("input[name=options]").change(function(e) {
        amount = $("input[name='options']:checked").val();
        $("input[name=other_amount]").val("");
        $("#other_amount_error").text("");
		$("#amount").val(amount);
        active = $("input[name='options']:checked").attr("id");
        active = "#" + active
    });
    $("input[name=other_amount]").focus(function(e) {
        amount = 0;
        $(active).parent().removeClass("active")
    });
    amount_regexp = /^([1-9][0-9]*|0)(\.[0-9]{2})?$/;
    $("input[name=other_amount]").change(function(e) {
		$("#amount").val('');
        amountinput = $("input[name=other_amount]").val();
        $("#other_amount_error").text("");
        amount_regexp.test(amountinput);
        if (!amount_regexp.test(amountinput)) {
            $("#other_amount_error").text("Please enter a cash value amount");
            $("input[name=other_amount]").val("");
            amount = 0
        } else {
            $("#other_amount_error").text("");
            amount = $("input[name=other_amount]").val();
			$("#amount").val(amount);
        }
    });
    $("#view_more").click(function(e) {
        $.ajax({
            url: "/experience/getReviewFromAjax",
            type: "POST",
            dataType: "json",
            data: {
                id: $("#exp_id").val()
            },
            success: function(e) {
                var t = "";
                for (var n = 0; n < e.length; n++) {
                    if (n < 4) continue;
                    //console.log(e[n].name);
                    var r = 5 - e[n].rating;
                    var i = e[n].date_seating.split(" ");
                    var s = i[0].split("-");
                    var o = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
                    t += "<li>";
                    t += '<div class="col-md-3"><p class="lead name">';
                    t += e[n].name + "</p></div>";
                    t += '<div class="col-md-9">';
                    t += '<div class="row star-info">';
                    t += '<div class="col-xs-8">';
                    t += '<ul class="list-inline">';
                    for (var u = 1; u <= e[n].rating; u++) {
                        t += '<li><span class="glyphicon glyphicon-star"></span></li>'
                    }
                    for (var a = 1; a <= r; a++) {
                        t += '<li class="inactive"><span class="glyphicon glyphicon-star"></span></li>'
                    }
                    t += "</ul>";
                    t += "</div>";
                    t += '<p class="col-xs-4 text-right date">' + o[s[1] - 1] + " " + s[2] + ", " + s[0] + "</p>";
                    t += "</div>";
                    t += "<p>" + e[n].review_paragraph + "</p>";
                    t += "</div>";
                    t += "</li>"
                }
                $("#review_comments").append(t);
                $("#view_more").css("display", "none")
            }
        });
        return false
    });
    $("#city_list li").click(function(e) {
        var t = $(this).find("a").attr("rel");
        var v = $(this).find("a").attr("data-cityID");
        //console.log('v = '+v);
        $("#gift_choose_city").html(ucfirst(t) + ' <span class="caret"></span>');
        $.ajax({
            url: "/gift_cards/show_exp",
            type: "POST",
            dataType: "json",
            data: {
                city: v
            },
            async: false,
            success: function(e) {
                $("#location_list").html(e)
            }
        });
        $("#gift_choose_exp").parent().removeClass("hidden");
        $("#one_price").removeClass("hidden")
    });
    $("#location_list").on("click", "li", function(e) {
        $(".addons_price_listing").css('display','none');
        $("#addons_list").css('display','none');
        $("#total_amount_error").text("");
        mylocation = $(this).find("a").attr("rel");
        info = mylocation.split("|");
        $("input[name=gift_choose_exp]").val(mylocation);
        button_name = $(this).find("a").children().eq(1).attr("rel");
        $("#gift_choose_exp").html(button_name + ' <span class="caret"></span>');
        one_price = info[2];
        $("#one_price span").text("Rs. " + one_price);
        $("#experiencePrice").val(one_price);
		$("#single_exp_price").val(one_price);
        gift_no_people = $("#gift_no_people").val();
        total = gift_no_people * one_price;
        total = parseFloat(total).toFixed(2);
        $("#total").text("Rs. " + total);
		$("#amount").val(total);
        link = $(this).find("a").children().eq(3).val();
        $("#link_to_experience_description").attr("href", link);
        var product_id = $(this).find("input[name=product_id]").val();
        //console.log("product_id = "+product_id);
        $.ajax({
            url: "/gift_cards/getRelatedAddons",
            type: "POST",
            dataType: "json",
            data: {
                pid: product_id
            },
            async: false,
            success: function(e) { //console.log(e);
                if(e.content != "" && e.addons_content != ""){
                    $("#addons_list").html('<p class="col-md-12"><strong>Meal Options:</strong></p>'+e.content).css('display','inline');
                    $(".addons_price_listing").html('<p class="col-md-12"><strong>This experiences have following addons</strong></p>'+e.addons_content).css('display','inline');
                }
            }
        });
    });
    $("#gift_no_people").change(function() {
        gift_no_people = $(this).val();
        str = "";
        for (var e = 0; e <= gift_no_people; e++) {
            str += "<option value='" + e + "'>" + e + "</option>"
        }
        $("#addons_list select").html(str);
        if (one_price != 0) {
            total = gift_no_people * one_price;
            total = parseFloat(total).toFixed(2);
            $("#total").text("Rs. " + total);
			$("#amount").val(total);
			$("#grandTotal").val(total);
        }
    });

    $("body").delegate(".giftcard_addons_list","change",function(){
        adi = $(this).attr('data-addonid');
        addonPrice = $("#"+adi).val();
        ppl = $(this).val();
        total2 = Math.floor($("#amount").val());
        addonamount = addonPrice * ppl;
        tamount = total2 + addonamount;
        total1 = parseFloat(tamount).toFixed(2);

        $("#total").text("Rs. " + total1);
        $("#grandTotal").val(total1);


    });
    if ($("input[name=checking]").val() != "") {
        $("#rewards").html($("input[name=checking]").val() + '<span class="caret"></span>')
    }
    $("#rewards_ul li").click(function() {
        rewards = $(this).attr("rel");
        text = $(this).find("a").text();
        $("#rewards").html(text + '<span class="caret"></span>');
        $("input[name=rewards]").val(rewards)
    });
    $("#qty_ul li").click(function() {
        qty = $(this).attr("rel");
        text = $(this).find("a").text();
        $("#qty_btn").html(text + '<span class="caret"></span>');
        $("input[name=quantity]").val(qty)
    });
    $("#redeem_sub").click(function(e) {
        e.preventDefault();
        id = $("input[name=rewards]").val();
        count = $("input[name=quantity]").val();
        if (id != "" && count != "") $(".points-form").submit()
    });
    $("#dob").datepicker({
        changeMonth: true,
        changeYear: true,
        yearRange: "-100:+0",
        format: "yyyy-mm-dd"
    });
     $("#aniversary_date").datepicker({
        changeMonth: true,
        changeYear: true,
        yearRange: "-100:+0",
        format: "yyyy-mm-dd"
    });
    $("#close_changes,#cancel").click(function() {
        $("#clear_location").remove();
        $("#clear_meal").remove()
    });
    $("#myres_div #cancel_reservation").click(function() {
        reserv = $(this).parent().next().val();
        $("#reserv_type").remove();
        $("#cancelModal").append('<input type="hidden" name="reserv_type" id="reserv_type" value="experience">')
    });
    $("#myres_div #ac_cancel_reservation").click(function() {
        reserv = $(this).parent().next().val();
        $("#reserv_type").remove();
        $("#cancelModal").append('<input type="hidden" name="reserv_type" id="reserv_type" value="alacarte">')
    });
    hidden_parram = 0;
    $("#party_edit").click(function() {
        if ($("#collapseTwo").hasClass("in")) {
            $("#date_edit").click();
            if ($("#date_edit span").text() != "") {
                $("#date_edit").removeClass("hidden")
            }
        } else if ($("#collapseThree").hasClass("in")) {
            $("#time_edit").click();
            if ($("#time_edit span").text() != "") {
                $("#time_edit").removeClass("hidden")
            }
        } else if ($("#collapseFour").hasClass("in")) {
            $("#meal_edit").click()
        }
        $("#party_size").removeClass("hidden");
        $("#location_edit").removeClass("hidden");
        $(".cant_change").addClass("hidden");
        $(this).addClass("hidden")
    });
    $("#date_edit").click(function() {
        if ($("#collapseThree").hasClass("in")) {
            $("#time_edit").click()
        } else if ($("#collapseFour").hasClass("in")) {
            $("#meal_edit1").click()
        }
        if ($("#time_edit span").text() != "") {
            $("#time_edit").removeClass("hidden")
        }
        $(".cant_change").addClass("hidden");
        $("#date_edit").addClass("hidden");
        $("#party_edit").removeClass("hidden");
        $("#party_size").addClass("hidden")
    });
    $("#time_edit").click(function() {
        if ($("#collapseTwo").hasClass("in")) {
            $("#date_edit").click()
        } else if ($("#collapseFour").hasClass("in")) {
            $("#meal_edit").click()
        }
        $(".cant_change").addClass("hidden");
        $("#party_size").addClass("hidden");
        $("#party_edit").removeClass("hidden")
    });
    $("#cancel_current").click(function(e) {
        
        e.preventDefault();
        $(".cancel_loader").show();
         var reserv_typee = $('#reserv_type').val();
         var user_id = $('#cancel_user_id').val();
         var added_by = $('#added_by').val();

            $.ajax({
                url: "/orders/cancel_reservation",
                type: "post",
                data: {
                    reserv_id:   reserv,
                    reserv_type: reserv_typee,
                    user_id: user_id,
                    added_by: added_by
                },
                success: function(e) {
                    if (e == 1) {
                        $(".cancel_reserv_form").addClass("hide");
                        $(".cancel_reserv_confirmation").removeClass("hide");
                        $(".cancel_loader").hide()
                    }
                }
            })
    });
    var dtp = "";
    $("#myres_div #change_reservation").click(function() { //alert('called');
        $("#last_reserv_date").val();
        $("#last_reserv_time").val();
        $("#last_reserv_outlet").val();
        $("#last_reserv_party_size").val();
        var bd_dates = new Array;
        var bd_time = new Array;
        var bd_time_end = new Array;
        //res_id = $(this).parent().next().next().val();
        var res_id = $("#my_reserv_id").val();
       // alert(res_id);
        var vendor_id = $(this).attr('href');
        $('#vendor_id').val(vendor_id);
        var reserve_type_array = vendor_id.split(',');
        var reserve_type = reserve_type_array[0];

        //alert(vendor_id);
        //alert(res_id);
        var now = new Date($("#now").val());
        cur_day = now.getDate();
        cur_month = now.getMonth() + 1;
        cur_year = now.getFullYear();
        cur_date = cur_year + "-" + cur_month + "-" + cur_day;
        cur_time = now.getHours();
        cur_minute = now.getMinutes();
        $(".cant_change").addClass("hidden");
        var g = 1;
        $.ajax({
            type: "GET",
            dataType: "json",
            timeout: 3000,
            url: "/users/get_reservetion/" + res_id,
            beforeSend:function()
                    {
                    $("#myselect_person").html('<img src="/images/loading.gif">');
                    },
            success: function(data) {
                //console.log("sad = "+data);
                $('#party_edit1').show();
               var prev_reserv_date = data["convert_date"];
               var reservation_time = data["convert_time"];
               var no_of_persons    = data["no_of_persons"];
               var last_reservation_date = data["last_reservation_date"];
               var last_reservation_time = data["last_reservation_time"];
               var last_reservation_giftcard_id = data["giftcard_id"];
               $('#myselect_person').text(no_of_persons);
               $('#myselect_date').text(prev_reserv_date);
               $('#myselect_time').text(reservation_time);
               $('#res_id').val(res_id);
               $('#last_reserv_date').val(last_reservation_date);
               $('#last_reserv_time').val(last_reservation_time);
               $('#last_reservation_date').val(last_reservation_date);
               $('#last_reservation_time').val(reservation_time);
               $('#last_reservation_party_size').val(no_of_persons);
               $('#last_reservation_giftcard_id').val(last_reservation_giftcard_id);

               if(reserve_type == 'experience')
               {
                var product_id = reserve_type_array[2];
                var city_id = reserve_type_array[3];
                var vendor_location_id = reserve_type_array[1];
                    $.ajax({
                      url: "/users/myreserv_locality",
                      type: "post",
                      timeout: 3000,
                      data: {
                         
                          product_id: product_id,
                          city_id:city_id,
                          res_id:res_id,
                          vendor_location_id:vendor_location_id
                      },
                      beforeSend:function()
                        {
                        $("#my_locality").html('<div id="load_layer" class="change_loader" ><img src="/images/loading.gif"></div>');
                        },
                      success: function(e) {
                         //console.log(e);
                         $('#my_locality').html(e);
                      },
                        error: function(x, t, m) 
                        {
                            if(t==="timeout") 
                            {
                                alert("Got timeout! Please reload page again.");
                            } 
                        }
                   });

                 $.ajax({
                      url: "/users/myreserv_addons",
                      type: "post",
                      timeout: 3000,
                      data: {
                         
                          product_id: product_id,
                          res_id:res_id,
                          no_of_persons:no_of_persons
                      },
                      beforeSend:function()
                        {
                        $("#my_addons").html('<div id="load_layer" class="change_loader" ><img src="/images/loading.gif"></div>');
                        },
                      success: function(e) {
                         //console.log(e);
                         $('#my_addons').html(e);
                      },
                        error: function(x, t, m) 
                        {
                            if(t==="timeout") 
                            {
                                alert("Got timeout! Please reload page again.");
                            } 
                        }
                   });

                      $.ajax({
                      url: "/users/myreserv_giftcard",
                      type: "post",
                      timeout: 3000,
                      data: {
                          res_id:res_id
                      },
                      beforeSend:function()
                        {
                        $("#my_giftcard").html('<div id="load_layer" class="change_loader" ><img src="/images/loading.gif"></div>');
                        },
                      success: function(e) {
                         //console.log(e);
                         $('#my_giftcard').html(e);
                      },
                        error: function(x, t, m) 
                        {
                            if(t==="timeout") 
                            {
                                alert("Got timeout! Please reload page again.");
                            } 
                        }
                   });

                }

               $.ajax({
                  url: "/users/party_sizeajax",
                  type: "post",
                  timeout: 3000,
                  data: {

                      vendor_id: vendor_id
                  },
                  beforeSend:function()
                    {
                    $("#party_size1").html('<img src="/images/loading.gif">');
                    },
                  success: function(e) {
                     //console.log(e);
                     $('#party_size1').html(e);
                  },
                  error: function(x, t, m)
                        {
                            if(t==="timeout")
                            {
                                alert("Got timeout! Please reload page again.");
                            }
                        }
               });
                /*

                if (data.block_dates.length > 0) {
                    $.each(data.block_dates, function(e, t) {
                        var n = t["block_time"].split("-");
                        bd_time.push(n[0]);
                        bd_time_end.push(n[1]);
                        bd_dates.push(t["block_date"])
                    })
                }
                $("#last_reserv_date").val(data.new_booking_date);
                $("#last_reserv_time").val(data.new_booking_time);
                $("#last_reserv_outlet").val(data.reservs[1].outlet);
                $("#last_reserv_party_size").val(data.reservs[1].no_of_tickets);
                var endtime = data["exp"]["end_date"];
                var reservtime = data["reservs"][1]["booking_time"];
                reservtime = reservtime.split(" ");
                reserv = reservtime[0].split("/");
                reservdate = reserv[2] + "-" + reserv[0] + "-" + reserv[1];
                var myday = reserv[1];
                var mymonth = reserv[0];
                if (mymonth.length == 1) mymonth = "0" + mymonth;
                if (myday.length == 1) myday = "0" + myday;
                var mydate = reserv[2] + "-" + mymonth + "-" + myday;
                var blockTime = "";
                var blockTime_end = "";
                $.each(bd_dates, function(e, t) {
                    if (t == mydate) {
                        blockTime = bd_time[e];
                        blockTime_end = bd_time_end[e]
                    }
                });
                $("#change_date").val(reservdate);
                week = ["sun", "mon", "tue", "wed", "thu", "fri", "sat"][(new Date(reserv[2], reserv[0] - 1, reserv[1])).getDay()];
                weektimes = data["shedule"][week];
                timeskey = Object.keys(weektimes);
                var str = '<div class="btn-group col-lg-10 pull-right actives ">';
                var string = "";
                for (var key in weektimes) {
                    var obj_length = Object.keys(weektimes).length;
                    active_tab = g == obj_length ? "active" : "";
                    active_blck = g == obj_length ? "" : "hidden";
                    str += '<label class="btn btn-warning btn-xs time_tab ' + active_tab + '" id="' + key + '">' + key.toUpperCase() + "</label>";
                    string += '<div id="' + key + '_tab"  class="' + active_blck + '">';
                    $("#" + key).removeClass("hidden");
                    for (var time in weektimes[key]) {
                        if (time >= blockTime && time <= blockTime_end) continue;
                        if (weektimes[key][time] == reservtime[1] + " " + reservtime[2]) {
                            string += '<div class="time col-lg-3 time_active" rel="' + time + '"><a href="javascript:">' + weektimes[key][time] + "</a></div>"
                        } else string += '<div class="time col-lg-3" rel="' + time + '"><a href="javascript:">' + weektimes[key][time] + "</a></div>"
                    }
                    string += "</div>";
                    g++
                }
                str += '</div><div class="clearfix"></div>';
                str += '<input type="hidden" name="booking_time" id="booking_time" value="">';
                $("#hours").html(string);
                $("#time").html(str);
                $("#reserv_type").remove();
                $("#accordion").append('<input type="hidden" name="reserv_type" id="reserv_type" value="experience">');
                $("#changetimes" + timeskey[0]).removeClass("hidden");
                locations = data["row"];
                if (Object.keys(locations).length > 1) {
                    hid_par = 0;
                    $("#accordion").prepend('<div class="panel panel-default" id="clear_location"><div class="panel-heading"><h4 class="panel-title"><a href="javascript:" style="text-decoration: none;">Location </a><select id="locations" name="location" class="pull-right"></select></h4></div></div>');
                    $("#location_edit span").text(data["reservs"][1]["outlet"]);
                    for (var location in locations) {
                        $("#locations").append('<option value="' + locations[location]["address"] + '" ' + (locations[location]["keyword"] == data["reservs"][1]["outlet"] ? "selected" : "") + ">" + locations[location]["keyword"] + "</option>")
                    }
                    $("#locations").change(function() {
                        $(".cant_change").addClass("hidden");
                        $("#save_changes").removeClass("hidden");
                        $("#location_edit").removeClass("hidden");
                        $("#location_edit span").text($(this).val());
                        hid_par = 1;
                        $("#location_edit").click()
                    });
                    $("#location_edit").click(function() {
                        if (hid_par != 1) {
                            $(this).addClass("hidden");
                            $("#party_edit").removeClass("hidden");
                            $("#date_edit").removeClass("hidden");
                            $("#time_edit").removeClass("hidden")
                        } else {
                            hid_par = 0
                        }
                    })
                } else {
                    tmp_str = '<input type="hidden" name="address_keyword" value="' + locations["1"]["keyword"] + '">';
                    tmp_str += '<input type="hidden" id="locations" name="address" value="' + locations["1"]["address"] + '">';
                    $("#accordion").prepend(tmp_str)
                }
                if (data["exp"]["price_non_veg"] != "0.00" || data["exp"]["price_alcohol"] != "0.00") {
                    $("#accordion").append('<div class="panel panel-default" id="clear_meal"><div class="panel-heading"><h4 class="panel-title"><a href="javascript:" style="text-decoration: none;">Meal options<strong><a id="meal_edit" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" style="text-decoration: none;float: right;font-size: 13px;color: #EAB703;"><span style="color: #fff;"></span> EDIT</a></strong></a></h4></div><div id="collapseFour" class="panel-collapse collapse"><div class="panel-body"><div class="col-lg-10" id="non_veg"></div><div class="col-lg-10" id="alcohol"></div></div></div></div>');
                    if (data["exp"]["price_non_veg"] != "0.00") {
                        meals = '<div class="form-group" id="mealoption"><label>Non-vegetarian </label><select class="form-control" id="nonveg">';
                        mealcount = data["reservs"][1]["no_of_tickets"];
                        for (var i = 0; i <= mealcount; i++) {
                            if (i == data["reservs"]["1"]["non_veg"]) meals += "<option selected>" + i + "</option>";
                            else meals += "<option>" + i + "</option>"
                        }
                        meals += "</select></div>";
                        $("#non_veg").html(meals)
                    }
                    if (data["exp"]["price_alcohol"] != "0.00") {
                        meals = '<div class="form-group" id="alcoholoption"><label>Alcohol </label><select class="form-control" id="alcoholedit">';
                        mealcount = data["reservs"][1]["no_of_tickets"];
                        for (var i = 0; i <= mealcount; i++) {
                            if (i == data["reservs"]["1"]["alcohol"]) meals += "<option selected>" + i + "</option>";
                            else meals += "<option>" + i + "</option>"
                        }
                        meals += "</select></div>";
                        $("#alcohol").html(meals)
                    }
                }
                $("#party_edit span").text(data["reservs"][1]["no_of_tickets"]);
                $("#date_edit span").text(formatDate(reservdate));
                $("#changed_date").val(reservdate);
                if (reservdate == cur_date && (cur_time > 19 || cur_time >= 19 && cur_minute >= 30)) {
                    $("#cant_change_table").removeClass("hidden");
                    $("#save_changes").addClass("hidden")
                } else {
                    $("#cant_change_table").addClass("hidden");
                    $("#save_changes").removeClass("hidden")
                }
                $("#save_changes").addClass("hidden");
                $("#time_edit span").text(reservtime[1] + " " + reservtime[2]);
                var min_num_tickets = parseInt(data["exp"]["min_num_tickets"]);
                var max_num_tickets = parseInt(data["exp"]["max_num_tickets"]);
                $("#party_size").find("option").remove();
                for (var i = min_num_tickets; i <= max_num_tickets; i++) {
                    per = i == 1 ? "Person" : "People";
                    if (i == data["reservs"][1]["no_of_tickets"]) {
                        $("#party_size").append("<option value='" + i + "' selected='selected'>" + i + " " + per + "</option>")
                    } else {
                        $("#party_size").append("<option value='" + i + "'>" + i + " " + per + "</option>")
                    }
                }
                $("input[name=typeoption]").change(function(e) {
                    if ($(this).val() == "lunch") {
                        $("#changetimes" + $(this).val()).removeClass("hidden");
                        $("#changetimesdinner").addClass("hidden")
                    } else {
                        $("#changetimes" + $(this).val()).removeClass("hidden");
                        $("#changetimeslunch").addClass("hidden")
                    }
                });
                $("#party_size").change(function() {
                    size = $(this).val();
                    $("#party_edit span").text(size);
                    sizehide = 1;
                    $(this).addClass("hidden");
                    $("#party_edit").removeClass("hidden");
                    if ($("#date_edit span").text() == "") {
                        $("#date_edit").click()
                    } else if ($("#time_edit span").text() == "") {
                        $("#time_edit").click()
                    } else if (!$("#collapseFour").hasClass("in")) {
                        $("#meal_edit").click()
                    }
                    ifchangedparams()
                });
                var nowTemp = new Date;
                var date_array = endtime.split("-");
                mytime = new Date(date_array[0], date_array[1] - 1, date_array[2]);
                var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
                var end = new Date(mytime.getFullYear(), mytime.getMonth(), mytime.getDate(), 0, 0, 0, 0);
                dtp = $("#change_date").datepicker({
                    dateFormat: "yy-m-d",
                    minDate: eval(data.new_start_date),
                    maxDate: eval(data.new_end_date),
                    beforeShowDay: function(e) {
                        var t = data.exp["is_event"];
                        if (t == 1) {
                            var n = e.getMonth(),
                                r = e.getDate(),
                                i = e.getFullYear();
                            if ($.inArray(i + "-" + (n + 1) + "-" + r, data.shedule) != -1) {
                                return [true, "", ""]
                            } else {
                                return [false]
                            }
                            return e
                        } else {
                            var s = $.datepicker.formatDate("D", e).toLowerCase()
                        }
                        if (data.shedule[s] == undefined) {
                            return new Array(false)
                        }
                        if (bd_dates.length > 0) {
                            tmp_date = $.datepicker.formatDate("yy-mm-dd", e);
                            tmp_day = $.datepicker.formatDate("dd", e);
                            for (var o in bd_dates) {
                                if (bd_dates[o] == tmp_date) {
                                    return new Array(false, "closed_date")
                                }
                            }
                        }
                        return [e]
                    },
                    onSelect: function(e, t) {
                        $(this).datepicker("hide");
                        datehidden = 1;
                        if (cur_date == e && (cur_time > 19 || cur_time >= 19 && cur_minute >= 30)) {
                            $("#cant_change_table").removeClass("hidden");
                            $("#save_changes").addClass("hidden")
                        } else {
                            $("#date_edit").click();
                            $("#time_edit").click();
                            $("#cant_change_table").addClass("hidden");
                            $("#save_changes").removeClass("hidden")
                        }
                        $("#date_edit span").text(formatDate(e));
                        $("#changed_date").val(e);
                        $("#date_edit").removeClass("hidden");
                        var n = $.datepicker.parseDate("yy-m-dd", e);
                        var r = $.datepicker.formatDate("D", n).toLowerCase();
                        var i = '<div class="btn-group col-lg-10 pull-right actives ">';
                        var s = "";
                        var o = 1;
                        for (key_sch in data.shedule[r]) {
                            var u = Object.keys(data.shedule[r]).length;
                            active_tab = o == u ? "active" : "";
                            active_blck = o == u ? "" : "hidden";
                            i += '<label class="btn btn-warning btn-xs time_tab ' + active_tab + '" id="' + key_sch + '">' + key_sch.toUpperCase() + "</label>";
                            s += '<div id="' + key_sch + '_tab"  class="' + active_blck + '">';
                            for (key_sch_time in data.shedule[r][key_sch]) {
                                s += '<div class="time col-lg-3" rel="' + data.shedule[r][key_sch][key_sch_time] + '"><a href="javascript:">' + data.shedule[r][key_sch][key_sch_time] + "</a></div>"
                            }
                            s += "</div>";
                            o++
                        }
                        if (data.shedule_time != undefined) {
                            var a = data.shedule_time;
                            var r = $.datepicker.formatDate("D", n).toLowerCase();
                            for (key in a[r]) {
                                var u = Object.keys(a[r]).length;
                                active_tab = o == u ? "active" : "";
                                active_blck = o == u ? "" : "hidden";
                                i += '<label class="btn btn-warning btn-xs time_tab ' + active_tab + '" id="' + key + '">' + key.toUpperCase() + "</label>";
                                s += '<div id="' + key + '_tab"  class="' + active_blck + '">';
                                for (key_sch_time in a[r][key]) {
                                    s += '<div class="time col-lg-3" rel="' + a[r][key][key_sch_time] + '"><a href="javascript:">' + a[r][key][key_sch_time] + "</a></div>";
                                    s += "</div>";
                                    o++
                                }
                            }
                        }
                        i += '</div><div class="clearfix"></div>';
                        i += '<input type="hidden" name="booking_time" id="booking_time" value="">';
                        $("#time").html(i);
                        $("#hours").html(s);
                        ifchangedparams()
                    }
                });
                $("#meal_edit").click(function() {
                    $("#time_edit").removeClass("hidden");
                    $("#date_edit").removeClass("hidden");
                    $("#party_edit").removeClass("hidden");
                    $("#location_edit").removeClass("hidden")
                })*/
            },
            error: function(x, t, m)
            {
                if(t==="timeout")
                {
                    alert("Got timeout! Please reload page again.");
                }
            }
        })
    });
    $("#myres_div #ac_change_reservation").click(function() {
        $("#last_reserv_date").val();
        $("#last_reserv_time").val();
        $("#last_reserv_outlet").val();
        $("#last_reserv_party_size").val();
        var bd_dates = new Array;
        res_id = $(this).parent().next().next().val();
        var now = new Date($("#now").val());
        cur_day = now.getDate();
        cur_month = now.getMonth() + 1;
        cur_year = now.getFullYear();
        cur_date = cur_year + "-" + cur_month + "-" + cur_day;
        cur_time = now.getHours();
        cur_minute = now.getMinutes();
        $(".cant_change").addClass("hidden");
        var g = 1;
        $.ajax({
            type: "GET",
            dataType: "json",
            url: "/users/ac_get_reservetion/" + res_id,
            success: function(data) {
                if (data.block_dates.length > 0) {
                    $.each(data.block_dates, function(e, t) {
                        bd_dates.push(t["block_date"])
                    })
                }
                $("#last_reserv_date").val(data.new_booking_date);
                $("#last_reserv_time").val(data.new_booking_time);
                $("#last_reserv_outlet").val(data.reservs[1].outlet);
                $("#last_reserv_party_size").val(data.reservs[1].no_of_tickets);
                //var endtime = data["exp"]["end_date"];
                var reservtime = data["reservs"][1]["booking_time"];
                reservtime = reservtime.split(" ");
                reserv = reservtime[0].split("/");
                reservdate = reserv[2] + "-" + reserv[0] + "-" + reserv[1];
                $("#change_date").val(reservdate);
                week = ["sun", "mon", "tue", "wed", "thu", "fri", "sat"][(new Date(reserv[2], reserv[0] - 1, reserv[1])).getDay()];
                weektimes = data["shedule"][week];
                timeskey = Object.keys(weektimes);
                var str = '<div class="btn-group col-lg-10 pull-right actives ">';
                var string = "";
                for (var key in weektimes) {
                    var obj_length = Object.keys(weektimes).length;
                    active_tab = g == obj_length ? "active" : "";
                    active_blck = g == obj_length ? "" : "hidden";
                    str += '<label class="btn btn-warning btn-xs time_tab ' + active_tab + '" id="' + key + '">' + key.toUpperCase() + "</label>";
                    string += '<div id="' + key + '_tab"  class="' + active_blck + '">';
                    $("#" + key).removeClass("hidden");
                    for (var time in weektimes[key]) {
                        if (weektimes[key][time] == reservtime[1] + " " + reservtime[2]) {
                            string += '<div class="time col-lg-3 time_active" rel="' + time + '"><a href="javascript:">' + weektimes[key][time] + "</a></div>"
                        } else string += '<div class="time col-lg-3" rel="' + time + '"><a href="javascript:">' + weektimes[key][time] + "</a></div>"
                    }
                    string += "</div>";
                    g++
                }
                str += '</div><div class="clearfix"></div>';
                str += '<input type="hidden" name="booking_time" id="booking_time" value="">';
                $("#hours").html(string);
                $("#time").html(str);
                $("#reserv_type").remove();
                $("#accordion").append('<input type="hidden" name="reserv_type" id="reserv_type" value="alacarte">');
                $("#changetimes" + timeskey[0]).removeClass("hidden");
                locations = data["row"];
                /*if (Object.keys(locations).length > 1) {
                    hid_par = 0;
                    $("#accordion").prepend('<div class="panel panel-default" id="clear_location"><div class="panel-heading"><h4 class="panel-title"><a href="javascript:" style="text-decoration: none;">Location </a><select id="locations" name="location" class="pull-right"></select></h4></div></div>');
                    $("#location_edit span").text(data["reservs"][1]["outlet"]);
                    for (var location in locations) {
                        $("#locations").append('<option value="' + locations[location]["address"] + '" ' + (locations[location]["outlet_name"] == data["reservs"][1]["outlet"] ? "selected" : "") + ">" + locations[location]["outlet_name"] + "</option>")
                    }
                    $("#locations").change(function() {
                        $(".cant_change").addClass("hidden");
                        $("#save_changes").removeClass("hidden");
                        $("#location_edit").removeClass("hidden");
                        $("#location_edit span").text($(this).val());
                        hid_par = 1;
                        $("#location_edit").click()
                    });
                    $("#location_edit").click(function() {
                        if (hid_par != 1) {
                            $(this).addClass("hidden");
                            $("#party_edit").removeClass("hidden");
                            $("#date_edit").removeClass("hidden");
                            $("#time_edit").removeClass("hidden")
                        } else {
                            hid_par = 0
                        }
                    })
                } else {*/
                    tmp_str = '<input type="hidden" name="address_keyword" value="' + locations["outlet_name"] + '">';
                    tmp_str += '<input type="hidden" id="locations" name="address" value="' + locations["address"] + '">';
                    $("#accordion").prepend(tmp_str)
                //}
                $("#party_edit span").text(data["reservs"][1]["no_of_tickets"]);
                $("#date_edit span").text(formatDate(reservdate));
                $("#changed_date").val(reservdate);
                if (reservdate == cur_date && (cur_time > 19 || cur_time >= 19 && cur_minute >= 30)) {
                    $("#cant_change_table").removeClass("hidden");
                    $("#save_changes").addClass("hidden")
                } else {
                    $("#cant_change_table").addClass("hidden");
                    $("#save_changes").removeClass("hidden")
                }
                $("#save_changes").addClass("hidden");
                $("#time_edit span").text(reservtime[1] + " " + reservtime[2]);
                var min_num_tickets = parseInt(1);
                var max_num_tickets = parseInt(10);
                $("#party_size").find("option").remove();
                for (var i = min_num_tickets; i <= max_num_tickets; i++) {
                    per = i == 1 ? "Person" : "People";
                    if (i == data["reservs"][1]["no_of_tickets"]) {
                        $("#party_size").append("<option value='" + i + "' selected='selected'>" + i + " " + per + "</option>")
                    } else {
                        $("#party_size").append("<option value='" + i + "'>" + i + " " + per + "</option>")
                    }
                }
                $("input[name=typeoption]").change(function(e) {
                    if ($(this).val() == "lunch") {
                        $("#changetimes" + $(this).val()).removeClass("hidden");
                        $("#changetimesdinner").addClass("hidden")
                    } else {
                        $("#changetimes" + $(this).val()).removeClass("hidden");
                        $("#changetimeslunch").addClass("hidden")
                    }
                });
                $("#party_size").change(function() {
                    size = $(this).val();
                    $("#party_edit span").text(size);
                    sizehide = 1;
                    $(this).addClass("hidden");
                    $("#party_edit").removeClass("hidden");
                    if ($("#date_edit span").text() == "") {
                        $("#date_edit").click()
                    } else if ($("#time_edit span").text() == "") {
                        $("#time_edit").click()
                    } else if (!$("#collapseFour").hasClass("in")) {
                        $("#meal_edit").click()
                    }
                    ifchangedparams()
                });
                var nowTemp = new Date;
                //var date_array = endtime.split("-");
                //mytime = new Date(date_array[0], date_array[1] - 1, date_array[2]);
                var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
                //var end = new Date(mytime.getFullYear(), mytime.getMonth(), mytime.getDate(), 0, 0, 0, 0);
                dtp = $("#change_date").datepicker({
                    dateFormat: "yy-m-d",
                    minDate: eval(data.new_start_date),
                    //maxDate: eval(data.new_end_date),
                    beforeShowDay: function(e) {
                        var t = data.exp["is_event"];
                        if (t == 1) {
                            var n = e.getMonth(),
                                r = e.getDate(),
                                i = e.getFullYear();
                            if ($.inArray(i + "-" + (n + 1) + "-" + r, data.shedule) != -1) {
                                return [true, "", ""]
                            } else {
                                return [false]
                            }
                            return e
                        } else {
                            var s = $.datepicker.formatDate("D", e).toLowerCase()
                        }
                        if (data.shedule[s] == undefined) {
                            return new Array(false)
                        }
                        if (bd_dates.length > 0) {
                            tmp_date = $.datepicker.formatDate("yy-mm-dd", e);
                            tmp_day = $.datepicker.formatDate("dd", e);
                            for (var o in bd_dates) {
                                if (bd_dates[o] == tmp_date) {
                                    return new Array(false, "closed_date")
                                }
                            }
                        }
                        return [e]
                    },
                    onSelect: function(e, t) {
                        $(this).datepicker("hide");
                        datehidden = 1;
                        if (cur_date == e && (cur_time > 19 || cur_time >= 19 && cur_minute >= 30)) {
                            $("#cant_change_table").removeClass("hidden");
                            $("#save_changes").addClass("hidden")
                        } else {
                            $("#date_edit").click();
                            $("#time_edit").click();
                            $("#cant_change_table").addClass("hidden")
                        }
                        $("#date_edit span").text(formatDate(e));
                        $("#changed_date").val(e);
                        $("#date_edit").removeClass("hidden");
                        var n = $.datepicker.parseDate("yy-m-dd", e);
                        var r = $.datepicker.formatDate("D", n).toLowerCase();
                        var i = '<div class="btn-group col-lg-10 pull-right actives ">';
                        var s = "";
                        var o = 1;
                        for (key_sch in data.shedule[r]) {
                            var u = Object.keys(data.shedule[r]).length;
                            active_tab = o == u ? "active" : "";
                            active_blck = o == u ? "" : "hidden";
                            i += '<label class="btn btn-warning btn-xs time_tab ' + active_tab + '" id="' + key_sch + '">' + key_sch.toUpperCase() + "</label>";
                            s += '<div id="' + key_sch + '_tab"  class="' + active_blck + '">';
                            for (key_sch_time in data.shedule[r][key_sch]) {
                                s += '<div class="time col-lg-3" rel="' + data.shedule[r][key_sch][key_sch_time] + '"><a href="javascript:">' + data.shedule[r][key_sch][key_sch_time] + "</a></div>"
                            }
                            s += "</div>";
                            o++
                        }
                        if (data.shedule_time != undefined) {
                            var a = data.shedule_time;
                            var r = $.datepicker.formatDate("D", n).toLowerCase();
                            for (key in a[r]) {
                                var u = Object.keys(a[r]).length;
                                active_tab = o == u ? "active" : "";
                                active_blck = o == u ? "" : "hidden";
                                i += '<label class="btn btn-warning btn-xs time_tab ' + active_tab + '" id="' + key + '">' + key.toUpperCase() + "</label>";
                                s += '<div id="' + key + '_tab"  class="' + active_blck + '">';
                                for (key_sch_time in a[r][key]) {
                                    s += '<div class="time col-lg-3" rel="' + a[r][key][key_sch_time] + '"><a href="javascript:">' + a[r][key][key_sch_time] + "</a></div>";
                                    s += "</div>";
                                    o++
                                }
                            }
                        }
                        i += '</div><div class="clearfix"></div>';
                        i += '<input type="hidden" name="booking_time" id="booking_time" value="">';
                        $("#time").html(i);
                        $("#hours").html(s);
                        ifchangedparams()
                    }
                });
                $("#meal_edit").click(function() {
                    $("#time_edit").removeClass("hidden");
                    $("#date_edit").removeClass("hidden");
                    $("#party_edit").removeClass("hidden");
                    $("#location_edit").removeClass("hidden")
                })
            }
        })
    });
   
    
    $(".close_modal").click(function() {
        setTimeout(window.location = "/users/myreservations", 3e3)
    });
    $("#editModal").on("hidden", function(e) {});
    $("#editModal > .modal-dialog").on("hide", function() {
        Utils.clearFields();
        $("#clear_location").remove();
        $("#clear_meal").remove()
    });
    $("#thank_details").click(function(e) {
        e.preventDefault();
        content = $("#det_content").val();
        emails = $("#guest_emails").val();
        user = $("input[name=user_email]").val();
        reservType = $("input[name=reserv_type]").val();
        if (reservType == "alacarte") {
            restlocid = $("input[name=vendorLocationID]").val();
            reservid = $("input[name=reservid]").val();
            expid =  0;
            explocid = 0;
            guests = $("input[name=number_guests]").val();
            date_reservation = $("input[name=date_reservation]").val();
            date_seating = $("input[name=date_seating]").val();
            outlet_name = $("input[name=outlet_name]").val();
            expname =  0;
        } else if(reservType == "experience") {
            restlocid = 0;
            reservid = $("input[name=reservid]").val();
            expid =  $("input[name=experienceid]").val();
            explocid = $("input[name=experienceLocationID]").val();
            guests = $("input[name=number_guests]").val();
            date_reservation = $("input[name=date_reservation]").val();
            date_seating = $("input[name=date_seating]").val();
            outlet_name = $("input[name=outlet_name]").val();
            expname =  0;
        } else if(reservType == "experience_detail") {
            restlocid = 0;
            reservid = 0;
            expid =  $("input[name=experienceid]").val();
            expname =  $("input[name=experience_name]").val();
            explocid = 0;
            guests = 0;
            date_reservation = 0;
            date_seating = 0;
            outlet_name = 0;
        } else if(reservType == "alacarte_detail"){
            restlocid = 0;
            reservid = 0;
            expid =  0;
            expname =  0;
            explocid = 0;
            guests = 0;
            date_reservation = 0;
            date_seating = 0;
            outlet_name = $("input[name=outlet_name]").val();
        }
        if (emails != "" || content != "") {
            $.ajax({
                url: '/thankyou/sharedetails',
                type: "post",
                data: {
                    content: content,
                    emails: emails,
                    user: user,
                    reservid: reservid,
                    userid: $("input[name=userid]").val(),
                    expid: expid,
                    explocid: explocid,
                    restid: $("input[name=restaurantID]").val(),
                    url_product: $("input[name=url_product]").val(),
                    reservation_type: $("input[name=reserv_type]").val(),
                    guests: guests,
                    date_reservation: date_reservation,
                    date_seating: date_seating,
                    restaurant: $("input[name=restaurant]").val(),
                    outlet_name: outlet_name,
                    short_description: $("input[name=short_description]").val(),
                    restlocid: restlocid,
                    expname:expname
                },
                success: function(e) {
                    if (e == 1) {
                        $("#error_email").addClass("hidden");
                        $("#error_content").addClass("hidden");
                        $("#email_form").addClass("hidden");
                        $("#email_sent_confirmation").removeClass("hidden");
                        $("#guest_emails").val('');
                        $("#det_content").val('');
                    }
                }
            })
        } else {
            $("#error_email").removeClass("hidden");
            $("#error_content").removeClass("hidden")
        }
    });
    $("#send_reservation").click(function() {
        $("#guest_emails").val('');
        $("#det_content").val('');
        $("#email_form").removeClass("hidden");
        $("#email_sent_confirmation").addClass("hidden")
    });
    hide = 0;
    sizehide = 0;
    $("#locations1").change(function(e) {
        address = $("#locations1").val();
        loc = $(this).find("option:selected").text();
        $("input[name=address_keyword]").val(loc);
        hide = 1;
        $("#location_edit").click()
    });
    $("#ac_locations1").change(function(e) {
        address = $("#ac_locations1").val();
        loc = $(this).find("option:selected").text();
        $("input[name=address_keyword]").val(loc);
        hide = 1;
        $("#ac_location_edit").click()
    });
    $("#location_edit").click(function(e) {
        $("#party_edit1").removeClass("hidden");
        if (hide != 1) {
            $(this).addClass("hidden")
        } else {
            hide = 0;
            $(this).removeClass("hidden")
        }
    });
    $("#party_size1").change(function(e) {
        $("#cant_do_reserv1,#cant_do_reserv2,#brs_my_reserv").addClass("hidden");
        $("#select_table_exp").removeClass("hidden");
        $("#select_all_data").addClass("hidden");
        $("#or_reservation").removeClass("hidden");

        size = $(this).val();
        $("#party_edit1 span").text(size);
        sizehide = 1;
        $(this).addClass("hidden");
        $("#party_edit1").removeClass("hidden");
        if ($("#date_edit1 span").text() == "") {
            $("#date_edit1").click()
        } else if ($("#time_edit1 span").text() == "") {
            $("#time_edit1").click()
        }
		if ($("#collapseFour").hasClass("in")) {		
            var party_count = $("#party_edit1 span").text();
			str = "";
			for (var e = 0; e <= party_count; e++) {
				str += "<option value='" + e + "'>" + e + "</option>";
			}
			$(".meals select").html(str);
        } 
    });

    $("#ac_party_size1").change(function(e) {
        size = $(this).val();
        $("#ac_party_edit1 span").text(size);
        sizehide = 1;
        $(this).addClass("hidden");
        $("#ac_party_edit1").removeClass("hidden");
        if ($("#ac_date_edit1 span").text() == "") {
            $("#ac_date_edit1").click()
        } else if ($("#ac_time_edit1 span").text() == "") {
            $("#ac_time_edit1").click()
        } else if (!$("#ac_collapseFour").hasClass("in")) {
            $("#ac_meal_edit1").click()
        }
    });

	/*** start alacarte*/
	$("#ac_party_size2").change(function(e) {
        size = $(this).val();
        $("#ac_party_edit2 span").text(size);
        sizehide = 1;
        $(this).addClass("hidden");
        $("#ac_party_edit2").removeClass("hidden");
        if ($("#ac_date_edit2 span").text() == "") {
            $("#ac_date_edit2").click()
        } else if ($("#ac_time_edit2 span").text() == "") {
            $("#ac_time_edit2").click()
        } /*else if (!$("#ac_collapseFour").hasClass("in")) {
            $("#ac_meal_edit1").click()
        }*/
    });
	/*** end alacarte*/

    $("#party_edit1").click(function() {
        $('#save_changes').show();
        if ($("#collapseTwo").hasClass("in")) {
            $("#date_edit1").click();
            if ($("#date_edit1 span").text() != "") {
                $("#date_edit1").removeClass("hidden")
            }
        } else if ($("#collapseThree").hasClass("in")) {
            $("#time_edit1").click();
            if ($("#time_edit1 span").text() != "") {
                $("#time_edit1").removeClass("hidden")
            }
        }

        $("#party_size1").removeClass("hidden");
        $("#location_edit").removeClass("hidden");
        $(this).addClass("hidden")
    });



    $('#time_edit1').click(function(){
          $("#cant_do_reserv1,#cant_do_reserv2,#brs_my_reserv").addClass("hidden");
          $("#select_table_exp").removeClass("hidden");
          $("#select_all_data").addClass("hidden");
          $("#or_reservation").removeClass("hidden");
          $('#party_edit1').removeClass('hidden');
          $('#date_edit1').removeClass('hidden');
          if(timehide!=1)
          {
              $(this).addClass('hidden');
          } 
          else 
          {
              timehide=0;
              $(this).removeClass('hidden');   
          }   
     });

    $("#date_edit1").click(function() {
        $("#cant_do_reserv1,#cant_do_reserv2,#brs_my_reserv").addClass("hidden");
        $("#select_table_exp").removeClass("hidden");
        $("#select_all_data").addClass("hidden");
        $("#or_reservation").removeClass("hidden");

        if ($("#collapseThree").hasClass("in")) {
            $("#time_edit1").click()
        } else if ($("#collapseFour").hasClass("in")) {
            $("#meal_edit1").click()
        }
        if ($("#time_edit1 span").text() != "") {
            $("#time_edit1").removeClass("hidden")
        }
        $("#date_edit1").addClass("hidden");
        $("#party_edit1").removeClass("hidden");
        $("#party_size1").addClass("hidden")
    });
    $("#ac_date_edit1").click(function() {
        if ($("#ac_collapseThree").hasClass("in")) {
            $("#ac_time_edit1").click()
        } else if ($("#ac_collapseFour").hasClass("in")) {
            $("#ac_meal_edit1").click()
        }
        if ($("#ac_time_edit1 span").text() != "") {
            $("#ac_time_edit1").removeClass("hidden")
        }
        $("#ac_date_edit1").addClass("hidden");
        $("#ac_party_edit1").removeClass("hidden");
        $("#ac_party_size1").addClass("hidden")
    });
    $("#time_edit1").click(function() {
        
        if ($("#collapseFour").hasClass("in")) {
            $("#meal_edit1").click()
        }
        $("#party_size1").addClass("hidden");
        $("#party_edit1").removeClass("hidden")
        $("#party_size1").addClass("hidden")
    });
    $(document).on("click", ".actives label", function() {
        label_id = $(this).attr("id");
        if (label_id == "breakfast") {
            $(this).addClass("active");
            $("#dinner,#lunch").removeClass("active");
            $("#breakfast_tab").removeClass("hidden");
            $("#lunch_tab,#dinner_tab").addClass("hidden")
        } else if (label_id == "lunch") {
            $(this).addClass("active");
            $("#dinner,#breakfast").removeClass("active");
            $("#lunch_tab").removeClass("hidden");
            $("#breakfast_tab,#dinner_tab").addClass("hidden")
        } else {
            $(this).addClass("active");
            $("#lunch,#breakfast").removeClass("active");
            $("#dinner_tab").removeClass("hidden");
            $("#lunch_tab,#breakfast_tab").addClass("hidden")
        }
    });
    $(document).on("click", ".time", function() {

        $("#hours").find(".time_active").removeClass("time_active");
        $(this).addClass("time_active");
        $("#time_edit span").text($(this).text());
        $("#booking_time").val($(this).text());
        var mybookTime = $("#booking_time").val(); //kailash
        $('#myselect_time').text(mybookTime);  //kailash
        $("#time_edit").removeClass("hidden");
        timehide = 1;
        $("#time_edit").click();
        counter = $("#party_edit1 span").text();
        str = "";
        for (var e = 0; e <= counter; e++) {
            str += "<option value='" + e + "'>" + e + "</option>"
        }
        $(".meals select").html(str);
        $("#meal_edit1").click();
        ifchangedparams()
    });

	/*** start alacarte details*/
	$(document).on("click", ".alacarte_time", function() {
        $("#alacarte_hours").find(".time_active").removeClass("time_active");
        $(this).addClass("time_active");
        $("#ac_time_edit2 span").text($(this).text());
        $("#alacarte_booking_time").val($(this).text());
        $("#ac_time_edit2").removeClass("hidden");
        timehide = 1;
        $("#ac_time_edit2").click();
        counter = $("#ac_party_edit2 span").text();
        str = "";
        for (var e = 0; e <= counter; e++) {
            str += "<option value='" + e + "'>" + e + "</option>"
        }
        $(".meals select").html(str);
        $("#meal_edit").click();
        ifchangedparams()
    });
	/*** end alacarte details*/
    var open_order_info = false;
    
    $("#ac_select_table").click(function(e) {
        e.preventDefault();
        if ($("#ac_booking_date").val() && $("#ac_booking_time").val()) {
            if (logged_in == "1") {
                has_reserv = false;
                $.ajax({
                    url: "/orders/ac_check_order_exists",
                    type: "POST",
                    data: {
                        booking_date: $("#ac_booking_date").val(),
                        booking_time: $("#ac_booking_time").val()
                    },
                    async: false,
                    success: function(e) {
                        if (e == 1) {
                            has_reserv = true;
                            $("#ac_cant_do_reserv1,#ac_cant_do_reserv2,#ac_brs_my_reserv").removeClass("hidden");
                            $("#ac_select_table").addClass("hidden");
                            $("#ac_or_reservation").addClass("hidden")
                        } else {
                            $("#ac_cant_do_reserv1,#ac_cant_do_reserv2,#ac_brs_my_reserv").addClass("hidden");
                            $("#ac_select_table").removeClass("hidden");
                            $("#ac_select_all_data").addClass("hidden");
                            $("#ac_or_reservation").removeClass("hidden");
                            has_reserv = false
                        }
                    }
                });
                $("#ac_load_layer").hide();
                if (!has_reserv) {
                    $("#jump2-expres").addClass("hidden");
                    $("#ac_reserv_table").slideUp();
                    $(this).addClass("hidden");
                    $("#ac_or_reservation").addClass("hidden");
                    if (open_order_info) {
                        $("#ac_order_info").slideDown()
                    }
                    $("#ac_order_info").removeClass("hidden");
                    full_info = $("#ac_party_edit1 span").text() + " people - " + $("#ac_time_edit1 span").text() + " - " + $("#ac_date_edit1 span").text();
                    $("#ac_fullinfo").html("<strong>" + full_info + "</strong>")
                }
            } else {
                $.ajax({
                    url: "/experience/request_reg",
                    type: "GET",
                    data: {
                        location: $("#ac_locations1").val(),
                        outlet: $("#ac_locations1 option:selected").text(),
                        date: $("#ac_date_edit1 span").text(),
                        time: $("#ac_time_edit1 span").text(),
                        non_veg: $("#ac_non_veg").val(),
                        alcohol: $("#ac_alcohol").val(),
                        qty: $("#ac_party_edit1 span").text(),
                        slug: $("#ac_slug").val()
                    },
                    success: function() {},
                    error: function() {},
                    cache: false
                })
            }
        } else {
            $("#ac_select_table").addClass("hidden");
            $("#ac_select_all_data").removeClass("hidden")
        }
    });

	/*** starts alacarte*/
	$("#ac_select_table2").click(function(e) {
        e.preventDefault();
        if ($("#ac_booking_date2").val() && $("#alacarte_booking_time").val()) {
            if (logged_in == "1") {
                has_reserv = false;
                $.ajax({
                    url: "/orders/ac_check_order_exists",
                    type: "POST",
                    data: {
                        booking_date: $("#ac_booking_date2").val(),
                        booking_time: $("#alacarte_booking_time").val()
                    },
                    async: false,
                    success: function(e) {
                        if (e == 1) {
                            has_reserv = true;
                            $('#alacarte_cant_do_reserv1,#alacarte_cant_do_reserv2,#alacarte_brs_my_reserv').removeClass("hidden");
                            $("#ac_select_table2").addClass("hidden");
                        } else {
                            $('#alacarte_cant_do_reserv1,#alacarte_cant_do_reserv2,#alacarte_brs_my_reserv').addClass("hidden");
                            $("#ac_select_table2").removeClass("hidden");
                            $("#ac_select_all_data2").addClass("hidden");
                            has_reserv = false
                        }
                    }
                });
                $("#alacarte_load_layer").hide();
                if (!has_reserv) {
                    $("#jump2-expres").addClass("hidden");
                    $("#ac_reserv_table2").slideUp();
                    $(this).addClass("hidden");
                    if (open_order_info) {
                        $("#ac_order_info2").slideDown()
                    }
                    $("#ac_order_info2").removeClass("hidden");
                    full_info = $("#ac_party_edit2 span").text() + " people - " + $("#ac_time_edit2 span").text() + " - " + $("#ac_date_edit2 span").text();
					//console.log("full info = "+full_info);
                    $("#ac_fullinfo2").html("<strong>" + full_info + "</strong>")
                }
            } else {
                $.ajax({
                    url: "/alacarte/request_alacarte_reg",
                    type: "GET",
                    data: {
                        date: $("#ac_date_edit2 span").text(),
                        time: $("#ac_time_edit2 span").text(),
                        qty: $("#ac_party_edit2 span").text(),
                        slug: $("#ac_slug2").val()
                    },
                    success: function() {},
                    error: function() {},
                    cache: false
                })
            }
        } else {
            $("#ac_select_table2").addClass("hidden");
            $("#ac_select_all_data2").removeClass("hidden")
        }
    });
	/*** end alacarte*/
    $("#info_edit1").click(function() {
        open_order_info = true;
        $("#reserv_table").slideDown();
        $("#select_table_exp").removeClass("hidden");
        $("#select_all_data").addClass("hidden");
        $("#or_reservation").removeClass("hidden");
        $("#jump2-alacarte").removeClass("hidden");
        $("#order_info").slideUp()
        $("#or_reservation").hide();
        $("#cant_do_reserv1,#cant_do_reserv2,#brs_my_reserv").hide();
    });
    $("#ac_info_edit1").click(function() {
        open_order_info = true;
        $("#ac_reserv_table").slideDown();
        $("#ac_select_table").removeClass("hidden");
        $("#ac_select_all_data").addClass("hidden");
        $("#ac_or_reservation").removeClass("hidden");
        $("#jump2-expres").removeClass("hidden");
        $("#ac_order_info").slideUp()
    });
	/*start  alacarte details*/
	$("#ac_info_edit2").click(function() { //console.log("clicked");
        open_order_info = true;
        $("#ac_reserv_table2").slideDown();
        $("#ac_select_table2").removeClass("hidden");
        $("#ac_select_all_data2").addClass("hidden");
        //$("#ac_or_reservation").removeClass("hidden");
        //$("#jump2-expres").removeClass("hidden");
        $("#ac_order_info2").slideUp()
    });
	/*** end alacarte details*/
    $(document).on("click", ".time", function() {
        $("#time_edit1 span").text($(this).text());
        $("#time_edit1").removeClass("hidden");
        $("#select_table_exp").removeClass("hidden");
        $("#select_all_data").addClass("hidden");
        $("#or_reservation").removeClass("hidden");
        $("#time_edit1").click()
        counter = $("#party_edit1 span").text();
        str = "";
        for (var e = 0; e <= counter; e++) {
            str += "<option value='" + e + "'>" + e + "</option>"
        }
        $(".meals select").html(str);
        $("#meal_edit1").click();
        ifchangedparams()
    });

	/*starts alacarte details*/
	$(document).on("click", ".alacarte_time", function() { //console.log("called");
        $("#ac_time_edit2 span").text($(this).text());
        $("#ac_time_edit2").removeClass("hidden");
        $("#ac_select_table2_ala").removeClass("hidden");
        $("#ac_select_all_data2").addClass("hidden");
        $("#or_reservation").removeClass("hidden");
        $("#ac_time_edit2").click()
    });
	/*end alacarte details*/

    $("#a_la_carte").click(function() {
        var e = $("#experience_id").val();
        $("#a_la_carte").parents().find(".disclaimer ").hide();
        $.ajax({
            url: "/users/sendemail",
            type: "POST",
            data: {
                id: e
            },
            success: function() {},
            error: function() {},
            cache: false
        })
    });
    $("#submit").click(function() {
        var e = $("#url").val();
        var t = $("#exp_id").val();
        if ($("#comments").val() == "") {
            alert("Please Enter your Question");
            return false
        }
        $.ajax({
            type: "POST",
            url: e + "experience_rq/save",
            data: {
                experience_id: t,
                comments: $("#comments").val()
            },
            success: function(e) {
                if (e == 1) {
                    $("#review").html("<p class='lead'>Your submission will be added to this page after a review.</p>")
                } else {
                    alert("Error Occured. Please Try again later.")
                }
            }
        })
    });
    $("#show_more_reservations").click(function() {
        $(this).hide();
        $("#load_layer").show();
        var map_id = $("#map_id").val();
        $.ajax({
            type: "GET",
            data: {
                map_id: map_id
            },
            dataType: "json",
            url: "/users/get_previous_reservs",
            success: function(data) {
                $(".last_reservations").before(data.str);
                for (var i in data.google_map) {
                    var str = "var dealer_lat" + map_id + " = data.google_map[" + i + "]['lat'];var dealer_lng" + map_id + " = data.google_map[" + i + "]['long'];var mylatlng" + map_id + "=  new google.maps.LatLng(dealer_lat" + map_id + ", dealer_lng" + map_id + ");var mapOptions" + map_id + " = {center: mylatlng" + map_id + ",zoom: 16};var map" + map_id + " = new google.maps.Map(document.getElementById('map" + map_id + "'),mapOptions" + map_id + ");var marker" + map_id + " = new google.maps.Marker({position: mylatlng" + map_id + ",map: map" + map_id + "});";
                    eval(str);
                    map_id++
                }
                $("#load_layer").hide()
            }
        })
    });
    $(".forward_guests").click(function() {
        var e = $(this).attr("rel").split("|");
        $("#reservid").val(e[0]);
        $("#experienceid").val(e[1]);
        $("#email_form").removeClass("hidden");
        $("#email_sent_confirmation").addClass("hidden");
        $("#guest_emails").val("");
        $("#guest_emails").text("");
        $("#det_content").val("");
        $("#det_content").text("")
    });
    $("#ac_party_edit1").click(function() {
        if ($("#ac_collapseTwo").hasClass("in")) {
            $("#ac_date_edit1").click();
            if ($("#ac_date_edit1 span").text() != "") {
                $("#ac_date_edit1").removeClass("hidden")
            }
        } else if ($("#ac_collapseThree").hasClass("in")) {
            $("#ac_time_edit1").click();
            if ($("#ac_time_edit1 span").text() != "") {
                $("#ac_time_edit1").removeClass("hidden")
            }
        } else if ($("#ac_collapseFour").hasClass("in")) {
            $("#ac_meal_edit1").click()
        }
        $("#ac_party_size1").removeClass("hidden");
        $("#ac_location_edit").removeClass("hidden");
        $(this).addClass("hidden")
    });
	/*starts alacarte page*/
	$("#ac_party_edit2").click(function() {
        if ($("#ac_collapseTwo").hasClass("in")) {
            $("#ac_date_edit2").click();
            if ($("#ac_date_edit2 span").text() != "") {
                $("#ac_date_edit2").removeClass("hidden")
            }
        } else if ($("#ac_collapseThree").hasClass("in")) {
            $("#ac_time_edit2").click();
            if ($("#ac_time_edit2 span").text() != "") {
                $("#ac_time_edit2").removeClass("hidden")
            }
        } /*else if ($("#ac_collapseFour").hasClass("in")) {
            $("#ac_meal_edit1").click()
        }*/
        $("#ac_party_size2").removeClass("hidden");
        $("#ac_location_edit").removeClass("hidden");
        $(this).addClass("hidden")
    });
	/*end alacarte page*/
    $("#ac_date_edit1").click(function() {
        if ($("#ac_collapseThree").hasClass("in")) {
            $("#ac_time_edit1").click()
        } else if ($("#ac_collapseFour").hasClass("in")) {
            $("#ac_meal_edit1").click()
        }
        if ($("#ac_time_edit1 span").text() != "") {
            $("#ac_time_edit1").removeClass("hidden")
        }
        $("#ac_date_edit1").addClass("hidden");
        $("#ac_party_edit1").removeClass("hidden");
        $("#ac_party_size1").addClass("hidden")
    });
	/*starts alacarte*/
	$("#ac_date_edit2").click(function() {
        $("#ac_time_edit2").removeClass("hidden");
        $("#ac_select_table2_ala").removeClass("hidden");
        $("#ac_select_all_data2").addClass("hidden");
        $("#or_reservation").removeClass("hidden");

        if ($("#ac_collapseThree").hasClass("in")) {
            $("#ac_time_edit2").click()
        } /*else if ($("#ac_collapseFour").hasClass("in")) {
            $("#ac_meal_edit1").click()
        }*/
        if ($("#ac_time_edit2 span").text() != "") {
            $("#ac_time_edit2").removeClass("hidden")
        }
        //$("#ac_date_edit2").addClass("hidden"); //kailash
        $("#ac_party_edit2").removeClass("hidden");
        $("#ac_party_size2").addClass("hidden")
    });
	/*end alacarte*/
    $("#ac_time_edit1").click(function() {
        if ($("#ac_collapseTwo").hasClass("in")) {
            $("#ac_date_edit1").click()
        } else if ($("#ac_collapseFour").hasClass("in")) {
            $("#ac_meal_edit1").click()
        }
        $("#ac_party_size1").addClass("hidden");
        $("#ac_party_edit1").removeClass("hidden")
    });
	/* starts alacarte*/
	$("#ac_time_edit2").click(function() {
        $("#ac_time_edit2").removeClass("hidden");
        $("#ac_select_table2_ala").removeClass("hidden");
        $("#ac_select_all_data2").addClass("hidden");
        $("#or_reservation").removeClass("hidden");

        if ($("#ac_collapseTwo").hasClass("in")) {
            $("#ac_date_edit2").click()
        } /*else if ($("#ac_collapseFour").hasClass("in")) {
            $("#ac_meal_edit1").click()
        }*/
        $("#ac_party_size2").addClass("hidden");
        $("#ac_party_edit2").removeClass("hidden")
    });
	/*end alacarte*/
    $(document).on("click", ".ac_actives label", function() {
        label_id = $(this).attr("id");
        if (label_id == "ac_breakfast") {
            $(this).addClass("active");
            $("#ac_dinner,#ac_lunch").removeClass("active");
            $("#ac_breakfast_tab").removeClass("hidden");
            $("#ac_lunch_tab,#ac_dinner_tab").addClass("hidden")
        } else if (label_id == "ac_lunch") {
            $(this).addClass("active");
            $("#ac_dinner,#ac_breakfast").removeClass("active");
            $("#ac_lunch_tab").removeClass("hidden");
            $("#ac_breakfast_tab,#ac_dinner_tab").addClass("hidden")
        } else {
            $(this).addClass("active");
            $("#ac_lunch,#ac_breakfast").removeClass("active");
            $("#ac_dinner_tab").removeClass("hidden");
            $("#ac_lunch_tab,#ac_breakfast_tab").addClass("hidden")
        }
    });
    $(document).on("click", ".ac_time", function() {
        $("#ac_hours").find(".time_active").removeClass("time_active");
        $(this).addClass("time_active");
        $("#ac_time_edit span").text($(this).text());
        $("#ac_booking_time").val($(this).text());
        $("#ac_time_edit").removeClass("hidden");
        timehide = 1;
        $("#ac_time_edit").click();
        counter = $("#ac_party_edit span").text();
        str = "";
        for (var e = 0; e <= counter; e++) {
            str += "<option value='" + e + "'>" + e + "</option>"
        }
        $(".meals select").html(str);
        $("#ac_meal_edit").click();
        $("#ac_time_edit1 span").text($(this).text());
        $("#ac_time_edit1").removeClass("hidden");
        $("#ac_select_table").removeClass("hidden");
        $("#ac_select_all_data").addClass("hidden");
        $("#ac_or_reservation").removeClass("hidden");
        $("#ac_time_edit1").click()
    })
});
$(".var-jump-exp").click(function() {
    $("#startBox").css("display", "none");
    $("#ReservationBox").fadeIn()
});
$(".var-jump-carte").click(function() {
    $("#startBox").css("display", "none");
    $("#AlacarteBox").fadeIn()
})