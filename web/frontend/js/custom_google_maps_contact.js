jQuery(document).ready(function() {

initialize();

    function initialize() {            
        
        var latlng = new google.maps.LatLng(52.742146, 15.235811);
        var settings = {
                zoom: 15,
                center: latlng,
                mapTypeControl: true,
                mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU},
                navigationControl: true,
                navigationControlOptions: {style: google.maps.NavigationControlStyle.SMALL},
                mapTypeId: google.maps.MapTypeId.ROADMAP};
        var map = new google.maps.Map(document.getElementById("map_canvas"), settings);
                
        var contentString = '<div>'+
                '<div id="googleNotice">'+
                '</div>'+
                '<h6 id="googleNoticeTitle"> P.U.H. TORHEN</h6>'+
                '<div id="googleNoticeContent">'+
                '<p>66-400 GORZÓW WLKP. <br/> UL. K. WYSZYŃSKIEGO 52</p>'+                           
                '</div>'+
                '</div>';
        var infowindow = new google.maps.InfoWindow({
                content: contentString
        });

        var logoImage = new google.maps.MarkerImage('/frontend/images/logo-min.png',
                new google.maps.Size(80,20),        
                new google.maps.Point(0,0),
                new google.maps.Point(26,30)
        );

        var logoShadow = new google.maps.MarkerImage('/frontend/images/logo-min-shadow.png',
                new google.maps.Size(80,20),                
                new google.maps.Point(0,0),
                new google.maps.Point(24, 28)
            );


        var companyPos = new google.maps.LatLng(52.742146, 15.235811);
        var companyMarker = new google.maps.Marker({
                position: companyPos,
                map: map,
                icon: logoImage,
                shadow: logoShadow,
                title:"P.U.H. TORHEN",
                zIndex: 3});
        
        google.maps.event.addListener(companyMarker, 'click', function() {
                infowindow.open(map,companyMarker);
        });             
    }

});