<?php

include_once("functions.php");

$obj = file_get_contents('php://input');

$json_o=json_decode($obj);

if(strcmp($json_o->header,"get_phn")==0)
{
    $phone_contacts = array();
    
    foreach($json_o->contacts as $c)
    {    
       
        $username=get_phone_contact($json_o->uid,$c->phone);
      
        if($username!=null)
        {
        $uid=set_user_id($username);
        $face=get_notify_face($uid); 
        
        $phone_contacts[] = array(
           'username'=>$username,
           'contact'=>$c->contact,  
           'phone'=>$c->phone,
           'face'=>$face
               );
        }
       // array_push($phone, $c->phone);
    }
    
    /*if (count($users)){
	$mycontacts = array_keys($phone);
    }*/
    
    echo json_encode(array('matches'=>$phone_contacts));
    
    
}


if(strcmp($json_o->header,"add_phn")==0)
{
    $contacts = array();
    foreach($json_o->contacts as $c)
    {    
        $trans =  "'".$c->username;
        array_push($contacts, $trans."'");              
    } 
    
    add_phone_contact($json_o->uid, $contacts);
  
}

?>

