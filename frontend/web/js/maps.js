let lat = document.querySelector('.lat_data').innerHTML;
let lon = document.querySelector('.lon_data').innerHTML;

ymaps.ready(init);
function init(){
    var myMap = new ymaps.Map("map", {
        center: [lat, lon],
        zoom: 15
    });
}
