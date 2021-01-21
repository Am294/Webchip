<?php
include '../scripts/fileGetter.php';

header("Content-Type: application/json");
$collection =  $_GET['collection'];
$file = $_GET['file'];
$title = getTitle("/mnt/d/SSDAN/Webchip/webchip/data", $file, $collection);
$response = array($title);


echo json_encode($response);


?>

