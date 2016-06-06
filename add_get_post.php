<?php

include_once("functions.php");

$PacketType=$_POST["PacketHead"];

$UID = $_POST["UID"];

if(strcmp($PacketType,"add_post_img")==0)
{
    
$body = substr($_POST['post_body'],0,140);
$img = $_POST['post_img'];

$data = add_post($UID,$body,$img);

echo json_encode(array('data'=>$data));

}


if(strcmp($PacketType,"add_post")==0)
{
    
$body = substr($_POST['post_body'],0,140);

$data = add_post($UID,$body,"null");

echo json_encode(array('data'=>$data));
}


if(strcmp($PacketType,"get_post")==0)
{

$LIMIT = $_POST["PostLoc"];
    
$users = show_users($UID);
if (count($users)){
	$myusers = array_keys($users);
}else{
	$myusers = array();
}
$myusers[] = $UID;

$posts = show_posts($UID,$myusers,$LIMIT); 

/*$NewLIM=$LIMIT+5;
$PostLoc[] = array(
    'postloc'=>$NewLIM
);*/
//print_r($posts);
echo json_encode(array(/*'extra'=>$PostLoc,*/'posts'=>$posts));
}


if(strcmp($PacketType,"refresh")==0)
{
   
$users = show_users($UID);
if (count($users)){
	$myusers = array_keys($users);
}else{
	$myusers = array();
}
$myusers[] = $UID;

$posts = refresh_posts($UID,$myusers);
$extra[] = array(
    
); 

echo json_encode(array('extra'=>$extra,'posts'=>$posts));
}



if(strcmp($PacketType,"delete_post")==0)
{
 $PID = $_POST["PID"];
   
 delete_post($UID,$PID);

}


?>








