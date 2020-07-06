<?php

function getdb(){
    $host = 'localhost';
$root = 'root';
$pwd = '';
$db = 'BE';
$port=3306;
    
    try {
       
        $conn = mysqli_connect($host,$root,$pwd,$db,$port);
         echo "Connected successfully"; 
        }
    catch(exception $e)
        {
        echo "Connection failed: " . $e->getMessage();
        }
        return $conn;
    }
    
$con = getdb();
 if(isset($_POST["Import"])){

    $tablename = $_POST["tablename"];

    $q0 = "CREATE TABLE `$tablename` ( `Seq` INT(13) NOT NULL AUTO_INCREMENT , `TransID` INT(13) NOT NULL , `ItemNo` INT(8) NOT NULL , `Category_id` INT(4) NOT NULL , PRIMARY KEY (`Seq`)) ENGINE = InnoDB;";
    $result0 = mysqli_query($con, $q0);    

    $q1 = "INSERT INTO `dblist` ( `tablename`, `Status`) VALUES ( '$tablename', 0);";
    $result1 = mysqli_query($con, $q1);

    $filename=$_FILES["file"]["tmp_name"];    
     if($_FILES["file"]["size"] > 0)
     {
        $file = fopen($filename, "r");
          $seqno = 0;
          while (($getData = fgetcsv($file, 100, ",")) !== FALSE)
           {
             $sql = "INSERT into $tablename (Seq,TransID, ItemNo, Category_id) 
                   values ($seqno,'".$getData[0]."','".$getData[1]."','".$getData[2]."')";
              $seqno = $seqno + 1;
                   $result = mysqli_query($con, $sql);
        
           }
      if(!isset($result))
        {
          echo "<script type=\"text/javascript\">
              alert(\"Invalid File:Please Upload CSV File.\");
              window.location = \"index.htlm\"
              </script>";    
        }
        else {
            echo "<script type=\"text/javascript\">
            alert(\"CSV File has been successfully Imported.\");
            window.location = \"index.html\"
          </script>";

            $q3 = "UPDATE `dblist` SET `Status` = '1' WHERE `dblist`.`tablename` = '$tablename' ;";
            $result3 = mysqli_query($con, $q3);

            $q4 = "DELETE FROM $tablename WHERE `Seq`= 0;";
            $result4 = mysqli_query($con, $q4);
        }
           fclose($file);  
     }
  }   
 ?>