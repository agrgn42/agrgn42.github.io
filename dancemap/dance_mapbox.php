<?php
/*
 * Title:   MySQL Points to GeoJSON
 * Notes:   Query a MySQL table or view of points with x and y columns and return the results in GeoJSON format, suitable for use in OpenLayers, Leaflet, etc.
 * Author:  Bryan R. McBride, GISP
 * Contact: bryanmcbride.com
 * GitHub:  https://github.com/bmcbride/PHP-Database-GeoJSON
 */


# Include required geoPHP library and define wkb_to_json function
// include_once('geoPHP/geoPHP.inc');
// function wkb_to_json($wkb) {
//     $geom = geoPHP::load($wkb,'wkb');
//     return $geom->out('json');
// }
# Connect to MySQL database
$conn = new PDO('sqlite:dance.db');
// mysql:host=127.0.0.1;dbname=dance', 'root', 'aragog743'
# Build SQL SELECT statement including x and y columns
$sql = 'SELECT *, Longitude AS x, Latitude AS y FROM Flickr';
/*
* If bbox variable is set, only return records that are within the bounding box
* bbox should be a string in the form of 'southwest_lng,southwest_lat,northeast_lng,northeast_lat'
* Leaflet: map.getBounds().pad(0.05).toBBoxString()
*/
if (isset($_GET['bbox']) || isset($_POST['bbox'])) {
    $bbox = explode(',', $_GET['bbox']);
    $sql = $sql . ' WHERE x <= ' . $bbox[2] . ' AND x >= ' . $bbox[0] . ' AND y <= ' . $bbox[3] . ' AND y >= ' . $bbox[1];
}
# Try query or error
$rs = $conn->query($sql);
if (!$rs) {
    echo 'An SQL error occured.\n';
    exit;
}
# Build GeoJSON feature collection array
$geojson = array(
   'type'      => 'FeatureCollection',
   'features'  => array()
);
# Loop through rows to build feature arrays
while ($row = $rs->fetch(PDO::FETCH_ASSOC)) {
    $properties = $row;
    # Remove x and y fields from properties (optional)
    unset($properties['x']);
    unset($properties['y']);
    $feature = array(
        'type' => 'Feature',
        'geometry' => array(
            'type' => 'Point',
            'coordinates' => array(
                $row['x'],
                $row['y']
            )
        ),
        'properties' => $properties
    );
    # Add feature arrays to feature collection array
    array_push($geojson['features'], $feature);
}
header('Content-type: application/json');
// echo json_encode($geojson, JSON_NUMERIC_CHECK);
// $fp = fopen('dance.geojson', 'w');
// fwrite($fp, json_encode($geojson, JSON_NUMERIC_CHECK));   //here it will print the array pretty
// fclose($fp);
$conn = NULL;
?>







// include_once('geoPHP/geoPHP.inc');
// function wkb_to_json($wkb) {
//     $geom = geoPHP::load($wkb,'wkb');
//     return $geom->out('json');
// }


// # Connect to MySQL database
// $conn = new PDO('mysql:host=127.0.0.1;dbname=dance', 'root', 'aragog743');

// # Build SQL SELECT statement including x and y columns
// $sql = 'SELECT *, Latitude AS x, Longitude AS y FROM Flickr';

// /*
// * If bbox variable is set, only return records that are within the bounding box
// * bbox should be a string in the form of 'southwest_lng,southwest_lat,northeast_lng,northeast_lat'
// * Leaflet: map.getBounds().pad(0.05).toBBoxString()
// // */
// // if (isset($_GET['bbox']) || isset($_POST['bbox'])) {
// //     $bbox = explode(',', $_GET['bbox']);
// //     $sql = $sql . ' WHERE x <= ' . $bbox[2] . ' AND x >= ' . $bbox[0] . ' AND y <= ' . $bbox[3] . ' AND y >= ' . $bbox[1];
// // }

// # Try query or error
// $rs = $conn->query($sql);
// if (!$rs) {
//     echo 'An SQL error occured.\n';
//     exit;
// }

// # Build GeoJSON feature collection array
// $geojson = array(
//    'type'      => 'FeatureCollection',
//    'features'  => array()
// );

// # Loop through rows to build feature arrays
// while ($row = $rs->fetch(PDO::FETCH_ASSOC)) {
//     $properties = $row;
//     # Remove x and y fields from properties (optional)
//     unset($properties['x']);
//     unset($properties['y']);
//     $feature = array(
//         'type' => 'Feature',
//         'geometry' => array(
//             'type' => 'Point',
//             'coordinates' => array(
//                 $row['x'],
//                 $row['y']
//             )
//         ),
//         'properties' => $properties
//     );
//     # Add feature arrays to feature collection array
//     array_push($geojson['features'], $feature);
// }

// header('Content-type: application/json');
// echo json_encode($geojson, JSON_NUMERIC_CHECK);
// $fp = fopen('geodance.geojson', 'w');
// fwrite($fp, json_encode($geojson));
// fclose($fp);
// $conn = NULL;
?>