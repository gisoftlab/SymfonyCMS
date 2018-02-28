/*!
 * GISOFT  CUSTOM JS
 * Damian Ostraszewski
 * @requires jQuery v1.9.0 or later 
 *  
 */

// 1 - START DROPDOWN SLIDER SCRIPTS ------------------------------------------------------------------------

$(document).ready(function () {
    $(".showhide-account").click(function () {
        //$(".account-content").slideToggle("fast");
        $(".account-content").slideDown("fast");
        $(this).toggleClass("active");
        return false;
    });
});

$(document).ready(function () {
    $(".action-slider").click(function () {
        $("#actions-box-slider").slideToggle("fast");
        $(this).toggleClass("activated");
        return false;
    });                 
});

//  END ----------------------------- 1

// 2 - START LOGIN PAGE SHOW HIDE BETWEEN LOGIN AND FORGOT PASSWORD BOXES--------------------------------------

$(document).ready(function () {
	$(".forgot-pwd").click(function () {
	$("#loginbox").hide();
	$("#forgotbox").show();
	return false;
	});

});

$(document).ready(function () {
	$(".back-login").click(function () {
	$("#loginbox").show();
	$("#forgotbox").hide();
	return false;
	});
});

// END ----------------------------- 2



// 3 - MESSAGE BOX FADING SCRIPTS ---------------------------------------------------------------------

$(document).ready(function () {
    $("a.close").click(function () {
        $(this).parent().fadeOut("slow");
    });    
});

// END ----------------------------- 3



// 4 - CLOSE OPEN SLIDERS BY CLICKING ELSEWHERE ON PAGE -------------------------------------------------------------------------

$(document).bind("click", function (e) {
    if (e.target.id != $(".showhide-account").attr("class")) $(".account-content").slideUp();
});

$(document).bind("click", function (e) {
    if (e.target.id != $(".action-slider").attr("class")) $("#actions-box-slider").slideUp();
});
// END ----------------------------- 4
 
 
 
// 5 - TABLE ROW BACKGROUND COLOR CHANGES ON ROLLOVER -----------------------------------------------------------------------
/*
$(document).ready(function () {
    $('#product-table	tr').hover(function () {
        $(this).addClass('activity-blue');
    },
    function () {
        $(this).removeClass('activity-blue');
    });
});
 */
// END -----------------------------  5
 
 
 
 // 6 - DYNAMIC YEAR STAMP FOR FOOTER -----------------------------------------------------------------------

 $('#spanYear').html(new Date().getFullYear()); 
 
// END -----------------------------  6 
  

// checkbox styling script 

    $(function () {
        //$('input').checkBox();
        $('#toggle-all').click(function () {                
            if ($(this).is('.toggle-checked')) {
                $(this).removeClass('toggle-checked');

                $('#mainform input[type=checkbox]').attr('checked', false); 
            }
            else{
                $(this).addClass('toggle-checked');   
                $('#mainform input[type=checkbox]').attr('checked', true); 
            }                                               
            return false;
        });
    });