(function ($, drupalSettings) {
    $(document).ready(function () {

      let lat = document.getElementById('lat').innerText;
      let lon = document.getElementById('lon').innerText;

      var mymap = L.map('mapid').setView([lat, lon], 9);

      L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
        maxZoom: 18,
        id: 'mapbox.streets-satellite',
        accessToken: 'pk.eyJ1IjoibGVvZmlzaG1hbiIsImEiOiJjaXo0NWVrYWIwNXljMnFuMXNhOG1yeDhxIn0.RbC6BlH8H7T0TwnwWrwS9Q'
      }).addTo(mymap);

   //   console.log('lat', lat, 'lon', lon);
    });

  })(jQuery, drupalSettings);
