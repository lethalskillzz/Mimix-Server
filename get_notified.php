<?php
include_once("functions.php");

$PacketType=$_POST["PacketHead"];

if(strcmp($PacketType,"getNotifications")==0)
{
   $UID = $_POST["UID"];
   $POS = $_POST["position"];
   $notifs = get_notifications($UID,$POS);
    
   echo json_encode(array('notifs'=>$notifs));
}


if(strcmp($PacketType,"get_notif_post")==0)
{
   $PID = $_POST["PID"];
   $notif_posts = get_notify_posts($PID);
    
   echo json_encode(array('notif_posts'=>$notif_posts));
}

?>
