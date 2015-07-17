(function($){


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

    function add_options(e, t, n) {
        str = "";
        for (var r = 0; r <= t; r++) {
            select = "";
            if (typeof n !== "undefined") {
                select = r == n ? 'selected="selected"' : ""
            }
            str += "<option value='" + r + "' " + select + ">" + r + "</option>"
        }
        $(e).html(str)
    }

    function get_info(url, id) {
        $.ajax({
            url: url,
            type: "POST",
            dataType: "json",
            data: {
                id: id
            },
            async: false,
            success: function(data) {
                $(".add_loader").hide();
                $("#save_changes").addClass("hidden");
                $("#myModalLabel").text("Add Reservation");
                $("#myModalsmallLabel").text(ucfirst(data.exp.city) + " / " + ucfirst(data.exp.venue) + " / " + ucfirst(data.exp.exp_title));
                $("#price").val(data.exp.price);
                $("#price_alcohol").val(data.exp.price_alcohol);
                $("#price_non_veg").val(data.exp.price_non_veg);
                $("#city").val(data.exp.city);
                var loc = "";
                if (Object.keys(data.row).length > 1) {
                    $.each(data.row, function(e, t) {
                        loc += "<option value='" + t.address + "'>" + t.keyword + "</option>"
                    });
                    $("#address").html(loc);
                    $("#location").removeClass("hidden");
                    $("#addr").val(data.row[1].address);
                    $("#address_keyword").val(data.row[1].keyword)
                } else {
                    $("#addr").val(data.row[1].address);
                    $("#address_keyword").val(data.row[1].keyword);
                    $("#location").addClass("hidden")
                }
                $("#party_size").removeClass("hidden");
                $("#party_change").addClass("hidden");
                $("#party_change span").text();
                var min_num = parseInt(data.exp.min_num_tickets);
                var max_num = parseInt(data.exp.max_num_orders - data.exp.tickets_sold < data.exp.max_num_tickets ? data.exp.max_num_orders - data.exp.tickets_sold : data.exp.max_num_tickets);
                var select_str = "<option>SELECT</option>";
                for (var i = min_num; i <= max_num; i++) {
                    var peop_name = i == 1 ? "Person" : "People";
                    select_str += "<option value='" + i + "'>" + i + " " + peop_name + "</option>"
                }
                $("#party_size").html(select_str);
                if ($("#collapseTwo").hasClass("in")) {
                    $("#select_date").click()
                }
                $("#select_date").addClass("hidden");
                $("#select_date span").text("");
                var nowTemp = new Date;
                var endtime = data.exp.end_date;
                var date_array = endtime.split("-");
                var bd_dates = new Array;
                if (data.block_dates.length > 0) {
                    $.each(data.block_dates, function(e, t) {
                        if (t["block_time"] == data.exp.start_time + "-" + data.exp.end_time) {
                            bd_dates.push(t["block_date"])
                        }
                    })
                }
                mytime = new Date(date_array[0], date_array[1] - 1, date_array[2]);
                var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
                var end = new Date(mytime.getFullYear(), mytime.getMonth(), mytime.getDate(), 0, 0, 0, 0);
                var now = new Date($("#now").val());
                cur_day = now.getDate();
                cur_month = now.getMonth() + 1;
                cur_year = now.getFullYear();
                cur_date = cur_year + "-" + cur_month + "-" + cur_day;
                cur_time = now.getHours();
                cur_minute = now.getMinutes();
                $("#party_date").datepicker("destroy");
                dtp = $("#party_date").datepicker({
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
                        var n = $.datepicker.parseDate("yy-m-dd", e);
                        $("#reserv_date").val(e);
                        var r = "";
                        var i = "";
                        $.each(data.block_dates, function(e, t) {
                            if ($.datepicker.formatDate("yy-mm-dd", n) == t.block_date) {
                                e = t.block_time.split("-");
                                r = e[0];
                                i = e[1]
                            }
                        });
                        var s = $("#select_table");
                        var o = $("#cant_select_table");
                        $("#select_date").click();
                        $("#select_time").click();
                        $("#select_date span").text(formatDate(e));
                        $("#select_date").removeClass("hidden");
                        var u = $.datepicker.parseDate("yy-m-dd", e);
                        var a = $.datepicker.formatDate("D", u).toLowerCase();
                        var f = '<div class="btn-group col-lg-10 pull-right actives ">';
                        var l = "";
                        var c = 1;
                        for (key_sch in data.shedule[a]) {
                            var h = Object.keys(data.shedule[a]).length;
                            active_tab = c == h ? "active" : "";
                            active_blck = c == h ? "" : "hidden";
                            f += '<label class="btn btn-warning btn-xs time_tab ' + active_tab + '" id="' + key_sch + '">' + key_sch.toUpperCase() + "</label>";
                            l += '<div id="' + key_sch + '_tab"  class="' + active_blck + '">';
                            for (key_sch_time in data.shedule[a][key_sch]) {
                                if (cur_date == e) {
                                    if (key_sch_time >= cur_time + ":" + cur_minute && (key_sch_time < r || key_sch_time > i)) {
                                        l += '<div class="time col-lg-3" rel="' + data.shedule[a][key_sch][key_sch_time] + '"><a href="javascript:">' + data.shedule[a][key_sch][key_sch_time] + "</a></div>"
                                    }
                                } else {
                                    if (key_sch_time < r || key_sch_time > i) {
                                        l += '<div class="time col-lg-3" rel="' + data.shedule[a][key_sch][key_sch_time] + '"><a href="javascript:">' + data.shedule[a][key_sch][key_sch_time] + "</a></div>"
                                    }
                                }
                            }
                            l += "</div>";
                            c++
                        }
                        if (data.shedule_time != undefined) {
                            var p = data.shedule_time;
                            var a = $.datepicker.formatDate("D", u).toLowerCase();
                            for (key in p[a]) {
                                var h = Object.keys(p[a]).length;
                                active_tab = c == h ? "active" : "";
                                active_blck = c == h ? "" : "hidden";
                                f += '<label class="btn btn-warning btn-xs time_tab ' + active_tab + '" id="' + key + '">' + key.toUpperCase() + "</label>";
                                l += '<div id="' + key + '_tab"  class="' + active_blck + '">';
                                for (key_sch_time in p[a][key]) {
                                    l += '<div class="time col-lg-3" rel="' + p[a][key][key_sch_time] + '"><a href="javascript:">' + p[a][key][key_sch_time] + "</a></div>";
                                    l += "</div>";
                                    c++
                                }
                            }
                        }
                        f += '</div><div class="clearfix"></div>';
                        $("#time").html(f);
                        $("#hours").html(l)
                    }
                });
                if ($("#collapseThree").hasClass("in")) {
                    $("#select_time").click()
                }
                $("#select_time").addClass("hidden");
                $("#select_time span").text("");
                var meal_options = data.exp.price_non_veg != "0.00" || data.exp.price_alcohol != "0.00";
                if (meal_options) {
                    $("#meal_options").removeClass("hidden");
                    if ($("#collapseFour").hasClass("in")) {
                        $("#select_meal").click()
                    }
                } else {
                    $("#meal_options").addClass("hidden")
                }
                var non_veg = data.exp.price_non_veg != "0.00";
                if (non_veg) {
                    $("#nonveg").removeClass("hidden")
                } else {
                    $("#nonveg").addClass("hidden")
                }
                var alcohol = data.exp.price_alcohol != "0.00";
                if (alcohol) {
                    $("#alcohol").removeClass("hidden")
                } else {
                    $("#alcohol").addClass("hidden")
                }
                if ($("#collapseFive").hasClass("in")) {
                    $("#additional_options").click()
                }
                $("#specialRequests").val("");
                $("#experienceTakers").val("");
                $("#giftID").val("");
                $("#giftAmt").val("");
                $("#avard_point").removeAttr("checked");
                $("#select_table").addClass("hidden")
            }
        })
    }

    function ac_get_info(url, id) {
		console.log("call");
        $.ajax({
            url: url,
            type: "POST",
            dataType: "json",
            data: {
                id: id
            },
            async: false,
            success: function(data) {
                $(".add_loader").hide();
                $("#save_changes").addClass("hidden");
                $("#ac_myModalLabel").text("Add Reservation");
				$("#alacarte_reward_points").val(data.exp[0].reward_points);
                $("#ac_myModalsmallLabel").text("A la carte reservation " + ucfirst(data.exp[0].venue));
				$("#ac_restaurant_id").val(data.exp[0].rest_id);
                var loc = "";
                /*if (Object.keys(data.row).length > 1) {
                    $.each(data.row, function(e, t) {
                        loc += "<option value='" + t.address + "'>" + t.outlet_name + "</option>"
                    });
                    $("#ac_address").html(loc);
                    $("#ac_location").removeClass("hidden");
                    $("#ac_addr").val(data.row[1].address);
                    $("#ac_address_keyword").val(data.row[1].outlet_name)
                } else {
                    if (data.row[1]) {
                        $("#ac_addr").val(data.row[1].address);
                        $("#ac_address_keyword").val(data.row[1].outlet_name);
                        $("#ac_location").addClass("hidden")
                    } else {
                        $("#ac_addr").val();
                        $("#ac_address_keyword").val();
                        $("#ac_location").addClass("hidden")
                    }
                }*/
                $("#ac_party_size").removeClass("hidden");
                $("#ac_party_change").addClass("hidden");
                $("#ac_party_change span").text();
                var min_num = 1;
                var max_num = 15;
                var select_str = "<option>SELECT</option>";
                for (var i = min_num; i <= max_num; i++) {
                    var peop_name = i == 1 ? "Person" : "People";
                    select_str += "<option value='" + i + "'>" + i + " " + peop_name + "</option>"
                }
                $("#ac_party_size").html(select_str);
                if ($("#ac_collapseTwo").hasClass("in")) {
                    $("#ac_select_date").click()
                }
                $("#ac_select_date").addClass("hidden");
                $("#ac_select_date span").text("");
                var nowTemp = new Date;
                //var endtime = data.exp.end_date;
                //var date_array = endtime.split("-");
                var bd_dates = new Array;
                if (data.block_dates.length > 0) {
                    $.each(data.block_dates, function(e, t) {
                        if (t["block_time"] == data.exp.start_time + "-" + data.exp.end_time) {
                            bd_dates.push(t["block_date"])
                        }
                    })
                }
                //mytime = new Date(date_array[0], date_array[1] - 1, date_array[2]);
                var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
                //var end = new Date(mytime.getFullYear(), mytime.getMonth(), mytime.getDate(), 0, 0, 0, 0);
                var now = new Date($("#ac_now").val());
                cur_day = now.getDate();
                cur_month = now.getMonth() + 1;
                cur_year = now.getFullYear();
                cur_date = cur_year + "-" + cur_month + "-" + cur_day;
                cur_time = now.getHours();
                cur_minute = now.getMinutes();
                $("#ac_party_date").datepicker("destroy");
                dtp = $("#ac_party_date").datepicker({
                    dateFormat: "yy-m-d",
                    minDate: eval(data.new_start_date),
                    maxDate: eval(data.new_end_date),
                    beforeShowDay: function(e) {
                        var t = 0;
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
                        var n = $.datepicker.parseDate("yy-m-dd", e);
                        $("#ac_reserv_date").val(e);
                        /*var r = "";
                        var i = "";*/
						var r = [];
                        var i = [];
                        $.each(data.block_dates, function(e, t) {
                           if ($.datepicker.formatDate("yy-mm-dd", n) == t.ala_block_date) {
                                e = t.ala_block_time.split("-");
                                /*r = e[0];
                                i = e[1];*/
								r.push(e[0]);
                                i.push(e[1]);
                            }
                        });
						//console.log("r == "+r+" , i == "+i);
                        var s = $("#ac_select_table");
                        var o = $("#ac_cant_select_table");
                        $("#ac_select_date").click();
                        $("#ac_select_time").click();
                        $("#ac_select_date span").text(formatDate(e));
                        $("#ac_select_date").removeClass("hidden");
                        var u = $.datepicker.parseDate("yy-m-dd", e);
                        var a = $.datepicker.formatDate("D", u).toLowerCase();
                        var f = '<div class="btn-group col-lg-10 pull-right ac_actives ">';
                        var l = "";
                        var c = 1;
                        for (key_sch in data.shedule[a]) {
                            var h = Object.keys(data.shedule[a]).length;
                            active_tab = c == h ? "active" : "";
                            active_blck = c == h ? "" : "hidden";
                            f += '<label class="btn btn-warning btn-xs time_tab ' + active_tab + '" id="ac_' + key_sch + '">' + key_sch.toUpperCase() + "</label>";
                            l += '<div id="ac_' + key_sch + '_tab"  class="' + active_blck + '">';
                            for (key_sch_time in data.shedule[a][key_sch]) {
                                /*if (cur_date == e) {
                                    if (key_sch_time >= cur_time + ":" + cur_minute && (key_sch_time < r || key_sch_time > i)) {
                                        l += '<div class="ac_time col-lg-3" rel="' + data.shedule[a][key_sch][key_sch_time] + '"><a href="javascript:">' + data.shedule[a][key_sch][key_sch_time] + "</a></div>"
                                    }
                                } else {
                                    if (key_sch_time < r || key_sch_time > i) {
                                        l += '<div class="ac_time col-lg-3" rel="' + data.shedule[a][key_sch][key_sch_time] + '"><a href="javascript:">' + data.shedule[a][key_sch][key_sch_time] + "</a></div>"
                                    }
                                }*/

								if (cur_date == e) {
                                    /*if (key_sch_time >= cur_time + ":" + cur_minute && (key_sch_time < r || key_sch_time > i)) {
                                        l += '<div class="ac_time col-lg-3" rel="' + data.shedule[a][key_sch][key_sch_time] + '"><a href="javascript:">' + data.shedule[a][key_sch][key_sch_time] + "</a></div>"
                                    }*/
									var is_valid = true;
									for(var j=0; j<r.length; j++){ //key_sch_time>=c_time && 
										/*if((String(key_sch_time) < String(bl_time_start[i]) || String(key_sch_time) > String(bl_time_end[i]))){
											txt2+= '<div class="time col-lg-3" rel="' + schedule[datestrInNewFormat][key_sch][key_sch_time] + '"><a href="javascript:">' + schedule[datestrInNewFormat][key_sch][key_sch_time] + '</a></div>';
										}*/
										if (String(key_sch_time) < String(r[j]) || String(key_sch_time) > String(i[j])) {
											is_valid = is_valid && true;

										} else {
											is_valid = is_valid && false;

										}

									}

									if(is_valid) {
										l += '<div class="ac_time col-lg-3" rel="' + data.shedule[a][key_sch][key_sch_time] + '"><a href="javascript:">' + data.shedule[a][key_sch][key_sch_time] + "</a></div>"
									}
                                } else {
                                    /*if (key_sch_time < r || key_sch_time > i) {
                                        l += '<div class="ac_time col-lg-3" rel="' + data.shedule[a][key_sch][key_sch_time] + '"><a href="javascript:">' + data.shedule[a][key_sch][key_sch_time] + "</a></div>"
                                    }*/
									var is_valid = true;
									for(var j=0; j<r.length; j++){ //key_sch_time>=c_time && 
										/*if((String(key_sch_time) < String(bl_time_start[i]) || String(key_sch_time) > String(bl_time_end[i]))){
											txt2+= '<div class="time col-lg-3" rel="' + schedule[datestrInNewFormat][key_sch][key_sch_time] + '"><a href="javascript:">' + schedule[datestrInNewFormat][key_sch][key_sch_time] + '</a></div>';
										}*/
										if (String(key_sch_time) < String(r[j]) || String(key_sch_time) > String(i[j])) {
											is_valid = is_valid && true;

										} else {
											is_valid = is_valid && false;

										}

									}

									if(is_valid) {
										l += '<div class="ac_time col-lg-3" rel="' + data.shedule[a][key_sch][key_sch_time] + '"><a href="javascript:">' + data.shedule[a][key_sch][key_sch_time] + "</a></div>"
									}
                                }
                            }
                            l += "</div>";
                            c++
                        }
                        if (data.shedule_time != undefined) {
                            var p = data.shedule_time;
                            var a = $.datepicker.formatDate("D", u).toLowerCase();
                            for (key in p[a]) {
                                var h = Object.keys(p[a]).length;
                                active_tab = c == h ? "active" : "";
                                active_blck = c == h ? "" : "hidden";
                                f += '<label class="btn btn-warning btn-xs time_tab ' + active_tab + '" id="ac_' + key + '">' + key.toUpperCase() + "</label>";
                                l += '<div id="ac_' + key + '_tab"  class="' + active_blck + '">';
                                for (key_sch_time in p[a][key]) {
                                    l += '<div class="ac_time col-lg-3" rel="' + p[a][key][key_sch_time] + '"><a href="javascript:">' + p[a][key][key_sch_time] + "</a></div>";
                                    l += "</div>";
                                    c++
                                }
                            }
                        }
                        f += '</div><div class="clearfix"></div>';
                        $("#ac_time").html(f);
                        $("#ac_hours").html(l)
                    }
                });
                if ($("#ac_collapseThree").hasClass("in")) {
                    $("#ac_select_time").click()
                }
                $("#ac_select_time").addClass("hidden");
                $("#ac_select_time span").text("");
                $("#ac_meal_options").addClass("hidden");
                $("#ac_nonveg").addClass("hidden");
                $("#ac_alcohol").addClass("hidden");
                if ($("#ac_collapseFive").hasClass("in")) {
                    $("#ac_additional_options").click()
                }
                $("#ac_specialRequests").val("");
                $("#ac_experienceTakers").val("");
                $("#ac_giftID").val("");
                $("#ac_giftAmt").val("");
                $("#ac_avard_point").removeAttr("checked");
                $("#ac_select_table").addClass("hidden")
            }
        })
    }

    function get_reserv_by_id(reserv_id) {
        $.ajax({
            url: "/adminreservations/get_reservetion",
            dataType: "json",
            type: "POST",
            data: {
                reserv_id: reserv_id
            },
            success: function(data) {
                var reserv = data.reservs[1];
                $(".add_loader").hide();
                $("#save_changes").removeClass("hidden");
                $("#myModalLabel").text("Change Reservation");
                $("#myModalsmallLabel").text(ucfirst(data.exp.city) + " / " + ucfirst(data.exp.venue) + " / " + ucfirst(data.exp.exp_title));
                $("#price").val(data.exp.price);
                $("#price_alcohol").val(data.exp.price_alcohol);
                $("#price_non_veg").val(data.exp.price_non_veg);
                $("#city").val(data.exp.city);
                $("#res_id").val(reserv_id);
                var loc = "";
                $("#last_reserv_outlet").val(reserv.outlet);
                if (Object.keys(data.row).length > 1) {
                    $.each(data.row, function(e, t) {
                        loc += "<option value='" + t.address + "'>" + t.keyword + "</option>"
                    });
                    $("#address").html(loc);
                    $("#location").removeClass("hidden");
                    $("#addr").val(data.row[1].address);
                    $("#address_keyword").val(data.row[1].keyword)
                } else {
                    $("#addr").val(data.row[1].address);
                    $("#address_keyword").val(data.row[1].keyword);
                    $("#location").addClass("hidden")
                }
                $("#last_reserv_party_size").val(reserv.no_of_tickets);
                $("#party_size").removeClass("hidden");
                $("#party_change").addClass("hidden");
                $("#party_change span").text();
                var min_num = parseInt(data.exp.min_num_tickets);
                var max_num = parseInt(data.exp.max_num_orders - data.exp.tickets_sold < data.exp.max_num_tickets ? data.exp.max_num_orders - data.exp.tickets_sold : data.exp.max_num_tickets);
                $("#party_size").addClass("hidden");
                var select_str = "<option>SELECT</option>";
                for (var i = min_num; i <= max_num; i++) {
                    var peop_name = i == 1 ? "Person" : "People";
                    var selected = i == reserv.no_of_tickets ? "selected='selected'" : "";
                    select_str += "<option value='" + i + "' " + selected + ">" + i + " " + peop_name + "</option>"
                }
                $("#party_size").html(select_str);
                $("#party_change span").text(reserv.no_of_tickets);
                $("#party_change").removeClass("hidden");
                if ($("#collapseTwo").hasClass("in")) {
                    $("#select_date").click()
                }
                var reserv_d = data.new_booking_date.split("/");
                reserv_date = reserv_d[2] + "-" + reserv_d[0] + "-" + reserv_d[1];
                $("#reserv_date").val(reserv_date);
                $("#last_reserv_date").val(reserv_date);
                $("#select_date").removeClass("hidden");
                $("#select_date span").text(formatDate(reserv_date));
                var nowTemp = new Date;
                var endtime = data.exp.end_date;
                var date_array = endtime.split("-");
                var bd_dates = new Array;
                if (data.block_dates.length > 0) {
                    $.each(data.block_dates, function(e, t) {
                        if (t["block_time"] == data.exp.start_time + "-" + data.exp.end_time) {
                            bd_dates.push(t["block_date"])
                        }
                    })
                }
                mytime = new Date(date_array[0], date_array[1] - 1, date_array[2]);
                var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
                var end = new Date(mytime.getFullYear(), mytime.getMonth(), mytime.getDate(), 0, 0, 0, 0);
                var now = new Date($("#now").val());
                cur_day = now.getDate();
                cur_month = now.getMonth() + 1;
                cur_year = now.getFullYear();
                cur_date = cur_year + "-" + cur_month + "-" + cur_day;
                cur_time = now.getHours();
                cur_minute = now.getMinutes();
                $("#party_date").datepicker("destroy");
                dtp = $("#party_date").datepicker({
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
                        var n = $.datepicker.parseDate("yy-m-dd", e);
                        $("#reserv_date").val(e);
                        var r = "";
                        var i = "";
                        $.each(data.block_dates, function(e, t) {
                            if ($.datepicker.formatDate("yy-mm-dd", n) == t.block_date) {
                                e = t.block_time.split("-");
                                r = e[0];
                                i = e[1]
                            }
                        });
                        var s = $("#select_table");
                        var o = $("#cant_select_table");
                        $("#select_date").click();
                        $("#select_time").click();
                        $("#select_date span").text(formatDate(e));
                        $("#select_date").removeClass("hidden");
                        var u = $.datepicker.parseDate("yy-m-dd", e);
                        var a = $.datepicker.formatDate("D", u).toLowerCase();
                        var f = '<div class="btn-group col-lg-10 pull-right actives ">';
                        var l = "";
                        var c = 1;
                        for (key_sch in data.shedule[a]) {
                            var h = Object.keys(data.shedule[a]).length;
                            active_tab = c == h ? "active" : "";
                            active_blck = c == h ? "" : "hidden";
                            f += '<label class="btn btn-warning btn-xs time_tab ' + active_tab + '" id="' + key_sch + '">' + key_sch.toUpperCase() + "</label>";
                            l += '<div id="' + key_sch + '_tab"  class="' + active_blck + '">';
                            for (key_sch_time in data.shedule[a][key_sch]) {
                                active_button = data.shedule[a][key_sch][key_sch_time] == data.new_booking_time ? "time_active" : "";
                                if (cur_date == e) {
                                    if (key_sch_time >= cur_time + ":" + cur_minute && (key_sch_time < r || key_sch_time > i)) {
                                        l += '<div class="time col-lg-3 ' + active_button + '" rel="' + data.shedule[a][key_sch][key_sch_time] + '"><a href="javascript:">' + data.shedule[a][key_sch][key_sch_time] + "</a></div>"
                                    }
                                } else {
                                    if (key_sch_time < r || key_sch_time > i) {
                                        l += '<div class="time col-lg-3 ' + active_button + '" rel="' + data.shedule[a][key_sch][key_sch_time] + '"><a href="javascript:">' + data.shedule[a][key_sch][key_sch_time] + "</a></div>"
                                    }
                                }
                            }
                            l += "</div>";
                            c++
                        }
                        if (data.shedule_time != undefined) {
                            var p = data.shedule_time;
                            var a = $.datepicker.formatDate("D", u).toLowerCase();
                            for (key in p[a]) {
                                var h = Object.keys(p[a]).length;
                                active_tab = c == h ? "active" : "";
                                active_blck = c == h ? "" : "hidden";
                                f += '<label class="btn btn-warning btn-xs time_tab ' + active_tab + '" id="' + key + '">' + key.toUpperCase() + "</label>";
                                l += '<div id="' + key + '_tab"  class="' + active_blck + '">';
                                for (key_sch_time in p[a][key]) {
                                    l += '<div class="time col-lg-3" rel="' + p[a][key][key_sch_time] + '"><a href="javascript:">' + p[a][key][key_sch_time] + "</a></div>";
                                    l += "</div>";
                                    c++
                                }
                            }
                        }
                        f += '</div><div class="clearfix"></div>';
                        $("#time").html(f);
                        $("#hours").html(l)
                    }
                });
                $("#last_reserv_time").val(data.new_booking_time);
                var week = ["sun", "mon", "tue", "wed", "thu", "fri", "sat"][(new Date(reserv_d[2], reserv_d[0] - 1, reserv_d[1])).getDay()];
                weektimes = data["shedule"][week];
                timeskey = Object.keys(weektimes);
                var str = '<div class="btn-group col-lg-10 pull-right actives ">';
                var string = "";
                var g = 1;
                for (var key in weektimes) {
                    var obj_length = Object.keys(weektimes).length;
                    active_tab = g == obj_length ? "active" : "";
                    active_blck = g == obj_length ? "" : "hidden";
                    str += '<label class="btn btn-warning btn-xs time_tab ' + active_tab + '" id="' + key + '">' + key.toUpperCase() + "</label>";
                    string += '<div id="' + key + '_tab"  class="' + active_blck + '">';
                    $("#" + key).removeClass("hidden");
                    for (var time in weektimes[key]) {
                        if (weektimes[key][time] == data.new_booking_time) {
                            string += '<div class="time col-lg-3 time_active" rel="' + time + '"><a href="javascript:">' + weektimes[key][time] + "</a></div>"
                        } else string += '<div class="time col-lg-3" rel="' + time + '"><a href="javascript:">' + weektimes[key][time] + "</a></div>"
                    }
                    string += "</div>";
                    g++
                }
                str += '</div><div class="clearfix"></div>';
                $("#hours").html(string);
                $("#time").html(str);
                if ($("#collapseThree").hasClass("in")) {
                    $("#select_time").click()
                }
                $("#select_time").removeClass("hidden");
                $("#select_time span").text(data.new_booking_time);
                var meal_options = data.exp.price_non_veg != "0.00" || data.exp.price_alcohol != "0.00";
                if (meal_options) {
                    $("#meal_options").removeClass("hidden");
                    if (!$("#collapseFour").hasClass("in")) {
                        $("#select_meal").click()
                    }
                } else {
                    $("#meal_options").addClass("hidden")
                }
                var non_veg = data.exp.price_non_veg != "0.00";
                add_options(".nonveg", reserv.no_of_tickets, reserv.non_veg);
                if (non_veg) {
                    $("#nonveg").removeClass("hidden")
                } else {
                    $("#nonveg").addClass("hidden")
                }
                var alcohol = data.exp.price_alcohol != "0.00";
                add_options(".alcohol", reserv.no_of_tickets, reserv.alcohol);
                if (alcohol) {
                    $("#alcohol").removeClass("hidden")
                } else {
                    $("#alcohol").addClass("hidden")
                }
                if (!$("#collapseFive").hasClass("in")) {
                    $("#additional_options").click()
                }
                $("#specialRequests").val("");
                $("#experienceTakers").val("");
                $("#giftID").val("");
                $("#giftAmt").val("");
                $("#avard_point").removeAttr("checked");
                $("#select_table").addClass("hidden")
            }
        })
    }

    function ac_get_reserv_by_id(reserv_id) {
        $.ajax({
            url: "/adminreservations/ac_get_reservetion",
            dataType: "json",
            type: "POST",
            data: {
                reserv_id: reserv_id
            },
            success: function(data) {
                var reserv = data.reservs[1];
                $(".add_loader").hide();
                $("#ac_save_changes").removeClass("hidden");
                $("#ac_myModalLabel").text("Change A la carte Reservation");
                $("#ac_myModalsmallLabel").text(ucfirst(data.exp.name));
                $("#ac_res_id").val(reserv_id);
                var loc = "";
                $("#ac_last_reserv_outlet").val(reserv.outlet);
                /*if (Object.keys(data.row).length > 1) {
                    $.each(data.row, function(e, t) {
                        loc += "<option value='" + t.address + "'>" + t.outlet_name + "</option>"
                    });
                    $("#ac_address").html(loc);
                    $("#ac_location").removeClass("hidden");
                    $("#ac_addr").val(data.row[1].address);
                    $("#ac_address_keyword").val(data.row[1].outlet_name)
                } else {*/
                    if (data.row[1]) {
                        $("#ac_addr").val(data.row[1].address);
                        $("#ac_address_keyword").val(data.row[1].outlet_name);
                        $("#ac_location").addClass("hidden")
                    } else {
                        $("#ac_addr").val();
                        $("#ac_address_keyword").val();
                        $("#ac_location").addClass("hidden")
                    }
                //}
                $("#ac_last_reserv_party_size").val(reserv.no_of_tickets);
                $("#ac_party_size").removeClass("hidden");
                $("#ac_party_change").addClass("hidden");
                $("#ac_party_change span").text();
                var min_num = 1;
                var max_num = 15;
                $("#ac_party_size").addClass("hidden");
                var select_str = "<option>SELECT</option>";
                for (var i = min_num; i <= max_num; i++) {
                    var peop_name = i == 1 ? "Person" : "People";
                    var selected = i == reserv.no_of_tickets ? "selected='selected'" : "";
                    select_str += "<option value='" + i + "' " + selected + ">" + i + " " + peop_name + "</option>"
                }
                $("#ac_party_size").html(select_str);
                $("#ac_party_change span").text(reserv.no_of_tickets);
                $("#ac_party_change").removeClass("hidden");
                if ($("#ac_collapseTwo").hasClass("in")) {
                    $("#ac_select_date").click()
                }
                var reserv_d = data.new_booking_date.split("/");
                reserv_date = reserv_d[2] + "-" + reserv_d[0] + "-" + reserv_d[1];
                $("#ac_reserv_date").val(reserv_date);
                $("#ac_last_reserv_date").val(reserv_date);
                $("#ac_select_date").removeClass("hidden");
                $("#ac_select_date span").text(formatDate(reserv_date));
                var nowTemp = new Date;
                //var endtime = data.exp.end_date;
                //var date_array = endtime.split("-");
                var bd_dates = new Array;
                if (data.block_dates.length > 0) {
                    $.each(data.block_dates, function(e, t) {
                        if (t["block_time"] == data.exp.start_time + "-" + data.exp.end_time) {
                            bd_dates.push(t["block_date"])
                        }
                    })
                }
                //mytime = new Date(date_array[0], date_array[1] - 1, date_array[2]);
                var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
                //var end = new Date(mytime.getFullYear(), mytime.getMonth(), mytime.getDate(), 0, 0, 0, 0);
                var now = new Date($("#now").val());
                cur_day = now.getDate();
                cur_month = now.getMonth() + 1;
                cur_year = now.getFullYear();
                cur_date = cur_year + "-" + cur_month + "-" + cur_day;
                cur_time = now.getHours();
                cur_minute = now.getMinutes();
                $("#ac_party_date").datepicker("destroy");
                dtp = $("#ac_party_date").datepicker({
                    dateFormat: "yy-m-d",
                    minDate: eval(data.new_start_date),
                    //maxDate: eval(data.new_end_date),
                    beforeShowDay: function(e) {
                        var t = 0;
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
                        var n = $.datepicker.parseDate("yy-m-dd", e);
                        $("#ac_reserv_date").val(e);
                        var r = "";
                        var i = "";
                        $.each(data.block_dates, function(e, t) {
                            if ($.datepicker.formatDate("yy-mm-dd", n) == t.block_date) {
                                e = t.block_time.split("-");
                                r = e[0];
                                i = e[1]
                            }
                        });
                        var s = $("#ac_select_table");
                        var o = $("#ac_cant_select_table");
                        $("#ac_select_date").click();
                        $("#ac_select_time").click();
                        $("#ac_select_date span").text(formatDate(e));
                        $("#ac_select_date").removeClass("hidden");
                        var u = $.datepicker.parseDate("yy-m-dd", e);
                        var a = $.datepicker.formatDate("D", u).toLowerCase();
                        var f = '<div class="btn-group col-lg-10 pull-right ac_actives ">';
                        var l = "";
                        var c = 1;
                        for (key_sch in data.shedule[a]) {
                            var h = Object.keys(data.shedule[a]).length;
                            active_tab = c == h ? "active" : "";
                            active_blck = c == h ? "" : "hidden";
                            f += '<label class="btn btn-warning btn-xs time_tab ' + active_tab + '" id="ac_' + key_sch + '">' + key_sch.toUpperCase() + "</label>";
                            l += '<div id="ac_' + key_sch + '_tab"  class="' + active_blck + '">';
                            for (key_sch_time in data.shedule[a][key_sch]) {
                                active_button = data.shedule[a][key_sch][key_sch_time] == data.new_booking_time ? "time_active" : "";
                                if (cur_date == e) {
                                    if (key_sch_time >= cur_time + ":" + cur_minute && (key_sch_time < r || key_sch_time > i)) {
                                        l += '<div class="ac_time col-lg-3 ' + active_button + '" rel="' + data.shedule[a][key_sch][key_sch_time] + '"><a href="javascript:">' + data.shedule[a][key_sch][key_sch_time] + "</a></div>"
                                    }
                                } else {
                                    if (key_sch_time < r || key_sch_time > i) {
                                        l += '<div class="ac_time col-lg-3 ' + active_button + '" rel="' + data.shedule[a][key_sch][key_sch_time] + '"><a href="javascript:">' + data.shedule[a][key_sch][key_sch_time] + "</a></div>"
                                    }
                                }
                            }
                            l += "</div>";
                            c++
                        }
                        if (data.shedule_time != undefined) {
                            var p = data.shedule_time;
                            var a = $.datepicker.formatDate("D", u).toLowerCase();
                            for (key in p[a]) {
                                var h = Object.keys(p[a]).length;
                                active_tab = c == h ? "active" : "";
                                active_blck = c == h ? "" : "hidden";
                                f += '<label class="btn btn-warning btn-xs time_tab ' + active_tab + '" id="ac_' + key + '">' + key.toUpperCase() + "</label>";
                                l += '<div id="ac_' + key + '_tab"  class="' + active_blck + '">';
                                for (key_sch_time in p[a][key]) {
                                    l += '<div class="ac_time col-lg-3" rel="' + p[a][key][key_sch_time] + '"><a href="javascript:">' + p[a][key][key_sch_time] + "</a></div>";
                                    l += "</div>";
                                    c++
                                }
                            }
                        }
                        f += '</div><div class="clearfix"></div>';
                        $("#ac_time").html(f);
                        $("#ac_hours").html(l)
                    }
                });
                $("#ac_last_reserv_time").val(data.new_booking_time);
                var week = ["sun", "mon", "tue", "wed", "thu", "fri", "sat"][(new Date(reserv_d[2], reserv_d[0] - 1, reserv_d[1])).getDay()];
                weektimes = data["shedule"][week];
                timeskey = Object.keys(weektimes);
                var str = '<div class="btn-group col-lg-10 pull-right ac_actives ">';
                var string = "";
                var g = 1;
                for (var key in weektimes) {
                    var obj_length = Object.keys(weektimes).length;
                    active_tab = g == obj_length ? "active" : "";
                    active_blck = g == obj_length ? "" : "hidden";
                    str += '<label class="btn btn-warning btn-xs time_tab ' + active_tab + '" id="ac_' + key + '">' + key.toUpperCase() + "</label>";
                    string += '<div id="ac_' + key + '_tab"  class="' + active_blck + '">';
                    $("#" + key).removeClass("hidden");
                    for (var time in weektimes[key]) {
                        if (weektimes[key][time] == data.new_booking_time) {
                            string += '<div class="ac_time col-lg-3 time_active" rel="' + time + '"><a href="javascript:">' + weektimes[key][time] + "</a></div>"
                        } else string += '<div class="ac_time col-lg-3" rel="' + time + '"><a href="javascript:">' + weektimes[key][time] + "</a></div>"
                    }
                    string += "</div>";
                    g++
                }
                str += '</div><div class="clearfix"></div>';
                $("#ac_hours").html(string);
                $("#ac_time").html(str);
                if ($("#ac_collapseThree").hasClass("in")) {
                    $("#ac_select_time").click()
                }
                $("#ac_select_time").removeClass("hidden");
                $("#ac_select_time span").text(data.new_booking_time);
                var meal_options = "";
                if (meal_options) {
                    $("#meal_options").removeClass("hidden");
                    if (!$("#collapseFour").hasClass("in")) {
                        $("#select_meal").click()
                    }
                } else {
                    $("#meal_options").addClass("hidden")
                }
                var non_veg = "";
                add_options(".nonveg", reserv.no_of_tickets, reserv.non_veg);
                if (non_veg) {
                    $("#nonveg").removeClass("hidden")
                } else {
                    $("#nonveg").addClass("hidden")
                }
                var alcohol = "";
                add_options(".alcohol", reserv.no_of_tickets, reserv.alcohol);
                if (alcohol) {
                    $("#alcohol").removeClass("hidden")
                } else {
                    $("#alcohol").addClass("hidden")
                }
                if (!$("#ac_collapseFive").hasClass("in")) {
                    $("#ac_additional_options").click()
                }
                $("#ac_specialRequests").val("");
                $("#ac_experienceTakers").val("");
                $("#ac_giftID").val("");
                $("#ac_giftAmt").val("");
                $("#ac_avard_point").removeAttr("checked");
                $("#ac_select_table").addClass("hidden")
            }
        })
    }

    function get_reserv_info(user_id) {
        var map_id = 0;
        var pr_map_id = 0;
        $.ajax({
            url: "/admin/adminreservations/mod_reservs",
            type: "POST",
            dataType: "json",
            async: true,
            data: {
                user_id: user_id
            },
            success: function(data) {
                $("#upcomings_reservs").html("");
                $("#pref_reservs").html("");
                $(".glyphicon-plus-sign").show();
                $(".prev_res").show();
                $(".ajax_loader").hide();
                if (data.upcomings.length != 0) {
                    $("#upcomings_reservs").html(data.upcomings);
                    /*for (var i in data.up_com_gm) {
                        var str = "var dealer_lat" + map_id + " = data.up_com_gm[" + i + "]['lat'];var dealer_lng" + map_id + " = data.up_com_gm[" + i + "]['long'];var mylatlng" + map_id + "=  new google.maps.LatLng(dealer_lat" + map_id + ", dealer_lng" + map_id + ");var mapOptions" + map_id + " = {center: mylatlng" + map_id + ",zoom: 16};var map" + map_id + " = new google.maps.Map(document.getElementById('map" + map_id + "'),mapOptions" + map_id + ");var marker" + map_id + " = new google.maps.Marker({position: mylatlng" + map_id + ",map: map" + map_id + "});";
                        eval(str);
                        map_id++
                    }*/
                }
                if (data.count_previous > 0) {
                    $("#last_reserv").show()
                }
                if (data.previous.length != 0) {
                    $("#pref_reservs").html(data.previous);
                    /*for (var i in data.prev_gm) {
                        var str = "var pr_dealer_lat" + pr_map_id + " = data.prev_gm[" + i + "]['lat'];var pr_dealer_lng" + pr_map_id + " = data.prev_gm[" + i + "]['long'];var pr_mylatlng" + pr_map_id + "=  new google.maps.LatLng(pr_dealer_lat" + pr_map_id + ", pr_dealer_lng" + pr_map_id + ");var pr_mapOptions" + pr_map_id + " = {center: pr_mylatlng" + pr_map_id + ",zoom: 16};var pr_map" + pr_map_id + " = new google.maps.Map(document.getElementById('pr_map" + pr_map_id + "'),pr_mapOptions" + pr_map_id + ");var pr_marker" + pr_map_id + " = new google.maps.Marker({position: pr_mylatlng" + pr_map_id + ",map: pr_map" + pr_map_id + "});";
                        eval(str);
                        pr_map_id++
                    }*/
                }
            }
        })
    }
    var delay = function() {
        var e = 0;
        return function(t, n) {
            clearTimeout(e);
            e = setTimeout(t, n)
        }
    }();
    $(window).keydown(function(e) {
        if (e.keyCode == 13) {
            e.preventDefault();
            return false
        }
    });
    $("#party_size").change(function() {
        var e = $("#party_size option:selected").val();
        $("#party_change span").text(e);
        $("#party_change").removeClass("hidden");
        $("#party_size").addClass("hidden");
        if (!$("#nonveg").hasClass("hidden")) {
            add_options(".nonveg", e)
        }
        if (!$("#alcohol").hasClass("hidden")) {
            add_options(".alcohol", e)
        }
        if ($("#select_date span").text() == "") {
            $("#select_date").click()
        } else if ($("#select_time span").text() == "") {
            $("#select_time").click()
        } else if (!$("#collapseFour").hasClass("in")) {
            $("#select_meal").click();
            $("#additional_options").click()
        }
    });
    $("#ac_party_size").change(function() {
        var e = $("#ac_party_size option:selected").val();
        $("#ac_party_change span").text(e);
        $("#ac_party_change").removeClass("hidden");
        $("#ac_party_size").addClass("hidden");
        if (!$("#ac_nonveg").hasClass("hidden")) {
            add_options(".nonveg", e)
        }
        if (!$("#ac_alcohol").hasClass("hidden")) {
            add_options(".alcohol", e)
        }
        if ($("#ac_select_date span").text() == "") {
            $("#ac_select_date").click()
        } else if ($("#ac_select_time span").text() == "") {
            $("#ac_select_time").click()
        } else if (!$("#ac_collapseFour").hasClass("in")) {
            $("#ac_select_meal").click();
            $("#ac_additional_options").click()
        }
    });
    $("#party_change").click(function() {
        if ($("#collapseTwo").hasClass("in")) {
            $("#select_date").click();
            if ($("#select_date span").text() != "") {
                $("#select_date").removeClass("hidden")
            }
        } else if ($("#collapseThree").hasClass("in")) {
            $("#select_time").click();
            if ($("#select_time span").text() != "") {
                $("#select_time").removeClass("hidden")
            }
        } else if ($("#collapseFour").hasClass("in")) {
            $("#select_meal").click();
            $("#additional_options").click()
        } else if ($("#collapseFive").hasClass("in")) {
            $("#additional_options").click()
        }
        $("#party_size").removeClass("hidden");
        $("#location_edit").removeClass("hidden");
        $(this).addClass("hidden")
    });
    $("#ac_party_change").click(function() {
        if ($("#ac_collapseTwo").hasClass("in")) {
            $("#ac_select_date").click();
            if ($("#ac_select_date span").text() != "") {
                $("#ac_select_date").removeClass("hidden")
            }
        } else if ($("#ac_collapseThree").hasClass("in")) {
            $("#ac_select_time").click();
            if ($("#ac_select_time span").text() != "") {
                $("#ac_select_time").removeClass("hidden")
            }
        } else if ($("#ac_collapseFour").hasClass("in")) {
            $("#ac_select_meal").click();
            $("#ac_additional_options").click()
        } else if ($("#ac_collapseFive").hasClass("in")) {
            $("#ac_additional_options").click()
        }
        $("#ac_party_size").removeClass("hidden");
        $("#ac_location_edit").removeClass("hidden");
        $(this).addClass("hidden")
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
    $(document).on("click", ".time", function() {
        $("#hours").find(".time_active").removeClass("time_active");
        $(this).addClass("time_active");
        $("#select_time span").text($(this).text());
        $("#select_time").removeClass("hidden");
        timehide = 1;
        $("#select_time").click();
        $("#additional_options").click();
        if (!$("#modif_reserv").parent().hasClass("active")) {
            $("#select_table").removeClass("hidden")
        }
        counter = $("#party_edit span").text();
        str = "";
        for (var e = 0; e <= counter; e++) {
            str += "<option value='" + e + "'>" + e + "</option>"
        }
        if (!$("#meal_options").hasClass("hidden")) {
            $(".meals select").html(str);
            $("#select_meal").click()
        }
    });
    $(document).on("click", ".ac_time", function() {
        $("#ac_hours").find(".time_active").removeClass("time_active");
        $(this).addClass("time_active");
        $("#ac_select_time span").text($(this).text());
        $("#ac_select_time").removeClass("hidden");
        timehide = 1;
        $("#ac_select_time").click();
        $("#ac_additional_options").click();
        if (!$("#modif_reserv").parent().hasClass("active")) {
            $("#ac_select_table").removeClass("hidden")
        }
        counter = $("#ac_party_edit span").text();
        str = "";
        for (var e = 0; e <= counter; e++) {
            str += "<option value='" + e + "'>" + e + "</option>"
        }
        if (!$("#ac_meal_options").hasClass("hidden")) {
            $(".meals select").html(str);
            $("#ac_select_meal").click()
        }
    });
    $("#select_date").click(function() {
        $(this).addClass("hidden")
    });
    $("#ac_select_date").click(function() {
        $(this).addClass("hidden")
    });
    $("#select_time").click(function() {
        $("#select_date").removeClass("hidden")
    });
    $("#address").change(function() {
        var e = $("#address option:selected").text();
        $("#address_keyword").val(e);
        var t = $("#address option:selected").val();
        $("#addr").val(t)
    });
    $("#select_table").click(function() {
        var e = $("#user_id").val();
        var t = $("#exp_id").val();
        var n = $("#customerEmail").val();
        var r = $("#customerNumber").val();
        var i = $("#customerName").val();
        var s = $("#city").val();
        var o = $("#specialRequests").val() + " ";
        if ($("#experienceTakers").val() != "") {
            o += " Experience takers: " + $("#experienceTakers").val() + " "
        }
        if ($("#giftID").val() != "") {
            o += " Gift Card ID: " + $("#giftID").val() + " "
        }
        if ($("#giftAmt").val() != "") {
            o += " Gift Card Amount: " + $("#giftAmt").val() + " "
        }
        var u = $("#select_time span").text();
        var a = $("#reserv_date").val();
        var f = a.split("-");
        var l = f[1] + "/" + f[2] + "/" + f[0] + " " + u;
        var c = parseInt($("#party_size").val());
        var h = parseFloat($("#price").val());
        var p = parseFloat($("#price_non_veg").val());
        var d = parseFloat($("#price_alcohol").val());
        if (!$("#nonveg").hasClass("hidden")) {
            non_veg_qty = parseInt($(".nonveg").val())
        } else {
            non_veg_qty = 0
        }
        if (!$("#alcohol").hasClass("hidden")) {
            alcohol_qty = parseInt($(".alcohol").val())
        } else {
            alcohol_qty = 0
        }
        var v = c * h + non_veg_qty * p + alcohol_qty * d;
        var m = $("#addr").val();
        var g = $("#address_keyword").val();
        var y = 0;
        if ($("#avard_point").is(":checked")) {
            y = 1
        }
        $(".add_loader").show();
        $.ajax({
            url: "/adminreservations/checkout",
            type: "POST",
            data: {
                experience_id: t,
                user_id: e,
                email: n,
                fullname: i,
                phone: r,
                special: o,
                qty: c,
                booking_time: u,
                booking_date: a,
                time: l,
                amount: v,
                address_keyword: g,
                address: m,
                city: s,
                is_Admin: 1,
                avard_point: y,
                non_veg: non_veg_qty,
                alcohol: alcohol_qty
            },
            dataType: "json",
            success: function(e) {
                $("#editModal").modal("hide");
                var t = "<div class='alert alert-success alert-dismissable' id='success_alert'>";
                t += "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>x</button>";
                t += "<strong>Success!</strong> You've just made a booking for <span>" + e.full_name + "</span> - <span>" + e.email + "</span>";
                t += " at <span>" + e.venue + "</span>. <span>" + e.exp_title + "</span> booked on <span>" + e.booking_date + "</span> at <span>" + e.booking_time + "</span>. Contact " + e.full_name + " at <span>" + e.phone + " no for changes.</span>";
                $("#success_alert").remove();
                $(".cms-title").before(t)
            }
        })
    });
    $("#ac_select_table").click(function() {
        var e = $("#user_id").val();
        var t = $("#ac_exp_id").val();
        var n = $("#customerEmail").val();
        var r = $("#customerNumber").val();
        var i = $("#customerName").val();
        var s = $("#ac_city").val();
        var o = parseInt($("#price" + t).val());
		var arp = $("#alacarte_reward_points").val();
        var u = $("#ac_specialRequests").val() + " ";
        if ($("#ac_experienceTakers").val() != "") {
            u += " Experience takers: " + $("#ac_experienceTakers").val() + " "
        }
        if ($("#ac_giftID").val() != "") {
            u += " Gift Card ID: " + $("#ac_giftID").val() + " "
        }
        if ($("#ac_giftAmt").val() != "") {
            u += " Gift Card Amount: " + $("#ac_giftAmt").val() + " "
        }
        var a = $("#ac_select_time span").text();
        var f = $("#ac_reserv_date").val();
        var l = f.split("-");
        var c = l[1] + "/" + l[2] + "/" + l[0] + " " + a;
        var h = parseInt($("#ac_party_size").val());
        var p = parseFloat($("#ac_price").val());
        var d = parseFloat($("#ac_price_non_veg").val());
        var v = parseFloat($("#ac_price_alcohol").val());
        if (!$("#ac_nonveg").hasClass("hidden")) {
            non_veg_qty = parseInt($(".nonveg").val())
        } else {
            non_veg_qty = 0
        }
        if (!$("#ac_alcohol").hasClass("hidden")) {
            alcohol_qty = parseInt($(".alcohol").val())
        } else {
            alcohol_qty = 0
        }
        var m = h * o;
        var g = $("#ac_addr").val();
        var y = $("#ac_address_keyword").val();
        var b = 0;
        if ($("#ac_avard_point").is(":checked")) {
            b = 1
        }
        $(".add_loader").show();
        $.ajax({
            url: "/adminreservations/ac_checkout",
            type: "POST",
            data: {
            	alacarte_id:t,
                experience_id: t,
                restaurant_id: t,
                user_id: e,
                email: n,
                fullname: i,
                phone: r,
                special: u,
                qty: h,
                booking_time: a,
                booking_date: f,
                time: c,
                amount: m,
                address_keyword: y,
                address: g,
                city: s,
                is_Admin: 1,
                avard_point: b,
                non_veg: non_veg_qty,
                alcohol: alcohol_qty,
				ala_reward_point: arp
            },
            dataType: "json",
            success: function(e) {
                $("#ac_editModal").modal("hide");
                var t = "<div class='alert alert-success alert-dismissable' id='success_alert'>";
                t += "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>x</button>";
                t += "<strong>Success!</strong> You've just made a booking for <span>" + e.full_name + "</span> - <span>" + e.email + "</span>";
                t += " at <span>" + e.venue + "</span>. <span>" + e.exp_title + "</span> booked on <span>" + e.booking_date + "</span> at <span>" + e.booking_time + "</span>. Contact " + e.full_name + " at <span>" + e.phone + " no for changes.</span>";
                $("#success_alert").remove();
                $(".cms-title").before(t)
            }
        })
    });

    $('#admin_restaurant_search').autocomplete({

        source: function( request, response ) {

            $.ajax({
                url: "/admin/adminreservations/restaurant_search/"+request.term,
                dataType: "JSON",
                success: function( data ) {
                    //console.log('response for all== '+data);
                    response( data );
                }
            });
        },
        select: function(event,ui){
            $(".small-ajax-loader-search").css('display','inline');
            var restaurant_name = ui.item.value;


            $.ajax({

                url: "/admin/adminreservations/getRelatedResults",
                dataType: "JSON",
                type: "post",
                //data: {restaurant_val : rest_val,date_value : date_val,time_value : time_val},
                data: {restaurant_val : restaurant_name},

                success: function(e) {
                    $(".small-ajax-loader-search").css('display','none');

                    var t = "";
                    var n = "";
                    var u = "";
                    if (e.experiences.length == 0 && e.alacarte.length == 0) {
                        t = "<tr><td colspan='2'><p class='text-center'>There are no any experiences or alacarte!</p></td></tr>"
                    } else {
                        $.each(e.experiences, function(r, i) {
                            t += "<tr>";
                            t += "<td><p>Experience: " + ucfirst(i.exp_name) + "</p>";
                            t += "<p>Restaurant: " + ucfirst(i.vendor_name) + "</p>";
                            t += "<p>City: " + ucfirst(i.city_name) + "</p>";
                            t += "<p><strong><a href='" + e.url + "/" + i.city_name + "/experiences/" + escape(i.slug) + "' target='_blank' class='details'>View Details</a></strong></p>";

                            t += "<td><a href='javascript:void(0)' class='btn btn-warning book_table'>Book Experience</a>" + n + "</td></tr>"
                        });

                        $.each(e.alacarte, function(a, b) {
                            //console.log("a = "+a+" , b = "+b.venue);
                            u += "<tr><td>";
                            //t += "<td><p>Experience: " + ucfirst(i.exp_title) + "</p>";
                            u += "<p>Restaurant: " + ucfirst(b.name) + "</p>";
                            u += "<p>City: " + ucfirst(b.city_name) + "</p>";
                            u += "<p>Area: " + ucfirst(b.area_name) + "</p>";
                            u += "<p><strong><a href='" + e.url + "/" + b.city_name + "/alacarte/" + escape(b.slug) + "' target='_blank' class='details'>View Details</a></strong></p>";
                            //if (i.alacarte_alow) {
                            u += "<td><a href='javascript:void(0)' class='btn btn-warning ac_book_table'>Book A la carte</a></td>"
                            //} else {
                            //n = ""
                            //}
                            //t += "<td><a href='javascript:void(0)' class='btn btn-warning book_table' rel='" + i.id + "'>book experience</a>" + n + "</td></tr>"*/
                        });

                    }
                    $("#experiences tbody").html(t+""+u);
                },
                timeout: 9999999
            });
        },
        minLength: 1
    })
    $("#exp_search").keyup(function() {
        var e = $(this).val();
        $("#search_error").html("");
        delay(function() {
            $(".search-ajax-loader").show();
            $.ajax({
                url: "/adminreservations/getExperiences",
                type: "POST",
                dataType: "json",
                data: {
                    search: e
                },
                success: function(e) {
                    $(".search-ajax-loader").hide();
                    if (e.error == 0) {
                        var t = "";
                        var n = "";
						var u = "";
                        if (e.result.length == 0 && e.ala_result.length == 0) {
                            t = "<tr><td colspan='2'><p class='text-center'>There are no any experiences or alacarte!</p></td></tr>"
                        } else {
                            $.each(e.result, function(r, i) {
                                t += "<tr>";
                                t += "<td><p>Experience: " + ucfirst(i.exp_title) + "</p>";
                                t += "<p>Restaurant: " + ucfirst(i.venue) + "</p>";
                                t += "<p>City: " + ucfirst(i.city) + "</p>";
                                t += "<p><strong><a href='" + e.url + i.city + "/experiences/" + i.slug + "' target='_blank' class='details'>View Details</a></strong></p>";
                                t += "<p><small>" + ucfirst(i.exp_short_desc) + "</small></p></td>";
                                t += "<input type='hidden' name='price' id='price" + i.restaurant_id + "' value='" + i.price + "'>";
                                /*if (i.alacarte_alow) {
                                    n = "<br><br><a href='javascript:void(0)' class='btn btn-warning ac_book_table' rel='" + i.restaurant_id + "'>Book A la carte</a>"
                                } else {
                                    n = ""
                                }*/
                                t += "<td><a href='javascript:void(0)' class='btn btn-warning book_table' rel='" + i.id + "'>book experience</a>" + n + "</td></tr>"
                            });

							$.each(e.ala_result, function(a, b) {
								//console.log("a = "+a+" , b = "+b.venue);
                                u += "<tr><td>";
                                //t += "<td><p>Experience: " + ucfirst(i.exp_title) + "</p>";
                                u += "<p>Restaurant: " + ucfirst(b.venue) + "</p>";
                                u += "<p>City: " + ucfirst(b.city) + "</p>";
								u += "<p>Area: " + ucfirst(b.area) + "</p>";
                                u += "<p><strong><a href='" + e.url + b.city + "/alacarte/" + b.slug + "' target='_blank' class='details'>View Details</a></strong></p>";
                                u += "<input type='hidden' name='price' id='price" + b.id + "' value='" + b.price + "'></td>";
                                //if (i.alacarte_alow) {
                                u += "<td><a href='javascript:void(0)' class='btn btn-warning ac_book_table' rel='" + b.id + "'>Book A la carte</a></td>"
                                //} else {
                                    //n = ""
                                //}
                                //t += "<td><a href='javascript:void(0)' class='btn btn-warning book_table' rel='" + i.id + "'>book experience</a>" + n + "</td></tr>"*/
                            });

                        }
						//console.log(t+" , this is for alacarte = "+u);
                        $("#experiences tbody").html(t+""+u)
                    } else {
                        $("#search_error").html(e.message)
                    }
                }
            })
        }, 1e3)
    });
    $(".table tbody").on("click", ".book_table", function() {
        var e = $("#user_id").val();
        var t = $(this).attr("rel");
        if (e != "") {
            $("#error_alert").html("");
            $("#error_alert").addClass("hidden");
            $("#exp_id").val(t);
            get_info("/adminreservations/getExp_info", t);
            $("#editModal").modal("show")
        } else {
            $("#error_alert").remove();
            $(".cms-title").before('<div class="alert alert-danger alert-dismissable" id="error_alert"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><p>Please enter a user</p></div>')
        }
    });
    $(".table tbody").on("click", ".ac_book_table", function() {
        var e = $("#user_id").val();
        var t = $(this).attr("rel");
        if (e != "") {
            $("#error_alert").html("");
            $("#error_alert").addClass("hidden");
            $("#ac_exp_id").val(t);
            ac_get_info("/adminreservations/ac_getExp_info", t);
            $("#ac_editModal").modal("show")
        } else {
            $("#error_alert").remove();
            $(".cms-title").before('<div class="alert alert-danger alert-dismissable" id="error_alert"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><p>Please enter a user</p></div>')
        }
    });
    $("#customerEmail").keyup(function() {
        $(".show_in_input_no").css('display','none');
        $(".show_in_input_yes").css('display','none');
        var e = $(this).val();
        delay(function() {
            $(".small-ajax-loader").show();
            $("#last_reserv").hide();
            $("#upcomings_reservs").html("");
            $("#pref_reservs").html("");
            $(".member-reservation-wrap li:first-child a").click();
            $.ajax({
                url: "/admin/adminreservations/checkUser",
                type: "POST",
                dataType: "json",
                data: {
                    email: e
                },
                success: function(e) {
                    $(".small-ajax-loader").hide();
                    if (e.error == 0) {
                        $("#email_error").html("");
                        if (e.exists) {
                            $(".show_in_input_no").css('display','none');
                            $(".show_in_input_yes").css('display','inline');
                            $("#customerName").val(e.user.full_name);
                            $("#customerNumber").val(e.user.phone_number);
                            $("#customerCity option").each(function(t, n) {
                                if ($(this).val() == e.user.location_id) {
                                    $(this).attr("selected", "selected")
                                }
                            });
                            $("#add_member").addClass("hidden");
                            $("#user_id").val(e.user.id)
                        } else {
                            $(".show_in_input_no").css('display','inline');
                            $(".show_in_input_yes").css('display','none');
                            $("#add_member").removeClass("hidden");
                            $("#customerName").val("");
                            $("#customerNumber").val("");
                            $("#customerCity option").each(function(e, t) {
                                $(this).removeAttr("selected")
                            });
                            $("#user_id").val("")
                        }
                    } else {
                        $("#email_error").html(e.errors.email.CustomerEmail);
                        $(".show_in_input_no").css('display','inline');
                        $(".show_in_input_yes").css('display','none');
                        $("#add_member").addClass("hidden");
                        $("#customerName").val("");
                        $("#customerNumber").val("");
                        $("#customerCity option").each(function(e, t) {
                            $(this).removeAttr("selected")
                        });
                        $("#user_id").val("")
                    }
                }
            })
        }, 1e3)
    });
    $("#add_member").click(function() {
        var e = false;
        var t = $("#customerEmail").val();
        var n = $("#customerName").val();
        var r = $("#customerNumber").val();
        var i = $("#customerCity option:selected").val();
        fullname_regex = /(^(?:(?:[a-zA-Z]{2,4}\.)|(?:[a-zA-Z]{2,24}))){1} (?:[a-zA-Z]{2,24} )?(?:[a-zA-Z']{2,25})(?:(?:, [A-Za-z]{2,6}\.?){0,3})?/gim;
        if (n == "") {
            $("#full_name_error").text("Please enter users first and last name");
            e = true
        } else if (!fullname_regex.test(n)) {
            $("#full_name_error").text("Please enter users first and last name");
            e = true
        } else {
            $("#full_name_error").empty()
        }
        if (r == "" || r.length < 10) {
            e = true;
            $("#phone_error").text("Please enter a valid telephone number.")
        } else if (!$.isNumeric(r)) {
            $("#phone_error").text("Please enter a valid telephone number.")
        } else {
            $("#phone_error").empty()
        }
        if (!e) {
            $.ajax({
                url: "/admin/adminreservations/addmember",
                type: "POST",
                dataType: "json",
                data: {
                    email: t,
                    full_name: n,
                    phone: r,
                    city: i
                },
                success: function(e) {
                    $("#success_alert").remove();
                    var t = '<div class="alert alert-success alert-dismissable" id="success_alert">';
                    t += '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
                    t += e.success_message + "</div>";
                    $(".cms-title").before(t);
                    $("#user_id").val(e.user_id);
                    $(".show_in_input_no").css('display','none');
                    $(".show_in_input_yes").css('display','inline');
                    $("#add_member").addClass("hidden")
                }
            })
        }
    });
    $("#modif_reserv").click(function() {
        var e = $("#user_id").val();
        if (e != "") {
            get_reserv_info(e)
        } else {
            $("#error_alert").remove();
            $(".cms-title").before('<div class="alert alert-danger alert-dismissable" id="error_alert"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><p>Please enter a user</p></div>')
        }
    });
    $("#last_reserv").click(function() {
        $(".glyphicon-plus-sign").hide();
        $(".prev_res").hide();
        $(".ajax_loader").show();
        var user_id = $("#user_id").val();
        var map_id = 0;
        $.ajax({
            type: "POST",
            data: {
                user_id: user_id
            },
            dataType: "json",
            url: "/adminreservations/get_previous_reservs",
            success: function(data) {
                $("#last_reserv").hide();
                $("#pref_reservs").append(data.str);
                for (var i in data.google_map) {
                    var str = "var pr_dealer_lat" + map_id + " = data.google_map[" + i + "]['lat'];var pr_dealer_lng" + map_id + " = data.google_map[" + i + "]['long'];var pr_mylatlng" + map_id + "=  new google.maps.LatLng(pr_dealer_lat" + map_id + ", pr_dealer_lng" + map_id + ");var pr_mapOptions" + map_id + " = {center: pr_mylatlng" + map_id + ",zoom: 16};var prs_map" + map_id + " = new google.maps.Map(document.getElementById('prs_map" + map_id + "'),pr_mapOptions" + map_id + ");var pr_marker" + map_id + " = new google.maps.Marker({position: pr_mylatlng" + map_id + ",map: prs_map" + map_id + "});";
                    eval(str);
                    map_id++
                }
            }
        })
    });
    $(document).on("click", ".cancel_but", function() {
        var e = $(this).attr("rel");
        $("#cancel_reserv_id").val(e);
        $("#cancelModal").modal("show")
    });
    $(document).on("click", ".ac_cancel_but", function() {
        var e = $(this).attr("rel");
        $("#ac_cancel_reserv_id").val(e);
        $("#ac_cancelModal").modal("show")
    });
    $("#confirm_cancel").click(function() {
        var e = $("#cancel_reserv_id").val();
        var t = $("#user_id").val();
        var n = $("#customerEmail").val();
        var r = $("#customerNumber").val();
        var i = $("#customerName").val();
        $(".cancel_loader").show();
        $.ajax({
            url: "/adminreservations/cancel_reservation",
            type: "POST",
            dataType: "json",
            data: {
                reserv_id: e,
                user_id: t,
                email: n,
                phone: r,
                full_name: i
            },
            success: function(e) {
                if (e.success == 1) {
                    $("#error_alert").remove();
                    $(".cms-title").before(e.message);
                    $("#modif_reserv").click();
                    $(".cancel_loader").hide();
                    $("#cancelModal").modal("hide")
                }
            }
        })
    });
    $("#ac_confirm_cancel").click(function() {
        var e = $("#ac_cancel_reserv_id").val();
        var t = $("#user_id").val();
        var n = $("#customerEmail").val();
        var r = $("#customerNumber").val();
        var i = $("#customerName").val();
        $(".cancel_loader").show();
        $.ajax({
            url: "/adminreservations/ac_cancel_reservation",
            type: "POST",
            dataType: "json",
            data: {
                reserv_id: e,
                user_id: t,
                email: n,
                phone: r,
                full_name: i
            },
            success: function(e) {
                if (e.success == 1) {
                    $("#error_alert").remove();
                    $(".cms-title").before(e.message);
                    $("#modif_reserv").click();
                    $(".cancel_loader").hide();
                    $("#ac_cancelModal").modal("hide")
                }
            }
        })
    });
    $("#upcomings_reservs").on("click", ".change_but", function() {
        $(".cant_change").addClass("hidden");
        var e = $(this).attr("rel");
        get_reserv_by_id(e);
        $("#editModal").modal("show")
    });
    $("#upcomings_reservs").on("click", ".ac_change_but", function() {
        $(".cant_change").addClass("hidden");
        var e = $(this).attr("rel");
        ac_get_reserv_by_id(e);
        $("#ac_editModal").modal("show")
    });
    $("#save_changes").click(function() {
        var e = $("#user_id").val();
        var t = $("#exp_id").val();
        var n = $("#customerEmail").val();
        var r = $("#customerNumber").val();
        var i = $("#customerName").val();
        var s = $("#city").val();
        var o = $("#specialRequests").val() + " ";
        if ($("#experienceTakers").val() != "") {
            o += " Experience takers: " + $("#experienceTakers").val() + " "
        }
        if ($("#giftID").val() != "") {
            o += " Gift Card ID: " + $("#giftID").val() + " "
        }
        if ($("#giftAmt").val() != "") {
            o += " Gift Card Amount: " + $("#giftAmt").val() + " "
        }
        var u = $("#select_time span").text();
        var a = $("#reserv_date").val();
        var f = a.split("-");
        var l = f[1] + "/" + f[2] + "/" + f[0] + " " + u;
        var c = parseInt($("#party_size").val());
        var h = parseFloat($("#price").val());
        var p = parseFloat($("#price_non_veg").val());
        var d = parseFloat($("#price_alcohol").val());
        if (!$("#nonveg").hasClass("hidden")) {
            non_veg_qty = parseInt($(".nonveg").val())
        } else {
            non_veg_qty = 0
        }
        if (!$("#alcohol").hasClass("hidden")) {
            alcohol_qty = parseInt($(".alcohol").val())
        } else {
            alcohol_qty = 0
        }
        var v = c * h + non_veg_qty * p + alcohol_qty * d;
        var m = $("#res_id").val();
        var g = $("#addr").val();
        var y = $("#address_keyword").val();
        var b = 0;
        if ($("#avard_point").is(":checked")) {
            b = 1
        }
        last_reserv_date = $("#last_reserv_date").val();
        last_reserv_time = $("#last_reserv_time").val();
        last_reserv_outlet = $("#last_reserv_outlet").val();
        last_reserv_party_size = $("#last_reserv_party_size").val();
        l_res_date = last_reserv_date.split("-");
        l_res_date = l_res_date[1] + "/" + l_res_date[2] + "/" + l_res_date[0];
        if (last_reserv_date == a && last_reserv_time == u && last_reserv_outlet == y && last_reserv_party_size == c) {
            $(".cant_change").removeClass("hidden");
            $("#save_changes").addClass("hidden")
        } else {
            $(".add_loader").show();
            $.ajax({
                url: "/adminreservations/edit_reservetion",
                type: "POST",
                dataType: "json",
                data: {
                    reserv_id: m,
                    user_id: e,
                    party_size: c,
                    edit_date: a,
                    edit_time: u,
                    address: g,
                    outlet: y,
                    alcohol: alcohol_qty,
                    non_veg: non_veg_qty,
                    last_reserv_date: l_res_date,
                    last_reserv_time: last_reserv_time,
                    last_reserv_outlet: last_reserv_outlet,
                    last_reserv_party_size: last_reserv_party_size,
                    email: n,
                    full_name: i,
                    phone: r,
                    special: o
                },
                success: function(e) {
                    $(".add_loader").hide();
                    var t = "<div class='alert alert-success alert-dismissable' id='success_alert'>";
                    t += "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>x</button>";
                    t += "<strong>Success!</strong> You've changed a booking with Reservation ID EU-" + e.reserv_id + " for <span>" + e.full_name + "</span> - <span>" + e.email + "</span>";
                    t += " at <span>" + e.venue + "</span>. <span>" + e.exp_title + "</span> booked on <span>" + e.booking_date + "</span> at <span>" + e.booking_time + "</span>. Contact " + e.full_name + " at <span>" + e.phone + " no for changes.</span>";
                    $("#success_alert").remove();
                    $(".cms-title").before(t);
                    $("#modif_reserv").click();
                    $("#editModal").modal("hide")
                }
            })
        }
    });
    $("#ac_save_changes").click(function() {
        var e = $("#user_id").val();
        var t = $("#ac_exp_id").val();
        var n = $("#customerEmail").val();
        var r = $("#customerNumber").val();
        var i = $("#ac_customerName").val();
        var s = $("#ac_city").val();
        var o = $("#ac_specialRequests").val() + " ";
        if ($("#ac_experienceTakers").val() != "") {
            o += " Experience takers: " + $("#ac_experienceTakers").val() + " "
        }
        if ($("#ac_giftID").val() != "") {
            o += " Gift Card ID: " + $("#ac_giftID").val() + " "
        }
        if ($("#ac_giftAmt").val() != "") {
            o += " Gift Card Amount: " + $("#ac_giftAmt").val() + " "
        }
        var u = $("#ac_select_time span").text();
        var a = $("#ac_reserv_date").val();
        var f = a.split("-");
        var l = f[1] + "/" + f[2] + "/" + f[0] + " " + u;
        var c = parseInt($("#ac_party_size").val());
        var h = parseFloat($("#ac_price").val());
        var p = parseFloat($("#ac_price_non_veg").val());
        var d = parseFloat($("#ac_price_alcohol").val());
        if (!$("#nonveg").hasClass("hidden")) {
            non_veg_qty = parseInt($(".nonveg").val())
        } else {
            non_veg_qty = 0
        }
        if (!$("#alcohol").hasClass("hidden")) {
            alcohol_qty = parseInt($(".alcohol").val())
        } else {
            alcohol_qty = 0
        }
        var v = c * h + non_veg_qty * p + alcohol_qty * d;
        var m = $("#ac_res_id").val();
        var g = $("#ac_addr").val();
        var y = $("#ac_address_keyword").val();
        var b = 0;
        if ($("#ac_avard_point").is(":checked")) {
            b = 1
        }
        last_reserv_date = $("#ac_last_reserv_date").val();
        last_reserv_time = $("#ac_last_reserv_time").val();
        last_reserv_outlet = $("#ac_last_reserv_outlet").val();
        last_reserv_party_size = $("#ac_last_reserv_party_size").val();
        l_res_date = last_reserv_date.split("-");
        l_res_date = l_res_date[1] + "/" + l_res_date[2] + "/" + l_res_date[0];
        if (last_reserv_date == a && last_reserv_time == u && last_reserv_outlet == y && last_reserv_party_size == c) {
            $(".cant_change").removeClass("hidden");
            $("#ac_save_changes").addClass("hidden")
        } else {
            $(".add_loader").show();
            $.ajax({
                url: "/adminreservations/ac_edit_reservetion",
                type: "POST",
                dataType: "json",
                data: {
                    reserv_id: m,
                    user_id: e,
                    party_size: c,
                    edit_date: a,
                    edit_time: u,
                    address: g,
                    outlet: y,
                    alcohol: alcohol_qty,
                    non_veg: non_veg_qty,
                    last_reserv_date: l_res_date,
                    last_reserv_time: last_reserv_time,
                    last_reserv_outlet: last_reserv_outlet,
                    last_reserv_party_size: last_reserv_party_size,
                    email: n,
                    full_name: i,
                    phone: r,
                    special: o
                },
                success: function(e) {
                    $(".add_loader").hide();
                    var t = "<div class='alert alert-success alert-dismissable' id='success_alert'>";
                    t += "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>x</button>";
                    t += "<strong>Success!</strong> You've changed a booking with Reservation ID EU-" + e.reserv_id + " for <span>" + e.full_name + "</span> - <span>" + e.email + "</span>";
                    t += " at <span>" + e.venue + "</span>. <span>" + e.exp_title + "</span> booked on <span>" + e.booking_date + "</span> at <span>" + e.booking_time + "</span>. Contact " + e.full_name + " at <span>" + e.phone + " no for changes.</span>";
                    $("#success_alert").remove();
                    $(".cms-title").before(t);
                    $("#modif_reserv").click();
                    $("#ac_editModal").modal("hide")
                }
            })
        }
    })

    /*code pasted from my reservations page*/

    var disabledAllDays = '';
    var allschedule = '';
    var reserveminmax = '';

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
        // loadDatePicker();

        /*$('#locality_select').on('click', function(){
         alert(1);
         //$('#locality').show();
         });*/

        $("body").delegate("#locality_select", "click", function() {
            $('#locality').show();
            $('#locality_select').hide();
        });

        $("body").delegate("#locality", "change", function() {
            //alert(1);
            var locality_change_val = $(this).val();
            var locality_select_txt = $(this).find('option:selected').text();
            //console.log("sad = "+locality_select_txt);
            if(locality_change_val !='0')
            {
                //$('#locality_val').val(locality_change_val);
                $('#myselect_locality').text(locality_select_txt);
                $('#save_changes').show();
            }
            $('#locality').hide();
            $('#locality_select').show();
            var product_id = $('#my_product_id').val();
            $.ajax({
                url: "/users/productVendorLoad",
                type: "post",
                data: {
                    product_id:  product_id,
                    locality_change_val: locality_change_val
                },
                beforeSend:function()
                {
                    $("#get_locality").html('<img src="/images/loading.gif">');
                },
                success: function(e) {
                    //console.log(e);
                    $('#get_locality').html(e);
                    $('#new_locality_value').val(locality_select_txt);
                }
            });

        });

        $("body").delegate("#party_size1", "change", function() {
            counter = $(this).val();
            //alert(counter);
            str = "";
            for (var e = 0; e <= counter; e++) {
                str += "<option value='" + e + "'>" + e + "</option>"
            }
            $(".meals2 select").html(str);
        });

        $("body").delegate(".myaddonselect", "change", function() {
            $('#save_changes').show();
        });

        $("body").delegate("#giftcard_id", "keyup", function() {
            $('#save_changes').show();
        });

        $("body").delegate("#special_request", "keyup", function() {
            $('#save_changes').show();
        });

        $('#locations1').change(function(){
            $('#party_edit1').trigger('click');

            loadPartySelect();
            loadDatePicker();
        });



        /*reservation over*/

        $('#time_edit5').click(function(){
            $('#save_changes').show();
            var last_reserve_date = $('last_reserv_date').val();
            var vendor_id = $('#vendor_id').val();
            var last_reserv_time = $('#last_reserv_time').val();
            $('#collapseThree5').slideToggle();
            $.ajax({
                url: "/users/timedataload",
                type: "post",
                data: {
                    dateText:  last_reserve_date,
                    vendor_id: vendor_id,
                    last_reserv_time:last_reserv_time
                },
                beforeSend:function()
                {
                    $("#timeajax").html('<img src="/images/loading.gif">');
                },
                success: function(e) {
                    //console.log(e);
                    $('#timeajax').html(e);
                }
            });
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

    /* function loadDatePicker() {
     $("#choose_date").datepicker("destroy");*/
    $('#date_edit12').click(function(){
        $('#save_changes').show();
        var vendor_id = $('#vendor_id').val();
        var last_reserv_time = $('#last_reserv_time').val();
        $("#choose_date").datepicker({
            dateFormat: 'yy-m-dd',
            minDate: 'new Date()',
            beforeShowDay: disableAllTheseDays,
            onSelect: function(dateText, inst)
            {
                var d = $.datepicker.parseDate("yy-m-dd",  dateText);

                var datestrInNewFormat = $.datepicker.formatDate( "D", d).toLowerCase();
                var txt = '<div class="btn-group col-lg-10 pull-right actives ">';
                var txt2 = '';
                var g = 1;
                var cur_date =  new Date();
                month = parseInt(cur_date.getMonth());
                month += 1;
                c_date = cur_date.getFullYear() + '-' + ((month<10)?'0':'')+month +  '-'  + cur_date.getDate();
                c_time = cur_date.getHours()+":"+((cur_date.getMinutes()<10)?'0':'')+cur_date.getMinutes()+':00';

                //console.log(c_date);
                //alert(dateText);
                $('#last_reserv_date').val(dateText);
                $.ajax({
                    url: "/users/timedataload",
                    type: "post",
                    data: {
                        dateText:dateText,
                        vendor_id:vendor_id,
                        last_reserv_time:last_reserv_time
                    },
                    beforeSend:function()
                    {
                        $("#timeajax").html('<img src="/images/loading.gif">');
                    },
                    success: function(e) {
                        //console.log(e);
                        $('#timeajax').html(e);
                    }
                });
                //here using ajax!!!
                //console.log(dateText);
                /*Time display container*/
                /*var location_id = '97';
                 var schedule = allschedule[location_id];
                 console.log(schedule);

                 if(schedule != undefined)
                 {
                 for(key_sch in schedule[datestrInNewFormat])
                 {

                 var obj_length = Object.keys(schedule[datestrInNewFormat]).length;
                 console.log(obj_length);
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
                 }*/
                /*Time display container*/


                /* txt += '</div><div class="clearfix"></div>';
                 txt += '<input type="hidden" name="booking_time" id="booking_time" value="">';
                 $('#hours').html(txt2);
                 $('#time').html(txt);

                 $('#booking_date').val(dateText);*/



                $('#date_edit12 span').text(formatDate(dateText));
                $('#date_edit12').click();
                timehide=0;
                // $('#time_edit1').click();
            }
        });
        $( "#choose_date" ).datepicker("refresh");
        /*}*/
    });





})(jQuery);