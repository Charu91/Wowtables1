(function($){

    'use strict';

    var signinForm = $("#adminSigninForm")
      , token = $("meta[name='_token']").attr('content');

    signinForm.validate({
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
    });

    signinForm.on('submit', function(e){
        var isvalidate = signinForm.valid();

        if(isvalidate)
        {
            e.preventDefault();

            var remember = ($('#rememberMe').prop('checked')) ? 1 : 0;
            var modalText = $('#signinPageError').find('.modal-text p');

            $.ajax({
                url: "/admin/login",
                method: "POST",
                data: {
                  email: $('#signinEmail').val(),
                  password: $('#signinPassword').val(),
                  remember: remember
                },
                headers: {
                    'X-XSRF-TOKEN': token
                }
            }).done(function(data){
                if(data.redirect_url !== undefined){
                    window.location.href = data.redirect_url;
                }else{
                    $.magnificPopup.open({
                        callbacks: {
                            beforeOpen: function(){
                                modalText.text('There was an unexpected Error. Please contact the administrator');
                            }
                        },
                        items: {
                            src: '#signinPageError'
                        },
                        type: 'inline',
                        preloader: false,
                        modal: true
                    });
                }
            }).fail(function( jqXHR ){
                $.magnificPopup.open({
                    callbacks: {
                        beforeOpen: function(){
                            if(jqXHR.responseJSON.message !== undefined){
                                modalText.text(jqXHR.responseJSON.message);
                            }else{
                                modalText.text('There was an unexpected Error. Please contact the administrator');
                            }
                        }
                    },
                    items: {
                        src: '#signinPageError'
                    },
                    type: 'inline',
                    preloader: false,
                    modal: true
                });
            });
        }
    });

    $(document).on('click', '.modal-dismiss', function (e) {
        e.preventDefault();
        $.magnificPopup.close();
    });


})(jQuery)