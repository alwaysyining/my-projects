<div class="container text-center">
    <div>
        
		    <h1 class="card-body">My Profile</h1>
            
            <div class="col-8 offset-2">
                <table class="table table-bordered table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Username:</th>
                            <th scope="col"><?php echo $user["username"]; ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">Email:</th>
                            <td><?php echo $user["email"]; ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Phone:</th>
                            <td><?php echo $user["phone"]; ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Birthday:</th>
                            <td><?php echo $user["birthday"]; ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Verified Status:</th>
                            <td><?php echo $user["verified_status"]; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

               
	</div>

    <div class="col-8 offset-2">
        <a class="nav-link" href="<?php echo site_url("register/edit_profile");?>">Edit My Profile</a>
    </div>

    <div class="py-5" onload="getLocation()">
        <button onclick="getLocation()">Show My location</button>

        <div id="coordinates"></div>
        <div id="map" class="map mt-3"></div>
        <script>
            var pos = document.getElementById("coordinates");

            function getLocation() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(showPosition);
                } else { 
                    pos.innerHTML = "Geolocation is not supported by this browser.";
                }
            }

            function showPosition(position) {
                const latitude  = position.coords.latitude;
		        const longitude = position.coords.longitude;
                /* Print out geolocation. */
                pos.innerHTML = "Latitude: " + latitude + 
                    "<br>Longitude: " + position.coords.longitude;

                    /* Show location on the map. */
                    var map = new ol.Map({
                        target: 'map',
                        layers: [
                            new ol.layer.Tile({
                                source: new ol.source.OSM()
                            })
                        ],
                        view: new ol.View({
                            center: ol.proj.fromLonLat([longitude, latitude]),
                            zoom: 16,
                            minZoom: 2,
                            maxZoom: 22,
                        })
                    });
                    var pnt = new ol.layer.Vector({
                        source: new ol.source.Vector({
                            features: [
                                new ol.Feature({
                                    geometry: new ol.geom.Point(ol.proj.fromLonLat([longitude, latitude]))
                                })
                            ]
                        })
                    });
                    map.addLayer(pnt);               
            }
        </script>
        <style>
            .map {
                height: 400px;
                width: 100%;
            }
        </style>
    </div>
    

</div>





