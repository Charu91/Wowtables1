var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    mix
        .styles([
            "vendor/bootstrap/css/bootstrap.css",
            "vendor/magnific-popup/magnific-popup.css",
            "vendor/bootstrap-colorpicker/css/bootstrap-colorpicker.css",
            "vendor/bootstrap-datepicker/css/datepicker3.css",
            "vendor/jquery-ui/css/ui-lightness/jquery-ui-1.10.4.custom.css",
            "vendor/select2/select2.css",
            "vendor/isotope/jquery.isotope.css",
            "vendor/bootstrap-multiselect/bootstrap-multiselect.css",
            "vendor/jquery-datatables-bs3/assets/css/datatables.css",
            "vendor/bootstrap-tagsinput/bootstrap-tagsinput.css",
            "vendor/bootstrap-timepicker/css/bootstrap-timepicker.css",
            "vendor/dropzone/css/basic.css",
            "vendor/dropzone/css/dropzone.css",
            "vendor/bootstrap-markdown/css/bootstrap-markdown.min.css",
            "vendor/summernote/summernote.css",
            "vendor/summernote/summernote-bs3.css",
            "redactor/redactor.css",
            "stylesheets/style.css",
            "stylesheets/media.css",
            "stylesheets/theme.css",
            "stylesheets/skins/default.css",
            "stylesheets/theme-custom.css"
        ], "public/css", "public")

        .scripts([
            "vendor/jquery/jquery.js",
            "vendor/jquery-browser-mobile/jquery.browser.mobile.js",
            "vendor/bootstrap/js/bootstrap.js",
            "vendor/nanoscroller/nanoscroller.js",
            "vendor/jquery-placeholder/jquery.placeholder.js",
            "vendor/isotope/jquery.isotope.js",
            "vendor/magnific-popup/magnific-popup.js",
            "vendor/dropzone/dropzone.js",
            "vendor/select2/select2.js",
            "vendor/bootstrap-tagsinput/bootstrap-tagsinput.js",
            "vendor/fuelux/js/spinner.js",
            "vendor/jquery-datatables/media/js/jquery.dataTables.min.js",
            "vendor/jquery-datatables-bs3/assets/js/datatables.js",
            "vendor/ios7-switch/ios7-switch.js",
            "vendor/bootstrap-multiselect/bootstrap-multiselect.js",
            "vendor/jquery-validation/jquery.validate.min.js",
            "vendor/bootstrap-datepicker/js/bootstrap-datepicker.js",
            "vendor/bootstrap-colorpicker/js/bootstrap-colorpicker.js",
            "vendor/jquery-maskedinput/jquery.maskedinput.js",
            "vendor/jquery-ui/js/jquery-ui-1.10.4.custom.js",
            "vendor/pnotify/pnotify.custom.js",
            "vendor/jquery-ui-touch-punch/jquery.ui.touch-punch.js",
            "vendor/bootstrap-timepicker/js/bootstrap-timepicker.js",
            "vendor/bootstrap-markdown/js/markdown.js",
            "vendor/bootstrap-markdown/js/to-markdown.js",
            "vendor/bootstrap-markdown/js/bootstrap-markdown.js",
            "vendor/summernote/summernote.js",
            "vendor/bootstrap-maxlength/bootstrap-maxlength.js",
            "vendor/slugit/jquery.slugit.js",
            "vendor/jquery-checkboxes/jquery.checkboxes-1.0.6.min.js",
            "javascripts/markdown/vue.min.js",
            "javascripts/markdown/marked.min.js",
            "javascripts/admin/theme.js",
            "javascripts/admin/theme.custom.js",
            "javascripts/admin/theme.init.js",
            "javascripts/admin/forms/examples.validation.js",
            "javascripts/admin/mediagallery.js",
            "javascripts/admin/media.js",
            "javascripts/admin/typeahead/typeahead.js",
            "vendor/gmaps/gmaps.js",
            "javascripts/admin/maps/snazzy.themes.js",
            "javascripts/admin/ui-elements/modals.js",
            "javascripts/admin/forms/advanced.form.js",
            "redactor/redactor.min.js",
            "javascripts/admin/custom.js",
            "javascripts/admin/locations.js",
            "javascripts/admin/users.js",
            "javascripts/admin/user_attributes.js",
            "javascripts/admin/vendor_attributes.js",
            "javascripts/admin/roles.js",
            "javascripts/admin/pages.js",
            "javascripts/admin/permissions.js",
            "javascripts/admin/experiences.js",
            "javascripts/admin/restaurants.js",
            "javascripts/admin/schedules.js",
            "javascripts/admin/events.js",
            "javascripts/admin/media_modal.js",
            "javascripts/admin/maps.js",
            "javascripts/admin/promotions.js",
            "javascripts/admin/curator.js"
        ], "public/js", "public")

        .version(["css/all.css","js/all.js"])

        .copy("public/vendor/jquery-datatables/media/images","public/build/images/")
        .copy("public/vendor/select2","public/build/css/")
        .copy("public/vendor/dropzone/images","public/build/images/")
        .copy("public/vendor/bootstrap-colorpicker/img/bootstrap-colorpicker/","public/build/img/bootstrap-colorpicker/")

    ;
});
