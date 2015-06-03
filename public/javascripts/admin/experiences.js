(function($){

    'use strict';

    $(document).on('ready', function(){

        $('#simpleExperienceDatePicker').datepicker({
            format: 'yyyy-mm-dd',
            startDate: '1d',
            todayHighlight: true,
            autoclose: true
        });

        $('.addDatepicker').datepicker({
            format: 'yyyy-mm-dd',
            startDate: '1d',
            todayHighlight: true,
            autoclose: true
        });

        $('#simpleExperienceTimePicker').timepicker({
            showSeconds: true,
            showMeridian: false
        });

        $("#experienceIncludes").redactor({
            minHeight: 300
        });

        $("#experienceInfo").redactor({
            minHeight: 300
        });

        $("#addonsMenu").redactor({
            minHeight: 300
        });

        var   token = $("meta[name='_token']").attr('content')
            , $addNewExperienceAddonBtn = $('#addNewExperienceAddonBtn')
            , $experienceAddonForm = $('#experienceAddonForm')
            , $experienceAddonHolder = $('#experienceAddonHolder')
            , $experienceAddonsTable = $('#experienceAddonsTable')
            , $experienceAddons = []
            , $experienceAddonBtn = $("#addExperienceAddonBtn")
            , $updateExperienceAddonBtn = $("#updateExperienceAddonBtn")
            , $cancelUpdateExperienceAddonBtn = $("#cancelUpdateExperienceAddonBtn")
            , $addonTitle = $('#addonTitle')
            , $addonReservationTitle = $('#addonReservationTitle')
            , $addonPriceBeforeTax = $('#addonPrice')
            , $addonPriceAfterTax = $('#addonPriceAfterTax')
            , $addonTax = $('#addon_tax')
            , $addonCommissionPerCover = $('#addon_commission_per_cover')
            , $addonCommissionOn = $('#addonCommissionOn')
            , $addonShortDescription = $('#addonShortDescription')
            , $addonsMenu = $('#addonsMenu')
            , $experiencePriceTypes = $('#experiencePriceTypes')
            , $experienceTaxes = $('#experienceTaxes')
            , $experienceCommissionOn = $('#experienceCommissionOn')
            , $experienceFlags = $('#experienceFlags')
            , $experienceCurators = $('#experienceCurators')




        $('#experiencesTable').DataTable();

        $addNewExperienceAddonBtn.on('click', function () {
            $experienceAddonForm.show();
            $(this).text($(this).text() == 'Add New Addon' ? 'Cancel Addon' : 'Add New Addon');
            $(this).hide();
            $cancelUpdateExperienceAddonBtn.show();
        });

        $experiencePriceTypes.multiselect({
            disableIfEmpty: true,
            enableFiltering: true,
            filterBehavior: 'text'
        });
        $experienceTaxes.multiselect({
            disableIfEmpty: true,
            enableFiltering: true,
            filterBehavior: 'text'
        });
        $experienceCommissionOn.multiselect({
            disableIfEmpty: true,
            enableFiltering: true,
            filterBehavior: 'text'
        });
        $experienceFlags.multiselect({
            disableIfEmpty: true,
            enableFiltering: true,
            filterBehavior: 'text'
        });
        $experienceCurators.multiselect({
            disableIfEmpty: true,
            enableFiltering: true,
            filterBehavior: 'text'
        });

        $experienceAddonForm.hide();
        $experienceAddonHolder.hide();
        $cancelUpdateExperienceAddonBtn.hide();

        $experienceAddonBtn.on('click',function() {

            if ( $addonTitle.val() != '' || $addonPriceBeforeTax.val() != '' || $addonPriceAfterTax.val() != '' || $addonShortDescription.val() != '' )
            {
                $experienceAddons.push({
                    addonTitle: $addonTitle.val(),
                    addonReservationTitle: $addonReservationTitle.val(),
                    addonPriceBeforeTax: $addonPriceBeforeTax.val(),
                    addonPriceAfterTax: $addonPriceAfterTax.val(),
                    addonTax: $addonTax.val(),
                    addonCommissionPerCover: $addonCommissionPerCover.val(),
                    addonCommissionOn: $addonCommissionOn.val(),
                    addonsMenu: $addonsMenu.val(),
                    addonDescription: $addonShortDescription.val()
                });

                emptyAddonForm();
                $experienceAddonForm.hide();
                $addNewExperienceAddonBtn.text('Add New Addon');
                $addNewExperienceAddonBtn.show();
                showHideExperienceAddonHolder();

                var tablebody;
                $.each($experienceAddons, function (key, value) {
                    key += 1;
                    tablebody +=
                        '<tr id="'+key+'">' +
                        '<td>' + key + '</td>' +
                        '<td >' + value.addonTitle + ' </td>' +
                        '<td >' + value.addonPriceBeforeTax + '</td>' +
                        '<td >' + value.addonPriceAfterTax + '</td>' +
                        '<td >' + value.addonDescription + '</td>' +
                        '<td>' +
                        '<a class="edit-addon-btn" data-addon-row-id="' + key + '" href="javascript:void(0);">' +
                        '<i class="fa fa-edit"></i></a>&nbsp;|&nbsp;' +
                        '<a class="delete-addon-btn" data-addon-row-id="' + key + '" href="javascript:void(0);">' +
                        '<i class="fa fa-trash-o"></i></a>' +
                        '</td>' +
                        '<input type="hidden" id="addonTitle_'+key+'" name="addons['+key+'][name]" value="'+value.addonTitle+'" />'+
                        '<input type="hidden" id="addon_reservation_title_'+key+'" name="addons['+key+'][reservation_title]" value="'+value.addonReservationTitle+'" />'+
                        '<input type="hidden" id="addonPriceBeforeTax_'+key+'" name="addons['+key+'][price]" value="'+value.addonPriceBeforeTax+'" />'+
                        '<input type="hidden" id="addon_tax_'+key+'" name="addons['+key+'][tax]" value="'+value.addonTax+'" />'+
                        '<input type="hidden" id="addonPriceAfterTax_'+key+'" name="addons['+key+'][post_tax_price]" value="'+value.addonPriceAfterTax+'" />'+
                        '<input type="hidden" id="addon_commission_per_cover_'+key+'" name="addons['+key+'][commission_per_cover]" value="'+value.addonCommissionPerCover+'" />'+
                        '<input type="hidden" id="addon_commission_on_'+key+'" name="addons['+key+'][commission_on]" value="'+value.addonCommissionOn+'" />'+
                        '<input type="hidden" id="addon_short_description_'+key+'" name="addons['+key+'][short_description]" value="'+value.addonDescription+'" />'+
                        '<input type="hidden" id="addon_addonsMenu_'+key+'" name="addons['+key+'][addonsMenu]" value="'+value.addonsMenu+'" />'+
                        '</tr>'
                        /*'<input id="addonInput_'+key+'" type="hidden" name="addons[]" value="'+
                            value.addonTitle +','+
                            value.addonPriceBeforeTax+','+
                            value.addonPriceAfterTax+','+
                            value.addonPriceBeforeTax+','+
                            value.addonInfo+
                        '">'*/
                });

                $("#experienceAddonsTable tbody").html(tablebody);
            }
        });

        $('body').delegate('.delete-addon-btn','click',function () {
            var id = $(this).data('addon-row-id');
            $('table#experienceAddonsTable tr#'+id).remove();
            $('#addonInput_'+id).remove();
            id = id - 1;
            $experienceAddons.splice(id,1);
            showHideExperienceAddonHolder();
        });

        $('body').delegate('.edit-addon-btn','click',function () {
            var id = $(this).data('addon-row-id');
            //console.log("title value == "+$('#addonTitle_'+id).val());
            $addonTitle.val( $('#addonTitle_'+id).val() );
            $addonReservationTitle.val( $('#addonTitle_'+id).val() );
            $addonTax.val($('#addon_tax_'+id).val());
            $addonPriceBeforeTax.val( $('#addonPriceBeforeTax_'+id).val() );
            $addonPriceAfterTax.val( $('#addonPriceAfterTax_'+id).val() );
            $addonCommissionPerCover.val( $('#addon_commission_per_cover_'+id).val() );
            $addonCommissionOn.val( $('#addon_commission_on_'+id).val() );
            $addonShortDescription.val( $('#addon_short_description_'+id).val() );
            $addonsMenu.val( $('#addon_addonsMenu_'+id).val() );
            $experienceAddonBtn.text('Update');
            $cancelUpdateExperienceAddonBtn.show();
            $experienceAddonForm.show();
            $addNewExperienceAddonBtn.hide();
        });

        function showHideExperienceAddonHolder () {
            if ( $experienceAddons.length == 0 ) {
                $experienceAddonHolder.hide();
            }
            if ( $experienceAddons.length > 0 ) {
                $experienceAddonHolder.show();
            }
        }

        function emptyAddonForm () {
            $addonTitle.val('');
            $addonPriceBeforeTax.val('');
            $addonPriceAfterTax.val('');
            $addonShortDescription.val('');
            //$addonInfo.val('');
        }

        $('body').delegate('#cancelUpdateExperienceAddonBtn','click',function () {
            emptyAddonForm();
            $addNewExperienceAddonBtn.text('Add New Addon');
            $experienceAddonBtn.text('Add Addon');
            $cancelUpdateExperienceAddonBtn.hide();
            $experienceAddonForm.hide();
            $addNewExperienceAddonBtn.show();
        });

        $('#experienceMenuHolder').hide();
        $('#addonsMenuHolder').hide();

        $('#insertExperienceMenu').on('click',function(){
            var menu = $('#expMenuText').val();
            var htmlmenu = $('.container').html();
            //console.log("menu == "+menu+"<br/> htmlmenu = "+htmlmenu); return false;
            $('#markdownmodal').modal('hide');
            $('#experienceMenuHolder').show();
            $('#expMenu').val(htmlmenu);
            $('#expMarkdownMenu').val(menu);
            $('#expMenuBtn').hide();
        });

        $('#insertAddonsMenu').on('click',function(){
            var menu = $('#addonsMenuText').val();
            $('#addonsMarkdownModal').modal('hide');
            $('#addonsMenuHolder').show();
            $('#addonsMenu').val(menu);
            $('#expMarkdownMenu').val(menu);
            $('#addOnsMenuBtn').hide();
        });

        var oldMarkdownSyntax = $('#oldMarkdownSyntax').val();

        if(oldMarkdownSyntax != ""){
            var getMarkdownhtml = markdown.toHTML( oldMarkdownSyntax );

            $("#oldExpMenu").val(getMarkdownhtml);
        }

        $(".inactive_addon").on('change',function(){
            var addon_id = $(this).data('addon_id');
            var addon_value = $(this).val();
            var set_addon_val;
            if(addon_value == 1){
                set_addon_val = 0;
            } else if(addon_value == 0){
                set_addon_val = 1;
            }
            $.ajax({
                method: 'POST',
                url: '/admin/experiences/deactive_Addon/'+addon_id,
                type:'json',
                data:{addon_value:addon_value},
                success: function(response){
                    var data = jQuery.parseJSON(response);
                    //alert(data.addon_status);
                    //console.log("asd = "+ response.addon_status+" , dsfdf = "+response);
                    if(data.status == "success"){
                        $(".addon_change_status_"+addon_id).html("("+data.addon_status+")");
                        //$(this).val(set_addon_val);
                    } else if(data.status == "failure"){
                        $(".addon_change_status_"+addon_id).html('Updating failed!');
                    }
                    location.reload();
                }
            }).fail(function (jqXHR) {
                console.log(jqXHR);
            });
        });






        $("#loc_exp").change(function(){
           var curr_val = $(this).val();
            /*$.ajax({
                method: 'POST',
                url: 'getVendorLocationsDetails',
                type:'json',
                data:{vendor_id:curr_val},
                success: function(response){
                    console.log('response == '+response);
                }
            }).fail(function (jqXHR) {
                console.log(jqXHR);
            });*/

            /* $.ajax({
                url: 'getVendorLocationsDetails',
                method: 'GET',
                data: {
                    vendor_id: curr_val
                }
            }).done(function(data){
                locationSlugInput.val(data.slug);
            }).fail(function(jqXHR){
                console.log(jqXHR);
            });
            /*
            .done(function (editHTML) {
             $editFlagHolder.html(editHTML);
             $editFlagHolder.show();
             })
             */
        });



        /*$('#addNewExperienceForm').submit(function(e){
            e.preventDefault();
            var input = {};
            $('#addNewExperienceForm :input').each(function() {
                input[this.name] = $(this).val();
            });

            $.ajax({
                url: '/admin/experiences',
                method: 'Post',
                data: {
                    data: input
                }
            }).done(function(data){
                console.log(data);
            }).fail(function(jqXHR){
                console.log(jqXHR);
            });
        });

        $( "#addNewExperienceForm" ).validate({
            rules: {
                min_people_per_reservation:{number: true},
                max_reservation_per_day:{number: true},
                max_people_per_reservation:{number: true},
                min_reservation_time_buffer:{number: true},
                max_reservation_per_time_slot:{number: true},
                max_reservation_time_buffer:{number: true},
                commision_per_reservation:{number: true},
                reward_points_per_reservation:{number: true},
                tax:{number: true},
                price_before_tax:{number: true},
                price_after_tax:{number: true},
            },
            ignore: [],
            highlight: function( label ) {
                $(label).closest('.form-group').removeClass('has-success').addClass('has-error');
            },
            success: function( label ) {
                $(label).closest('.form-group').removeClass('has-error');
                label.remove();
            },
            errorPlacement: function( error, element ) {
                var placement = element.closest('.input-group');
                if (!placement.get(0)) {
                    placement = element;
                }
                if (error.text() !== '') {
                    placement.after(error);
                }
            }
        });*/

    });
})(jQuery);