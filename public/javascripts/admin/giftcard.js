(function($){
	
	$("#edit_member").click(function() {
       var giftId = $("#gift_id").val();
        
       
            $.ajax({
                url: "/admin/admingiftcards/detailGiftCard",
                type: "POST",
                dataType: "json",
                data: {
                    GIft_CardId: giftId
                },
                success: function(e) {
                    $("#add_gift_card_detail").removeClass("hidden");
					 $("#gift-card-id").val(e.gift_card_id);
					 $("#buyer_name").val(e.buyer);
                      $("#buyer_contact").val(e.buyer_contact);
                      $("#gift_details").val(e.buyer_detail);
                      $("#number_of_guest").val(e.number_of_guest);
                      $("#cash_value").val(e.cash_value);
                      $("#name_of_giftee").val(e.name_of_giftee);
                      $("#giftee_detail").val(e.contact_of_giftee);
                      $("#expire_date").val(e.gift_card_expire_date);
                      $("#redeemed").val(e.redeem);
                      $("#credit_remaining").val(e.credit_remaining);
                      $("#gift_note").val(e.notes);
                      $("#giftee_contact_email").val(e.giftee_email);
                      $("#buyer_contact_email").val(e.buyer_email);
                      $("#card_id").val(e.id);
                }
            })
       
    });
	
	$( "#expire_date" ).datepicker({
		showOtherMonths: true,
		selectOtherMonths: true,
		minDate: 0
	});
	
	
	$("#GiftCardId").keyup(function() {
        $(".show_in_input_no").css('display','none');
        $(".show_in_input_yes").css('display','none');
        var CardId = $(this).val();
      
            $(".small-ajax-loader").show();
            $("#last_reserv").hide();
            $("#upcomings_reservs").html("");
            $("#pref_reservs").html("");
            $(".member-reservation-wrap li:first-child a").click();
            $.ajax({
                url: "/admin/admingiftcards/checkGiftCard",
                type: "POST",
                dataType: "json",
                data: {
                    CardId: CardId
                },
                success: function(e) {
                    $(".small-ajax-loader").hide();
                    if (e.error == 0) {
                        $("#email_error").html("");
                        if (e.exists) {
                         $(".show_in_input_no").css('display','none');
                            $(".show_in_input_yes").css('display','inline');
                            $("#Buyer_name").val(e.gift_card.buyer);
                            $("#Buyer_contact").val(e.gift_card.buyer_contact);
                           $("#add_gift_card_detail").addClass("hidden");
                           
                           $("#gift_id").val(e.gift_card.id)
							 $("#edit_member").removeClass("hidden");
                        } else {
                            $(".show_in_input_no").css('display','inline');
                            $(".show_in_input_yes").css('display','none');
                            $("#add_member").removeClass("hidden");
                            $("#add_gift_card_detail").removeClass("hidden");
                            $("#customerName").val("");
                            $("#customerNumber").val("");
							 $("#gift-card-id").val(CardId)
							 $("#Buyer_name").val("");
                            $("#Buyer_contact").val("");
                            $("#customerCity option").each(function(e, t) {
                                $(this).removeAttr("selected")
                            });
                            $("#user_id").val("")
                        }
                    } else {
                       $(".show_in_input_no").css('display','inline');
                        $(".show_in_input_yes").css('display','none');
                         $("#edit_member").removeClass("hidden");
						$("#add_gift_card_detail").addClass("hidden");
                        $("#gift-card-id").val(CardId)
                         $("#Buyer_name").val("");
                            $("#Buyer_contact").val("");
                    }
                }
            })
        
    });
	
	$("#add_gift_card").click(function() {
        var e = false;
		var gift_card_id =$("#card_id").val();
		var buyer_contact_email =$("#buyer_contact_email").val();
        var giftId = $("#gift-card-id").val();
        var buyer_name = $("#buyer_name").val();
        var buyer_contact = $("#buyer_contact").val();
        var gift_details = $("#gift_details").val();
        var number_of_guest = $("#number_of_guest").val();
        var cash_value = $("#cash_value").val();
        var name_of_giftee = $("#name_of_giftee").val();
        var giftee_detail = $("#giftee_detail").val();
		var giftee_contact_email =$("#giftee_contact_email").val();
        var expire_date = $("#expire_date").val();
        var redeemed = $("#redeemed option:selected").val();
        var credit_remaining = $("#credit_remaining").val();
        var gift_note = $("#gift_note").val();
       if (gift_card_id == "") {
            gift_card_id="0";
        }
	
	
        fullname_regex = /(^(?:(?:[a-zA-Z]{2,4}\.)|(?:[a-zA-Z]{2,24}))){1} (?:[a-zA-Z]{2,24} )?(?:[a-zA-Z']{2,25})(?:(?:, [A-Za-z]{2,6}\.?){0,3})?/gim;
        if (buyer_name == "") {
            $("#buyer_error").text("Please enter Buyer first and last name");
            e = true
        } else if (!fullname_regex.test(buyer_name)) {
            $("#buyer_error").text("Please enter Buyer first and last name");
            e = true
        } else {
            $("#buyer_error").empty()
        }
        if (buyer_contact == "" || buyer_contact.length < 10) {
            e = true;
            $("#buyer_contact_error").text("Please enter a valid telephone number.")
        } else if (!$.isNumeric(buyer_contact)) {
            $("#buyer_contact_error").text("Please enter a valid telephone number.")
        } else {
            $("#buyer_contact_error").empty()
        }
		if (buyer_contact_email == "") {
            e = true;
            $("#buyer_contact_email_error").text("Please enter email")
        } else {
            $("#buyer_contact_email_error").empty()
        }
		if (giftee_contact_email == "") {
            e = true;
            $("#giftee_contact_email_error").text("Please enter email")
        } else {
            $("#giftee_contact_email_error").empty()
        }
		
		if (giftId == "") {
            e = true;
            $("#giftId_error").text("Please enter  Gift Id")
        } else {
            $("#giftId_error").empty()
        }
		 if (gift_details == "") {
            e = true;
            $("#gift_detail_error").text("Please enter  Gift Details")
        } else {
            $("#gift_detail_error").empty()
        }
		 if (number_of_guest == "") {
            e = true;
            $("#guest_no_error").text("Please enter  Number of Guests")
        } 
		else if (!$.isNumeric(number_of_guest)) {
            $("#guest_no_error").text("Please enter a valid Guests number.")
        } 
		else {
            $("#guest_no_error").empty()
        }
		 if (cash_value == "") {
            e = true;
            $("#cash_value_error").text("Please enter  Cash Value")
        } else {
            $("#cash_value_error").empty()
        }
		 if (name_of_giftee == "") {
            e = true;
            $("#gift_no_error").text("Please enter a name of Giftee")
        } else {
            $("#gift_no_error").empty()
        }
		if (giftee_detail == "") {
            e = true;
            $("#giftee_detail_error").text("Please enter a Giftee Detail")
        } else {
            $("#giftee_detail_error").empty()
        }
		if (expire_date == "") {
            e = true;
            $("#gift_expire_error").text("Please enter a Giftee Expite Date")
        } else {
            $("#gift_expire_error").empty()
        }
		if (redeemed == "") {
            e = true;
            $("#gift_redeemed_error").text("Please enter a Status")
        } else {
            $("#gift_redeemed_error").empty()
        }
		if (credit_remaining == "") {
            e = true;
            $("#gift_credit_remaining_error").text("Please enter a Credit Remaining.")
        } else {
            $("#gift_credit_remaining_error").empty()
        }
		
        if (!e) {
            $.ajax({
                url: "/admin/admingiftcards/addGiftCard",
                type: "POST",
                dataType: "json",
                data: {
                    GiftId: giftId,
                    Buyer_name: buyer_name,
                    Gift_details: gift_details,
                    Buyer_contact: buyer_contact,
                    Number_of_guest: number_of_guest,
                    Cash_value: cash_value,
                    Name_of_giftee: name_of_giftee,
                    Giftee_detail: giftee_detail,
                    Expire_date: expire_date,
                    Redeemed: redeemed,
                    Credit_remaining: credit_remaining,
                    Gift_note: gift_note,
                    Buyer_contact_email: buyer_contact_email,
                    Giftee_contact_email: giftee_contact_email,
                    Gift_card_id: gift_card_id
                },
				
                success: function(e) {
                    $("#success_alert").remove();
                    var t = '<div class="alert alert-success alert-dismissable" id="success_alert">';
                    t += '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
                    t += e.success_message + "</div>";
                    $(".cms-title").before(t);
					
					 $("#gift-card-id").val("");
					 $("#buyer_name").val("");
                      $("#buyer_contact").val("");
                      $("#gift_details").val("");
                      $("#number_of_guest").val("");
                      $("#cash_value").val("");
                      $("#name_of_giftee").val("");
                      $("#giftee_detail").val("");
                      $("#expire_date").val("");
                      $("#redeemed").val("");
                      $("#credit_remaining").val("");
                      $("#gift_note").val("");
                      $("#card_id").val("");
                   
                }
            })
        }
    });
	})(jQuery);
	// code end here 