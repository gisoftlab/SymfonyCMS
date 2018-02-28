
/*!
 * GISOFT WIDGET
 * Damian Ostraszewski
 * version: 1.2 (01-10-2013)
 * @requires jQuery v2.0.0 or later 
 *  
 */

function GsWidget()
{
    var _I = this;
    
    if(this.hasJQuery()){    
        this.initialize();
        this.NOTIFICATION_SUCCESS = "ok";
        this.NOTIFICATION_ERROR = "error";
        this.NOTIFICATION_INFO = "info";
        this.html = "";

        /*
         *  Load widget
         */
        this.invoke("widget",{ lang: _I.language },"GET",function (result,statusText){           
            //var object = jQuery.parseJSON(result.responseText);                                        
            if(lang = $(_I.targetTag).attr("lang"))
                this.language = lang;

            $(_I.targetTag).empty();
            $(_I.targetTag).append( result.product.html ) ;                             
            $(_I.searchTag,$(_I.targetTag)).click(_I.submitSearch);

        })
    
    }
    
    /*
     *  search food
     */
     this.submitSearch = function(){                                      
            if(_I.validateText($(_I.inputType).val(),_I.validation_min,_I.validation_max)){
                var page = 1;
                if($(this).html())
                    page = $(this).html();
            
                $(_I.contentTag).empty();
                $(_I.detailsTag).empty();
                $(_I.notificationTag).empty();
                _I.invoke("search",{ lang: _I.language, search:$( _I.formTag).serializeArray(),page: page },"GET",function (result,statusText){               
                   $(_I.contentTag).append( result.product.html ) ;  
                   $(_I.detailsBtnTag,$(_I.contentTag)).click(_I.showDetails);
                   $(".pagination span a",$(_I.contentTag)).click(_I.submitSearch);
                })  
            }
            
             return false;
     }         
     
     /*
     *  show details
     */
     this.showDetails = function(){                     
            $(_I.detailsTag).empty();
            _I.invoke("details",{  id:$(this).attr("idx") },"GET",function (result,statusText){                                                                          
               $(_I.detailsTag).append( result.product.html ) ;                  
            })  
            
             return false;
     }               
}

GsWidget.prototype.initialize = function(options)    // initialize
{
   this.uriFile = '/app_dev.php';
   //this.uriFile = '';
   this.serverUri = "gisoft.local";             
   //this.serverUri = "dev.gisoft.com";   
   this.serviceUrl = "http://" + this.serverUri +this.uriFile+ "/api/gisoft/";       
        
   this.notificationTag = "#widget-gisoft-notification";
   this.async =true;
   this.loaderTag = "#gisoft-widget-loader";     
   this.targetTag = "#widget-gisoft";  
   this.contentTag = "#widget-gisoft-result";  
   this.detailsTag = "#widget-gisoft-details";  
   this.detailsBtnTag = ".widget-gisoft-details-btn";
   this.searchTag = "#widget-gisoft-search-btn";
   this.inputType = "#searcherFood";   
   this.formTag = "#widget-gisoft-search-food";
   
   this.validation_max = 20;
   this.validation_min = 3;
      
   this.language = "es"; 
   this.setOptions(options);
   this.toggleAction = "";
   this.data = "";
   
}

GsWidget.prototype.setOptions = function(options)    // set options
{
  if(options != undefined){
       if(options.uriFile != undefined)
            this.uriFile = options.uriFile;
       if(options.serverUri != undefined)
            this.serverUri = options.serverUri; 
       if(options.async != undefined)
            this.async = options.async;  
       if(options.targetTag != undefined)
            this.targetTag = options.targetTag;   
       if(options.language != undefined)
            this.language = options.language;    
       if(options.notificationTag != undefined)
            this.notificationTag  = options.notificationTag;         
  }
}


GsWidget.prototype.invoke = function(method, data, type, callback, complete, beforeSend) {
                 
     var postParams = data;//{modelId:id,lang:lang};                
               
     if (type == undefined)
         type = "GET";
            
     if (callback != undefined)
          this.calbeck = callback;
      
     if (complete != undefined)
          this.complete = complete; 
      
    if (beforeSend != undefined)
          this.beforeSend = beforeSend; 
                                      
    var result =  $.ajax({
        url: this.serviceUrl+method,        
        async: this.async, 
        data: postParams,
        type: type,
        processData: true,
        contentType: "application/json",
      //  timeout: 20000,        
        dataType: 'json',       
        beforeSend: this.beforeSend,      
        success: this.calbeck,        
        error: this.errorReporting,
        complete:   this.complete

    });        

    return result.responseText;
}

GsWidget.prototype.validateText = function(text, min, max)    // calbeck reporting AJAX
{
    if(text == ""){
        this.notification("error","Search can't be empty");        
    }else if( text.length < min){
        this.notification("error","Searching food is to short min  "+min+" chars");        
    }else if( text.length >= max){
        this.notification("error","Searching food is to long max  "+max+" chars");        
    }else{
        return true;
    }
    
    return false;                
}

GsWidget.prototype.calbeck = function(data)    // calbeck reporting AJAX
{
    //console.log(data);
}

GsWidget.prototype.complete = function(data)    // complete reporting AJAX
{
    //console.log(data);
}

GsWidget.prototype.beforeSend = function(data)    // beforeSend reporting AJAX
{
    $(this.contentTag).append("<div class='loadder-contarcalorais-widget'>loadding..</div>")
}

GsWidget.prototype.errorReporting = function(x,e)    // error reporting AJAX
{
    if(x.status==0){
        console.log("Not conected");                    
    }else if(x.status==404){
        console.log("Requested URL not found. 404");                                                        
    }else if(x.status==500){
        console.log("Internel Server Error.");
    }else if(e=='parsererror'){
        $(this.targetTag).append("Error.\nParsing JSON Request failed."+x.responseText);                    
    }else if(e=='timeout'){
        console.log("Request Time out."+x.responseText);
    }else {
        console.log("Unknow Error.\n"+x.responseText);                    
    }      
}

GsWidget.prototype.notification = function(note,msg,notificationTag)
{
    if(notificationTag != undefined)
        this.notificationTag = notificationTag;            
          
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
            
    $(this.notificationTag).html(message);            
    
    $("a.close").click(function () {
        $(this).parent().fadeOut("slow");
    });    
}
       

GsWidget.prototype.parseObjtoJson = function(object)  // parse object to json
{
    if(typeof JSON === 'object' && typeof JSON.stringify === 'function'){
    return  JSON.stringify(object);
    } else {
      $.getScript('//cdnjs.cloudflare.com/ajax/libs/json2/20121008/json2.min.js', winHasJSON)
    }
}

GsWidget.prototype.hasJQuery = function()    // has jquery
{

    if (typeof jQuery == 'undefined') {          
        //alert("Please add Jquery library  ( <script src='http://code.jquery.com/jquery-2.0.3.min.js'></script> )");
        if (!window.jQuery) {
        var jq = document.createElement('script'); jq.type = 'text/javascript';
        // Path to jquery.js file, eg. Google hosted version
        jq.src = 'http://code.jquery.com/jquery-2.0.3.min.js';
        document.getElementsByTagName('head')[0].appendChild(jq);
        }               
        return false;        
    } else {        
        return true;      
    }
}

var proxy = new GsWidget();
