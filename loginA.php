<?php
include_once('functions.php');

   $Username = $_POST["username"]; 
   $Password = $_POST["password"]; 
   $GCM_ID = $_POST["gcm_regid"]; 
   
   if($Username&&$Password)
   {
       login($Username, $Password, $GCM_ID);
   }
 else {
    //  Print "Please enter username and password!";
}
   
   //header("Location:index.php");
   
?>