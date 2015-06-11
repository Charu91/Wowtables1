$(document).ready(function(){

    $('.ios-switch:first').on('click',function(){
        if ($('.ios-switch:first').hasClass('on')) {
            $('.if-a-la-cart').show();
        }
        else{
            $('.if-a-la-cart').hide();
        }
    });

    $('.text-redactor').redactor({
    });

    var substringMatcher = function(strs) {
        return function findMatches(q, cb) {
            var matches, substrRegex;

            // an array that will be populated with substring matches
            matches = [];

            // regex used to determine if a string contains the substring `q`
            substrRegex = new RegExp(q, 'i');

            // iterate through the pool of strings and for any string that
            // contains the substring `q`, add it to the `matches` array
            $.each(strs, function(i, str) {
                if (substrRegex.test(str)) {
                    // the typeahead jQuery plugin expects suggestions to a
                    // JavaScript object, refer to typeahead docs for more info
                    matches.push({ value: str });
                }
            });

            cb(matches);
        };
    };

    var states = ['Alabama', 'Alaska', 'Arizona', 'Arkansas', 'California',
        'Colorado', 'Connecticut', 'Delaware', 'Florida', 'Georgia', 'Hawaii',
        'Idaho', 'Illinois', 'Indiana', 'Iowa', 'Kansas', 'Kentucky', 'Louisiana',
        'Maine', 'Maryland', 'Massachusetts', 'Michigan', 'Minnesota',
        'Mississippi', 'Missouri', 'Montana', 'Nebraska', 'Nevada', 'New Hampshire',
        'New Jersey', 'New Mexico', 'New York', 'North Carolina', 'North Dakota',
        'Ohio', 'Oklahoma', 'Oregon', 'Pennsylvania', 'Rhode Island',
        'South Carolina', 'South Dakota', 'Tennessee', 'Texas', 'Utah', 'Vermont',
        'Virginia', 'Washington', 'West Virginia', 'Wisconsin', 'Wyoming'
    ];

    $('#the-basics .typeahead').typeahead({
            hint: true,
            highlight: true,
            minLength: 1
        },
        {
            name: 'states',
            displayKey: 'value',
            source: substringMatcher(states)
        });

    $(".typeahead").on("focusout",function(){
        if(states.indexOf($(".typeahead.form-control.tt-input").val())>0){
            //Create Button
            $(".location-tab>.col-md-12")
                .append("<div class='btn btn-primary mb-xs mr-xs mt-xs'>" +
                $(".typeahead.form-control.tt-input").val() +
                "<span class='fa fa-close' ></span> "+
                "</div>");
            console.log("Button Creation",$(".typeahead.form-control.tt-input").val());
        }
        //console.log($(".typeahead.form-control.tt-input").val());
    });


    var markdownTemplate =  '### Menu Title ' + '\n' +
        '**The Main Title Description**'+ '\n' +
        '#### Section ( Appetizers,Dessert,etc )'+ '\n' +
        '##### Sub Menu'+ '\n' +
        '_The Appetizer Description_'+ '\n' +
        '###### Item Title'+ '\n' +
        '**veg**,**non-veg**,**specials**'+ '\n' +
        '_Item Description_';


    new Vue({
        el: '#editor',
        data: {
            input: markdownTemplate
        },
        filters: {
            marked: marked
        }
    });


    new Vue({
        el: '#menuPicksEditor',
        data: {
            input: markdownTemplate
        },
        filters: {
            marked: marked
        }
    });

    new Vue({
        el: '#expertTipsEditor',
        data: {
            input: markdownTemplate
        },
        filters: {
            marked: marked
        }
    });

    new Vue({
        el: '#termsConditionsEditor',
        data: {
            input: markdownTemplate
        },
        filters: {
            marked: marked
        }
    });

    $('#title').on('click',function(){
        $('#title').slugIt({
            output: '#slug'
        });
    });

    $('#description').redactor({
        minHeight: 300
    });

    $('.redactor-text').redactor({
        minHeight: 150
    });


    $('#terms_conditions').redactor({
        minHeight: 150
    });

    $.fn.findNext = function(selector, steps, scope)
    {
        // Steps given? Then parse to int
        if (steps)
        {
            steps = Math.floor(steps);
        }
        else if (steps === 0)
        {
            // Stupid case :)
            return this;
        }
        else
        {
            // Else, try the easy way
            var next = this.next(selector);
            if (next.length)
                return next;
            // Easy way failed, try the hard way :)
            steps = 1;
        }

        // Set scope to document or user-defined
        scope = (scope) ? $(scope) : $(document);

        // Find kids that match selector: used as exclusion filter
        var kids = this.find(selector);

        // Find in parent(s)
        hay = $(this);
        while(hay[0] != scope[0])
        {
            // Move up one level
            hay = hay.parent();
            // Select all kids of parent
            //  - excluding kids of current element (next != inside),
            //  - add current element (will be added in document order)
            var rs = hay.find(selector).not(kids).add($(this));
            // Move the desired number of steps
            var id = rs.index(this) + steps;
            // Result found? then return
            if (id > -1 && id < rs.length)
                return $(rs[id]);
        }
        // Return empty result
        return $([]);
    }


    $('.curators-select-box').select2({
        'placeholder': 'Select Value',
         data: curatorsList
    });

    $('.flags-select-box').select2({
        'placeholder': 'Select Value',
         data: flagsList
    });

    $('.restaurants-select-box').select2({
        'placeholder': 'Select Value',
        data: restaurantsList
    });

    $('.localities-select-box').select2({
        'placeholder': 'Select Value',
        data: localitiesList
    });

    $("#experience_seo_details_tab").click(function(){
        console.log("called");
        var v = $("#experience_name").val();
        var seo_title = 'WowTables: '+v;
        var seo_desc = 'Reserve '+v+'. Exclusive curated set menus for fine dining. Find information, address, maps, photos, menu and reviews';

        if(v != ""){
            $("#experience_seo_title").val(seo_title);
            $("#seo_meta_desciption").val(seo_desc);
        }
    });

    $("#restaurant_seo_tab").click(function(){
       var rest_name = $('.select-restaurant').select2('data').text;
        var location_name =  $('.select-location').select2('data').text;
        var cuisine = $(".restaurantCuisines").text();
        //console.log("cuisine = "+cuisine);
        var cuisine_split = cuisine.split(" ");
        var cusines_array = [];
        $.each(cuisine_split,function(key,val){
            //console.log("key = "+key+" , value = "+val);
            if(val != ""){
                cusines_array.push(val);
            }
        });
        $.ajax({
            method: 'POST',
            url: '/admin/restaurants/locations/getCity/'+location_name,
            type:'json',
            data:{locality_value:location_name},
            success: function(response){
                var data = jQuery.parseJSON(response);
                if(rest_name != "" && location_name != "" && cuisine != "" && data.ancestor_name != ""){
                    //console.log("if not null , "+rest_name+' , '+data.ancestor_name+" , "+location_name+" , "+cusines_array[0]+' cuisine in '+data.ancestor_name+' , '+location_name);
                    $("#restaurant_seo_title").val('WowTables: '+rest_name+', '+location_name+", "+data.ancestor_name);
                    //$("#seo_meta_description").val(cusines_array[0]+' cuisine in '+data.ancestor_name+' , '+location_name);
                    $("#seo_meta_description").val("Reserve a table at "+rest_name+". Enjoy "+cusines_array[0]+" cuisine in "+location_name+", "+data.ancestor_name+". Find information, curator's suggestions, maps, address, photos and reviews");
                } else {
                    //console.log("if null");
                    $("#restaurant_seo_title").val('');
                    $("#seo_meta_description").val('');
                }


            }
        }).fail(function (jqXHR) {
            console.log(jqXHR);
        });
    });


});

