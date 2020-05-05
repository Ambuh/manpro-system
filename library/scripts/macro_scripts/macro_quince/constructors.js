async function getLocation() {
    if (navigator.geolocation) {
         return  navigator.geolocation.getCurrentPosition(showPosition, showError);
    } else {

        return null;
    }

}let mainLocation; function showPosition(position){
    mainLocation={lat:position.coords.latitude,lon:position.coords.longitude}

}function showError(error) {
    switch(error.code) {
        case error.PERMISSION_DENIED:
            x.innerHTML = "User denied the request for Geolocation."
            break;
        case error.POSITION_UNAVAILABLE:
            x.innerHTML = "Location information is unavailable."
            break;
        case error.TIMEOUT:
            x.innerHTML = "The request to get user location timed out."
            break;
        case error.UNKNOWN_ERROR:
            x.innerHTML = "An unknown error occurred."
            break;
    }
};