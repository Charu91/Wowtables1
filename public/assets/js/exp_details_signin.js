function getURLParameters(e) {
    var t = {};
    var n = e.indexOf("?");
    if (n == -1) return t;
    var r = e.substring(n + 1);
    var i = r.split("&");
    for (var s = 0; s < i.length; s++) {
        var o = i[s].split("=");
        t[o[0]] = o[1]
    }
    return t
}
$(document).ready(function() {
    $(".tooltip1").tooltip();
    email_regex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
    $("input[name='email1']").focus(function() {
        $("#email_error").css("display", "none");
        $("input[name='email1']").css("border", "0px")
    });
    $("input[name='password1']").focus(function() {
        $("#password_error").css("display", "none");
        $("input[name='password']").css("border", "0px")
    });
    $("#city").focus(function() {
        $("#city_error").css("display", "none");
        $("#city").css("border", "0px")
    });
    $("#full_name").focus(function() {
        $("#error_name").css("display", "none");
        $("#full_name").css("border", "0px")
    });
    $("#email1").focus(function() {
        $("#email_error_1").css("display", "none")
    });
    $("#pass1").focus(function() {
        $("#password_error_1").css("display", "none")
    });
    $("#email_error").on("click", "a", function() {
        $("#redirectloginModal ul").find(".active").removeClass("active");
        $("#redirectloginModal li").first().addClass("active")
    });
    $("#send").click(function(e) { console.log("send");
        e.preventDefault();
        email_address = $("input[name='email1']");
        err = 0;
        mypassword = "";
        if (email_address.val() == "") {
            $("#email_error").text("Please enter your email address");
            $("#email_error").css("display", "block");
            $("input[name='email1']").css("border", "2px solid #B94A39");
            err++
        } else if (!email_regex.test(email_address.val())) {
            $("#email_error").text("Please enter a valid email address");
            $("#email_error").css("display", "block");
            $("input[name='email1']").css("border", "2px solid #B94A39");
            err++
        } else {
            $("#email_error").css("display", "none");
            $("input[name='email1']").css("border", "0px")
        }
        if ($("input[name='password1']").val() == "") {
            $("#password_error").text("Please enter a password");
            $("input[name='password1']").css("border", "2px solid #B94A39");
            $("#password_error").css("display", "block");
            err++
        } else if ($("input[name='password1']").val().replace(/\s+$/, "") == "" || $("input[name='password1']").val().replace(/\s+$/, "") == "******") {
            $("#password_error").text("Write correct password");
            $("input[name='password']").css("border", "2px solid #B94A39");
            $("#password_error").css("display", "block");
            err++
        } else {
            $("input[name='password1']").css("border", "0px");
            $("#password_error").css("display", "none");
            mypassword = $("input[name='password1']").val()
        }
        if ($("#city").val() == -1) {
            $("#city").css("border", "2px solid #B94A39");
            $("#city_error").css("display", "block");
            err++
        } else {
            $("#city").css("border", "0px");
            $("#city_error").css("display", "none")
        }
        if (err >= 1) {
            return false
        } else {
            $.ajax({
                url: "/registration/check_email",
                data: {
                    email: email_address.val(),
                    gothrough: "gothrough"
                },
                type: "post",
                success: function(e) {
                    if (e != "") {
                        console.log(e);
                        $("input[name='email1']").css("border", "2px solid #B94A39");
                        $("#email_error").html(e);
                        $("#email_error").css("display", "block");
                        $("#email_error a").on("click", function(e) {
                            $(".form-slide-wrapper").animate({
                                left: "-320px"
                            });
                            $("input[name='email1']").css("border", "0px")
                        });
                        err++
                    } else {
                        var t = getURLParameters($(location).attr("href"));
                        if (t.utm_source && t.utm_medium && t.utm_campaign) {
                            var n = "/registration/register?utm_source=" + t.utm_source + "&utm_medium=" + t.utm_medium + "&utm_campaign=" + t.utm_campaign
                        } else {
                            var n = "/registration/register"
                        }
                        yourcity = $("#city option:selected").val();
                        var r = $("#year_bd").val() + "-" + $("#month_bd").val() + "-" + $("#day_bd").val();
                        $.ajax({
                            type: "POST",
                            url: n,
                            data: {
                                email: email_address.val(),
                                password: mypassword,
                                city: yourcity,
                                full_name: $("#full_name").val(),
								reg_page : window.location.href,
                            },
                            success: function(e) {
                                var t = "";
                                var n = $("#url").val();
                                if (e == 1) {
                                    t = $("#slug").val();
									console.log("slug = "+t);
                                    if (t != "" && t != "listing") {
                                        window.location = n + "experiences/" + t
                                    } else if(t == "listing") {
                                        window.location = n 
                                    }else {
                                        window.location = n + "experience/lists/?signup=true"
                                    }
                                } else {}
                            }
                        })
                    }
                }
            })
        }
    });
    $("#send1").click(function(e) { //console.log("send 1");
        e.preventDefault();
        error = 0;
        fullname_regex = /(^(?:(?:[a-zA-Z]{2,4}\.)|(?:[a-zA-Z]{2,24}))){1} (?:[a-zA-Z]{2,24} )?(?:[a-zA-Z']{2,25})(?:(?:, [A-Za-z]{2,6}\.?){0,3})?/gim;
        if ($("#full_name").val() == "") {
            $("#error_name").css("display", "block");
            $("#full_name").css("border", "2px solid #B94A39");
            error++
        } else if (!fullname_regex.test($("#full_name").val())) {
            $("#error_name").text("Please enter your first and last name");
            $("#error_name").css("display", "block");
            $("#full_name").css("border", "2px solid #B94A39");
            error++
        } else {
            $("#error_name").css("display", "none");
            $("#full_name").css("border", "0px")
        }
        if ($("#day_bd").val() == 0 || $("#month_bd").val() == 0) {
            $("#error_bd").text("Please select your birthday.");
            $("#error_bd").css("display", "block");
            $("#day_bd,#month_bd").css("border", "2px solid #B94A39");
            error++
        } else {
            $("#error_bd").css("display", "none");
            $("#day_bd,#month_bd").css("border", "0px")
        }
        var t = $("#url").val();
        console.log($("#slug").val());
        console.log($("#url").val());
        if (error >= 1) {
            return false
        } else {
            var n = getURLParameters($(location).attr("href"));
            if (n.utm_source && n.utm_medium && n.utm_campaign) {
                var r = "/registration/register?utm_source=" + n.utm_source + "&utm_medium=" + n.utm_medium + "&utm_campaign=" + n.utm_campaign
            } else {
                var r = "/registration/register"
            }
            yourcity = $("#city option:selected").val();
            var i = $("#year_bd").val() + "-" + $("#month_bd").val() + "-" + $("#day_bd").val();
            $.ajax({
                type: "POST",
                url: r,
                data: {
                    email: email_address.val(),
                    password: mypassword,
                    city: yourcity,
                    full_name: $("#full_name").val(),
                    gender: $("input:radio[name=generRadios]:checked").val(),
                    dob: i
                },
                success: function(e) {
                    var t = "";
                    var n = $("#url").val();
					console.log("url == "+n);
                    if (e == 1) {
                        t = $("#slug").val();
                        if (t != "") {
                            window.location = n + "experiences/" + t
                        } else {
                            window.location = n + "experience/lists/?signup=true"
                        }
                    } else {}
                }
            })
        }
    });
    $("#login").click(function(e) { 
        e.preventDefault();
        login = $("#email1").val();
        psw = $("#pass1").val();
        logerror = 0;
        if (login == "") {
            $("#email_error_1").text("Please enter your email address");
            $("#email_error_1").css("display", "block");
            $("#email1").css("border", "2px solid #B94A39");
            logerror++
        } else if (!email_regex.test(login)) {
            $("#email_error_1").text("Please enter a valid email address");
            $("#email_error_1").css("display", "block");
            $("#email1").css("border", "2px solid #B94A39");
            logerror++
        } else {
            $("#email_error_1").css("display", "none");
            $("#email1").css("border", "0px")
        }
        if (psw == "") {
            $("#password_error_1").text("Please enter a password.");
            $("#password_error_1").css("display", "block");
            $("#pass1").css("border", "2px solid #B94A39");
            logerror++
        } else if (psw.replace(/\s+$/, "") == "" || psw.replace(/\s+$/, "") == "******") {
            $("#password_error_1").text("Write correct password.");
            $("#pass1").css("border", "2px solid #B94A39");
            $("#password_error_1").css("display", "block");
            logerror++
        } else {
            $("#pass1").css("border", "0px");
            $("#password_error_1").css("display", "none")
        }
        if (logerror == 0) {
            $.ajax({
                url: "/users/check_user",
                type: "POST",
                data: {
                    email: login,
                    password: psw
                },
                dataType: "json",
                async: false,
                success: function(e) {
                    if (e.all_res == 0) {
                        logerror++;
                        if (e.check_res >= 1) {
                            error_text = "Incorrect password. Please try again."
                        } else {
                            error_text = "Unrecognized email address. Please register for an account."
                        }
                        $("#password_error_1").text(error_text);
                        $("#password_error_1").show()
                    } else {
                        $("#password_error_1").text("");
                        $("#password_error_1").hide()
                    }
                }
            })
        }
        if (logerror > 0) {
            return false
        } else {
            $("#login").closest("form").submit()
        }
    });
    $("#forgot_password").submit(function(e) {
        e.preventDefault();
        $("#f_response").addClass("hidden");
        forgotmail = $(this).find("input").val();
        if (forgotmail.length == 0) {
            $(this).find("label").css("display", "block");
            $(this).find("input").css("border", "2px solid #B94A39")
        } else {
            $.ajax({
                type: "POST",
                url: "/users/forgot_password",
                data: {
                    forgotemail: forgotmail
                },
                success: function(e) {
                    $("#f_response").html(e);
                    $("#f_response").removeClass("hidden")
                }
            })
        }
    });
    $("#forgot_password input").focus(function() {
        $(this).next().css("display", "none");
        $(this).css("border", "0px");
        $("#f_response").addClass("hidden")
    });
    $("#skip").click(function(e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "/registration/register",
            data: {
                email: email_address.val(),
                password: mypassword,
                city: $("#city option:selected").val()
            },
            success: function(e) {
                var t = "";
                console.log(e);
                if (e == 1) {
                    url = $("#url").val();
                    t = $("#slug").val();
					console.log("url in skip == "+url);
                    if (t != "") {
                        window.location = url + "experiences/" + t
                    } else {
                        window.location = url + "experience/lists/?signup=true"
                    }
                } else {}
            }
        })
    });

	$(".header_loc").on('click',function(){
		var v = $(this).attr('data-page_loc');
		$.ajax({
            type: "POST",
            url: "/login/set_reservation_location",
            data: {resv_loc : v},
            success: function(e) {
                //console.log(e);
            }
        });
	});
})