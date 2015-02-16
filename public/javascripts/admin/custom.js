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


    new Vue({
        el: '#editor',
        data: {
            input: '##Start Typing Here'
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

    $('#alacarte_terms_conditions').redactor({
        minHeight: 300
    });

    $('#terms_conditions').redactor({
        minHeight: 150
    });
});
