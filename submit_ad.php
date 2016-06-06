<?php
include_once("functions.php");

$PacketType=$_POST["PacketHead"];
$UID = $_POST["UID"];
$Cat = $_POST["cat"];
$Title = $_POST["title"];
$Desc = $_POST["desc"];
$Price = $_POST["price"];

if(strcmp($PacketType,"submit_ad")==0)
{
    submit_ad($UID,$Cat,$Title,$Desc,$Price,"null");
}

if(strcmp($PacketType,"submit_ad_img")==0)
{
    $Img = $_POST["img"];
    submit_ad($UID, $Cat, $Title, $Desc, $Price, $Img);
    
}

?>
