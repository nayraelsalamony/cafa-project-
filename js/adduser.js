$(document).ready(function(){

    var current_fs, next_fs, previous_fs; //fieldsets
    var opacity;
    


    $(".next").click(function(){
    $("#second-fieldset").show();
    $("#first-fieldset").animate({opacity: 0}, {
        step: function(now) {
        // for making fielset appear animation
        opacity = 1 - now;
        
        $("#first-fieldset").css({
        'display': 'none',
        'position': 'relative'
        });
        $("#second-fieldset").css({'opacity': opacity});
        },
        duration: 600
        });
    });
    
    $(".previous").click(function(){
    $("#first-fieldset").show();
    $("#second-fieldset").animate({opacity: 0}, {
        step: function(now) {
        // for making fielset appear animation
        opacity = 1 - now;
        
       $("#second-fieldset").css({
        'display': 'none',
        'position': 'relative'
        });
        $("#first-fieldset").css({'opacity': opacity});
        },
        duration: 600
        });
    });
    
    $('.radio-group .radio').click(function(){
    $(this).parent().find('.radio').removeClass('selected');
    $(this).addClass('selected');
    });
    });