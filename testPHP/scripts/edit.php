<?php
include '../scripts/pythonHelper.php';

//header("Content-Type: application/json");
//$collection =  $_GET['collection'];
//$file = $_GET['file'];
//$funcType = $_GET['funcType'];


function editIndex($collection, $file, $funcType){
    $path = "/mnt/d/SSDAN/Webchip/webchip/data";
    //$path = "/afs/umich.edu/group/s/ssdan/Public/scripts";

    if ($funcType == "removeFile"){
        $out = removeName($path, $file, $collection);
        if(gettype($out) == "boolean" && $out){
            //echo json_encode(True);
            return True;
        }
    
        else{
            //echo json_encode(False);
            return False;
        }
    }
    
    
    if ($funcType == "insertFile"){
        $out = insertName($path, $file, $collection);
        if(gettype($out) == "boolean" && $out){
            //echo json_encode(True);
            return True;
        }
    
        else{
            //echo json_encode(False);
            return False;
        }
    }
    
    if ($funcType == "removeCollection"){
        $out = removeCollection($path,  $collection);
        if(gettype($out) == "boolean" && $out){
            //echo json_encode(True);
            return True;
        }
    
        else{
            //echo json_encode(False);
            return False;
        }
    }
    
    if ($funcType == "insertCollection"){
        $out = insertCollection($path,  $collection);
        if(gettype($out) == "boolean" && $out){
            //echo json_encode(True);
            return True;
        }
    
        else{
            //echo json_encode(False);
            return False;
        }
    }
}


?>