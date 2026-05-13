<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Geolocation Example</title>
</head>

<body>
    <h2>Get Your Location</h2>
    <button onclick="get_location()">Get My Location</button>
    <p id="status"></p>
    <p id="address"></p>

    <script>
        function get_location() {
            const statusElement = document.getElementById('status');
            const addressElement = document.getElementById('address');

            if (navigator.geolocation) {
                statusElement.textContent = "Fetching your location...";
                addressElement.textContent = "";

                navigator.geolocation.getCurrentPosition(function(position) {
                    const latitude = position.coords.latitude;
                    const longitude = position.coords.longitude;

                    // Send latitude and longitude to PHP via AJAX
                    fetch('location.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: `latitude=${latitude}&longitude=${longitude}`
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.address) {

                                addressElement.textContent = `Your Address: ${data.address}`;
                                statusElement.textContent = "";
                            } else {
                                addressElement.textContent = "Unable to retrieve address.";
                                statusElement.textContent = "";
                            }
                        })
                        .catch(error => {
                            addressElement.textContent = "Error fetching address.";
                            statusElement.textContent = "";
                        });
                }, function(error) {
                    switch (error.code) {
                        case error.PERMISSION_DENIED:
                            statusElement.textContent = "Permission denied. Please allow location access.";
                            break;
                        case error.POSITION_UNAVAILABLE:
                            statusElement.textContent = "Location information is unavailable.";
                            break;
                        case error.TIMEOUT:
                            statusElement.textContent = "Request to get location timed out.";
                            break;
                        case error.UNKNOWN_ERROR:
                            statusElement.textContent = "An unknown error occurred.";
                            break;
                    }
                });
            } else {
                statusElement.textContent = "Geolocation is not supported by this browser.";
            }

        }
    </script>
</body>

</html>