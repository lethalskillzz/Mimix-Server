<?php


include_once("functions.php");

$PacketType=$_POST["PacketHead"];
$MYUID = $_POST["UID"];


if(strcmp($PacketType,"Get_MAIN")==0)
{
   
$users = showMAIN_users($MYUID);
$trendhash = get_trendhash();

echo json_encode(array('trendhash'=>$trendhash,'users'=>$users));    
  
}


if(strcmp($PacketType,"Get_ALL")==0)
{
   
$LIMIT = $_POST["PostLoc"];
$users = showALL_users($MYUID,$LIMIT);

echo json_encode(array('users'=>$users));    
  
}



if(strcmp($PacketType,"Get_Myfollowing")==0)
{
    
$users = show_following($MYUID,$MYUID);

if (count($users)){

echo json_encode(array('users'=>$users));    
  
/*foreach ($users as $key => $value){
	echo "$value\n";
}*/
        
}
}


if(strcmp($PacketType,"Get_Myfollowers")==0)
{    

$users = show_followers($MYUID,$MYUID);

if (count($users)){

echo json_encode(array('users'=>$users));    
    
}
}





if(strcmp($PacketType,"Get_following")==0)
{

$USRNAME = $_POST["UsrNME"];
$UID = set_user_id($USRNAME);
    
$users = show_following($UID,$MYUID);

if (count($users)){

echo json_encode(array('users'=>$users));    
  
/*foreach ($users as $key => $value){
	echo "$value\n";
}*/
    
    
}
}


if(strcmp($PacketType,"Get_followers")==0)
{
 
$USRNAME = $_POST["UsrNME"];
$UID = set_user_id($USRNAME);

$users = show_followers($UID,$MYUID);

if (count($users)){

echo json_encode(array('users'=>$users));    
    
}
}


?>
