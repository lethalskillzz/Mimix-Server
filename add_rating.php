<?php
include_once("functions.php");

$PacketType=$_POST["PacketHead"];
$pid = $_POST["pid"];
$rate = $_POST["rate"];
$UID = $_POST["UID"];

if(strcmp($PacketType,"add_rate")==0)
{ 
    add_ratings($pid,$UID,$rate);
}

?>
