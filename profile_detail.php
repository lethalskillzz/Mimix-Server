<?php
session_start();
include_once("functions.php");

$PacketType=$_POST["PacketHead"];


if(strcmp($PacketType,"get_mydetail")==0)
{

$UID = $_POST["UID"];

$detail=profile_detail($UID);

//if (count($posts)){
 
echo json_encode(array('detail'=>$detail)); 

//}
}


if(strcmp($PacketType,"get_detail")==0)
{

$MYUID = $_POST["UID"];    
$UNAME = $_POST["UNAME"];
$UID=set_user_id($UNAME); 


$detail=profile_detail($UID);

$isTogether = isTogether($MYUID, $UID);

$extra[] = array(
    'isTogether'=>$isTogether
);

echo json_encode(array('extra'=>$extra,'detail'=>$detail)); 

}



if(strcmp($PacketType,"myprofile_posts")==0)
{

$UID = $_POST["UID"]; 
$LIMIT = $_POST["PostLoc"];
    
$posts = profile_posts($UID,$UID,$LIMIT);

echo json_encode(array('posts'=>$posts)); 

}





if(strcmp($PacketType,"profile_posts")==0)
{
$UNAME = $_POST["UNAME"];
$UID=set_user_id($UNAME); 
$MYUID = $_POST["UID"]; 
$LIMIT = $_POST["PostLoc"];

$posts = profile_posts($UID,$MYUID,$LIMIT);

echo json_encode(array('posts'=>$posts)); 

}


if(strcmp($PacketType,"changeprofpic")==0)
{
    $UID = $_POST["UID"];
    $IMG = $_POST["PROFPIC"];
    $target_file = 'img_';
    $target_file = $target_file.$UID;
    $target_file = $target_file.".png";
    $target_dir = 'profile/image/'.$target_file;
    $save_img = str_replace('data:image/png;base64,', '', $IMG);
    if(file_put_contents($target_dir, base64_decode($save_img)))
    {
         $data = array();
         echo json_encode(array('data'=>$data)); 

    }
 else {
        echo 'error';
    }
   
}


?>
