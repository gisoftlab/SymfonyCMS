/*!
 * GISOFT  CUSTOM JS
 * Damian Ostraszewski
 * @requires jQuery v1.9.0 or later
 *
 */

$(document).on("click", ".btnShowBlock", function(){
    $(this).next(".showBlock") .toggle();
    return false;
});


/**
 *  Close gallery
 */
$(document).on("click", "a.btn.close-gallery", function(){
    var href = $(this).attr("href");
    var closeTag = $(this).attr("closeTag");
    $(href).html("");

    $(closeTag+" a").removeClass("active");

    return false;
});

/**
 *  File open
 */
$(document).on("click", "span.input-file-btn", function(){
    var parent = $(this).parent();
    $("input[type=file]", parent).click();
    $("input[type=file]", parent).change(function () {
        $("div", parent).html($(this).val());
    });
});

/**
 *  Gallery actions
 *
 *  Delete
 */
$(document).on("click", ".thumbnail .view .tools a.delete-image", function(){
    var ajax = $(this).attr("ajax");
    try {
        ajax = JSON.parse(ajax);

        gsAjax.deleteIcon($(this),ajax.idx, ajax.idf, ajax.target, ajax.callback, ajax.confirmedMessage);
        return false;
    }
    catch (e) {
        console.log("Error - deleteIcon");
        console.log(e);
        return false
    }

    return false
});

/**
 *  Edit Icon title actions
 */
$(document).on("click", ".thumbnail .view .tools a.edit-title", function(){
    var ajax = $(this).attr("ajax");
    try {
        ajax = JSON.parse(ajax);
        gsAjax.editIconTitle($(this), ajax.idf, ajax.target);
        return false;
    }
    catch (e) {
        console.log("Error - editIconTitle");
        console.log(e);
        return false
    }

    return false
});

/**
 *  Edit Icon title actions
 */
$(document).on("click", ".thumbnail .view .tools a.setup-icon", function(){

    var ajax = $(this).attr("ajax");
    try {
        ajax = JSON.parse(ajax);
        gsAjax.setupIcon($(this),ajax.idx, ajax.target, ajax.srcx);
        return false;
    }
    catch (e) {
        console.log("Error - setupIcon");
        console.log(e);
        return false
    }

    return false
});

/**
 *  Edit Icon title actions
 */
$(document).on("click", ".gallery .fileManager input[type='submit']", function(){
   // var ajax = $(this).attr("ajax");

    var ajaxObj =  $(this).next("span");
    var ajax = $(ajaxObj).attr("ajax");

    try {
        ajax = JSON.parse(ajax);

        gsAjax.fileUploader($(this),ajax.idx, ajax.target, ajax.feedback, ajax.quick);
        $(this).submit();

        return false;
    }
    catch (e) {
        console.log("Error - fileUploader");
        console.log(e);
        return false
    }

    return false
});

/**
 *  Set Icon Title
 */
$(document).on("click", ".content-gallery .titleFileEdit a", function(){

    try {
        gsAjax.editIconTitleSubmit($(this));
        return false;
    }
    catch (e) {
        console.log(e);
        return false
    }

    return false
});


/**
 *  Quick form submit action
 */
$(document).on("click", ".add-icon.page-list", function(){
    var ajax = $(this).attr("ajax");

    try {
        ajax = JSON.parse(ajax);
        gsAjax.loadIconUploader($(this), ajax.target, ajax.post.id);
        return false;
    }
    catch (e) {
        console.log("Error - JSON is incorrect");
        console.log(e);
        return false
    }

    return falsel
});



$(document).ready(function() {
    // execute quick actions
    $(".quick-form a").on("click", function () {

        var ajax = $(this).attr("ajax");

        if(!ajax){
            window.location.href = $(this).attr("href");
        }

        try {
            var ajax = JSON.parse(ajax);
        }
        catch (e) {
            console.log("Error - JSON is incorrect")
            console.log(e);
            return false
        }

        try {
            if (gsAjax != undefined) {
                eval("var executeFunction = gsAjax." + ajax.action);

                executeFunction($(this), ajax.targetTag, ajax.post);
            }
        }
        catch (e) {
                console.log("Error - function is incorrect. Cheack => "+ajax.action)
                console.log(e);
                return false
            }

        return false;

    });


    if ($("#uploader") != undefined){
        var ajax = $("#uploader").attr("ajax");

        if (ajax != undefined) {
            try {
                var ajax = JSON.parse(ajax);
                gsAjax.loadUploader(ajax.selector, ajax.action, ajax.id);
            }
            catch (e) {
                console.log("Error - JSON is incorrect");
                console.log(e);
                return false
            }
        }
    }

    // iCheck
    gsAjax.initIcheck();


});

/**
 *
 */
$(document).ready(function() {

    //  Forgot Password
    gsAjax.forgotPassword();
});