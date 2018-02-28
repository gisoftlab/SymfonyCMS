/**
 *
 * GISOFT  AJAX
 * Damian Ostraszewski
 * version: 1.4 (25-10-2016)
 * @requires jQuery v2.0.0 or later
 *
 */

function GsAjax()
{
    this.initialize();
    this.module  = 'Pages'
    this.NOTIFICATION_SUCCESS = "ok";
    this.NOTIFICATION_ERROR = "error";
    this.NOTIFICATION_INFO = "info";
    if(typeof tinymce != 'undefined') {
        this.tinyMCE = tinymce;
    }

    $(document).ready(function(){
        notificationClose();
    });
}

var notificationClose = function(imie) {
    $("a.close").click(function () {
        $(this).parent().fadeOut("slow");
    });
};

GsAjax.prototype.initialize = function (options)    // initialize
{
    this.uriFile = 'backend.php';
    this.module = 'Pages'
    this.notificationTag = "#notification";
    this.loaderTag = "#LoadingInfo";
    this.targetTag = "#load-form";
    this.setOptions(options);
    this.toggleAction = "";
    this.toggleContent = "";
}

GsAjax.prototype.setOptions = function(options)    // set options
{
    if( options != undefined )
    {
        if( options.uriFile != undefined )
            this.uriFile = options.uriFile;
        if( options.module != undefined )
            this.module  = options.module;
        if( options.action != undefined )
            this.action  = options.action;
        if( options.notificationTag != undefined )
            this.notificationTag  = options.notificationTag;
    }
}

GsAjax.prototype.notification = function(note,msg,notificationTag)
{
    var message = "";

    if(notificationTag != undefined) {
        this.notificationTag = notificationTag;
    }

    if( note == "error" ) {
        message += "<div class='flash error'>";
    }

    if( note == "ok" ) {
        message += "<div class='flash ok'>";
    }

    if( note == "info" ) {
        message += "<div class='flash info'>";
    }

    if( note == "notice" ) {
        message += "<div class='flash notice'>";
    }

    message +="<div class='flash-content'>";
    message +="<div> "+msg+"</div>";
    message +="<a class='close'></a>";
    message +="</div>";
    message +="</div>";

    //$("#FlashMessage").html(message);
    $(this.notificationTag).html(message);

    notificationClose();

}

GsAjax.prototype.errorReporting2 = function()    // error reporting AJAX
{
    console.log("sss");
}


GsAjax.prototype.errorReporting = function(x,e)    // error reporting AJAX
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
}


GsAjax.prototype.loadActionHref = function(obj,loadBox,href,id,lang)
{
    var action = href;
    var parent = $(obj).parent();
    var content = $(loadBox,parent);

    var _error = this.errorReporting;

    if( $(content).html() != "" ){
        content.empty();
        // load only when  is clicked another link with action
        if(this.toggleAction == action)
            return false;
    } else {
        content.empty();
    }

    var postParams = {modelId:id,lang:lang};

    $.ajax({
        type: "GET",
        url: action,
        data:postParams,
        dataType: "html",
        beforeSend: function(){
            content.html(" loading...");
        },
        success: function(data){
            content.html(data);
            this.toggleAction = action;
        },
        error:function(x,e){
            _error(x,e);
        },
        complete:   function(data){
        }

    });

    return false;
}

GsAjax.prototype.loadAction = function(obj,loadBox,id,lang){

    // load-form
    var action = $(obj).attr('href');
    var parent = $(obj).parent();
    var content = $(loadBox,parent);

    this.oadActionHref(obj,loadBox,action,id,lang)

}

GsAjax.prototype.loadUploader = function(object,action,id){

    var content = $(object);
    content.empty();

    $.ajax({
        type: "GET",
        url: action,
        data: {
            id: id
        },
        dataType: "html",
        timeout: 2000,
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

GsAjax.prototype.quickEditFeedback = function(data)
{
    $("#quick-form"+data.id+" a").removeClass("active");

    if(data.quick != undefined)
    {
        $("#pagesTitle"+data.id+" a").html(data.title);
        $("#pagesSlug"+data.id+" a").html(data.slug);
        $("#pagesSlug"+data.id+" a").attr("href","/"+data.slug);
        $("#load-form"+data.id+" a").html("");

        var publish = $("#pagesPublish"+data.id+" a");
        if(data.published)
        {
            if(!$(publish).hasClass("icon-5-ok"))
            {
                $(publish).removeClass("icon-5");
                $(publish).addClass("icon-5-ok");

                var href = $(publish).attr("href");
                $(publish).attr("href",href.replace("Unpublished","Published"));
            }
        }else{
            $(publish).removeClass("icon-5-ok");
            $(publish).addClass("icon-5");

            var href = $(publish).attr("href");
            if(href)
                $(publish).attr("href",href.replace("Published","Unpublished"));
        }
    }
}

GsAjax.prototype.activeClicked = function(obj,data)
{
    $("#quick-form"+data.id+" a").removeClass("active");
    $(obj).addClass("active");
}

GsAjax.prototype.setToggle = function(value)
{
    this.toggleAction = value;
}

GsAjax.prototype.getToggle = function()
{
    return this.toggleAction;
}

GsAjax.prototype.loadGetAction = function(obj,targetTag,post)
{
    var _error = this.errorReporting;
    var _active = this.activeClicked;
    var _notification = this.notification;
    var _success = this.NOTIFICATION_SUCCESS;
    var loader = this.loaderTag+post.id;
    var targeted = this.targetTag+post.id;
    var _setToggle = this.setToggle;
    var _getToggle = this.getToggle;

    var uri =$(obj).attr("href");
    var  postParams  = post;

    if($(targeted).html() != ""){
        $(targeted).empty();
        $("#quick-form"+post.id+" a").removeClass("active");

        if(_getToggle() == uri)
            return false;
    } else {
        $(targeted).empty();
    }

    $.ajax({
        type: "GET",
        url: uri,
        data: postParams,
        dataType: "json",
        beforeSend: function(data){
            $(loader).show();
        },

        success: function(data){
            $(targeted).html(data.html);
            _setToggle(uri);
            _active(obj,data);
        },

        error:function(x,e){
            _error(x,e);
        },

        complete:function(data){}
    });
}

GsAjax.prototype.uploaderAction = function(obj, targetTag, action)
{
    var content = $(targetTag);
    var that = this;

    $.ajax({
        type: "GET",
        url: action,
        data: {

        },
        dataType: "html",
        timeout: 2000,
        beforeSend: function(){
            // loading...
            // content.html(" loading...");
        },
        success: function(data){
            content.html(data);
            that.iconActionInit(content);
        },
        error:function(data){
            content.html(data);
        },
        complete:   function(data){
        }

    });

    return false;
}


GsAjax.prototype.loadQuickUploader = function(obj,targetTag,post)
{
    var _error = this.errorReporting;
    var _active = this.activeClicked;
    var _notification = this.notification;
    var _success = this.NOTIFICATION_SUCCESS;
    var loader = this.loaderTag+post.id;
    var targeted = this.targetTag+post.id;
    var _setToggle = this.setToggle;
    var _getToggle = this.getToggle;

    var parent = $(obj).parent();
    var content = $(targeted,parent);

    var uri =$(obj).attr("href");
    var  postParams  = post;
    postParams["feedback"] = targetTag;

    if($(content).html() == null){
        var temp = $("tr",$(parent).parent()).next();
        var loadBoxId = targetTag.replace(".","#");
        content = $(loadBoxId+post.id);
    }

    if($(content).html() != ""){
        $(content).empty();
        $("#quick-form"+post.id+" a").removeClass("active");

        if(_getToggle() == uri)
            return false;
    } else {
        $(targeted).empty();
    }

    $.ajax({
        type: "GET",
        url: uri,
        data: postParams,
        dataType: "html",
        timeout: 2000,
        beforeSend: function(data){
            content.html(" loading...");
        },

        success: function(data){
            content.html(data);
            _setToggle(uri);
            _active(obj,data);
        },

        error:function(x,e){
            _error(x,e);
        },

        complete:   function(data){}
    });
}

GsAjax.prototype.loadIconUploader = function(obj,loadBox,id)
{
    var _active = this.activeClicked;
    var action = $(obj).attr('href');
    var parent = $(obj).parent();
    var content = $(loadBox,parent);

    if($(content).html() == null)
    {
        var loadBoxId = loadBox.replace(".","#");
        content = $(loadBoxId+id);
    }

    if( $(content).html() != "" )
    {
        content.empty();
        // load only when  is clicked another link with action
        $("#quick-form"+id+" a").removeClass("active");
        if(toggleAction == action)
            return false;
    } else {
        $("#quick-form"+id+" a.gallery").addClass("active");
        content.empty();
    }

    $.ajax({
        type: "GET",
        url: action,
        data: {
            id: id,
            feedback: loadBox
        },
        dataType: "html",
        timeout: 2000,
        beforeSend: function(data){
            // loading...
            content.html(" loading...");
        },
        success: function(data){
            content.html(data);
            toggleAction = action;
            _active(obj,data);
        },
        error:function(data){
            content.html(data);
        },
        complete:   function(data){
        }

    });

    return false;
}

GsAjax.prototype.setupIcon = function(obj,targetTag)
{
    this.uploaderAction($(obj), targetTag, $(obj).attr("href"));
    var idx = $(obj).attr("idx");
    var srcx = $(obj).attr("srcx");
    var iconTag = "<img alt='' title='' src='" + srcx + "'>";
    $("#pagesIcon" + idx).html(iconTag);

    return false;
}

GsAjax.prototype.deleteIcon = function(obj,targetTag)
{
    confirmed = confirm('Skasować Plik?');
    if (confirmed) {
        this.uploaderAction($(obj), targetTag, $(obj).attr("href"));
        if ($(obj).hasClass("icon")) {
            var idx = $(obj).attr("idx");
            var callback = $(obj).attr("callback");

            var iconTag = "<a  onclick=\"gsAjax.loadQuickUploader($(obj),'.load-form','" + idx + "'); return false;\" class='add-icon info-tooltip' href=" + callback + " title='Dodaj Ikonę - dodaj obrazek w galerii - zaznacz jako ikona'>&nbsp;</a>"
            $("#pagesIcon" + idx).html(iconTag);
        }

    }

    return false;
}

GsAjax.prototype.editIconTitleSubmit = function(obj)
{
    var form = $(obj).parent();
    $(form).submit(function () {
        var form = $(this);
        var field = $(form).parent().parent().parent();

        $(this).ajaxSubmit({
            type: "POST",
            url: $(form).attr("action"),
            data: {},
            dataType: "html",

            beforeSend: function (data) {
                //console.log(data);
            },
            success: function (data) {
                $(".formTitle").hide();
                $("a.btn-edit-title").html("Edytuj Tytuł");
                $("div.imageTitle span.showImageTitle a", field).html($("input[type=text]", field).val());
            },
            error: function (data) {
                console.log(data);
            },
            complete: function (data) {
            }

        });

        return false;
    });

    form.submit();
    return false;
}

GsAjax.prototype.iconActionInit = function(obj)
{
    $(".imageTitle a.btn-title",obj).click(function () {
        return false;
    })

    $(".imageTitle a.btn-title",obj).mousemove(function () {
        $(this).parent().next(".showImageTitle").show();
    })

    $(".imageTitle a.btn-title",obj).mouseout(function () {
        $(this).parent().next(".showImageTitle").hide();
    })
}

GsAjax.prototype.editIconTitle = function(obj, targetTag, id)
{
    var formTitle = $(targetTag+id);
    var editName = $(obj).attr("xEdit");
    var cancelName = $(obj).attr("xCancel");

    if ($(obj).html() == editName) {
        $(".formTitle").hide();
        $("a.btn-edit-title").html(editName);
        $(formTitle).show();

        $(obj).html(cancelName);

    } else {

        $(formTitle).hide();
        $(obj).html(editName);
    }
    return false;
}

GsAjax.prototype.closeForm = function(obj,id)
{
    var targeted = $(obj).attr("href");
    $(targeted).html("");
    $("#quick-form"+id+" a").removeClass("active");
    return false;
}

GsAjax.prototype.ToggleMeta = function(obj)
{
    var targeted = $(obj).parent();
    if($(".meta-tag-content",targeted).is(':visible')){
        $(".meta-tag-content",targeted).hide();
        $(obj).html("Edytuj MetaTagi");
    }else{
        $(".meta-tag-content",targeted).show();
        $(obj).html("Zamknij MetaTagi");
    }
    return false;
}

GsAjax.prototype.ToggleParent = function(obj)
{
    var targeted = $(obj).parent();
    if($(".parent-content",targeted).is(':visible')){
        $(".parent-content",targeted).hide();
        $(obj).html("Edytuj Rodzica");
    }else{
        $(".parent-content",targeted).show();
        $(obj).html("Zamknij Rodzica");
    }
    return false;
}

GsAjax.prototype.saveForm = function(targetTag,post)
{
    var _error = this.errorReporting;
    var _notification = this.notification;
    var _quick = this.quickEditFeedback;
    var _success = this.NOTIFICATION_SUCCESS;
    var _tinyMCE = this.tinyMCE;

    var loader = this.loaderTag+post.id;
    var targeted = this.targetTag+post.id;

    if(targetTag != undefined)
        targeted = $(targeted);
    else
        targeted = $(this.targetTag+post.id);

    var form   = $("form",targeted);
    var uri =$("form",targeted).attr("action");

    $("textarea",form).html(_tinyMCE.activeEditor.getContent());
    var  postParams  = $(form).serializeArray();


    //postParams += $(form).serializeArray();
    //    var i;
    //    for(i in post)
    //        postParams[i]=post[i];

    $.ajax({
        type: "POST",
        url: uri,
        data: postParams,
        dataType: "json",
        beforeSend: function(data){
            $(loader).show();
        },

        success: function(data){
            if(data.note != _success)
                $(targeted).html(data.html);
            else{
                _notification(data.note,data.msg,"#notification");
                _quick(data)
                $(targeted).html("");
            }
        },

        error:function(x,e){
            _error(x,e);
        },

        complete:   function(data){}
    });
}

GsAjax.prototype.showForm = function(id)    // error reporting AJAX
{
    var form  = $('#form'+id);
    var _error = this.errorReporting;
    this.action  = 'showForm';
    var uri = '/'+this.uriFile+'/'+this.module+'/'+this.action;
    var postParams = {id:id};

    $.ajax({
        type: "GET",
        url: uri,
        data: postParams,
        dataType: "json",
        beforeSend: function(data){
        },

        success: function(data){
            $(form).html(data.html);
        },

        error:function(x,e){
            _error(x,e);
        },

        complete:   function(data){}
    });
}


GsAjax.prototype.deleteResult = function(id)    // error reporting AJAX
{

    var answer = confirm ('Are you sure?')
    var form  = $('#form'+id);
    var _error = this.errorReporting;

    if (!answer) {
        return false
    }

    this.action  = 'deleteResult';
    var uri = '/'+this.uriFile+'/'+this.module+'/'+this.action;
    var postParams = {id:id};

    $.ajax({
        type: "POST",
        url: uri,
        data: postParams,
        dataType: "json",
        beforeSend: function(data){
        },

        success: function(data){
            if(data.ok != undefined)
            {
                $(form).hide();
                alert(data.ok);
            }
        },

        error:function(x,e){
            _error(x,e);
        },

        complete:   function(data){}
    });
}


GsAjax.prototype.cancel = function(id)    // error reporting AJAX
{
    var form  = $('#form'+id);
    var _error = this.errorReporting;
    this.action  = 'showResult';
    var uri = '/'+this.uriFile+'/'+this.module+'/'+this.action;
    var postParams = {id:id};

    $.ajax({
        type: "GET",
        url: uri,
        data: postParams,
        dataType: "json",
        beforeSend: function(data){
        },

        success: function(data){
            $(form).html(data.html);
        },

        error:function(x,e){
            _error(x,e);
        },

        complete:   function(data){}
    });
}

var gsAjax = new GsAjax();

