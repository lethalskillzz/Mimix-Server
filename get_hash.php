<?php

include_once("functions.php");

$PacketType=$_POST["PacketHead"];
$UID = $_POST["UID"];
$HASH = $_POST["hash"];

if(strcmp($PacketType,"refresh")==0)
{
    $posts = refresh_hashes($UID,$HASH);
    
    $extra[] = array(
    
); 
    echo json_encode(array('extra'=>$extra,'posts'=>$posts));
}


if(strcmp($PacketType,"get_hash")==0)
{
  $LIMIT = $_POST["PostLoc"];
 
$posts =  get_hashes($UID,$HASH,$LIMIT);

echo json_encode(array('posts'=>$posts));

}
?>
