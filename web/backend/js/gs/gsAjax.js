/**
 *
 * GISOFT  AJAX
 * Damian Ostraszewski
 * version: 1.5 (25-11-2016)
 * @requires jQuery v2.0.0 or later 
 *  
 */


var gsAjax = (function(){

    var $errorReporting = "";
    var $activeClicked = "";
    var $notification = "";
    var $loaderTag = "";
    var $NOTIFICATION_SUCCESS = "ok";

    var $uriFile = 'backend.php';
    var $module = 'Pages'
    var $notificationTag = "#notification";
    var $loaderTag = "#LoadingInfo";
    var $targetTag = "#load-form";
    var $toggleAction = "";
    var $toggleContent = "";


    /**
     *
     * @param obj
     * @param data
     */
    function activeClicked(obj,data)
    {
        $("#quick-form"+data.id+" a").removeClass("active");
        $(obj).addClass("active");
    }

    /**
     *
     * @param value
     */
    function setToggle(value)
    {
        $toggleAction = value;
    }

    /**
     *
     * @returns {string|*|jQuery}
     */
    function getToggle()
    {
        return $toggleAction;
    }

    /**
     *
     * @param options
     */
    function setOptions(options)    // set options
    {
        if( options != undefined )
        {
            if( options.uriFile != undefined )
                $uriFile = options.uriFile;
            if( options.module != undefined )
                $module  = options.module;
            if( options.action != undefined )
                $action  = options.action;
            if( options.notificationTag != undefined )
                $notificationTag  = options.notificationTag;
        }
    }

    var notificationClose = function(imie) {
        $("a.close").click(function () {
            $(this).parent().parent().fadeOut("slow");
        });
    };

    function notification(note,msg,notificationTag)
    {
        var message = "";

        if(notificationTag != undefined) {
            this.notificationTag = notificationTag;
        }

        if( note == "error" ) {
            message += "<div class='flash error x_panel'>";
        }

        if( note == "ok" ) {
            message += "<div class='flash ok x_panel'>";
        }

        if( note == "info" ) {
            message += "<div class='flash info x_panel'>";
        }

        if( note == "notice" ) {
            message += "<div class='flash notice x_panel'>";
        }

        message +="<div class='flash-content'>";
        message +="<div> "+msg+"</div>";
        message +="<a class='close close-link'></a>";
        message +="</div>";
        message +="</div>";

        //$("#FlashMessage").html(message);
        $($notificationTag).html(message);

        notificationClose();

    }

    /**
     *
     * @param x
     * @param e
     */
    function errorReporting(x,e)    // error reporting AJAX
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


    /**
     *
     * @param data
     */
    function quickEditFeedback(data)    // error reporting AJAX
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

    /**
     *  initIcheckLoading
     */
    function initIcheckLoading() {
        if ($("input.flat")[0]) {
            $('input.flat').iCheck({
                checkboxClass: 'icheckbox_flat-green',
                radioClass: 'iradio_flat-green'
            });
        }
    }

    /**
     *  closeQuickAction
     */
    function closeQuickAction() {
        $(".closeQuickAction").on("click", function () {
            var ajax = $(this).attr("ajax");

            try {
                ajax = JSON.parse(ajax);

                if (typeof(tinyMCE) == 'object') {
                    tinyMCE.remove();
                }

                var targeted = $(this).attr("href");
                $(targeted).html("");
                $("#quick-form"+ajax.id+" a").removeClass("active");

                return false;
            }
            catch (e) {
                console.log("Error - closeQuickAction");
                console.log(e);
                return false
            }

            return falsel
        })
    }

    /**
     *  saveQuickAction
     */
    function saveQuickAction() {
        $(".saveQuickAction").on("click", function () {
            var ajax = $(this).attr("ajax");

            try {
                ajax = JSON.parse(ajax);
                gsAjax.saveForm(ajax.target, ajax.data);

                if (typeof(tinyMCE) == 'object') {
                    tinyMCE.remove();
                }

                return false;
            }
            catch (e) {
                console.log("Error - saveQuickAction");
                console.log(e);
                return false
            }

            return false
        })
    }

    /**
     *
     * @param obj
     * @param targetTag
     * @param action
     * @returns {boolean}
     */
    function uploaderAction(obj, targetTag, action)    // error reporting AJAX
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
            },
            error: function(data){
                content.html(data);
            },
            complete: function(data){
            }

        });

        return false;
    }


    return {
        /**
         *
         * @param options
         */
        initialize: function (options) {
            $uriFile = 'backend.php';
            $module = 'Pages'
            $notificationTag = "#notification";
            $loaderTag = "#LoadingInfo";
            $targetTag = "#load-form";
            setOptions(options);
            $toggleAction = "";
            $toggleContent = "";
        },
        /**
         * loadGetAction
         *
         * @param obj
         * @param targetTag
         * @param post
         * @returns {boolean}
         */
        loadGetAction: function (obj, targetTag, post) {
            var _error = errorReporting;
            var _active = activeClicked;
            var _initIcheckLoading = initIcheckLoading;
            var _closeQuickAction = closeQuickAction;
            var _saveQuickAction = saveQuickAction;
            var _notification = notification;
            var _success = $NOTIFICATION_SUCCESS;
            var loader = $loaderTag + post.id;
            var targeted = targetTag + post.id;
            var _setToggle = setToggle;
            var _getToggle = getToggle;

            var uri = $(obj).attr("href");
            var postParams = post;

            if ($(targeted).html() != "") {

                if (typeof(tinyMCE) == "object") {
                    tinyMCE.remove();
                }

                $(targeted).empty();
                $("#quick-form" + post.id + " a").removeClass("active");

                if (_getToggle() == uri) {
                    return false;
                }
            } else {
                $(targeted).empty();
            }

            $.ajax({
                type: "GET",
                url: uri,
                data: postParams,
                dataType: "json",
                beforeSend: function (data) {
                    $(loader).show();
                },

                success: function (data) {
                    $(targeted).html(data.html);
                    _setToggle(uri);
                    _active(obj, data);
                    _initIcheckLoading();
                    _closeQuickAction();
                    _saveQuickAction();
                },

                error: function (x, e) {
                    _error(x, e);
                },

                complete: function (data) {
                }
            });
        },
        /**
         * Load Quick Uploader
         *
         * @param obj
         * @param targetTag
         * @param post
         * @returns {boolean}
         */
        loadQuickUploader: function (obj, targetTag, post) {
            var _error = errorReporting;
            var _active = activeClicked;
            var _notification = $notification;
            var _success = $NOTIFICATION_SUCCESS;
            var loader = $loaderTag + post.id;
            var targeted = $targetTag + post.id;
            var _setToggle = setToggle;
            var _getToggle = getToggle;
            var parent = $(obj).parent();
            var content = $(targeted, parent);

            var uri = $(obj).attr("href");
            var postParams = post;
            postParams["feedback"] = targetTag;

            if ($(content).html() == null) {
                var temp = $("tr", $(parent).parent()).next();
                var loadBoxId = targetTag.replace(".", "#");
                content = $(loadBoxId + post.id);
            }

            if ($(content).html() != "") {
                $(content).empty();
                $("#quick-form" + post.id + " a").removeClass("active");

                if (_getToggle() == uri)
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
                beforeSend: function (data) {
                    content.html(" loading...");
                },

                success: function (data) {
                    content.html(data);
                    _setToggle(uri);
                    _active(obj, data);
                },

                error: function (x, e) {
                    _error(x, e);
                },

                complete: function (data) {
                }
            });
        },
        /**
         * load Uploader
         *
         * @param object
         * @param action
         * @param id
         * @returns {boolean}
         */
        loadUploader: function (selector, action, id) {
            var content = $(selector);
            content.empty();

            $.ajax({
                type: "GET",
                url: action,
                data: {
                    id: id
                },
                dataType: "html",
                timeout: 2000,
                beforeSend: function (data) {
                    // loading...
                    content.html(" loading...");
                },
                success: function (data) {
                    content.html(data);
                },
                error: function (data) {
                    content.html(data);
                },
                complete: function (data) {
                }

            });

            return false;
        },

        /**
         * Save Form
         *
         * @param targetTag
         * @param post
         */
        saveForm: function (targetTag, post) {
            var _error = errorReporting;
            var _notification = notification;
            var _quick = quickEditFeedback;
            var _closeQuickAction = closeQuickAction;
            var _saveQuickAction = saveQuickAction;
            var _success = $NOTIFICATION_SUCCESS;
            var _tinyMCE = tinyMCE;

            var loader = $loaderTag + post.id;
            var targeted = targetTag + post.id;

            if (targetTag != undefined) {
                targeted = $(targeted);
            }else {
                targeted = $(targetTag + post.id);
            }

            var form = $("form", targeted);
            var uri = $("form", targeted).attr("action");

            $("textarea", form).html(_tinyMCE.activeEditor.getContent());
            //var editable_id = $("textarea", form).attr("id");
            var postParams = $(form).serializeArray();

            $.ajax({
                type: "POST",
                url: uri,
                data: postParams,
                dataType: "json",
                beforeSend: function (data) {
                },

                success: function (data) {
                    if (data.note != _success)
                        $(targeted).html(data.html);
                    else {
                        _notification(data.note, data.msg, "#notification");
                        _quick(data)
                        $(targeted).html("");
                    }

                    _closeQuickAction();
                    _saveQuickAction();
                },

                error: function (x, e) {
                    _error(x, e);
                },

                complete: function (data) {
                    _tinyMCE.remove();
                }
            });
        },

        /**
         * iconActionInit
         *
         * @param obj
         */
        iconActionInit: function(obj) {
            $(".imageTitle a.btn-title", obj).click(function () {
                return false;
            })

            $(".imageTitle a.btn-title", obj).mousemove(function () {
                $(this).parent().next(".showImageTitle").show();
            })

            $(".imageTitle a.btn-title", obj).mouseout(function () {
                $(this).parent().next(".showImageTitle").hide();
            })
        },

        /**
         * loadIconUploader
         *
         * @param obj
         * @param loadBox
         * @param id
         * @returns {boolean}
         */
        loadIconUploader: function(obj, loadBox, id) {
            var _active = activeClicked;
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
                if($toggleAction == action)
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
                    $toggleAction = action;
                    _active(obj,data);

                    $("#pagesIcon"+id).html($("#showIcon", $(data)).html());
                },
                error:function(data){
                    content.html(data);
                },
                complete:   function(data){
                }

            });

            return false;
        },

        /**
         * Edit Icon title submit action
         *
         * @param obj
         * @param id
         * @returns {boolean}
         */
        editIconTitleSubmit: function(obj, id) {
            var form = $(obj).parent();
            $(form).submit(function (e) {
                var form = $(this);
                var field = $(form).parent().parent().parent();
                e.preventDefault();

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
                        $(".thumbnail .view .mask p", field).html($("input[type=text]", field).val());
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
        },

        /**
         * setupIcon
         *
         * @param obj
         * @param idx
         * @param targetTag
         * @param srcx
         * @returns {boolean}
         */
        setupIcon: function(obj,idx, targetTag, srcx) {

            uploaderAction($(obj), targetTag, $(obj).attr("href"));
            var iconTag = "<img alt='' title='' src='" + srcx + "'>";
            $("#pagesIcon" + idx).html(iconTag);

            return false;
        },
        /**
         * deleteIcon
         *
         * @param obj
         * @param idx
         * @param targetTag
         * @param callback
         * @param confirmedMessage
         * @returns {boolean}
         */
        deleteIcon: function(obj,idx, idf, targetTag,callback, confirmedMessage) {
            confirmedMessage = (confirmedMessage == undefined)?"Skasować Plik?":confirmedMessage;
            confirmed = confirm(confirmedMessage);
            if (confirmed) {
                uploaderAction($(obj), targetTag, $(obj).attr("href"));

                if ($(".icon-title"+idf+" b.icon").length > 0) {
                    var iconTag = "<a  ajax='{\"target\":\".load-form\", \"post\":{\"id\":\""+idx+"\"}}' class='add-icon page-list icon green info-tooltip' href=\""+callback+"\" title='Dodaj Ikonę - dodaj obrazek w galerii - zaznacz jako ikona'><i class=\"fa fa-plus-circle\"></i></a>";

                    $("#pagesIcon" + idx).html(iconTag);
                }

            }

            return false;
        },

        /**
         * editIconTitle
         *
         * @param obj
         * @param targetTag
         * @param id
         * @returns {boolean}
         */
        editIconTitle: function(obj, id, targetTag) {
            $(targetTag+id).toggle();
            return false;
        },
        /**
         * fileUploader
         *
         * @param obj
         * @param idx
         * @param targetTag
         * @param callback
         * @param confirmedMessage
         * @returns {boolean}
         */
        fileUploader: function(obj,idx, targetTag, feedback, quick) {

            $(targetTag+idx).submit(function (e) {
                var form = $(this);
                var object = $(this).parent().parent().parent().parent();
                e.preventDefault();

                $(this).ajaxSubmit({
                    type: "POST",
                    url: $(form).attr("action"),
                    data: {
                        id: idx,
                        feedback: feedback,
                        quick: quick
                    },
                    dataType: "html",

                    beforeSend: function (data) {
                        $("#loadingInfo"+idx).show();
                    },
                    success: function (data) {
                        $(object, feedback).html(data);

                        var src = $(data).find(".iconUrl").attr("icon");
                        if(src != undefined) {
                            if ("#uploader" != feedback)
                                $("#pagesIcon" + idx).html('<img src="' + src + '">');
                        }
                    },
                    error: function (data) {
                        console.log(data);
                    },
                    complete: function (data) {
                    }

                });
                return false;

            });
            return false;
        },
        /**
         *  forgot Password
         */
        forgotPassword: function() {
            $("#forgot-password").submit(function(e) {
                var form = $(this);
                e.preventDefault();
                $(this).ajaxSubmit({
                    type: "POST",
                    url: $(form).attr("action"),
                    data: {
                        ajax: true
                    },
                    dataType: "html",
                    beforeSend: function(data) {
                        //console.log(data);
                    },
                    success: function(data) {

                        console.log(data);

                        $("#forgotbox-text").html(data);

                    },
                    error: function(data) {
                        //console.log(data);
                    },
                    complete: function(data) {
                    }

                });
                return false;

            });
        },
        /**
         *  initIcheck
         */
        initIcheck: function() {
            initIcheckLoading();
        },
        /**
         *  initIcheck
         */
        closeQuickAction: function() {
            closeQuickAction();
        },
    };
})();