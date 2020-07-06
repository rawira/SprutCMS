<?php

error_reporting(E_ERROR);

// ############################################################################

$url = rawurldecode($_GET['url']);

if (!$url) {
	header('HTTP/1.0 404 Not Found');
	echo "<h1>Error 404 Not Found</h1>";
	echo "The page that you have requested could not be found.";
	exit();
}

// Get new dimensions
list($width, $height) = getimagesize($url);

if (!$width || !$height) {
	header('HTTP/1.0 404 Not Found');
	echo "<h1>Error 404 Not Found</h1>";
	echo "The page that you have requested could not be found.";
	exit();
}

// Content type
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Max-Age: 86400'); // cache for 1 day
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Expires: " . date("D, j M Y", strtotime("tomorrow")) . " 02:00:00 GMT");
header('Content-Type: image/jpeg');

// percentage of resize
$percent = 600 / $width;

$new_width = $width * $percent;
$new_height = $height * $percent;

// Resample
$image_p = imagecreatetruecolor($new_width, $new_height);
$image = imagecreatefromjpeg($url);

if ($image !== FALSE) {
	imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
} else {
	header('HTTP/1.0 404 Not Found');
	echo "<h1>Error 404 Not Found</h1>";
	echo "The page that you have requested could not be found.";
	exit();
}

// Output
imagejpeg($image_p, null, 80);

?>