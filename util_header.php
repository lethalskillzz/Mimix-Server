<?php

$SERVER = 'localhost';
$USER = 'root';
$PASS = '';
$DATABASE = 'mimix_util_db';

$mylink = mysql_connect( $SERVER, $USER, $PASS, $DATABASE);
 
$xsql = "CREATE DATABASE $DATABASE";

 mysql_query($xsql,$mylink);

 
$sql1 = "CREATE TABLE `addons` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
`category` VARCHAR( 15 ) NOT NULL,
`addon_package` TEXT NOT NULL,
`addon_name` VARCHAR( 30 ) NOT NULL,
`img` LONGBLOB NOT NULL ,
`url` TEXT NOT NULL
) ";

mysql_select_db($DATABASE);// or die('could not select database');
// Execute query
mysql_query($sql1,$mylink);




$sql2 = "CREATE TABLE `hotmix` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
`adminid` INT NOT NULL,
`status` ENUM( 'approved', 'disapproved', 'pending' ) NOT NULL,
`title` VARCHAR( 100 ) NOT NULL,
`content` MEDIUMTEXT NOT NULL,
`cover`  LONGBLOB NOT NULL,
`category` VARCHAR( 15 ) NOT NULL,
`reference` VARCHAR( 15 ) NOT NULL,
`stamp` VARCHAR( 30 ) NOT NULL
) ";

mysql_select_db($DATABASE);// or die('could not select database');
// Execute query
mysql_query($sql2,$mylink);

//mysql_close($mylink2);


?>
