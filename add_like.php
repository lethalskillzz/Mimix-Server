<?php
include_once("functions.php");

$PacketType=$_POST["PacketHead"];
$pid = $_POST["pid"];
$UID = $_POST["UID"];

if(strcmp($PacketType,"add_like")==0)
{ 
    add_likes($pid,$UID);
}

?>
