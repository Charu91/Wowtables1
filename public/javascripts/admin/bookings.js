(function($){
  'use strict';
    $(document).on('ready', function(){
      $('#unbookings').DataTable({
        "order": [], //for getting latest on top
        columnDefs: [{ targets: 'no-sort', orderable: false }],
        "scrollX": true
      });

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

        $('#all_bookings').DataTable({
            "order": [], //for getting latest on top
            columnDefs: [{ targets: 'no-sort', orderable: false }],
            "scrollX": true
        });

        $('#todaybookings').DataTable({
            "order": [], //for getting latest on top
            columnDefs: [{ targets: 'no-sort', orderable: false }],
            "scrollX": true
        });

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
            //console.log(reservId);
            $('#reserv_status').val(reservStatus);
            $('#reserv_id').val(reservId);
            $('#adminComments').modal('show');
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
