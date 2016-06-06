<?php
$SERVER = 'localhost';
$USER = 'root';
$PASS = '';
$DATABASE = 'mimix_db';

define("GOOGLE_API_KEY", "AIzaSyCBeVutnkZ5s8NwNCnccs_oVwranXm05dU");
define("Mimix_id", 1);

//NOTIFY_IM
//NOTIFY_NEW_FOLLOW
//NOTIFY_POST_MENTION
//NOTIFY_SUBSCRIBE_POST
  


 $mylink = mysql_connect( $SERVER, $USER, $PASS, $DATABASE);

 // Check connection
 // if (mysqli_connect_errno())
  //{
  //echo "\nFailed to connect to MySQL: " . mysqli_connect_error();
  //}
  //echo "\nConnected to MySQL";

 

 // Create database
//$xsql = "CREATE DATABASE $DATABASE";
 $xsql = "CREATE DATABASE $DATABASE";

 mysql_query($xsql,$mylink);

 
  
  
 // Create table
$Csql = "CREATE TABLE `users` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`username` VARCHAR( 16 ) NOT NULL ,
`password` VARCHAR( 12 ) NOT NULL ,
`gender` ENUM( 'null', 'Male', 'Female' ) NOT NULL,
`phone` VARCHAR( 14 ) NOT NULL ,
`email` VARCHAR( 150 ) NOT NULL ,
`location` VARCHAR( 50 ) NOT NULL ,
`workplace` VARCHAR( 60 ) NOT NULL ,
`occupation` VARCHAR( 16 ) NOT NULL ,
`bio` VARCHAR( 140 ) NOT NULL ,
`reputation` FLOAT NOT NULL,
`commerce_reputation` FLOAT NOT NULL,
`gcm_regid` TEXT NOT NULL ,
`isVerified` ENUM( 'NO', 'YES' ) NOT NULL,
`award` VARCHAR( 20 ) NOT NULL ,
`status` ENUM( 'offline', 'online', 'away' ) NOT NULL,
`device` VARCHAR( 50 ) NOT NULL,
`last_seen` VARCHAR( 30 ) NOT NULL

) "; 

mysql_select_db($DATABASE);// or die('could not select database');
// Execute query
mysql_query($Csql,$mylink);
 

$Csql2 = "CREATE TABLE `posts` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`user_id` INT NOT NULL ,
`body` VARCHAR( 140 ) NOT NULL ,
`postimg` LONGTEXT NOT NULL ,
`stamp` VARCHAR( 30 ) NOT NULL ,
`IMG` VARCHAR( 4 ) NOT NULL ,
`linkid` VARCHAR( 236 ) NOT NULL 
) ";

mysql_select_db($DATABASE);// or die('could not select database');
// Execute query
mysql_query($Csql2,$mylink);



$Csql3 = "CREATE TABLE `msgs` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`userid` INT NOT NULL ,
`targetid` INT NOT NULL ,
`msgbody` LONGTEXT NOT NULL ,
`stamp` VARCHAR( 25 ) NOT NULL,
`status` ENUM( 'unread', 'read' ) NOT NULL,
`poped` ENUM( 'no', 'yes' ) NOT NULL
) ";

mysql_select_db($DATABASE);// or die('could not select database');
// Execute query
mysql_query($Csql3,$mylink);




$Csql4 =  "CREATE TABLE `following` (
`user_id` INT NOT NULL ,
`follower_id` INT NOT NULL ,
PRIMARY KEY ( `user_id` , `follower_id` )
) ";

mysql_select_db($DATABASE);// or die('could not select database');
// Execute query
mysql_query($Csql4,$mylink);


$Csql5 = "CREATE TABLE `ecommerce` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`userid` INT NOT NULL ,
`category` VARCHAR( 25 ) NOT NULL ,
`title` VARCHAR( 25 ) NOT NULL ,
`description` VARCHAR( 60 ) NOT NULL ,
`price` VARCHAR( 12 ) NOT NULL ,
`ad_img` LONGTEXT NOT NULL ,
`stamp` VARCHAR( 30 ) NOT NULL,
`IMG` VARCHAR( 4 ) NOT NULL 
) ";

mysql_select_db($DATABASE);// or die('could not select database');
// Execute query
mysql_query($Csql5,$mylink);


/*$Csql6 = "CREATE TABLE `comments` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`usrid` INT NOT NULL ,
`comment` VARCHAR( 140 ) NOT NULL  
) ";

mysql_select_db($DATABASE);// or die('could not select database');
// Execute query
mysql_query($Csql6,$mylink);*/


$Csql7 = "CREATE TABLE `ratings` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`postid` INT NOT NULL ,
`userid` INT NOT NULL ,
`rate` FLOAT NOT NULL  
) ";

mysql_select_db($DATABASE);// or die('could not select database');
// Execute query
mysql_query($Csql7,$mylink);





$Csql9 = "CREATE TABLE `notifications` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`type` VARCHAR( 25 ) NOT NULL,
`data` VARCHAR( 15 ) NOT NULL,
`body` VARCHAR( 140 ) NOT NULL,
`stamp` VARCHAR( 30 ) NOT NULL,
`userid` INT NOT NULL ,
`targetid` INT NOT NULL 
) ";

mysql_select_db($DATABASE);// or die('could not select database');
// Execute query
mysql_query($Csql9,$mylink);




$Csql10 = "CREATE TABLE `hashtags` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`hash` VARCHAR( 25 ) NOT NULL,
`count` INT NOT NULL 
) ";

mysql_select_db($DATABASE);// or die('could not select database');
// Execute query
mysql_query($Csql10,$mylink);


$Csql11 = "CREATE TABLE `comments` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`postid` INT NOT NULL ,
`userid` INT NOT NULL ,
`comment` VARCHAR( 140 ) NOT NULL,
`stamp` VARCHAR( 30 ) NOT NULL
) ";

mysql_select_db($DATABASE);// or die('could not select database');

mysql_query($Csql11,$mylink);


$Csql12= "CREATE TABLE `likes` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`postid` INT NOT NULL ,
`userid` INT NOT NULL ,
`stamp` VARCHAR( 30 ) NOT NULL
) ";

mysql_select_db($DATABASE);// or die('could not select database');

mysql_query($Csql12,$mylink);


//mysql_close($mylink);




?>
