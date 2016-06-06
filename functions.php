<?php
include_once("header.php");
date_default_timezone_set('Africa/Lagos');


function get_postCount($userid)
{
         $sql = "select id from posts
			where user_id = '$userid'";  
                
         $result = mysql_query($sql);
         $count = mysql_num_rows($result);
      
         return $count;    
}



function add_post($userid,$body,$img){

    $count = get_postCount($userid);
    $tid = NULL;
    
    if($count>14)
    {
    $msql = "select min(id)from posts 
		where user_id='$userid'order by id desc";
   
    $mresult = mysql_query($msql);
    
                while($data = mysql_fetch_array($mresult)){
                    
                $tid = $data['min(id)'];                                                       
                } 

   
                $dsql = "delete from posts 
				where id='$tid'";           
                $dresult = mysql_query($dsql);
                if($dresult!=null)
                {
                          
                $dsql = "delete from notifications 
				where data='$tid'";   
	        mysql_query($dsql);
                
                
                $dsql2 = "delete from comments 
				where postid='$tid'";   
	        mysql_query($dsql2);
                
                
                $dsql3 = "delete from ratings 
				where postid='$tid'";    
	        mysql_query($dsql3);
                
                }
                 
                
    }

    $sql = NULL;
    
        if(strcmp($img,"null")==0)
        {
                
           	$sql = "insert into posts (user_id, body, stamp, IMG) 
			values ($userid, '" .mysql_real_escape_string($body). "', now(), '" .mysql_real_escape_string("NO"). "')"; 
        
                
        }
   else {
     
	         $sql = "insert into posts (user_id, body, postimg, stamp, IMG) 
			values ($userid, '" .mysql_real_escape_string($body). "', '" .mysql_real_escape_string($img). "', now(), '" .mysql_real_escape_string("YES"). "')";

        }
 
	$result = mysql_query($sql); //or die (mysqli_error () . " The query was:" . $sql_query);
       
        if($result!=null)
        {
         $lastid = mysql_insert_id();
         $data[] = array(
                       'msg'=> "Post added"
                           );
                   
        $mentions = get_mentions($body);
                  //$usr_string='';
        if (count($mentions)){
                  
                  $usr_string = implode(',', $mentions);
                  
                  $extra =  "where username in ($usr_string)";

	$Rsql = "select id from users $extra order by id";

        $Rresult = mysql_query($Rsql);// or die (mysqli_error () . " The query was:" . $Rsql);        
               
	while ($Rdata = mysql_fetch_object($Rresult)){
                    
                $username = get_username($userid);
                $username = "@".$username;
                $mbody = $username." mentioned you in a post."; 
                
                add_notifications("NOTIFY_POST_MENTION",$lastid, "Post mention", $mbody, $userid, $Rdata->id);
        }
                 
        }
        
        
        $hashtags = get_hashtags($body);
        
        if (count($hashtags)){
                  
        $hash_string = implode(',', $hashtags);
                  
        //$Hextra =  "where hash in ($hash_string)";
        $Hextra = explode(',', $hash_string);
        foreach ($Hextra as $value)
        {
        
        $SplitVal = explode('\'', $value);                
        $HashVal = '#'.$SplitVal[1]; 
        
	$Hsql = "select hash, count from hashtags where hash = '$HashVal' order by id";
        
        $Hresult = mysql_query($Hsql);
        
        if(mysql_num_rows($Hresult) != 0)
        {
                    
	while ($Hdata = mysql_fetch_object($Hresult)){
             //echo $Hdata->hash; 
             if(strlen($Hdata->hash)>0)
             {
             $countplus = $Hdata->count+1;
             $sqlupdate = "UPDATE hashtags SET count = '$countplus' WHERE hash = '$HashVal' ";
            
             $updateresult = (mysql_query($sqlupdate));
             }

             
        }

        
        }
        else {
 
                $countplus = 1;
            	$isql = "insert into hashtags (hash, count) 
			values ('$HashVal',$countplus)";
 

	       $iresult = mysql_query($isql); //or die (mysqli_error () . " The query was:" . $sql_query);*/
    
        }
        
        
        }
        }
       
                 
        //echo json_encode(array('data'=>$data)); 
                  
        
            
        }
        else 
        {
                   $data[] = array(
                       'msg'=> "Error: Unable to add post"
                           );
                   //echo json_encode(array('data'=>$data)); 
          
        }
        
       // mysqli_close($mylink);
          
       return $data;
}   


function delete_post($userid,$pid){
    
        $sql = "delete from posts 
				where id='$pid'";
    
        $result = mysql_query($sql);
                
        if($result!=null)
        {
             $sql = "delete from notifications 
				where data='$pid'";    
	     mysql_query($sql);
             
             
             $sql2 = "delete from comments 
				where postid='$pid'";    
	     mysql_query($sql2);

             
             $sql3 = "delete from ratings 
				where postid='$pid'";    
	     mysql_query($sql3);
  
            
                   $data[] = array(
                       'msg'=> "deleted"
                           );
                   echo json_encode(array('data'=>$data));   
        }

 else {
       
                      $data[] = array(
                       'msg'=> "Error"
                           );
                   echo json_encode(array('data'=>$data));  
     
 }    
    
}



function add_likes($pid,$userid)
{
    
         $isLike = array();
		$isql = "select userid from likes
				where postid='$pid'";
		$iresult = mysql_query($isql);

		while($i = mysql_fetch_object($iresult)){
			array_push($isLike, $i->userid);
		}
                
                
            if (in_array($userid,$isLike)){
                
             $dsql = "delete from likes 
				where postid='$pid' and userid='userid'";    
	     mysql_query($dsql);
                
            }  else {
 
       $sql = "insert into likes (postid,userid) 
			values ($pid,$userid)";
        
        $result = mysql_query($sql);
        if($result!=null)
        {
                $data[] = array(
                'msg'=> "Liked"
                           );
                echo json_encode(array('data'=>$data));   
        }
 else {
     
 }               
                
            }
    
}




function get_likesCount($pid)
{
         $sql = "select * from likes
			where postid = '$pid'";  
                
         $result = mysql_query($sql);
         $count = mysql_num_rows($result);

      
        return $count;
    
}

 


function get_likesCheck($userid,$pid)
{
    
         $isLike = '';
         $sql = "select * from likes
			where postid = '$pid' and userid = $userid";  
                
         $result = mysql_query($sql);
         if($result!=null)
        {
             $isLike= 'yes';
        }       
      
        return $isLike;
    
}


function add_comment($pid,$userid,$comment)
{
    
        $sql = "insert into comments (postid,userid,comment,stamp) 
			values ($pid,$userid,'" .mysql_real_escape_string($comment). "', now())";
        
        $result = mysql_query($sql);
        if($result!=null)
        {
                   $commentcount=  get_commentsCount($pid);

                   $data[] = array(
                       'msg'=> "ok",
                       'commentcount'=>$commentcount
                           );
                   echo json_encode(array('data'=>$data));   
        }

 else {
       
                      $data[] = array(
                       'msg'=> "Could not post comment"
                           );
                   echo json_encode(array('data'=>$data));  
     
 }    
    
}


function get_comment($pid)
{
            $comments = array();
    
            $sql = "select id, userid, stamp, comment from comments
				where postid='$pid'";
    
		$result = mysql_query($sql);

		while($data = mysql_fetch_object($result)){
                
                //$face = get_1_face($data->userid);
		$username = get_username($data->userid);
                    
                            $comments[] = array( 	
                                         'stamp' => time_ago($data->stamp),
                                          'userid' => $data->userid,
					  'username' => $username, 
					  'comment' => $data->comment
					);                       
		}
                
    return $comments;
}


 function get_mentions($string) {
                 preg_match_all('/@(\w+)/',$string,$matches);
                 $keywords = array(); 
                 $i = 0;
                  
                   foreach ($matches[1] as $match) {
                   $count = count($matches[1]);
                   $dmatch = "'".$match;
                   array_push($keywords, $dmatch."'");
                   $i++;
                 } 
                return $keywords;
                }

        
         function get_hashtags($string) {
                 preg_match_all('/#(\w+)/',$string,$matches);
                 $keywords = array(); 
                 $i = 0;
                  
                   foreach ($matches[1] as $match) {
                   $count = count($matches[1]);
                   $dmatch = "'".$match;
                   array_push($keywords, $dmatch."'");
                   $i++;
                 } 
                return $keywords;
                }

                
                
                
                
              /*  function get_mentions($string, $str = 1) {
                 preg_match_all('/@(\w+)/',$string,$matches);
                  $i = 0;
                  if ($str) {
                   foreach ($matches[1] as $match) {
                   $count = count($matches[1]);
                   $keywords = "$match";
                   $i++;
                   if ($count > $i) $keywords = ",";
                  }
                 } else {
                   foreach ($matches[1] as $match) {
                    $keyword[] = $match;
                       }
                      $keywords = $keyword;
                 }
                return $keywords;
                }*/



function add_ratings($pid,$userid,$rate)
{
    
         $isRated = array();
		$isql = "select userid from ratings
				where postid='$pid'";
		$iresult = mysql_query($isql);

		while($i = mysql_fetch_object($iresult)){
			array_push($isRated, $i->userid);
		}
                
                
            if (in_array($userid,$isRated)){
                
                $data[] = array(
                'msg'=> "Can't rate a post more than once"
                           );
                echo json_encode(array('data'=>$data));   
                
            }  else {
 
       $sql = "insert into ratings (postid,userid,rate) 
			values ($pid,$userid,$rate)";
        
        $result = mysql_query($sql);
        if($result!=null)
        {
            
              $rating = get_ratings($pid);

               if($rating==null)
                {
                   $rating = "0.0"; 
                }
                
                $ratingcount=get_ratingsCount($pid);
                
                
                if($ratingcount)
                {
                $finalrating = $rating/$ratingcount;
                }
                else {
                $finalrating = '0.0';
                }
                      
       
                   $data[] = array(
                       'msg'=> "ok",
                       'ratecount' => $ratingcount,  
                       'finalrating' => $finalrating  
                           );
                   echo json_encode(array('data'=>$data));   
        }
 else {
     
 }               
                
            }
    
    
  
    
}



function add_msg($userid,$targetid,$msgbody,$pos){
    
    	$sql = "insert into msgs (userid, targetid, msgbody, stamp) 
			values ($userid, $targetid, '".mysql_real_escape_string($msgbody). "',now())";
        
        $result = mysql_query($sql);
        if($result!=null)
        {
            
 
         $msgs = get_msg($userid,$targetid,$pos);
 
         $isTogether = isTogether($userid,$targetid);

         $extra[]= array(    
          'isTogether'=>$isTogether
         );
 
          echo json_encode(array('extra'=>$extra,'msgs'=>$msgs));  
            
        
        $username = get_username($userid);
        $status = get_status($userid);
        
        if(strcmp($status,"online")==0)
        {
            $regid = get_gcm_regid ($targetid);
            send_notification($regid,"NOTIFY_IM",$username,$msgbody);
            
        }  
        elseif (strcmp($status,"offline")==0) {
        
    }else {
            
            
            
        }
        
        }  
        
}





function get_msgmonitor($userid){
    
    $msgs = array();
    
         $sql = "select id, userid, targetid, msgbody, stamp from msgs
	 where poped='no' and targetid='$userid'  order by id desc";
         
         $result = mysql_query($sql);
         
         if($result!=null)
         {
         
         while($data = mysql_fetch_object($result))
         {
                        $username = get_username($data->userid);
             		$msgs[] = array( 	//'stamp' => $data->stamp,
                                                        //'mid'=> $data->id,
							//'userid' => $data->userid, 
                                                        'username' => $username,
							'msgbody' => $data->msgbody
                                                        
					);      
         $sqlupdate = "UPDATE msgs SET poped = 'yes' WHERE targetid ='$userid' and id = '$data->id'";
         $updateresult = mysql_query($sqlupdate);
         }
         return $msgs;
         
         }
    
}

//function read_msg($userid)




function get_msg($userid,$targetid,$limit){
         $extra = "limit $limit,18446744073709551615";
         $msgs = array();
    
         $sql = "select id, userid, targetid, msgbody, stamp, status from msgs
	 where userid = '$userid' and targetid = '$targetid' 
         or userid = '$targetid' and targetid = '$userid' order by stamp asc $extra";
         
         $result = mysql_query($sql);
         
         while($data = mysql_fetch_object($result))
         {
             
             		 $msgs[] = array(   'stamp' => time_ago($data->stamp),
                                                        'mid'=> $data->id,
							'userid' => $data->userid, 
                                                        'targetid' => $data->targetid,
							'msgbody' => $data->msgbody,
                                                        'status' => $data->status                                
					); 
                        
                        
                        if(strcmp($data->userid,$targetid)==0)
                        {
                         msg_read($userid,$data->id);
                        }
         }
         return $msgs;
    
}








function get_msglist($userid,$limit){
    
         $msgs = array();
         $extra = "limit $limit,18446744073709551615";
         
         $sql = "select id, userid, targetid, msgbody, status, stamp from msgs
	 where  targetid = $userid or userid = $userid  order by id desc $extra";
         
         $result = mysql_query($sql);
         
         while($data = mysql_fetch_object($result))
         {
             if(strcmp($data->userid,$userid)==0){
                 
                  $username = get_username($data->targetid); 
             }
 else {
      $username = get_username($data->userid); 
 }
                                
             
             		$msgs[] = array( 	'stamp' => time_ago($data->stamp),
                                                        'username' => $username,
                                                        'mid'=> $data->id,
                                                        'status' => $data->status,
							'userid' => $data->userid,
                                                        'targetid'=> $data->targetid,
							'msgbody' => $data->msgbody
					);       
         }
         return $msgs;
    
}







function msg_read($userid,$mid){
    
             $sqlupdate = "UPDATE msgs SET status = 'read' WHERE targetid ='$userid' and id = '$mid'";
             if($updateresult = mysql_query($sqlupdate)==null)
             {
                 
             }
}






function submit_ad($userid,$cat,$title,$desc,$price,$img){
 
    
        if(strcmp($img,"null")==0)
        {
           	$sql = "insert into ecommerce(userid, category, title, description, price, stamp, IMG) 
			values($userid, '" .mysql_real_escape_string($cat). "', '" .mysql_real_escape_string($title). "', '" .mysql_real_escape_string($desc). "',
                            '" .mysql_real_escape_string($price). "', now(), 
                                'NO')"; 
        }
 else {

           	$sql = "insert into ecommerce(userid, category, title, description, price, ad_img, stamp, IMG) 
			values($userid, '" .mysql_real_escape_string($cat). "', '" .mysql_real_escape_string($title). "', '" .mysql_real_escape_string($desc). "',
                            '" .mysql_real_escape_string($price). "', '" .mysql_real_escape_string($img). "', now(), 
                                'YES')"; 
     
 }

	$result = mysql_query($sql);// or die (mysqli_error () . " The query was:" . $sql_query);
        
        if($result!=null)
        {
                   $data[] = array(
                       'msg'=> "ad submited"
                           );
                   echo json_encode(array('data'=>$data));   
            
        }
        else 
        {
                   $data[] = array(
                       'msg'=> "Error"
                           );
                   echo json_encode(array('data'=>$data));
        }
            
}





function get_ads_cat($Cat, $limit)
{
           $ads = array();
           
           $extra = "limit $limit,5";
           
           $sql = "select id, userid, title, description, price, ad_img, stamp, IMG from ecommerce
	   where category = '$Cat' order by id desc $extra";
                    
           
           $result = mysql_query($sql);
           
           if($result == null)
           {
               echo 'errrrr';
           }
           
        while($data = mysql_fetch_object($result))
        {
                $user = get_username($data->userid);
                $email = get_mail($data->userid);
                $phone = get_phone($data->userid);
                
		$ads[] = array(
                             'ad_id' => $data->id,
                             'user' => $user,
                             'title' => $data->title,
                             'description' => $data->description,
                             'price' => $data->price,
                             'ad_img' => $data->ad_img,
                             'stamp' => time_ago($data->stamp),
                             'IMG' => $data->IMG,
                             'email' => $email,
                             'phone' => $phone
                        );
	}
	return $ads;
    
}


function refresh_ads_cat($Cat)
{
           $ads = array();
           
           $extra = "limit 0,5";
           
           $sql = "select id, userid, title, description, price, ad_img, stamp, IMG from ecommerce
	   where category = '$Cat' order by id desc $extra";
                    
           
           $result = mysql_query($sql);
           
           if($result == null)
           {
               echo 'errrrr';
           }
           
        while($data = mysql_fetch_object($result))
        {
                $user = get_username($data->userid);
                $email = get_mail($data->userid);
                $phone = get_phone($data->userid);
                
		$ads[] = array(
                             'ad_id' => $data->id,
                             'user' => $user,
                             'title' => $data->title,
                             'description' => $data->description,
                             'price' => $data->price,
                             'ad_img' => $data->ad_img,
                             'stamp' => time_ago($data->stamp),
                             'IMG' => $data->IMG,
                             'email' => $email,
                             'phone' => $phone
                    );
	}
	return $ads;
    
}



function add_notifications($type,$data,$title,$body,$userid,$targetid)
{
                
                $sql = "insert into notifications (type, data, body, stamp, userid, targetid) 
		values ('" .mysql_real_escape_string($type). "','" .mysql_real_escape_string($data). "','" .mysql_real_escape_string($body). "',
                    now(),$userid,$targetid)";
	        $result = mysql_query($sql);
                
                if($result!=null)                    
                { 
                 
                  $regid = get_gcm_regid ($targetid);
                  send_notification($regid,$type,$title,$body);
                 
                }
}







function get_notifications($userid, $limit)
{
       $extra = "limit  $limit,18446744073709551615";
       $notifs = array();
       
           $sql = "select id, type, data, body, stamp, userid from notifications
	   where targetid = '$userid' order by id desc $extra";
           
           $result = mysql_query($sql);
           
                while($data = mysql_fetch_object($result))
         {
                $user = get_username($data->userid);

                
		$notifs[] = array(
                             'type' => $data->type,
                             'ndata' => $data->data,
                             'body' => $data->body,
                             'stamp' => time_ago($data->stamp),
                             'nid' => $data->id,
                             'username' => $user,
                             'uid' => $data-> userid
                        );
	}
	return $notifs;
    
}



function get_notify_posts($postid)
{
    
    	$notifyposts = array();

	$sql = "select id, user_id, body, postimg, stamp, IMG from posts 
		where id = $postid";
	
	$result = mysql_query($sql);

	while($data = mysql_fetch_object($result)){
                
                $username = get_username($data->user_id);
                $rating = get_ratings($data->id);
                
                if($rating==null)
                {
                   $rating = "0.0"; 
                }
                
                $ratingcount=get_ratingsCount($data->id);
                $commentcount=  get_commentsCount($data->id);
                
                
                if($ratingcount)
                {
                $finalrating = $rating/$ratingcount;
                }
                else {
                $finalrating = '0.0';
                }
      
 
                $face = get_1_face($data->user_id);
        
		$notifyposts[] = array(    'stamp' => time_ago($data->stamp), 
                                                        'pid' => $data->id,
							'userid' => $data->user_id, 
                                                        'username' => $username,
                                                        'face'=>$face,
                    					'body' => $data->body,
                                                        'pimg' => $data->postimg,
                                                        'IMG' => $data-> IMG,
                                                        'finalrating' => $finalrating,
                                                        'rating' => $rating,
                                                        'ratecount' => $ratingcount,
                                                        'commentcount' => $commentcount
					);          
	}
	return $notifyposts;
    
}






/*function get_profpic0($userid){
           
          // $imgdata = array();
    
           $sql = "select profilepic, username from users
	   where id = '$userid'";
           
           $result = mysql_query($sql);
           if($result==null)
           {
               echo 'errrrr';
           }
           
        while($data = mysql_fetch_object($result))
        {
		
               $imgdata =  $data->profilepic;
	}
	return $imgdata;
}
*/





function get_face($userid){
        $face = array();
        
        
               
                
        	$sql = "select id, username, gender, phone, email, location,
                       workplace, occupation, bio, reputation, isVerified, award from users where id='$userid' ";
 	
	        $result = mysql_query($sql);
                
                while($data = mysql_fetch_object($result)){
                    
                   $numfollowers=Get_num_followers($data->id);
                   $numfollowing=Get_num_following($data->id);

                    
                   $face[] = array(
                              'userid' => $data->id, 
                              'username' => $data->username,
                              'gender'=>$data->gender,
                              'phone'=>$data->phone,
                              'email'=>$data->email,
                              'location'=>$data->location,                           
                              'workplace'=>$data->workplace,
                              'occupation'=>$data->occupation,
                              'bio'=>$data->bio,
                              'reputation'=>$data->reputation,
                              'isVerified'=>$data->isVerified,
                              'award'=>$data->award,                       
                              'numfollowers'=> $numfollowers,
                              'numfollowing'=>$numfollowing
            
                       );
                   
                 
                } 
                
                return $face;
}






function get_faces($userid){
        $faces = array();
    
    	$user_string = implode(',', $userid);
	$extra =  "id in ($user_string)";
        
        
        	$sql = "select id, username, gender, phone, email, location,
                    workplace, occupation, bio, reputation, isVerified, award  from users where $extra 
		order by id desc";
                
 
	
	        $result = mysql_query($sql);
                
                while($data = mysql_fetch_object($result)){
                    
                   $numfollowers=Get_num_followers($data->id);
                   $numfollowing=Get_num_following($data->id);

                    
                   $faces[] = array(
                              'userid' => $data->id, 
                              'username' => $data->username,
                              'gender'=>$data->gender,
                              'phone'=>$data->phone,
                              'email'=>$data->email,
                              'location'=>$data->location,                           
                              'workplace'=>$data->workplace,
                              'occupation'=>$data->occupation,
                              'bio'=>$data->bio,
                              'reputation'=>$data->reputation,
                              'isVerified'=>$data->isVerified,
                              'award'=>$data->award,                       
                              'numfollowers'=> $numfollowers,
                              'numfollowing'=>$numfollowing
            
                       );
                   
                    
                } 
                
                return $faces;
}




function get_1_face($userid){
        
                $face = '';
        	$sql = "select id, profilepic from users where id='$userid' 
		order by id desc";
	
	        $result = mysql_query($sql);
                
                while($data = mysql_fetch_object($result)){
                    
                $face = $data->profilepic;                      
                       
                    
                } 
                
                return $face;
}



function get_hashes($myuserid,$hash,$limit){
    $extra = "limit $limit,5";
    $posts = array();
    
    	$sql = "select id, user_id, body, postimg, stamp, IMG from posts 
		where body like '%$hash%'
		order by id desc $extra";
	//echo $sql;
	$result = mysql_query($sql);
        
        	while($data = mysql_fetch_object($result)){
                
                $username = get_username($data->user_id);
                $rating = get_ratings($data->id);
                
                if($rating==null)
                {
                   $rating = "0.0"; 
                }
                
                $ratingcount=get_ratingsCount($data->id);
                $commentcount=  get_commentsCount($data->id);
                
                
                if($ratingcount)
                {
                $finalrating = $rating/$ratingcount;
                }
                else {
                $finalrating = '0.0';
                }
      
             
           
        
		$posts[] = array( 	'stamp' => time_ago($data->stamp), 
                                                        'pid' => $data->id,
							'userid' => $data->user_id, 
                                                        'username' => $username,
                    					'body' => $data->body,
                                                        'pimg' => $data->postimg,
                                                        'IMG' => $data-> IMG,
                                                        'finalrating' => $finalrating,
                                                        'rating' => $rating,
                                                        'ratecount' => $ratingcount,
                                                        'commentcount' => $commentcount,
					);
	}
	return $posts;

}







function refresh_hashes($myuserid,$hash){
	$posts = array();

        $extra = "limit 0,5";
       
	$sql = "select id, user_id, body, postimg, stamp, IMG from posts 
		where body like '%$hash%' 
		order by id desc $extra";
	//echo $sql;
	$result = mysql_query($sql);

	while($data = mysql_fetch_object($result)){
                
                $username = get_username($data->user_id);
                $rating = get_ratings($data->id);
                
                if($rating==null)
                {
                   $rating = "0.0"; 
                }
                
                $ratingcount=get_ratingsCount($data->id);
                $commentcount=  get_commentsCount($data->id);
                
                
                if($ratingcount)
                {
                $finalrating = $rating/$ratingcount;
                }
                else {
                $finalrating = '0.0';
                }
      
                             
        
		$posts[] = array( 	'stamp' => time_ago($data->stamp), 
                                                        'pid' => $data->id,
							'userid' => $data->user_id, 
                                                        'username' => $username,
                    					'body' => $data->body,
                                                        'pimg' => $data->postimg,
                                                        'IMG' => $data-> IMG,
                                                        'finalrating' => $finalrating,
                                                        'rating' => $rating,
                                                        'ratecount' => $ratingcount,
                                                        'commentcount' => $commentcount,
					);
            
	}
	return $posts;

}




function get_trendhash()
{
    $hash = array();
       $extra = "limit 0,3";
    
    	$sql = "select hash, count from hashtags 
		order by count desc $extra";
	//echo $sql;
	$result = mysql_query($sql);
        
        	while($data = mysql_fetch_object($result)){
                    
                    $hash[] = array( 	
                        'hash' => $data->hash, 
                        'count' => $data->count
                            );
                    
                }
                
                return $hash;
}





function show_posts($myuserid,$userid,$limit){
	$posts = array();

	$user_string = implode(',', $userid);
	
         $username = get_username($myuserid);
         $usermention = "@".$username;
		$extra = "limit $limit,5";
     

	$sql = "select id, user_id, body, postimg, stamp, IMG from posts 
		where user_id in ($user_string) or body like '%$usermention%'
		order by id desc $extra";
	
	$result = mysql_query($sql);

	while($data = mysql_fetch_object($result)){
                
                $username = get_username($data->user_id);
                $isLike=get_likesCheck($myuserid,$data->id);
                $likecount=get_likesCount($data->id);
                $commentcount=  get_commentsCount($data->id);
                

        
		$posts[] = array( 	'stamp' => time_ago($data->stamp), 
                                                        'pid' => $data->id,
							'userid' => $data->user_id, 
                                                        'username' => $username,
                    					'body' => $data->body,
                                                        'pimg' => base64_encode($data->postimg),
                                                        'IMG' => $data-> IMG,
                                                        'isLike' => $isLike,
                                                        'likecount' =>$likecount, 
                                                        'commentcount' => $commentcount,
					);
            
	}
	return $posts;
}





function refresh_posts($myuserid,$userid){
	$posts = array();

	$user_string = implode(',', $userid);
	
                $username = get_username($myuserid);
                $usermention = "@".$username;
		$extra = "limit 0,5";
       

	$sql = "select id, user_id, body, postimg, stamp, IMG from posts 
		where user_id in ($user_string) or body like '%$usermention%'
		order by id desc $extra";
	//echo $sql;
	$result = mysql_query($sql);

	while($data = mysql_fetch_object($result)){
                
                $username = get_username($data->user_id);
                $isLike=get_likesCheck($myuserid,$data->id);
                $likecount=get_likesCount($data->id);
                $commentcount=  get_commentsCount($data->id);
                

        
		$posts[] = array( 	'stamp' => time_ago($data->stamp), 
                                                        'pid' => $data->id,
							'userid' => $data->user_id, 
                                                        'username' => $username,
                    					'body' => $data->body,
                                                        'pimg' => base64_encode($data->postimg),
                                                        'IMG' => $data-> IMG,
                                                        'isLike' => $isLike,
                                                        'likecount' =>$likecount, 
                                                        'commentcount' => $commentcount,
					);
            
	}
	return $posts;

}






function profile_posts($userid, $myuserid, $limit){
      
	$posts = array();
         $username = get_username($userid);
         $usermention = "@".$username;
         
         $extra = "limit $limit,5";

        $sql = "select id, user_id, body, postimg, stamp, IMG from posts
	 where user_id = '$userid' or body like '%$usermention%' order by id desc $extra";
         
        $result = mysql_query($sql);// or die (mysqli_error () . " The query was:" . $sql_query);
        
	while($data = mysql_fetch_object($result))
        {
                $username = get_username($data->user_id);
                
                $rating = get_ratings($data->id);
                
                if($rating==null)
                {
                   $rating = "0.0"; 
                }
                
                $ratingcount=get_ratingsCount($data->id);
                $commentcount=  get_commentsCount($data->id);

                if($ratingcount)
                {
                $finalrating = $rating/$ratingcount;
                }
                else {
                $finalrating = '0.0';
                }
    
                  
        
                
		$posts[] = array( 	'stamp' => time_ago($data->stamp),
                                                        'pid' => $data->id,
                                                        'username'=> $username,
							'uid' => $data->user_id, 
							'body' => $data->body,
                                                        'pimg' => $data->postimg,
                                                        'IMG' => $data-> IMG,
                                                        'finalrating' => $finalrating,
                                                        'rating' => $rating,
                                                        'ratecount' => $ratingcount,
                                                        'commentcount' => $commentcount,
					);
	}
	return $posts;

}




function profile_detail($userid)
{        
        $detail = array();
    
        	$sql = "select id, username, gender, phone, email, location,
                    workplace, occupation, bio, reputation, isVerified, award, status from users where id='$userid' 
		order by id desc";
                
 
	
	        $result = mysql_query($sql);
                
                while($data = mysql_fetch_object($result)){
                    
                   $numfollowers=Get_num_followers($data->id);
                   $numfollowing=Get_num_following($data->id);

                    
                   $detail[] = array(
                              'userid' => $data->id, 
                              'username' => $data->username,
                              'gender'=>$data->gender,
                              'phone'=>$data->phone,
                              'email'=>$data->email,
                              'location'=>$data->location,                           
                              'workplace'=>$data->workplace,
                              'occupation'=>$data->occupation,
                              'bio'=>$data->bio,
                              'reputation'=>$data->reputation,
                              'isVerified'=>$data->isVerified,
                              'award'=>$data->award,  
                              'status'=>$data->status,
                              'numfollowers'=> $numfollowers,
                              'numfollowing'=>$numfollowing
            
                             
                );
        }
        
        return $detail;
}









function show_users($user_id=0){
	if ($user_id > 0){
		$follow = array();
		$fsql = "select user_id from following
				where follower_id='$user_id'";
		$fresult = mysql_query($fsql);

		while($f = mysql_fetch_object($fresult)){
			array_push($follow, $f->user_id);
		}

		if (count($follow)){
			$id_string = implode(',', $follow);
			$extra =  "id in ($id_string)";
		}else{
			return array();
		}

	}

	$users = array();
	$sql = "select id, username from users 
		where $extra order by username";
        	//$sql = "select id, username from users $extraE order by username";


	$result = mysql_query($sql);

	while ($data = mysql_fetch_object($result)){
	$users[$data->id] = $data->username;
            /*$iuser[] = array(
                
                   'username'=>$data->username
            ); */
	}
	return $users;
}




function Get_num_followers($user_id=0)
{

    	if ($user_id > 0){
		$follow = array();
		$fsql = "select follower_id from following
				where user_id ='$user_id'";
		$fresult = mysql_query($fsql);
                $numfollow = mysql_num_rows($fresult);
                return $numfollow;
        }
}


function Get_num_following($user_id=0)
{

    	if ($user_id > 0){
		$follow = array();
		$fsql = "select user_id from following
				where follower_id ='$user_id'";
		$fresult = mysql_query($fsql);
                $numfollow = mysql_num_rows($fresult);
                return $numfollow;
        }
}




function show_followers($user_id=0,$myuser_id){
	if ($user_id > 0){
		$follow = array();
		$fsql = "select follower_id from following
				where user_id ='$user_id'";
		$fresult = mysql_query($fsql);
                $numfollow = mysql_num_rows($fresult);
                
                if($fresult==0)
                {
                    //echo "error1";
                }

		while($f = mysql_fetch_object($fresult)){
			array_push($follow, $f->follower_id);
		}

		if (count($follow)){
			$id_string = implode(',', $follow);
			$extra =  "where id in ($id_string)";
		}else{
			return array();
		}

	}

        $fusers = array();
        $following = following($myuser_id);
        
	$sql = "select id, username, gender, location, workplace from users
		$extra order by username";


	$result = mysql_query($sql);
        
                if($fresult==0)
                {
                    //echo "error2";
                }

	while ($data = mysql_fetch_object($result)){

           if($myuser_id!=0)
           {
            if (in_array($data->id,$following)){
            $fusers[] = array(
                
                   'username'=>$data->username,
                   'gender'=>$data->gender,
                   'location'=>$data->location,
                   'workplace'=>$data->workplace,
                   'userid'=>$data->id,
                    'following'=>"yes"
            );
            
            }
            elseif (strcmp($myuser_id,$data->id)==0) {
                 
                $fusers[] = array(
                
                   'username'=>$data->username,
                   'gender'=>$data->gender,
                   'location'=>$data->location,
                   'workplace'=>$data->workplace,
                   'userid'=>$data->id,
                    'following'=>"me"
                       );
                  
        }
 else {
                   $fusers[] = array(
                
                   'username'=>$data->username,
                   'gender'=>$data->gender,
                   'location'=>$data->location,
                   'workplace'=>$data->workplace,
                   'userid'=>$data->id,
                    'following'=>"no"
                       );
 }
           }
 else {
                 $fusers[] = array(
                
                   'username'=>$data->username,
                   'gender'=>$data->gender,
                   'location'=>$data->location,
                   'workplace'=>$data->workplace,
                   'userid'=>$data->id,
                    'following'=>"yes"
                 
            );
 }
	}
	return $fusers;
}




function show_following($user_id=0,$myuser_id){
	if ($user_id > 0){
		$follow = array();
		$fsql = "select user_id from following
				where follower_id='$user_id'";
		$fresult = mysql_query($fsql);

		while($f = mysql_fetch_object($fresult)){
			array_push($follow, $f->user_id);
		}

		if (count($follow)){
			$id_string = implode(',', $follow);
			$extra =  "where id in ($id_string)";
		}else{
			return array();
		}

	}

        $fusers = array();
        $following = following($myuser_id);
        
	$sql = "select id, username, gender, location, workplace from users
		$extra order by username";


	$result = mysql_query($sql);

	while ($data = mysql_fetch_object($result)){

           if($myuser_id!=0)
           {
            
            if (in_array($data->id,$following)){
            $fusers[] = array(
                
                   'username'=>$data->username,
                   'gender'=>$data->gender,
                   'location'=>$data->location,
                   'workplace'=>$data->workplace,
                   'userid'=>$data->id,
                   'following'=>"yes"
            );
            
            }
            elseif (strcmp($myuser_id,$data->id)==0) {
                 
                $fusers[] = array(
                
                   'username'=>$data->username,
                   'gender'=>$data->gender,
                   'location'=>$data->location,
                   'workplace'=>$data->workplace,
                   'userid'=>$data->id,
                   'following'=>"me"
                       );
                  
        }
        
 else {
                   $fusers[] = array(
                
                   'username'=>$data->username,
                   'gender'=>$data->gender,
                   'location'=>$data->location,
                   'workplace'=>$data->workplace,
                   'userid'=>$data->id,
                   'following'=>"no"
                       );
 }
           }
        
 else {
                 $fusers[] = array(
                
                   'username'=>$data->username,
                   'gender'=>$data->gender,
                   'location'=>$data->location,
                   'workplace'=>$data->workplace,
                   'userid'=>$data->id,
                   'following'=>"yes"
            );
 }
	}
	return $fusers;
}









function showMAIN_users($myuser_id){
	$fusers = array();
        $extra = "limit 0,3";
        $following = following($myuser_id);
        
        if(count($following))
        {
           $id_string = implode(',', $following);
           $extra2= "where id not in ($id_string)";
        }
 else {
     $extra2="";
 }
	$sql = "select id, username, gender, location, workplace from users 
        $extra2 order by rand() $extra";
        
	$result = mysql_query($sql);

	while ($data = mysql_fetch_object($result)){
		//$users[$data->id] = $data->username;
                      
           //if($myuser_id!=0)
          // {
            if (in_array($data->id,$following)){
           /* $fusers[] = array(
                
                   'username'=>$data->username,
                   'detail'=>$data->department,
                   'profimg'=>$data->profilepic,
                   'following'=>"yes"
            );*/
            
            }
            
            elseif (strcmp($myuser_id,$data->id)==0) {
                 
               /* $fusers[] = array(
                
                   'username'=>$data->username,
                  'detail'=>$data->department,
                   'profimg'=>$data->profilepic,
                   'following'=>"me"
                       );*/
                  
        }
        
 else {
    // if(strcmp($myuser_id,$data->id)!=0)
    // {
                   $fusers[] = array(
                
                   'username'=>$data->username,
                   'gender'=>$data->gender,
                   'location'=>$data->location,
                   'workplace'=>$data->workplace,
                   'userid'=>$data->id,
                   'following'=>"no"
                       );
   /*  }
 else {
                           $fusers[] = array(
                
                   'username'=>$data->username,
                   'following'=>"yes"
                       );
     
     }*/
 }
          // }
	}
	return $fusers;
}





function get_phone_contact($myuser_id,$phone){
    
    $user="";
    $following = following($myuser_id);
    $sql = "select id, username from users where phone = '$phone' order by phone";
    $result = mysql_query($sql);
   
    while ($data = mysql_fetch_object($result)){
      if (!in_array($data->id,$following)){
       if(strcmp($myuser_id,$data->id)!=0){
           
           $user = $data->username;
       }  
      }
    }

    return $user;
    
}




function add_phone_contact($myuser_id,$contacts){
    
    
        $uids = array();
    		
	if (count($contacts)){
	$user_string = implode(',', $contacts);
        
	$extra =  "where username in ($user_string)";
                        
	$sql = "select id from users
		$extra order by id";


	$result = mysql_query($sql);

	while ($data = mysql_fetch_object($result)){
            
              follow_user($myuser_id,$data->id);     
        }

                           
                
	}                    
		                
                
    
}
    

    


function showALL_users($myuser_id,$limit){
	$fusers = array();
        $extra = "limit $limit,10";
        $following = following($myuser_id);

        if(count($following))
        {
           $id_string = implode(',', $following);
           $extra2= "where id not in ($id_string)";
        }
 else {
     $extra2="";
 }
        
        $sql = "select id, username, gender, location, workplace from users 
        $extra2 order by username $extra";
        
	$result = mysql_query($sql);

	while ($data = mysql_fetch_object($result)){
		//$users[$data->id] = $data->username;
                      
           //if($myuser_id!=0)
          // {
            if (in_array($data->id,$following)){
            /*$fusers[] = array(
                
                   'username'=>$data->username,
                   'detail'=>$data->department,
                   'profimg'=>$data->profilepic,
                   'following'=>"yes"
            );*/
            
            }
            
            elseif (strcmp($myuser_id,$data->id)==0) {
                 
                /*$fusers[] = array(
                
                   'username'=>$data->username,
                   'detail'=>$data->department,
                   'profimg'=>$data->profilepic,
                   'following'=>"me"
                       );*/
                  
        }
        
 else {
    // if(strcmp($myuser_id,$data->id)!=0)
    // {
                   $fusers[] = array(
                
                   'username'=>$data->username,
                   'gender'=>$data->gender,
                   'location'=>$data->location,
                   'workplace'=>$data->workplace,
                   'userid'=>$data->id,
                   'following'=>"no"
                       );
   /*  }
 else {
                           $fusers[] = array(
                
                   'username'=>$data->username,
                   'following'=>"yes"
                       );
     
     }*/
 }
          // }
	}
	return $fusers;
}







function following($userid){
	$users = array();

	$sql = "select distinct user_id from following
			where follower_id = '$userid'";
	$result = mysql_query($sql);

	while($data = mysql_fetch_object($result)){
		array_push($users, $data->user_id);

	}

	return $users;

}




function search_user($myuser_id,$search_query)
{
  	$users = array();
        $extra = "limit 0,5";
       /* $following = following($myuser_id);
        
        if(count($following))
        {
           $id_string = implode(',', $following);
           $extra2= "where id not in ($id_string)";
        }
   else {
           $extra2="";
        }*/
	$sql = "select id, username, gender, location, workplace from users 
        where username like '%$search_query%' order by username $extra";
        
	$result = mysql_query($sql);

	while ($data = mysql_fetch_object($result)){
		//$users[$data->id] = $data->username;
                      
           //if($myuser_id!=0)
          // {
          /*  if (in_array($data->id,$following)){

            
            }
            
            elseif (strcmp($myuser_id,$data->id)==0) {
                 
              
        }
        
 else {*/
                   $atuser = '@'.$data->username;
                   $users[] = array(
                   'userid'=>$data->id,
                   'username'=>$atuser,
                   'gender'=>$data->gender,
                   'location'=>$data->location,
                   'workplace'=>$data->workplace,
                   'following'=>"no"
                       );
 
 //}
         
	}
	return $users;
  
}



function search_hash($userid,$search_query)
{
        $hash = array();
       $extra = "limit 0,5";
    
    	$sql = "select hash, count from hashtags where hash like '%$search_query%'
		order by count desc $extra";
	//echo $sql;
	$result = mysql_query($sql);
        
        	while($data = mysql_fetch_object($result)){
                    
                    $hash[] = array( 	
                        'hash' => $data->hash, 
                        'count' => $data->count
                            );
                    
                }
                
                return $hash;
}





function register($username, $phone, $email, $gender, $location, $password){
//function register($username, $email, $password){

    $csql = "select * from users
			where username = '$username'";
        
        $cresult = mysql_query($csql);
        
	$numrow = mysql_num_rows($cresult);
        
        if($numrow!=null)
        {
           while($row=  mysql_fetch_assoc($cresult))
           {
               if($username=$row['username'])
               {
                   
                   $data[] = array(
                       'msg'=> "'$username' is not available choose another username"
                           );
                   echo json_encode(array('data'=>$data));
                   //Print "username '$username' is not available";
                   exit;
               }
           }
            
        }
        
    
 	$sql = "insert into users (username, gcm_regid, gender, location, phone, email, password) 
			values ('$username', 'pending...', '$gender', '$location', '$phone', '$email', '$password')";


	$result = mysql_query($sql);// or die (mysqli_error () . " The query was:" . $sql_query); 
        
        if($result!=null)
        {
            //print "Registration successful!";
                  $data[] = array(
                       'msg'=> "Registration successful!"
                           );
                  
                           $uid = set_user_id($username);
                           $msgbody = "Hi @$username welcome to Mimix";
                           
                           //add_msg(Mimix_id, $uid, $msgbody);                         
                           add_post(Mimix_id, $msgbody, "null");
                   
        }
 else {
                      $data[] = array(
                       'msg'=> "Registration not successful!"
                           );

     
}
        echo json_encode(array('data'=>$data));
    
}


function login($username, $password, $gcm_regid){
 
        //$pwd = "";
        
        $csql = "select password from users
			where username = '$username'";
        
        $cresult = mysql_query($csql);
        
	//$numrow = mysql_num_rows($cresult);
        
        while($row = mysql_fetch_array($cresult,MYSQL_ASSOC))
        {        
          
          $pwd = $row['password'];
                 
        
        
        if(strcmp($pwd,$password)==0)
        {
            
              
              $uid = set_user_id ($username);
             //Print "welcome $username".$id['id'];
              $username = get_username($uid);
              
              session_start();
              $_SESSION['userid'] = $uid;
              //print "LOGGED IN!:$uid";
              
                  $data[] = array(
                       'msg'=> "LOGGED IN!",
                       'uid'=> "$uid",
                       'user_name'=> "$username",
                       'pass_word'=> "$password",
                       'gcm_regid'=> "$gcm_regid"
                       
                           );
                   echo json_encode(array('data'=>$data));
              
              //header("Location:index.php");
                   update_login($uid, $gcm_regid);
                   
              exit; 
            
        }
        else
        //Print "$username does not exist!";
        //Print "Incorrect username or password";
                   $data[] = array(
                       'msg'=> "Incorrect username or password"
                           );
                   echo json_encode(array('data'=>$data));
        }
        //Print "user does not exist";
                   $data[] = array(
                       'msg'=> "user '$username' does not exist"
                           );
                   echo json_encode(array('data'=>$data));       
    
}


function logout($userid){
    
             $sqlupdate = "UPDATE users SET status = 'offline', gcm_regid = '...' WHERE id = '$userid' ";
            
             if($updateresult = (mysql_query($sqlupdate)) != null)
             {
                 
                   $data[] = array(
                       'msg'=> "logged out"
                           );
                   echo json_encode(array('data'=>$data));     
                
             }
}


function update_login($userid, $gcm_regid){
    
             $sqlupdate = "UPDATE users SET gcm_regid = '".mysql_real_escape_string($gcm_regid). "', status = 'online' WHERE id = '$userid' ";
            
             if($updateresult = (mysql_query($sqlupdate)) == null)
             {
                 
             }
}


function set_user_id ($username)
{
          //$id = array();
          
          $sql = "select id from users
			where username = '$username'";  
          
          $result = mysql_query($sql);
          
         
        
        while($row = mysql_fetch_array($result,MYSQL_ASSOC))
        {
         
          
          $id = $row['id'];
        
              
        }
        //print $id;
        return $id;
}




function get_username ($userid)
{
          
          $sql = "select username from users
			where id = '$userid'";  
          
          $result = mysql_query($sql);
          
         
        
        while($row = mysql_fetch_array($result,MYSQL_ASSOC))
        {
         
          
          $usrname = $row['username'];
        
              
        }
        return $usrname;
}





function get_gcm_regid ($userid)
{
          
          $sql = "select gcm_regid from users
			where id = '$userid'";  
          
          $result = mysql_query($sql);
           
          
        while($row = mysql_fetch_array($result,MYSQL_ASSOC))
        {
         
          $regid = $row['gcm_regid'];
               
        }
        
        return $regid;
}




function get_phone ($userid)
{
          $sql = "select phone from users
			where id = '$userid'";  
          
          $result = mysql_query($sql);
        
        while($row = mysql_fetch_array($result,MYSQL_ASSOC))
        {
          
          $xphone = $row['phone'];
       
        }
        return $xphone;  
       
}


function get_mail ($userid)
{
          $sql = "select email from users
			where id = '$userid'";  
          
          $result = mysql_query($sql);
          
         
        
        while($row = mysql_fetch_array($result,MYSQL_ASSOC))
        {
          
          $xmail = $row['email'];
       
        }
        return $xmail;  
       
}



function get_status($userid){
    
         $sql = "select status from users
			where id = '$userid'";  
          
         $result = mysql_query($sql);
          
        
        while($row = mysql_fetch_array($result,MYSQL_ASSOC))
        {
          
          $status = $row['status'];
       
        }
        return $status; 
    
}


function get_ratings($pid)
{
         $sql = "select rate from ratings
			where postid = '$pid'";  
                
         $result = mysql_query($sql);
         //$count = mysql_num_rows($result);

         $rate = 0.0;
         while($data = mysql_fetch_object($result)){
	 $rate+=(float)$data->rate;    
	} 
        
       // $num=intval($count);
       // $num2 = 1+intval($num);
        //$num3 = intval($num2) - 1;
        $rating = floatval($rate);///$num2;
        return $rating;
        //return $count;
    
}

function get_ratingsCount($pid)
{
         $sql = "select rate from ratings
			where postid = '$pid'";  
                
         $result = mysql_query($sql);
         $count = mysql_num_rows($result);

      
        return $count;
    
}




function get_commentsCount($pid)
{
         $sql = "select comment from comments
			where postid = '$pid'";  
                
         $result = mysql_query($sql);
         $count = mysql_num_rows($result);

      
        return $count;
    
}

function check_count($first, $second){
    
	$sql = "select count(*) from following 
			where user_id='$second' and follower_id='$first'";
	$result = mysql_query($sql);

	$row = mysql_fetch_row($result);
	return $row[0];

}

function follow_user($me,$them){
	$count = check_count($me,$them);

	if ($count == 0){
		$sql = "insert into following (user_id, follower_id) 
				values ($them,$me)";

		$result = mysql_query($sql);
                
                if($result!=null)
                {               
                $username = get_username($me);
                $username = "@".$username;
                $body = $username." is now following you.";
                
                add_notifications("NOTIFY_NEW_FOLLOW","null","New Follower",$body,$me,$them);
                         
                    $face = get_face($them);
       
                    $data[] = array(
                     'msg'=> "isFollowed"
                    );
                    echo json_encode(array('data'=>$data,'face'=>$face));
                }
 
                else
                {
                    $data[] = array(
                    'msg'=> "Could not follow ".$username
                    );
                    echo json_encode(array('data'=>$data));
          
                }
 
	}
}



function unfollow_user($me,$them){
	$count = check_count($me,$them);

	if ($count != 0){
		$sql = "delete from following 
				where user_id='$them' and follower_id='$me'
				limit 1";

		$result = mysql_query($sql);
                
                   
                if($result!=null)
                {
                    
                $username = get_username($me);
                $username = "@".$username;
                $body = $username." has unfollowed you.";
                
                add_notifications("NOTIFY_NEW_FOLLOW","null","New Follower",$body,$me,$them);
                         
                    $face = get_face($them);
       
                    $data[] = array(
                    'msg'=> "isUnFollowed"
                    );
                    echo json_encode(array('data'=>$data,'face'=>$face));
                   
                    
                }
                else
                {
                    $data[] = array(
                    'msg'=> "Could not unfollow ".$username
                    );
                    echo json_encode(array('data'=>$data));
          
                }
	}
}


/*function show_posts2($userid){
	$posts = array();

	$sql = "select body, stamp from posts
	 where user_id = '$userid' order by id desc";
	$result = mysql_query($sql);

	while($data = mysql_fetch_object($result)){
		$posts[] = array( 	'stamp' => $data->stamp, 
							'userid' => $userid, 
							'body' => $data->body
					);
	}
	return $posts;

}*/





    function send_notification($regids,  $not_type, $msg_title, $msg_body) {
       
        // Set POST variables
        $url = 'https://android.googleapis.com/gcm/send';
 
        $message = array();
        $gcm_regids = array();
        
        if(strcmp($not_type,"NOTIFY_IM")==0)
        {
            $message = array(
                 'type'=> $not_type,
                 'title'=> $msg_title,
                 'body'=> $msg_body
                );
            
            $gcm_regids = array($regids);
                
        }
        /*else if(strcmp($not_type,"POST_NOTIFY")==0)
        {
               
        }*/
        
          
        $fields = array(
            'data' => $message,
            'registration_ids' => $gcm_regids,
        );
 
        $headers = array(
            'Authorization: key=' . GOOGLE_API_KEY,
            'Content-Type: application/json'
        );
        // Open connection
        $ch = curl_init();
 
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
 
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
 
        // Execute post
        $result = curl_exec($ch);
        if ($result == FALSE) {
           // die('Curl failed: ' . curl_error($ch));
        }

        // Close connection
        curl_close($ch);
       // echo $result;
    }


 

function isTogether($me,$them)
{
  $palx='';
  $check1 = check_count($me,$them);
  if($check1!=0)
  {
      $check2 = check_count($them,$me);
      if($check2!=0)
      {
         $palx='true'; 
      }
 else {
         $palx='false'; 
      }
  }
else {
         $palx='false'; 
     }
    
     return $palx;
}



function time_ago( $date )
{
    if( empty( $date ) )
    {
        return "No date provided";
    }
    $periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
    
    $lengths = array("60","60","24","7","4.35","12","10");
    $now = time();
    $unix_date = strtotime( $date );
    // check validity of date
    if( empty( $unix_date ) )
    {
        return "Bad date";
    }
    // is it future date or past date
    if( $now > $unix_date )
    {
        $difference = $now - $unix_date;
        $tense = "ago";
    }
    else
    {
        $difference = $unix_date - $now;
        $tense = "from now";
    }
    for( $j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++ )
    {
        $difference /= $lengths[$j];
    }
    $difference = round( $difference );
    if( $difference != 1 )
    {
        $periods[$j].= "s";
    }
    return "$difference $periods[$j] {$tense}";
}



/*
function image_resize($newWidth, $targetFile, $originalFile) {

    $info = getimagesize($originalFile);
    $mime = $info['mime'];

    switch ($mime) {
            case 'image/jpeg':
                    $image_create_func = 'imagecreatefromjpeg';
                    $image_save_func = 'imagejpeg';
                    $new_image_ext = 'jpg';
                    break;

            case 'image/png':
                    $image_create_func = 'imagecreatefrompng';
                    $image_save_func = 'imagepng';
                    $new_image_ext = 'png';
                    break;

            case 'image/gif':
                    $image_create_func = 'imagecreatefromgif';
                    $image_save_func = 'imagegif';
                    $new_image_ext = 'gif';
                    break;

            default: 
                    throw Exception('Unknown image type.');
    }
    
    $img = $image_create_func($originalFile);
    list($width, $height) = getimagesize($originalFile);
    $newHeight = ($height / $width) * $newWidth;
    $tmp = imagecreatetruecolor($newWidth, $newHeight);
    imagecopyresampled($tmp, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

    if (file_exists($targetFile)) {
            unlink($targetFile);
    }
    $image_save_func($tmp, "$targetFile.$new_image_ext");
}



$img=$_REQUEST['img'];
$id=$_REQUEST['id'];

  //  echo $img
resize(120, $_SERVER['DOCUMENT_ROOT'] ."/images/$id",$_SERVER['DOCUMENT_ROOT'] ."/images/$img") ;
*/

?>