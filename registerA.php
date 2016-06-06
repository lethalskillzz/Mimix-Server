
<?php
include_once('functions.php');

   $Username = $_POST["username"]; 
   $Email = $_POST["email"]; 
   $Password = $_POST["password"];
   $Phone = $_POST["phone"];
   $Location = $_POST["location"];
   $Gender = $_POST["gender"];
   
   register($Username, $Phone, $Email, $Gender, $Location, $Password);
   
   //header("Location:index.php");
?>