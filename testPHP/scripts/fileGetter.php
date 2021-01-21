<?php 
include 'pythonHelper.php';


function inIndex($refDict, $collection, $fileName=""){
    //Returns true if only collection and it exists in ref or if file in collection and in index
    //Else returns false and adds files or collections in if not in ref

    if(array_key_exists($collection, $refDict)){
        if($fileName != ""){
            $tempDict = $refDict[$collection];
            $fileNameNew = substr($fileName, 0, strlen($fileName) - 5);
            if(array_key_exists($fileNameNew, $tempDict)){
                return $tempDict[$fileNameNew]["inIndex"];
            }
            else{
                $in = array("file_ref", $collection, $fileNameNew);
                //Python Path
                $arr = executePython("/mnt/d/SSDAN/Webchip/webchip/data", "manageIndex.py", $in);
                return False;
            }
        }

        else{
            return true;
        }
    }

    else{
        $in = array("collection_ref", $collection);
        $arr = executePython("/mnt/d/SSDAN/webchip/Webchip/data", "manageIndex.py", $in);
        return false;
    }

}

function getReferenceDict($path){
    $json = file_get_contents($path);
    return json_decode($json, true);
}

function getAllDataDirs($path, $refDict){
    $dir = scandir($path);
    
    $i = 0;
    $returnDirs = [];

    foreach($dir as $d){
        // Ignores . and ..
        if ($i >= 2){
            $fullPath =  $path . '/' . $d;
            $isDir =  is_dir($fullPath);
            if($isDir && $d != "__pycache__" && $d != "prevFiles"){
                $dirInRef = inIndex($refDict, $d);
                $returnDirs[$d] = $dirInRef;
            }
        }
        $i += 1;
    }


    return $returnDirs;
}

function getAllFilesInCollection($path, $collection, $refDict){
    $fullPath = $path . "/" . $collection;
    $files = scandir($fullPath);

    $returnFiles = [];

    $i = 0;
    foreach($files as $f){
        if($f[0] == '.'){
            $i += 1;
            continue;
        }
        if ($i >=2){
            $fileInIndex = inIndex($refDict, $collection, $f);
            $returnFiles[$f] = $fileInIndex;
        }
        $i += 1;
    }

    return $returnFiles;

}


?>