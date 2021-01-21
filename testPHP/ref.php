<html>
 <head>
  <title>PHP Test</title>
 </head>
 <body>
 <?php
    
    $switchVar = 1;
    $text = "Bye Bye";

    function sayHi(){
        return "Hello";
    }
 ?> 

 <h2>
 <?php
    /*
    SUBSTRING
    // echo substr($text, 0, 2);
    */

    //Arrays
    $hi = sayHi();
    $arr = array(1,2,3);
    // echo $arr[0] gives first element
    // echo $arr says array
    // Pushes to end of array
    array_push($arr, 72);

    /*
    // Dictonaries
    // More like associative arrays
    */
    $dict = array(
      "foo" => "bar",
      "bar" => "foo",
    );

    // If Statmenets
    // Works like js/C++. Curly braces and &&.
    // Use elseif instead of else if
    // Terenary operator is like js $cond ? 'val' : 'otherVal';
    
    // Loops
    // While loop is like js and c++
    //For loop works like js and c++
    // Length of array is count(arrName)

      /*
   $in = array("edit_title", "employ10.json", "abc123", "idktest123");
   $in = array("remove_name", "employ10.json", "abc123");
   $in = array("remove_collection", abc123);
   $in = array("insert_collection", "abc123");
   $in = array("insert_name", "employ10.json", "abc123");
  


   $arr = executePython("/mnt/c/Users/7339R/Documents/SSDAN/webchip/data", "manageIndex.py", $in);
   printArr($arr);
   $ver = verifyOut($arr);
   */

   //$out = handleScriptInput("/mnt/c/Users/7339R/Documents/SSDAN/webchip/data", "manageIndex.py", $in);
   
     //$dirs = getAllDataDirs("/mnt/c/Users/7339R/Documents/SSDAN/webchip/data");
   //$files = getAllFilesInCollection("/mnt/c/Users/7339R/Documents/SSDAN/webchip/data", "abc123", $refDict);
   //$out = inIndex($refDict, "abc123");
   //print_r($out);

   /*Get title
   $out = getTitle("/mnt/c/Users/7339R/Documents/SSDAN/webchip/data", "employ10.json", "abc123");
   print_r($out);
   */


    echo $hi;
    echo $dict["foo"];

    for ($i = 0; $i < count($arr); $i++){
       echo $arr[$i];
    }

    echo $text;
 ?> 
 </h2>
 </body>
</html>

