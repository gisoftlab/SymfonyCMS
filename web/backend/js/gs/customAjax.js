/*!
 * GISOFT  AJAX
 * Damian Ostraszewski
 * @requires jQuery v2.0.0 or later 
 *  
 */

var toggleAction = null;


function notification(note,msg,notificationTag)
{
    var message = "";
    
    if(note == "error")
        message +="<div class='flash error'>";        
    
    
    if(note == "ok")
        message +="<div class='flash ok'>";        
    
    
    if(note == "info")
        message +="<div class='flash info'>";        
    
    
    if(note == "notice")
        message +="<div class='flash notice'>";        

    message +="<div class='flash-content'>";
    message +="<div> "+msg+"</div>";
    message +="<a class='close'></a>";
    message +="</div>";
    message +="</div>";        
    
    //$("#FlashMessage").html(message);            
    $(notificationTag).html(message);            
    
    $("a.close").click(function () {
        $(this).parent().fadeOut("slow");
    });    
}

function loadProductAction(obj,loadBox,href,id)
{
    var action = href;
    var parent = $(obj).parent();            
    var content = $(loadBox);
    
    $.ajax({
        type: "GET",
        url: action,
        data: {
           modelId: id
        },
        dataType: "html",
        beforeSend: function(data){
            content.html(" loading...");                        
        },
        success: function(data){
            content.html(data);            
        },
        error:function(data){
           content.html(data);
        },
        complete:   function(data){
        }

    });

    return false;
}


function loadProduct(obj,loadBox,id)
{
    var action = $(obj).attr('href');
    
    loadProductAction(obj,loadBox,action,id); 
}

function loadActionHref(obj,loadBox,href,id,lang)
{
    var action = href;
    var parent = $(obj).parent();            
    var content = $(loadBox,parent);
            
    if($(content).html() != ""){        
        content.empty();   
        // load only when  is clicked another link with action 
        if(toggleAction == action)            
            return false;                            
    }
    else
        content.empty();             
    
                
    $.ajax({
        type: "GET",
        url: action,
        data: {
           modelId: id,
           lang:lang
        },
        dataType: "html",
        beforeSend: function(data){
            content.html(" loading...");                        
        },
        success: function(data){
            content.html(data);
            toggleAction = action;
        },
        error:function(data){
           content.html(data);
        },
        complete:   function(data){
        }

    });

    return false;
}

function loadAction(obj,loadBox,id,lang)
{
    
    // load-form
    var action = $(obj).attr('href');
    var parent = $(obj).parent();            
    var content = $(loadBox,parent);
    
    loadActionHref(obj,loadBox,action,id,lang)
               
}     


function loadQuickUploader(obj,loadBox,id)
{
    var action = $(obj).attr('href');
    var parent = $(obj).parent();            
    var content = $(loadBox,parent);
    
    if($(content).html() == null){
      
         var temp = $("tr",$(parent).parent()).next();
         
         var loadBoxId = loadBox.replace(".","#");
         content = $(loadBoxId+id);                  
    }

            
    if($(content).html() != ""){        
        content.empty();   
        // load only when  is clicked another link with action 
        if(toggleAction == action)            
            return false;                            
    }
    else
        content.empty();               

    $.ajax({
        type: "GET",
        url: action,
        data: {
            id: id,
            feedback: loadBox
        },
        dataType: "html",
        timeout: 4000,
        beforeSend: function(data){
            // loading...
            content.html(" loading...");                        
        },
        success: function(data){
            content.html(data);
            toggleAction = action;
        },
        error:function(data){
           content.html(data);
        },
        complete:   function(data){
        }

    });

    return false;
}

function loadUploader(object,action,id)
{
    var content = $(object);
    content.empty();        

    $.ajax({
        type: "GET",
        url: action,
        data: {
            id: id                             
        },
        dataType: "html",
        timeout: 4000,
        beforeSend: function(data){
            // loading...
            content.html(" loading...");                        
        },
        success: function(data){
            content.html(data);
        },
        error:function(data){
           content.html(data);
        },
        complete:   function(data){
        }

    });

    return false;
}     

function UploaderAction(action){
    var content = $('.content-gallery');
   // content.empty();      
    
    $.ajax({
        type: "GET",
        url: action,
        data: {
          
        },
        dataType: "html",
        timeout: 4000,
        beforeSend: function(data){
            // loading...
           // content.html(" loading...");                        
        },
        success: function(data){
            content.html(data);
        },
        error:function(data){   
           content.html(data);
        },
        complete:   function(data){
        }

    });

    return false;
}   

function DeleteImgAction(action){
    var content = $('.content-gallery');
   // content.empty();      
    
    $.ajax({
        type: "GET",
        url: action,
        data: {
          
        },
        dataType: "json",
        timeout: 4000,
        beforeSend: function(data){
            // loading...
           // content.html(" loading...");                        
        },
        success: function(data){
            content.html(data);
        },
        error:function(data){
           content.html(data);
        },
        complete:   function(data){
        }

    });

    return false;
}   