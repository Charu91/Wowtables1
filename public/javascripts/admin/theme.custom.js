(function( $ ){

    'use strict';

    var $html = $('html');


    $('#sidebarRightClose').on('click', function(evt){
        evt.stopPropagation();

        if($html.hasClass('sidebar-right-opened')){
            $html.removeClass('sidebar-right-opened');
        }
    });

})( jQuery );
