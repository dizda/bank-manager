$(function()
{
    unicorn.peity();
    $('[rel=tooltip]').tooltip();


    $('.span9').on('mouseenter', 'td.check .pointer', function(){
        if($(this).hasClass('disabled'))
        {
            $(this).find('i').removeClass('icon-ok');
            $(this).find('i').addClass('icon-remove');
        }
    });

    $('.span9').on('mouseleave', 'td.check .pointer', function(){
        if($(this).hasClass('disabled'))
        {
            $(this).find('i').removeClass('icon-remove');
            $(this).find('i').addClass('icon-ok');
        }
    });


    /* Pointer in AJAX */
    $('.span9').on('click', 'td.check .pointer', function(){

        var button  = $(this);
        var transId = $(this).parent().data('id');

        $.get("pointer/"+transId , function(data){

            if(data.pointer)
            {
                button.addClass('disabled');
            }else{
                button.removeClass('disabled');
                button.find('i').removeClass('icon-remove');
                button.find('i').addClass('icon-ok');
            }

        }, "json");

    });


    /* Exclude a transaction */
    $('.span9').on('click', 'td.check ul.dropdown-menu .exclude', function(e){
        e.preventDefault();


        var button  = $(this);
        var transId = $(this).parents('.btn-group').data('id');

        $.get("exclude/"+transId , function(data){

            var label = button.parents('tr').find('td.date .label-important');

            if(data.excluded)
            {
                label.show();
            }else{
                label.hide();
            }

        }, "json");

    });


    /* Fetch last month */
    $('.span9').on('click', 'a.getLastMonth', function(){

        var btn     = $(this);
        var account = btn.data('account');


        btn.addClass('disabled');

        $.get(account + '/transaction/'+btn.data('year')+'/'+btn.data('month') , function(data){

            btn.after(data);

            btn.remove();

        });

    });



});

unicorn = {
    // === Peity charts === //
    peity: function(){		
        $.fn.peity.defaults.line = {
            strokeWidth: 1,
            delimeter: ",",
            height: 24,
            max: null,
            min: 0,
            width: 50
        };
        $.fn.peity.defaults.bar = {
            delimeter: ",",
            height: 24,
            max: null,
            min: 0,
            width: 50
        };
        $(".peity_line_good span").peity("line", {
            colour: "#B1FFA9",
            strokeColour: "#459D1C"
        });
        $(".peity_line_bad span").peity("line", {
            colour: "#FFC4C7",
            strokeColour: "#BA1E20"
        });	
        $(".peity_line_neutral span").peity("line", {
            colour: "#CCCCCC",
            strokeColour: "#757575"
        });
        $(".peity_bar_good span").peity("bar", {
            colour: "#459D1C"
        });
        $(".peity_bar_bad span").peity("bar", {
            colour: "#BA1E20"
        });	
        $(".peity_bar_neutral span").peity("bar", {
            colour: "#757575"
        });
    },

    // === Tooltip for flot charts === //
    flot_tooltip: function(x, y, contents) {
			
        $('<div id="tooltip">' + contents + '</div>').css( {
            top: y + 5,
            left: x + 5
        }).appendTo("body").fadeIn(200);
    }
}