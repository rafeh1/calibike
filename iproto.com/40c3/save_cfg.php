<?php

$filename = $_GET['fn'] ?? 'default.gpx';
$filename = basename($filename);

$xmlData = file_get_contents('php://input');

if (!empty($xmlData)) {

    file_put_contents(__DIR__ . "/" . $filename, $xmlData);

    http_response_code(200);
    echo "OK";

} else {
    http_response_code(400);
    echo "NO DATA";
}
?>