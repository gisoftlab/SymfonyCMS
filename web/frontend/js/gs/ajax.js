var Ajax = new Class({
   
   //uriFile,module,action
   /* Options {
    *  uriFile : 'ajax_dev.php'
    *  module  : 'inbox'
    *  action  : 'show'
    *  beforeSend : 'html' 
    *  complete   : 'html' 
    * }
    *  
    */
        
    initialize: function(options){                
        this.setOptions(options);
    },
    
    setStatus: function(status)
    {
        this.status = status;        
    },  
    
    setCallBackFunction: function(callback)
    {
        this.callbackFunction = callback;        
    },    
    
    getCallBackFunction: function()
    {
        return this.callbackFunction;        
    },

    setOptions: function(options) {
        if(options != undefined)
        {
            if(options.uriFile != undefined)
                this.uriFile = options.uriFile;                        
            if(options.module != undefined)
                this.module  = options.module;
            if(options.action != undefined)
                this.action  = options.action;  
            
            /*
             *  actions happend after or befor this action ajax
             */
             if(options.beforeSend != undefined)
                this.beforeSend = options.beforeSend; 
            if(options.complete != undefined)
                this.complete = options.complete; 
            
             /*
             *   create uri 
             */                        
            
            if(this.uriFile != undefined)             
                this.uri = '/'+this.uriFile+'/'+this.module+'/'+this.action;                             
            
             /*
             *   set option when we hhave more parametres
             */
            if(options.postParams != undefined)
                this.postParams = options.postParams;
            
            /*
             *   set directly post parametres when we put only post param
             */
            if(options.tagAction != undefined)
                this.postParams = options;
        }
    },       
    
    errorReporting: function(x,e)
    {        
        if(x.status==0){
            console.log("Not conected");                    
        }else if(x.status==404){
            console.log("Requested URL not found. 404");                                                        
        }else if(x.status==500){
            console.log("Internel Server Error.");
        }else if(e=='parsererror'){
            console.log("Error.\nParsing JSON Request failed."+x.responseText);                    
        }else if(e=='timeout'){
            console.log("Request Time out."+x.responseText);
        }else {
            console.log("Unknow Error.\n"+x.responseText);                    
        }      
    },

    postAjax: function(action,tagAction, options){
    
        var _error = this.errorReporting;
        var _feedback = this.callbackFunction;
        // clear function callback only onne use
        this.setCallBackFunction(undefined);
        var _status = this.status;
                
        this.postParams = null;
        this.uri = null;        
        this.action = action;
           
        this.setOptions(options);                                     
    
        $.ajax({
        type: "POST",
        url: this.uri,
        data: this.postParams,
        dataType: "json",
        beforeSend: function(data){
                    // console.log(data);
                },

        success: function(data){                
                   if(contentManager.motificationMessage(data)) {                   
                     if(_feedback != undefined){
                        inbox = new Inbox();
                        inbox.feedback  = _feedback;
                        inbox.feedback(tagAction,_status);                                                                        
                     }else
                       $(tagAction).html(data.html);         
                   }else
                       $(tagAction).html(data.html);                                                                                                   
                },

        error:function(x,e){
                    _error(x,e);               
                },

        complete:   function(data){}
                // console.log(data);
        });  
                          
    }
            

});



