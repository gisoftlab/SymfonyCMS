
/*!
 * GISOFT WIDGET
 * Damian Ostraszewski
 * version: 1.2 (01-10-2013)
 * @requires jQuery v2.0.0 or later 
 *  JSONP
 *  cross-domain AJAX with dynamic script tag
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
        this.jsonpID = 0;      
        /*
         *  Load widget
         */
        this.invoke("widget",{ lang: _I.language },"GET",function (result){           
                                    
            if(lang = $(_I.targetTag).attr("lang"))
                this.language = lang;

            $(_I.targetTag).empty();
            $(_I.targetTag).append( result.html ) ;                             
            $(_I.searchTag,$(_I.targetTag)).click(_I.submitSearch);
            
                        
            $(_I.inputType,$(_I.targetTag)).keyup(function( event ) {                
                    if(_I.validateKey(event.which)){
                           _I.keySearch();
                    }
              });

        })
    
    }else{
        //alert(this.translation[this.language]['jquery']);
        alert("JQuery is necessary to work gisoft widget");
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
                _I.invoke("search",{ lang: _I.language, search:$( _I.formTag).serializeArray(),page: page },"GET",function (result){               
                                        
                   $(_I.contentTag).append( result.html ) ;  
                   $(_I.detailsBtnTag,$(_I.contentTag)).click(_I.showDetails);
                   $(".pagination span a",$(_I.contentTag)).click(_I.submitSearch);
                })  
            }
            
             return false;
     }       
     
     /*
     *  search food by typing
     */
     this.keySearch = function(){                                      
            if(_I.validateText($(_I.inputType).val(),_I.validation_min,_I.validation_max)){
                var page = 1;            
                                                    
                _I.invoke("search",{ lang: _I.language, search:$( _I.formTag).serializeArray(),page: page },"GET",function (result){               
               $(_I.contentTag).empty();
                $(_I.detailsTag).empty();
                $(_I.notificationTag).empty();
                                        
                   $(_I.contentTag).append( result.html ) ;  
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
            _I.invoke("details",{  id:$(this).attr("idx") },"GET",function (result){                                                                          
               $(_I.detailsTag).append( result.html ) ;                  
            })  
            
             return false;
     }               
}

GsWidget.prototype.initialize = function(options)    // initialize
{
   //this.uriFile = '/app_dev.php';
   this.uriFile = '';
   //this.serverUri = "cms.local";  
   this.serverUri = "gisoft.pl";      
   
   this.serverTUri = {
      'pl' : {
          'url' : 'gisoft.pl',          
      },
      'en' : {
          'url' : 'gisoft.com',          
      },
   };
        
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
      
   this.language = $(this.targetTag).attr("lang"); 
   this.setOptions(options);
   this.toggleAction = "";
   this.data = "";
   
   this.translation = {
      'es' : {
          'jquery' : 'JQuery es necesario trabajar widget de gisoft',
          'too-long' : 'Buscando comida es demasiado largo max  ',
          'too-short' : 'Buscando comida es demasiado corta min  ',
          'chars' : ' signos',
      },
      'pt' : {
          'jquery' : 'JQuery é necessário trabalhar gisoft Widget',
          'too-long' : 'Buscando comida es Demasiado largo max',
          'too-short' : 'Buscando comida es Demasiado corta min',
          'chars' : ' sinais',
      },        
   };
   
   if(this.serverTUri[this.language] != undefined)
   this.serverUri = this.serverTUri[this.language]["url"];   
         
   this.serviceUrl = "http://" + this.serverUri +this.uriFile+ "/api/gisoft/";             
               
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


GsWidget.prototype.invoke = function(method, data, type, callback) {
                 
     var postParams = data;//{modelId:id,lang:lang};                
               
     if (type == undefined)
         type = "GET";
            
     if (callback != undefined)
          this.calbeck = callback;            
      
    var query = '?';       
    query += decodeURIComponent($.param( data  ));
    query += "&";                   
         
    return  this.JSONP(this.serviceUrl+method+query+'callback=?', callback);                                                   
}

GsWidget.prototype.validateText = function(text, min, max)    // calbeck reporting AJAX
{
    if(text == ""){
       // this.notification("error","Search can't be empty");        
    }else if( text.length < min){
        //this.notification("error",this.translation[this.language]['too-short']+min+this.translation[this.language]['chars']);        
        //this.notification("error","Searching food is too short min  "+min+" chars");        
    }else if( text.length >= max){
        this.notification("error",this.translation[this.language]['too-long']+max+this.translation[this.language]['chars']);        
        //this.notification("error","Searching food is too long max  "+max+" chars");        
    }else{
        return true;
    }
    
    return false;                
}

GsWidget.prototype.validateKey = function(key)    // calbeck reporting AJAX
{
    var keysArray = new Array();
    keysArray[13] = "enter";
    keysArray[27] = "esc";
    keysArray[20] = "caps lock";
    keysArray[16] = "shift";
    keysArray[17] = "ctrl";
    keysArray[18] = "alt";
    keysArray[91] = "win";
    keysArray[220] = "\\";
    keysArray[61] = "\\";
    keysArray[37] = "left";
    keysArray[40] = "down";
    keysArray[39] = "right";
    keysArray[38] = "up";
    keysArray[45] = "insert";
    keysArray[36] = "home";
    keysArray[35] = "end";
    keysArray[34] = "page down";
    keysArray[33] = "page up";
    
    if(  keysArray[key] == undefined)
        return true;

    return false; 
}

GsWidget.prototype.calbeck = function(data)    // calbeck reporting AJAX
{
    //console.log(data);
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


GsWidget.prototype.JSONP  = function(url, callback){

  // create script tag    
     var script = document.createElement('script'); 
     script.type = 'text/javascript';
             
  // Dynamically generate a callback function name.
  // Make the name unique so there's no chance we
  // override another function of the same name. By
  // attaching the counter jsonpID to the name we can make
  // invoke JSONP multiple times, each time with a different
  // function name
  var callbackName = '_JSONP_callback__' + (++this.jsonpID)
    
  // replace question mark with callbackName
  script.src = url.replace(/=\?/, '=' + callbackName)
      
  // append script to document's head
  document.getElementsByTagName('head')[0].appendChild(script)

  // create and assign the callback function
  window[callbackName] = function(data){
    delete window[callbackName]
    // remove script element from document
    script.parentElement.removeChild(script)

    // call the passed function passing data
    callback(data);
  }
}


var proxy = new GsWidget();

