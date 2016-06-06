<?php
include_once("util_header.php");
date_default_timezone_set('Africa/Lagos');

function  get_addons($i_addons)
{
    $addons = array();
    
    if (count($i_addons)){
   
           $i_addon_string = implode(',', $i_addons);
           $extra =  "where addon_name not in ($i_addon_string)";
         }else{
		//return array();
             $extra = '';
	 }
         
           $sql = "select id, category, addon_package, addon_name, img, url from addons
	   $extra order by id desc";

	   $result = mysql_query($sql);
           
           while($data = mysql_fetch_object($result))
           {
                 
		$addons[] = array(
                             'category' => $data->category,
                             'addon_package' => $data->addon_package,
                             'addon_name' => $data->addon_name,
                             'img' => base64_encode($data->img),
                             'url' => $data->url                         
                        );
                
	    }
	    return $addons;
    

}

function get_hotmix($limit)
{
           $posts = array();
           
           $extra = "limit $limit,5";
           
           $sql = "select id, title, content, cover, category, reference, stamp from hotmix
	   where status = approved order by id desc $extra";                    
           
           $result = mysql_query($sql);
           
           if($result == null)
           {
               echo 'errrrr';
           }

           
        while($data = mysql_fetch_object($result))
        {
                 
		$posts[] = array(
                             'post_id' => $data->id,
                             'title' => $data->title,
                             'content' => $data->content,
                             'cover_img' => base64_encode($data->cover),
                             'category' => $data->category,
                             'reference' => $data->reference,
                             'stamp' => time_ago($data->stamp)
                        );
                
	}
	return $posts;
    
}



function refresh_hotmix()
{
           $posts = array();
           
           $extra = "limit 0,5";
           
           $sql = "select id, title, content, cover, category, reference, stamp from hotmix
	   where status = 'approved' order by id desc $extra";                                       
           
           $result = mysql_query($sql);
           
           if($result == null)
           {
               echo 'errrrr';
           }
           
        while($data = mysql_fetch_object($result))
        {
		$posts[] = array(
                             'post_id' => $data->id,
                             'title' => $data->title,
                             'content' => $data->content,
                             'cover_img' => base64_encode($data->cover),
                             'category' => $data->category,
                             'reference' => $data->reference,
                             'stamp' => time_ago($data->stamp)
                        );  
	}              
	return $posts;    
}





?>
