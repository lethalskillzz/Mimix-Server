<?php


include_once("functions.php");

$PacketType=$_POST["PacketHead"];
$UID = $_POST["UID"];
$PID = $_POST["pid"];


if(strcmp($PacketType,"add_comment")==0)
{
    $COMMENT = $_POST["comment"];
    
    add_comment($PID,$UID,$COMMENT);
   
    
}

if(strcmp($PacketType,"get_comment")==0)
{
    $comments = get_comment($PID);
    $commentcount=  get_commentsCount($PID);
    
    $extra[]= array(    
     'commentcount'=>$commentcount
    );
    
    echo json_encode(array('extra'=>$extra,'comments'=>$comments));
}
?>
