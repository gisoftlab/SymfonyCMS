
/*!
 * GISOFT WIDGET
 * Damian Ostraszewski
 * version: 1.2 (01-10-2013)
 * @requires jQuery v2.0.0 or later 
 *  JSONP
 *  cross-domain AJAX with dynamic script tag
 *  
 */

if (typeof jQuery == 'undefined') {
    if (!window.jQuery) {
        var jq = document.createElement('script');
        jq.type = 'text/javascript';        
        jq.src = 'http://code.jquery.com/jquery-2.0.3.min.js';
        document.getElementsByTagName('head')[0].appendChild(jq);
    }
}

if (typeof ContarWidget == 'undefined') {
    if (!window.ContarWidget) {
        var jq = document.createElement('script');
        jq.type = 'text/javascript';        
        jq.src = 'http://www.gisoft.pl/widget/js/gsWidgetJson.js';

    }
    document.getElementsByTagName('head')[0].appendChild(jq);
}