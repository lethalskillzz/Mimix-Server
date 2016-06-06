<?php
include_once("util_functions.php");

$obj = file_get_contents('php://input');

$json_o=json_decode($obj);

if(strcmp($json_o->header,"get_addons")==0)
{
   $i_addons = array();
    
   foreach($json_o->i_addons as $c)
   {    
    $i_addon_name = "'".$c->i_addon_name;
    array_push($i_addons, $i_addon_name."'");   
   }
    $addons=get_addons($i_addons);     

    echo json_encode(array('addons'=>$addons));
    //echo json_encode($json_o->i_addons);
    
    
    
}




?>

