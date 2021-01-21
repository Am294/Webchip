<?php 
include 'scripts/fileGetter.php';
include 'scripts/edit.php';
?>

<style>
   <?php 
   include 'css/style.css';
   ?>
</style>




 <?php

   
   $updateCollection =  $_GET['collection'];
   $didUpdate = $_GET['updated'];
  
   if(isset($_GET['funcType'])){
      $t = $_GET['funcType'];
      $didUpdate = editIndex($updateCollection, ' ', $t);
      
   }

   //$refDict = getReferenceDict("/afs/umich.edu/group/s/ssdan/Public/html/sites/all/themes/bootstrap_subtheme/js/data/reference.json");
   $refDict = getReferenceDict("/mnt/d/SSDAN/Webchip/webchip/data/reference.json");

   
 ?>

<?php

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

   function generateDirsHtml($refDict){
      //$dirs = getAllDataDirs("/afs/umich.edu/group/s/ssdan/Public/html/sites/all/themes/bootstrap_subtheme/js/data", $refDict);
      $dirs = getAllDataDirs("/mnt/d/SSDAN/Webchip/webchip/data", $refDict);
      $counter = 0;

      foreach($dirs as $d => $val){

       
         /*
         div class="redHover" onclick="editIndex('none','<?php echo $d;?>','removeCollection');"> 
               <p> Remove All From Index </p>
            </div>
            <a href=<?php echo "files.php?collection=" . $d; ?> >
               <h3> <?php echo $d; ?> </h3>
            </a>
            <div class="greenHover" onclick="editIndex('none','<?php echo $d;?>','insertCollection');"> 
               <p> Add All To Index </p>
            </div>
         */
         ?>

         <div class="lineStyle"  >
            <div></div>
            <div class="redHover"> 
               <a href=<?php echo 'index.php?collection=' . $d  . '&funcType=removeCollection' ?>>
                  <p> Remove All From Index </p>
               </a>
            </div>
            <a href=<?php echo "files.php?collection=" . $d; ?> >
               <h3> <?php echo $d; ?> </h3>
            </a>
            <div class="greenHover"> 
               <a href=<?php echo 'index.php?collection=' . $d  . '&funcType=insertCollection' ?>>
                  <p> Add All To Index </p>
               </a>
            </div>
         </div>

         <?php
         $counter += 1;
      }
   }

?>
 
 <html>
 <head>
  <title>Modify index.json</title>
 </head>
 <body>
    <div id="titleHeader">
      <h1> Modify Index </h1>
      <?php

      if (isset($didUpdate)){
         if($didUpdate){addEditBox("Sucessfully updated " . $updateCollection, $didUpdate);}
         else{addEditBox("Failed to edit " . $updateCollection, $didUpdate);}
      }

      ?>
    </div>

    <a href="index.php" >
      <h3 style="text-align: center">Directories</h3>
    </a>
    <div class="infoStyle">
      <div></div>
      <div id="middleStyle">
         <?php generateDirsHtml($refDict) ?>
      </div>
    </div>
 </body>

 <script>
    <?php 
   include 'js/modal.js';
   ?>
</script>

</html>