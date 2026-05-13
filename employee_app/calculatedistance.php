<?php 
$latitude = '21.2542';
$longitude = '81.6247';
$branch_latitude = '21.2542';
$branch_longitude = '81.6247';
calculateDistance($latitude, $longitude, $branch_latitude, $branch_longitude);
?>

<script type="text/javascript">
    function calculateDistance($latitude, $longitude, $branch_latitude, $branch_longitude) {
    // Earth radius in meters
    $earthRadius = 6371000;

    // Convert degrees to radians
    $lat1 = deg2rad($latitude);
    $lon1 = deg2rad($longitude);
    $lat2 = deg2rad($branch_latitude);
    $lon2 = deg2rad($branch_longitude);

    // Calculate the differences
    $deltaLat = $lat2 - $lat1;
    $deltaLon = $lon2 - $lon1;

    // Apply Haversine formula
    $a = sin($deltaLat / 2) * sin($deltaLat / 2) +
         cos($lat1) * cos($lat2) *
         sin($deltaLon / 2) * sin($deltaLon / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

    // Calculate distance
    $distance = $earthRadius * $c;

    
    return $distance; // Distance in meters
}

</script>