<?php

include_once("functions.php");

$PacketType=$_POST["PacketHead"];
$UID = $_POST["UID"];



if(strcmp($PacketType,"add_msg")==0)
{
 $TNAME = $_POST["Target_Name"];
 $POS = $_POST["position"];
 $TID = set_user_id($TNAME);    
 $msgbody = $_POST['msg_body'];
 //$msgbody = substr($_POST['msg_body'],0,140);
 add_msg($UID,$TID,$msgbody,$POS);

}

if(strcmp($PacketType,"get_msg")==0)
{

$TNAME = $_POST["Target_Name"];
$POS = $_POST["position"];
$TID = set_user_id($TNAME);  
    
 $msgs = get_msg($UID,$TID,$POS);
 $isTogether = isTogether($UID,$TID);

 $extra[]= array(    
     'isTogether'=>$isTogether
 );
 
 echo json_encode(array('extra'=>$extra,'msgs'=>$msgs));  
 
}


if(strcmp($PacketType,"get_msgmonitor")==0)
{

 $msgs = get_msgmonitor($UID);
 echo json_encode(array('msgs'=>$msgs)); 

}



if(strcmp($PacketType,"get_msglist")==0)
{
 $POS = $_POST["position"];
 $msgs = get_msglist($UID,$POS);
 echo json_encode(array('msgs'=>$msgs)); 

}


if(strcmp($PacketType,"msg_read")==0)
{

 $MID = $_POST["READMID"];   
    
 msg_read($UID,$MID);

}
?>
