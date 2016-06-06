<?php
include_once("functions.php");

$Header = $_POST['PacketHead'];

if(strcmp($Header,"FollowORunfollow")==0) {
    
    $CNAME = $_POST['CNAME'];
    $MYUID = $_POST['UID'];
    
    $UID = set_user_id($CNAME);
    $following = following($MYUID);
    if(in_array($UID,$following))
    {
       unfollow_user($MYUID,$UID);
    }
else {
   
       follow_user($MYUID,$UID);
     
    }
    
}

?>