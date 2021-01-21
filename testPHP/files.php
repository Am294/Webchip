<?php 
include 'scripts/fileGetter.php';
include 'scripts/edit.php';

$collection =  $_GET['collection'];
$didUpdate = $_GET['updated'];
$updatedFile = $_GET['file'];

//$path = "/afs/umich.edu/group/s/ssdan/Public/scripts ";
$path = "/mnt/d/SSDAN/Webchip/webchip/data";

if (isset($_POST['title'])) {

   $title = $_POST['title'];
   $collection = $_POST['collection'];
   $file = $_POST['file'];

   $ret = editTitle($path, $file, $collection, $title);
   
   // Instead of localhost:8000 I think I will have to use https://ssdan.net/. Actually, that may require login
   // SSDAN.net works. Just use that. Localhost doesn't work on the server
   echo "<script>window.location = 'http://localhost:8000/files.php?collection=" . $collection . "&updated=" .  $ret . "&file=" . $file . "'</script>";
}

if(isset($_GET['funcType'])){
   $t = $_GET['funcType'];
   $didUpdate = editIndex($collection, $updatedFile, $t);
   
}

//$refDict = getReferenceDict("/afs/umich.edu/group/s/ssdan/Public/html/sites/all/themes/bootstrap_subtheme/js/data/reference.json");
$refDict = getReferenceDict("/mnt/d/SSDAN/Webchip/webchip/data/reference.json");
//$files = getAllFilesInCollection("/afs/umich.edu/group/s/ssdan/Public/html/sites/all/themes/bootstrap_subtheme/js/data", $collection, $refDict);
$files = getAllFilesInCollection("/mnt/d/SSDAN/Webchip/webchip/data", $collection, $refDict);



?>

<style>
   <?php 
   include 'css/style.css';
   include 'css/modal.css'
   ?>
</style>



<?php

  
   function generateFilesHtml($collection, $refDict, $files){
      
     
   
      $counter = 0;

      foreach($files as $f => $val){

         ?>

         <div class="lineStyle"  >
            <div></div>
            
                <?php 
                if($val){
                   /*
                    ?>
                    <div class="redHover" onclick="editIndex('<?php echo $f;?>','<?php echo $collection;?>','removeFile');" >
                        <p> Remove From Index </p>
                    </div>
                    <?php
                    */
                    ?>
                    <div class="redHover" >
                        <a href=<?php echo 'files.php?collection=' . $collection . '&file=' . $f . '&funcType=removeFile' ?>>
                           <p> Remove From Index </p>
                        </a>
                    </div>
                    <?php
                }
                
                else{
                   /*
                    ?>
                    <div class="greenHover" onclick="editIndex('<?php echo $f;?>','<?php echo $collection;?>','insertFile');">
                        <p> Add To Index </p>
                    </div> 
                    <?php
                    */
                    ?>
                    <div class="greenHover" >
                        <a href=<?php echo 'files.php?collection=' . $collection . '&file=' . $f . '&funcType=insertFile' ?>>
                           <p> Add To Index </p>
                        </a>
                    </div>
                    <?php
                }
                 
                ?>
            
            
            <h3 style=<?php if($val){ echo"color:green";} else{ echo "color:red";} ?> > 
                <?php echo $f; ?> 
            </h3>
           
            <div class="editHover"  onclick="editTitleClick('<?php echo $f;?>','<?php echo $collection;?>');">
               <p> Edit Title </p>
            </div>
         </div>

         <?php
         $counter += 1;
      }
   }

   function addEditBox($message, $success){
      if ($success){
         ?>
         <div class="sucessBox">
            <h3>
            <?php
               echo $message;
            ?>
            </h3>
         </div>
         <?php
      }


      else{
         ?>
         <div class="failBox">
            <h3>
            <?php
               echo $message;
            ?>
            </h3>
         </div>
         <?php
      }


   }


?>


<html>
 <head>
  <title>Modify Collections</title>
 </head>
 <body>
    <div id="titleHeader">
    <a href="index.php">
      <h1> <?php echo $collection; ?> </h1>
   </a>
      <?php

      if (isset($didUpdate)){
         if($didUpdate){addEditBox("Sucessfully updated " . $updatedFile, $didUpdate);}
         else{addEditBox("Failed to edit " . $updatedFile, $didUpdate);}
      }

      ?>
    </div>

    <a href=<?php echo "files.php?collection=" . $collection; ?> >
      <h3 style="text-align: center">
         Files
      </h3>
   </a>
    <div class="infoStyle">
      <div></div>
      <div id="middleStyle">
         <?php generateFilesHtml($collection, $refDict, $files) ?>
      </div>
      <div id="myModal" class="modal">

      <!-- Modal content -->
      <div class="modal-content">
         <span onclick="closeModal()" class="close">&times;</span>
      <p>Edit Title</p>
         <div id="hiddenContentDiv" style="margin-right:1%;">

         </div>
      </div>
      </div>

    </div>
 </body>

 <script>
    <?php 
   include 'js/modal.js';
   ?>
</script>


</html>