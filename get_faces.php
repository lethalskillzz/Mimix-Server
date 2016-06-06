<?php
include_once("functions.php");
include_once("header.php");

$PacketType=$_POST["PacketHead"];
$UID = $_POST["UID"];

if(strcmp($PacketType,"get_faces")==0)
{

$users = show_users($UID);
if (count($users)){
	$myusers = array_keys($users);
}else{
	$myusers = array();
}
$myusers[] = $UID;
$myusers[] = Mimix_id;

$faces = get_faces($myusers);

echo json_encode(array('faces'=>$faces));


}



if(strcmp($PacketType,"get_face")==0)
{
 $user=$_POST["user"];
 
 $faces = get_faces($user);
 echo json_encode(array('faces'=>$faces));

 
}
?>
