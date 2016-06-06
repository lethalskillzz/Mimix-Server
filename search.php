<?php
include_once("functions.php");

$PacketType=$_POST["PacketHead"];
$UID = $_POST["UID"];
$search_query = $_POST["query"];



if(strcmp($PacketType,"search")==0)
{
  $user_result = search_user($UID,$search_query);
  $hash_result = search_hash($UID,$search_query);
  
  echo json_encode(array('user_result'=>$user_result,'hash_result'=>$hash_result));


}



?>
