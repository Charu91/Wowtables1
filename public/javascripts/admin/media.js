(function($){

    "use strict";

    $(document).on('ready', function(){

        var token = $("meta[name='_token']").attr('content')
          , $html = $('html');

        if( $('#mediaDropZone').length > 0) {
            var myDropzone = new Dropzone("#mediaDropZone", {
                init: function () {
                    this.on('success', function (file, response) {
                        myDropzone.removeFile(file);
                    });

                    this.on('error', function (file, error) {
                        myDropzone.removeFile(file);
                    });
                },
                url: "/admin/media",
                method: "POST",
                maxFilesize: 10,
                paramName: 'media',
                headers: {
                    'X-XSRF-TOKEN': token
                }
            });
        }

        $('.sidebar-right').on('click', '#updateMediaBtn', function () {

            var $btn = $(this).button('loading')
              , name = $('#mediaName').val()
              , title = $('#mediaTitle').val()
              , alt = $('#mediaAlt').val()
              , tags = $('#mediaTagsSelect').tagsinput('items')
              , media_id = $(this).data('media_id');

            $.ajax({
                method: 'PUT',
                url: '/admin/media/' + media_id,
                data: {
                    name: name,
                    title: title,
                    alt: alt,
                    tags: tags
                },
                headers: {
                    'X-XSRF-TOKEN': token
                }
            }).done(function(data){
                if(data.status === 'success'){
                    $btn.button('reset')
                    if($html.hasClass('sidebar-right-opened')){
                        $html.removeClass('sidebar-right-opened');
                    }
                }else{
                    console.log(data);
                }
            }).fail(function(jqXHR){
                console.log(jqXHR);
            });

        });
    });

    $('#mediaFilterSelect').select2({
        width: 'resolve',
        multiple: 'true',
        maximumSelectionSize: 3,
        minimumInputLength: 2,
        placeholder: "Enter either part of the title or a tag",
        ajax: {
            url: '/admin/media/search',
            dataType: 'json',
            quietMillis: 250,
            data: function (term, page) {
                return {
                    q: term, //search term
                    page: page // page number
                };
            },
            results: function (data, page) {
                var more = (page * 30) < data.total_count; // whether or not there are more results available

                // notice we return the value of more so Select2 knows if more results can be loaded
                return { results: data.items, more: more };
            }
        }
    });
})(jQuery);
