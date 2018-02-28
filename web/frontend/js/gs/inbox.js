var Inbox = new Class({
   
   //uriFile,module,action
   /* Options {
    *  uriFile : 'ajax_dev.php'
    *  module  : 'files'
    *  action  : 'add'
    * }
    *  
    */
              
    initialize: function(options){  
        
         var defaultOptions = {
                uriFile : 'ajax.php',
                module : 'inbox'
            }
        
        if(options == undefined){                               
            options = defaultOptions;
            this.uriFile = options.uriFile;
            this.module  = options.module;                                
        }
                
        this.setOptions(options);                        
        this.ajax = new Ajax(options);
        
        
    },
    
    showTabFavourite: function()
    {
        $('#tabsMine div.resultsCanvas.ui-tabs-panel').each(function() {            
             
          if(!$(this).hasClass('ui-tabs-hide'))
              $(this).addClass('ui-tabs-hide');                    
              
        });
        
        $('#tabsMine ul li.ui-tabs-selected.ui-state-active').each(function() {              
              $(this).removeClass('ui-tabs-selected ui-state-active');
            
        });
        
        $('#tabsMine ul li a#myFavourite').each(function() {              
              $(this).parent().addClass('ui-tabs-selected ui-state-active');            
              $($(this).attr('href')).removeClass('ui-tabs-hide');
        });
                
               
    },

    setOptions: function(options) {
        if(options != undefined)
        {
            this.uriFile = options.uriFile;
            this.module  = options.module;
            this.action  = options.action;  
        }        
    },       
       
    composeMessage: function(tagAction,type,form,status,partial) {

        var postParams = null; 
        var postForm = null;
        
        if(partial == undefined)
           partial = 'inbox_compose';                 
        
        this.action  = 'composeMessage';                
        
        if(type == 'POST' && form != undefined)
        {
            postForm = $(form).serializeArray();                            
            this.ajax.setCallBackFunction(this.showUserInboxSended);
            this.ajax.setStatus(status);             
        }
        
        postParams = {tagAction:tagAction,type:type,postForm:postForm,partial:partial};                                                          
        
        this.ajax.postAjax(this.action,tagAction,postParams);       
        
        return false;
    },   
    
    replyMessage: function(tagAction,id_inbox,type,form,status) {

        var postParams = null; 
        var postForm = null;
        
        this.action  = 'replyMessage';
        
        if(type == 'POST' && form != undefined)
        {
           postForm = $(form).serializeArray();         
           this.ajax.setCallBackFunction(this.showUserInboxReceived);
           this.ajax.setStatus(status);    
        }           
        
        postParams = {tagAction:tagAction,id_inbox:id_inbox,type:type,postForm:postForm};                                                          
        
        this.ajax.postAjax(this.action,tagAction,postParams);       
        
        return false;
    }, 
    
    showUserInboxSended: function(tagAction,status) {

        var postParams = null;                
        this.action  = 'sendedMails';
        
        postParams = {tagAction:tagAction,status:status};                                                  
        
        this.ajax.postAjax(this.action,tagAction,postParams);       
        
        return false;
    },
    
    
    moveInboxSendedMailToTrash: function(tagAction,id_inbox) {

        var postParams = null;                
        this.action  = 'moveInboxSendedMailToTrash';
        
        postParams = {tagAction:tagAction,id_inbox:id_inbox};                                                  
        
        this.ajax.postAjax(this.action,tagAction,postParams);       
        
        return false;
    },
    
     moveInboxSendedMailToDeleted: function(tagAction,id_inbox,retrivateStatus) {

        var postParams = null;                
        this.action  = 'moveInboxSendedMailToDelete';
        
        postParams = {tagAction:tagAction,id_inbox:id_inbox,retrivateStatus:retrivateStatus};                                                  
        
        this.ajax.postAjax(this.action,tagAction,postParams);       
        
        return false;
    },
    
    showUserInboxReceived: function(tagAction,status) {

        var postParams = null;                
        this.action  = 'receivedMails';
        
        postParams = {tagAction:tagAction,status:status};                                                                  
        
        this.ajax.postAjax(this.action,tagAction,postParams);       
        
        return false;
    },
    
    readUserInbox: function(tagAction,id_inbox,status) {

        var postParams = null;                
        this.action  = 'readMail';
        
        postParams = {tagAction:tagAction,id_inbox:id_inbox,status:status};                                                                  
        
        this.ajax.postAjax(this.action,tagAction,postParams);       
        
        return false;
    },
    
    moveInboxReceivedMailToTrash: function(tagAction,id_inbox) {

        var postParams = null;                
        this.action  = 'moveInboxReceivedMailToTrash';
        
        postParams = {tagAction:tagAction,id_inbox:id_inbox};                                                  
        
        this.ajax.postAjax(this.action,tagAction,postParams);       
        
        return false;
    },
    
     moveInboxReceivedMailToDelete: function(tagAction,id_inbox,retrivateStatus) {

        var postParams = null;                
        this.action  = 'moveInboxReceivedMailToDelete';
        
        postParams = {tagAction:tagAction,id_inbox:id_inbox,retrivateStatus:retrivateStatus};                                                  
        
        this.ajax.postAjax(this.action,tagAction,postParams);       
        
        return false;
    }
            

});



