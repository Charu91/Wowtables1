(function($){
  'use strict';
    $(document).on('ready', function(){

      $("#vendor").click(function(){
        var vendor_id = "v"+$(this).data('vendor-id');
        var vendorReservationIds = decodeURIComponent($("#vendor_reservation_id").val());
        var vendor_reservation_id = $.parseJSON(vendorReservationIds);
        var resevation_ids = JSON.stringify(vendor_reservation_id[vendor_id]);

        $("#finalvendor_id").val(vendor_id);
        $("#finalreserv_ids").val(resevation_ids);
        $( "#finalpdf").attr("action","/admin/invoice/vendor/pdf");
        $( "#finalpdf" ).submit();

        /*var formData = {reservation_ids:resevation_ids,vendor_id:vendor_id}; //Array
        $.post("/admin/invoice/pdf",formData,function(data, status){
          var w = window.open();
          $(w.document.body).html(data);
        });*/
      });

      $("#vendor_location").click(function(){
        var vendor_location_id = "vl"+$(this).data('vendor-location-id');
        var vendor_id = "v"+$(this).data('vendor-location-id');

        var vendorLocationReservationIds = decodeURIComponent($("#vendor_location_reservation_id").val());
        var vendorlocation_reservation_id = $.parseJSON(vendorLocationReservationIds);
        var resevation_ids = JSON.stringify(vendorlocation_reservation_id[vendor_location_id]);

        $("#finalvendor_id").val(vendor_id);
        $("#finalvendor_location_id").val(vendor_location_id);
        $("#finalreserv_ids").val(resevation_ids);
        $( "#finalpdf").attr("action","/admin/invoice/vendor/location/pdf");
        $( "#finalpdf" ).submit();

        /*var formData = {reservation_ids:resevation_ids,vendor_id:vendor_id}; //Array
         $.post("/admin/invoice/pdf",formData,function(data, status){
         var w = window.open();
         $(w.document.body).html(data);
         });*/
      });

    });
})(jQuery);
