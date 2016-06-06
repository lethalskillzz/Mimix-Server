<?php
include_once("functions.php");

$PacketType=$_POST["PacketHead"];
$UID = $_POST["UID"];


if(strcmp($PacketType,"refresh_ads_cat")==0)
{
$Cat = $_POST["cat"];

$ads = refresh_ads_cat($Cat);

$extra[] = array(
    
); 
//echo json_encode(array('ads'=>$ads));
echo json_encode(array('extra'=>$extra,'ads'=>$ads));


}


if(strcmp($PacketType,"get_ads_cat")==0)
{
$Cat = $_POST["cat"];
$LIMIT = $_POST["limit"];

$ads = get_ads_cat($Cat,$LIMIT);

echo json_encode(array('ads'=>$ads));

}

if(strcmp($PacketType,"get_ads")==0)
{
    
}

?>
