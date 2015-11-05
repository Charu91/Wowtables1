(function($){
  'use strict';
    $(document).on('ready', function(){

      $('html').addClass('sidebar-left-collapsed');

      $('.nav-tabs').stickyTabs();

      /*$('#unbookings').DataTable({
        "order": [], //for getting latest on top
        columnDefs: [{ targets: 'no-sort', orderable: false }],
        "scrollX": true,
          "scrollY":        "300px",
          "scrollCollapse": true,
          /*"ajax": {
              "url": "/admin/bookings/unconfirmedbookings"
          }
      });*/

        $('#reserv_time').timepicker({
            showSeconds: true,
            showMeridian: false
        });

        $('#reserv_date').datepicker({
            format: 'dd/mm/yyyy',
            dateFormat: "dd/mm/yy",
            todayHighlight: true,
            autoclose: true
        });

        $('#order_completed').click(function() {
            if (this.checked) {
                var sure = confirm("Are you sure?");

                //this.checked = sure;
                //alert(sure.toString());
                var reservId = $(this).data('reserv-id');
                //alert(reservId);
                //throw Error;
                if(sure){
                    $.ajax({
                        url: '/admin/bookings/order_completed/'+reservId+'/1',
                        type: 'post',
                        success: function( data){
                            if(data == "success"){
                                alert("Order completed Status changed to Yes");
                            }
                        },
                        error: function( jqXhr, textStatus, errorThrown ){
                            console.log( errorThrown );
                        }
                    });
                }
            } else {
                var sure = confirm("Are you sure?");

                //this.checked = sure;
                //alert(sure.toString());
                var reservId = $(this).data('reserv-id');
                //alert(reservId);
                //throw Error;
                if(sure){
                    $.ajax({
                        url: '/admin/bookings/order_completed/'+reservId+'/0',
                        type: 'post',
                        success: function( data){
                            if(data == "success"){
                                alert("Order completed changed to No");
                            }
                        },
                        error: function( jqXhr, textStatus, errorThrown ){
                            console.log( errorThrown );
                        }
                    });
                }
            }
        });


        $('#unbookings').on('change','#zoho_booking_cancelled', function() {
            // From the other examples.
            //console.log("yo tp");
            if (this.checked) {
                var sure = confirm("Are you sure change the status in zoho?");
                var reservId = $(this).data('reserv-id');
                var reservType = $(this).data('reserv-type');
                var statusId = $(this).data('status-id');
                if(sure){
                    $.ajax({
                        url: '/admin/bookings/bookingcancel/'+reservId+'/'+reservType+'/'+statusId,
                        type: 'post',
                        success: function( data){
                            if(data == "success"){
                                alert("status has been updated to zoho");
                            }
                        },
                        error: function( jqXhr, textStatus, errorThrown ){
                            console.log( errorThrown );
                        }
                    });
                }
            }
        });


        /*$('#zoho_booking_cancelled').click(function() {
            if (this.checked) {
                var sure = confirm("Are you sure change the status in zoho to Booking Cancelled?");
                var reservId = $(this).data('reserv-id');
                var reservType = $(this).data('reserv-type');
                if(sure){
                    $.ajax({
                        url: '/admin/bookings/bookingcancel/'+reservId+'/'+reservType,
                        type: 'post',
                        success: function( data){
                            if(data == "success"){
                                alert("Status changed to Booking Cancelled In Zoho");
                            }
                        },
                        error: function( jqXhr, textStatus, errorThrown ){
                            console.log( errorThrown );
                        }
                    });
                }
            }

        });*/

        /*$('#all_bookings').DataTable({
            "order": [], //for getting latest on top
            columnDefs: [{ targets: 'no-sort', orderable: false }],
            "scrollX": true,
            "scrollY":        "300px",
            "scrollCollapse": true
        });

        $('#todaybookings').DataTable({
            "order": [], //for getting latest on top
            columnDefs: [{ targets: 'no-sort', orderable: false }],
            "scrollX": true,
            "scrollY":        "300px",
            "scrollCollapse": true
        });

        $('#postbookings').DataTable({
            "order": [], //for getting latest on top
            columnDefs: [{ targets: 'no-sort', orderable: false }],
            "scrollX": true,
            "scrollY":        "300px",
            "scrollCollapse": true
        });*/



        $(".dropdown").hover(
            function() {
                $('.dropdown-menu', this).stop( true, true ).fadeIn("fast");
                $(this).toggleClass('open');
                $('b', this).toggleClass("caret caret-up");
            },
            function() {
                $('.dropdown-menu', this).stop( true, true ).fadeOut("fast");
                $(this).toggleClass('open');
                $('b', this).toggleClass("caret caret-up");
            });

        $("#update-prices").on("click",function(event){

            //event.preventDefault();

            var totalBooked = $("#total_seated").val();
            if(totalBooked == ""){
                alert("Please enter Total Seated");
                return false;
            }

            var totalExpNo = $("#actual_experience_takers").val();
            if(totalExpNo == ""){
                alert("Please enter actual experience takers");
                return false;
            }

            var totalAlaNo = $("#actual_alacarte_takers").val();
            if(totalAlaNo == ""){
                alert("Please enter actual alacarte takers");
                return false;
            }
            var totalAddonNo = $("#no_of_people_addon").val();
            if(totalAddonNo == ""){
                alert("Please enter actual addon takers");
                return false;
            }
            var reservId = $("#update-prices").data('reserv-id');
            if(reservId == ""){
                alert("Please refresh the page");
                return false;
            }

            //check if addon info is present
            var addonDetails = $("#addons_details").val();
            //console.log(addonDetails);
            //return false;
            if(typeof addonDetails !== "undefined"){
                //console.log("asdas");return false;
                var addonArr = $.parseJSON($("#addons_details").val());
                var addonInfo = {};
                $.each(addonArr, function(key,value){
                    var validate = $("#addon_"+key).val();
                    if(validate == ""){
                        alert("Please enter addon takers");
                        return false;
                    } else {
                        addonInfo[key] = $("#addon_"+key).val();
                    }
                });
            }

            //console.log(JSON.stringify(addonInfo));
            //return false;


            $.ajax({
                url: '/admin/bookings/pricing',
                type: 'post',
                data: {bookedno:totalBooked,expnos:totalExpNo,alano:totalAlaNo,addoninfo:addonInfo,reservid:reservId},
                success: function( data){
                    var response = $.parseJSON(data)
                    console.log(response);
                    if(response.status == "success"){
                        $("#total_billing").val(response.total_billing);
                        $("#total_commission").val(response.total_commission);
                        alert("Prices are updated");
                    }
                },
                error: function( jqXhr, textStatus, errorThrown ){
                    console.log( errorThrown );
                }
            });

        });

        $(".change-status").on('click',function(e){
            e.preventDefault();
            var reservId = $(this).data('reserv-id');
            var reservStatus = $(this).data('reserv-status');
            var reservType = $(this).data('reserv-type');

            $('#reserv_status').val(reservStatus);
            $('#reserv_id').val(reservId);
            $('#reserv_type').val(reservType);
            if(reservStatus == 6){
                $('#adminComments').modal('show');
            } else {

                $("#admin_comments").submit();
                /*$.ajax({
                    url: '/admin/bookings/changestatus',
                    type: 'post',
                    data: {reserv_id:reservId,reserv_status:reservStatus,reserv_type:reservType},
                    success: function(data){
                        if(data == "success"){
                            alert("Status changed");
                        }
                    },
                    error: function( jqXhr, textStatus, errorThrown ){
                        console.log( errorThrown );
                    }
                });*/
            }

            //return false;
        });

        /*$("#adminSave").on('click',function(){
            e.preventDefault();

            $.ajax({
                url: '/admin/bookings/pricing',
                type: 'post',
                data: $('#admin_comments').serialize(),
                success: function( data){
                    var response = $.parseJSON(data)
                    //console.log(response.status);
                    if(response.status == "success"){
                        $("#total_billing").val(response.total_billing);
                        $("#total_commission").val(response.total_commission);
                        alert("Prices are updated");
                    }
                },
                error: function( jqXhr, textStatus, errorThrown ){
                    console.log( errorThrown );
                }
            });
        });*/


    });
})(jQuery);
