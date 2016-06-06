<?php
include_once("util_functions.php");

$PacketType=$_POST["PacketHead"];
$UID = $_POST["UID"];


if(strcmp($PacketType,"refresh_hotmix")==0)
{

$posts = refresh_hotmix();

$extra[] = array(   
);

echo json_encode(array('extra'=>$extra,'posts'=>$posts));
}


if(strcmp($PacketType,"get_hotmix")==0)
{
$LIMIT = $_POST["limit"];

$posts = get_hotmix($LIMIT);

echo json_encode(array('posts'=>$posts));

}



?>
