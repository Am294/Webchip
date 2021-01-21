<?php
include '../scripts/fileGetter.php';

header("Content-Type: application/json");
$collection =  $_GET['collection'];
$file = $_GET['file'];
$title = getTitle("/mnt/c/Users/7339R/Documents/SSDAN/webchip/data", $file, $collection);
$response = array($title);


echo json_encode($response);


?>

