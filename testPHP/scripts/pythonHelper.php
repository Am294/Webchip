<?php 

    function executePython($path, $filename, $vars){
        $command = "python3 " . $path . "/" . $filename;

        foreach ($vars as $v){
            $command .= (" " . $v); 
        }
        
        exec($command, $output, $ret_code);
    
        return $output;
        
    }

    function printArr($arr){
        foreach ($arr as $a){
            echo $a;
            echo "\n";
        } 
    }

    function verifyOut($arr, $typeCheck){
        $length = count($arr);
        if ($arr[$length - 1] == "True"){
            return array(true, $arr[$length - 2]);
        }
        // Edge case for getting file name
        else if($typeCheck == "get_title" && $arr[$length - 1] != "False"){
            return array("returnVal", $arr[$length - 1]);
        }
        else{
            return array(false, $arr[$length - 2]);
        }
    }

    function handleScriptInput($filePath, $fileName, $vars){
        $arr = executePython($filePath, $fileName, $vars);
        $ver = verifyOut($arr, $vars[0]);

        if($ver[0] == "returnVal" && gettype($ver[0]) == "string"){return $ver[1];}
        else if ($ver[0] == true){return true;}
        else{return $ver[1];}
    }

    function editTitle($path, $fileName, $collection, $newTitle){
        $in =  array("edit_title", $fileName, $collection, $newTitle);
        return handleScriptInput($path, "manageIndex.py", $in);
    }

    function getTitle($path, $fileName, $collection){
        $in =  array("get_title", $fileName, $collection);
        return handleScriptInput($path, "manageIndex.py", $in);
    }

    function removeName($path, $fileName, $collection){
        $in =  array("remove_name", $fileName, $collection);
        return handleScriptInput($path, "manageIndex.py", $in);
    }

    function insertName($path, $fileName,  $collection){
        $in =  array("insert_name", $fileName, $collection);
        return handleScriptInput($path, "manageIndex.py", $in);
    }

    function removeCollection($path,  $collection){
        $in =  array("remove_collection", $collection);
        return handleScriptInput($path, "manageIndex.py", $in);
    }

    function insertCollection($path,  $collection){
        $in =  array("insert_collection", $collection);
        return handleScriptInput($path, "manageIndex.py", $in);
    }

   

 ?> 