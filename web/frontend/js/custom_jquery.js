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
    $("#search_mini_form").mousemove(function () {
        
        if($("input[type=text]",this).val() == "Szukaj...") 
        $("input[type=text]",this).val("");        
    
        return false;
    });      
     $("#search_mini_form").mouseout(function () {
        if($("input[type=text]",this).val() == "")    
        $("input[type=text]",this).val("Szukaj...");        
        return false;
    });
    
     $("#newsletter_form").mouseover(function () {
        if($("input[type=email]",this).val() == "Podaj swój email...")  
        $("input[type=email]",this).val("");        
        return false;
    });      
     $("#newsletter_form").mouseout(function () {
         if($("input[type=email]",this).val() == "")   
        $("input[type=email]",this).val("Podaj swój email...");        
        return false;
    });
    
    $("#notification").click(function(){
        $(this).html("");
    })
            
});
