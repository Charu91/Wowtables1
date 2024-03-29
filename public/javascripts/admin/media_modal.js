(function($){

    'use strict';

    $(document).on('ready', function(){

        var $mediaModalBtn = $(".media-modal-btn");
        var $collectionMediaModalBtn = $(".collection-media-modal-btn");
        var $mediaModalBtnListing = $(".media-modal-btn-listing");
        var $mediaModalBtnGallery = $(".media-modal-btn-gallery");
        var $mediaModalBtnMobileListing = $(".media-modal-btn-mobile-listing");
        var $mediaModalBtnMobileListingExperiences = $(".media-modal-btn-mobile-listing-experience");
        var $mediaModalBtnWebCollectionImages = $(".media-modal-btn-web-collection-images");
        var $mediaModalBtnSidebarImages = $(".sidebar-media-modal-btn");
        var $mediaModalBtnEmailFooterPromotions = $(".media-modal-btn-email-footer-promotions");


        $('body').delegate('a.edit-media-link-modal','click', function () {
            var $mediaEditContentHolder = $("#mediaEditContentHolder"),
                $mediaEditContent = $('#mediaEditContent'),
                $mediaId = $(this).data('media_id');
            $.ajax({
                method: 'GET',
                url: '/admin/media/edit/' + $mediaId
            }).done(function (editHTML) {
                $mediaEditContent.html(editHTML);
                $mediaEditContentHolder.toggleClass('hide');
                $('#mediaTagsSelect').tagsinput({
                    maxTags: 6,
                    trimValue: true
                });
            }).fail(function (jqXHR) {
                console.log(jqXHR);
            });
        });

        $('body').delegate('#closeMediaContentHolder','click',function(){
            $("#mediaEditContentHolder").toggleClass('hide');
        });

        $mediaModalBtn.on('click',function(){

            var   mediaSelect = $(this).data('media-select')
                , mediaType = $(this).data('media-type')
                , galleryPosition = $(this).data('gallery-position');

            $.ajax({
                method: 'GET',
                url: '/admin/media/modal'
            }).done( function(result) {
                    $( "#mediaModalHolder" ).html( result );
                    $('#mediaModal').modal('show');
                    $('#selectMediaBtn').attr('data-gallery-position',galleryPosition);
                    $('#selectMediaBtn').attr('data-media-select',mediaSelect);
                    $('#selectMediaBtn').attr('data-media-type',mediaType);
                    $('#mediaModal').checkboxes('max', mediaSelect);
                    $("#selectMediaBtn").attr('disabled','disabled');
            }).fail(function (jqXHR) {
                console.log(jqXHR);
            });

        });

        $collectionMediaModalBtn.on('click',function(){

            var   mediaSelect = $(this).data('media-select')
                , mediaType = $(this).data('media-type')
                , galleryPosition = $(this).data('gallery-position');

            $.ajax({
                method: 'GET',
                url: '/admin/media/collection_modal'
            }).done( function(result) {
                    $( "#mediaModalHolder" ).html( result );
                    $('#mediaModal').modal('show');
                    $('#selectMediaBtn').attr('data-gallery-position',galleryPosition);
                    $('#selectMediaBtn').attr('data-media-select',mediaSelect);
                    $('#selectMediaBtn').attr('data-media-type',mediaType);
                    $('#mediaModal').checkboxes('max', mediaSelect);
                    $("#selectMediaBtn").attr('disabled','disabled');
            }).fail(function (jqXHR) {
                console.log(jqXHR);
            });

        });

        $mediaModalBtnSidebarImages.on('click',function(){
            console.log('called');
            var   mediaSelect = $(this).data('media-select')
                , mediaType = $(this).data('media-type')
                , galleryPosition = $(this).data('gallery-position');

            $.ajax({
                method: 'GET',
                url: '/admin/media/sidebar_modal'
            }).done( function(result) {
                    $( "#mediaModalHolder" ).html( result );
                    $('#mediaModal').modal('show');
                    $('#selectMediaBtn').attr('data-gallery-position',galleryPosition);
                    $('#selectMediaBtn').attr('data-media-select',mediaSelect);
                    $('#selectMediaBtn').attr('data-media-type',mediaType);
                    $('#mediaModal').checkboxes('max', mediaSelect);
                    $("#selectMediaBtn").attr('disabled','disabled');
            }).fail(function (jqXHR) {
                console.log(jqXHR);
            });

        });

        $mediaModalBtnEmailFooterPromotions.on('click',function(){
            console.log('called');
            var   mediaSelect = $(this).data('media-select')
                , mediaType = $(this).data('media-type')
                , galleryPosition = $(this).data('gallery-position');

            $.ajax({
                method: 'GET',
                url: '/admin/media/email_footer_promotions_modal'
            }).done( function(result) {
                    $( "#mediaModalHolder" ).html( result );
                    $('#mediaModal').modal('show');
                    $('#selectMediaBtn').attr('data-gallery-position',galleryPosition);
                    $('#selectMediaBtn').attr('data-media-select',mediaSelect);
                    $('#selectMediaBtn').attr('data-media-type',mediaType);
                    $('#mediaModal').checkboxes('max', mediaSelect);
                    $("#selectMediaBtn").attr('disabled','disabled');
            }).fail(function (jqXHR) {
                console.log(jqXHR);
            });

        });

        $mediaModalBtnListing.on('click',function(){

            var   mediaSelect = $(this).data('media-select')
                , mediaType = $(this).data('media-type')
                , galleryPosition = $(this).data('gallery-position');

            $.ajax({
                method: 'GET',
                url: '/admin/media/listing_modal'
            }).done( function(result) {
                    $( "#mediaModalHolder" ).html( result );
                    $('#mediaModal').modal('show');
                    $('#selectMediaBtn').attr('data-gallery-position',galleryPosition);
                    $('#selectMediaBtn').attr('data-media-select',mediaSelect);
                    $('#selectMediaBtn').attr('data-media-type',mediaType);
                    $('#mediaModal').checkboxes('max', mediaSelect);
                    $("#selectMediaBtn").attr('disabled','disabled');
            }).fail(function (jqXHR) {
                console.log(jqXHR);
            });

        });

        $mediaModalBtnWebCollectionImages.on('click',function(){

            var   mediaSelect = $(this).data('media-select')
                , mediaType = $(this).data('media-type')
                , galleryPosition = $(this).data('gallery-position');

            $.ajax({
                method: 'GET',
                url: '/admin/media/web_collection_modal'
            }).done( function(result) {
                    $( "#mediaModalHolder" ).html( result );
                    $('#mediaModal').modal('show');
                    $('#selectMediaBtn').attr('data-gallery-position',galleryPosition);
                    $('#selectMediaBtn').attr('data-media-select',mediaSelect);
                    $('#selectMediaBtn').attr('data-media-type',mediaType);
                    $('#mediaModal').checkboxes('max', mediaSelect);
                    $("#selectMediaBtn").attr('disabled','disabled');
            }).fail(function (jqXHR) {
                console.log(jqXHR);
            });

        });

        $mediaModalBtnGallery.on('click',function(){

            var   mediaSelect = $(this).data('media-select')
                , mediaType = $(this).data('media-type')
                , galleryPosition = $(this).data('gallery-position');

            $.ajax({
                method: 'GET',
                url: '/admin/media/gallery_modal'
            }).done( function(result) {
                $( "#mediaModalHolder" ).html( result );
                $('#mediaModal').modal('show');
                $('#selectMediaBtn').attr('data-gallery-position',galleryPosition);
                $('#selectMediaBtn').attr('data-media-select',mediaSelect);
                $('#selectMediaBtn').attr('data-media-type',mediaType);
                $('#mediaModal').checkboxes('max', mediaSelect);
                $("#selectMediaBtn").attr('disabled','disabled');
            }).fail(function (jqXHR) {
                console.log(jqXHR);
            });

        });

        $mediaModalBtnMobileListing.on('click',function(){

            var   mediaSelect = $(this).data('media-select')
                , mediaType = $(this).data('media-type')
                , galleryPosition = $(this).data('gallery-position');

            $.ajax({
                method: 'GET',
                url: '/admin/media/mobile_modal'
            }).done( function(result) {
                $( "#mediaModalHolder" ).html( result );
                $('#mediaModal').modal('show');
                $('#selectMediaBtn').attr('data-gallery-position',galleryPosition);
                $('#selectMediaBtn').attr('data-media-select',mediaSelect);
                $('#selectMediaBtn').attr('data-media-type',mediaType);
                $('#mediaModal').checkboxes('max', mediaSelect);
                $("#selectMediaBtn").attr('disabled','disabled');
            }).fail(function (jqXHR) {
                console.log(jqXHR);
            });

        });

        $mediaModalBtnMobileListingExperiences.on('click',function(){

            var   mediaSelect = $(this).data('media-select')
                , mediaType = $(this).data('media-type')
                , galleryPosition = $(this).data('gallery-position');

            $.ajax({
                method: 'GET',
                url: '/admin/media/mobile_exp_modal'
            }).done( function(result) {
                $( "#mediaModalHolder" ).html( result );
                $('#mediaModal').modal('show');
                $('#selectMediaBtn').attr('data-gallery-position',galleryPosition);
                $('#selectMediaBtn').attr('data-media-select',mediaSelect);
                $('#selectMediaBtn').attr('data-media-type',mediaType);
                $('#mediaModal').checkboxes('max', mediaSelect);
                $("#selectMediaBtn").attr('disabled','disabled');
            }).fail(function (jqXHR) {
                console.log(jqXHR);
            });

        });



        $('body').delegate('input[name="media[]"]','change',function () {
            var media = new Array();
            $('input[name="media[]"]:checked').each(function () {
                media.push(this.value);
            });
            if ( media.length > 0 )
            {
                $("#selectMediaBtn").removeAttr('disabled');
            } else {
                $("#selectMediaBtn").attr('disabled','disabled');
            }
        });

        $('body').delegate('#selectMediaBtn', 'click', function () {

            var   galleryPosition = $('#selectMediaBtn').data('gallery-position')
                , mediaType = $(this).data('media-type')
                , media = new Array();

            $('input[name="media[]"]:checked').each(function () {
                media.push(this.value);
            });

            if ( media.length > 0 )
            {
                $.ajax({
                    url: '/admin/media/fetch',
                    data: {
                        media : media,
                        media_type : mediaType
                    }}).done( function ( result ) {
                        //console.log("result == "+result);
                        $('#mediaModal').modal('hide');
                        $('.popup-gallery[data-gallery-position = '+galleryPosition+' ]').html( result );
                }).fail(function (jqXHR) {
                    console.log(jqXHR);
                });
            }

        });

        $('body').delegate('#gallery-images','click',function(){

            var gallery_images = new Array();
            $('input[name="gallery_images[]"]').each(function () {

                gallery_images.push(this.value)

            });
            console.log(gallery_images)
        });


        $('body').delegate('.media-modal-pagenum-btn','click',function(){

            var pagenum = $(this).data('media-pagenum');
            if(pagenum > 0) {
                $.ajax({
                    url: '/admin/media/?pagenum=' + pagenum + '&type=modal'
                }).done(function (result) {
                    $('#mediaModalFiles').html(result);
                }).fail(function (jqXHR) {
                    console.log(jqXHR);
                });
            }
        });

        $('body').delegate('.media-pagenum-btn','click',function(){

            var pagenum = $(this).data('media-pagenum');
            if(pagenum > 0) {
                $.ajax({
                    url: '/admin/media/?pagenum=' + pagenum
                }).done(function (result) {
                    $('#mediaModalFiles').html(result);
                }).fail(function (jqXHR) {
                    console.log(jqXHR);
                });
            }
        });

    });
})(jQuery);