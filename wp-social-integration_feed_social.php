<?php  

// Example Use: [wp_social_integration_feed_replace_com post_title="true" excerpt_length="true" categories="all" thumbnail="true" img_width="250" img_height="150" rows="2" columns="1" pages_number="2" template="amaz-columns.php"]
function wp_social_integration_feed_replace_scode($atts) { 				
	$wp_scintg_options = get_option('wp_scintg_plugin_home_feed_settings');		
		    	    
  	  //get short code attributes, if any attribute not present then right hand side default value will be set
	  $atts = shortcode_atts( array(
	    'id' => ''.$wp_scintg_options['wp_scintg_fbid'].'',
		'header' => ''. ($wp_scintg_options['wp_scintg_header']) ? "true" : '',  		
		'num' => ''. $wp_scintg_options['wp_scintg_postnum'] .'',
	  	'width' => ''. $wp_scintg_options['wp_scintg_facebookwidth'] .'',
	  	'height' => ''. $wp_scintg_options['wp_scintg_facebookheight'] .'', 
	  	//'ajax_comments' => ''. ($wp_scintg_options['wp_scintg_ajaxcomments']) ? "true" : '', 
	  	'guest_posts' => ''. ($wp_scintg_options['wp_scintg_guestentries']) ? "true" : '', 
	  	'border' => ''. ($wp_scintg_options['wp_scintg_showborder']) ? "true" : '', 
	  	'cache_time' => ''. $wp_scintg_options['wp_scintg_cache_time'].'', 
	  	'cache_unit' => ''. $wp_scintg_options['wp_scintg_cache_time_unit'] .'',
	  	 
	  	'backg_color' => ''. $wp_scintg_options["wp_scintg_backcolor"] .'', 
	  	'post_brd_color' => ''. $wp_scintg_options["wp_scintg_postbordercolor"].'',  
	  	'author_text_color' => ''. $wp_scintg_options["wp_scintg_postauthorcolor"] .'', 	  	
	  	'post_text_color' => ''. $wp_scintg_options["wp_scintg_posttextcolor"] .'', 	  	
	  	'date_color' => ''. $wp_scintg_options["wp_scintg_datecolor"] .'', 	  		  	
	  	'text_link_color' => ''.$wp_scintg_options["wp_scintg_posttextlinkcolor"] .'', 	  		  				             
    ), $atts);
	  
/////////////check critical errors first 
$wp_scintg_accesstoken= $wp_scintg_options['wp_scintg_accesstoken'];
	  
$error_flag=false;	
if (trim($wp_scintg_accesstoken) == '') {
	
	$wp_scintg_html_content_first.= 'Please enter a valid Access Token in settings page.<br /><br />';
    $error_flag=true;
}  
//Check if number of posts has been defined
if (count(trim($atts['num']))<=0) {
	$wp_scintg_html_content_first.= "Please enter the number of posts value. Enter in show number of posts box setting page or in short code(see instructions at bottom)<br /><br />";
	$error_flag=true;
} else { $wp_scintg_postnum= trim($atts['num']); }

if(!$error_flag)
{
//general from short codes	
$wp_scintg_facebookwidth = $atts['width'];
$wp_scintg_heightoption = $atts['height_option'];
$wp_scintg_facebookheight = $atts['height'];
$wp_scintg_header  = $atts['header'] =="true" ? true : false ;  //from short code
 
$wp_scintg_guestentries = $atts['guest_posts'] =="true" ? true : false ; 
$wp_scintg_showborder = $atts['border'] =="true" ? true : false ; 
$wp_scintg_cache_time = $atts['cache_time'];
$wp_scintg_cache_time_unit = $atts['cache_unit'];
/******************/  
$wp_scintg_dateformat=$wp_scintg_options["wp_scintg_dateformat"]; 
$wp_scintg_timezone=$wp_scintg_options["wp_scintg_timezone"]; 
$wp_scintg_timezone = (!empty($wp_scintg_timezone))? $wp_scintg_timezone : "Europe/London"; 

/******************/
 //color
	  $wp_scintg_backcolor=$atts["backg_color"]; 
	   $wp_scintg_postbordercolor=$atts["post_brd_color"];	  
	   $wp_scintg_postauthorcolor=$atts["author_text_color"];	  
	   $wp_scintg_posttextcolor=$atts["post_text_color"];	  
	  $wp_scintg_datecolor=$atts["date_color"];	
  	 $wp_scintg_posttextlinkcolor=$atts["text_link_color"];
 
////////////define other variables 	
$re_facebookwidth="11"; $temp_width=(int)$wp_scintg_facebookwidth; 
if(is_int($temp_width)) { if(($temp_width>90)&&($temp_width<=100)){ $re_facebookwidth="12"; } if(($temp_width>80)&&($temp_width<=90)){ $re_facebookwidth="11"; } if(($temp_width>70)&&($temp_width<=80)){ $re_facebookwidth="10"; } if(($temp_width>60)&&($temp_width<=70)){ $re_facebookwidth="9"; } if(($temp_width>50)&&($temp_width<=60)){ $re_facebookwidth="8"; } if(($temp_width>40)&&($temp_width<=50)){ $re_facebookwidth="7"; } if(($temp_width>30)&&($temp_width<=40)){ $re_facebookwidth="6"; } if(($temp_width>20)&&($temp_width<=30)){ $re_facebookwidth="5"; } if(($temp_width>10)&&($temp_width<=20)){ $re_facebookwidth="4"; } if($temp_width<=10){ $re_facebookwidth="3"; } if($temp_width>100){ $re_facebookwidth="11"; }} 
$wp_scintg_type=""; $wp_scintg_source_id=""; 
//feed or posts
//$wp_scintg_type = ($wp_scintg_guestentries) ? 'feed' : 'posts'; //if showGuest false = posts
//graph api url
$wp_scintg_posts_url = 'https://graph.facebook.com/me/home?access_token='.$wp_scintg_accesstoken.'&limit='.$wp_scintg_postnum;
//set time zone for date calculation //check if empty function is appropriate when string is empty check done
date_default_timezone_set($wp_scintg_timezone);

$wp_scintg_cache_time = trim($wp_scintg_cache_time); //for test
if($wp_scintg_cache_time_unit == 'minutes') $wp_scintg_cache_time_unit = 60;
if($wp_scintg_cache_time_unit == 'hours') $wp_scintg_cache_time_unit = 60*60;
if($wp_scintg_cache_time_unit == 'days') $wp_scintg_cache_time_unit = 60*60*24;
if(trim($wp_scintg_cache_time_unit) == '') $wp_scintg_cache_time_unit = 60; //if empty
$cache_in_seconds = $wp_scintg_cache_time * $wp_scintg_cache_time_unit;

////////////Now get data from Graph api
/* function microtime_float2(){  list($usec, $sec) = explode(" ", microtime()); return ((float)$usec + (float)$sec); }
$time_start2 = microtime_float2(); */
if (($wp_scintg_cache_time != 0)&&(($wp_scintg_cache_time !=""))){
	// Get any existing copy of our transient data
	$transient_name = 'wp_scintg_home_feed_cache';
	if ( false === ( $wp_scintg_data_objs_first = get_transient( $transient_name ) ) || $wp_scintg_data_objs_first === null ) {
		//Get the contents of the Facebook page
		$wp_scintg_data_objs_first = wp_scintg_Wall_Get_Graph_API_Data($wp_scintg_posts_url);
		//Cache the JSON
		$wp_scintg_data_objs = $wp_scintg_data_objs_first;
        //json decode of data
        $wpintgData = json_decode($wp_scintg_data_objs);
        if(isset($wpintgData->error)) { } else { if(count($wpintgData->data)<=0){ } else { set_transient( $transient_name, $wp_scintg_data_objs_first, $cache_in_seconds ); } }  
		/*goto skip; not works php v <5.3*/
	} else {
		$wp_scintg_data_objs_first = get_transient( $transient_name ); $wp_scintg_html_content_first.="<!-- getting data from cache -->";
		//If we can't find the transient then fall back to just getting the json from the api
		if ($wp_scintg_data_objs_first == false)
		{ 
			$wp_scintg_data_objs_first = wp_scintg_Wall_Get_Graph_API_Data($wp_scintg_posts_url); $wp_scintg_html_content_first.="<!-- transient not found -->";
			$wp_scintg_data_objs = $wp_scintg_data_objs_first; $wpintgData = json_decode($wp_scintg_data_objs);
		}
		else 
		{
			$wp_scintg_data_objs = $wp_scintg_data_objs_first; $wpintgData = json_decode($wp_scintg_data_objs);
		}
	}
} else {
	$wp_scintg_data_objs_first = wp_scintg_Wall_Get_Graph_API_Data($wp_scintg_posts_url);
	$wp_scintg_data_objs = $wp_scintg_data_objs_first; $wpintgData = json_decode($wp_scintg_data_objs);
}
$wp_scintg_counter=0;

/////////////data extraction.Check error first
if(isset($wpintgData->error)){ $wp_scintg_html_content_first.= $wpintgData->error->message ; $error_flag=true; } else {   
// check if no record found
$wp_scintg_max = count($wpintgData->data);
if($wp_scintg_max==0){			
	$wp_scintg_html_content_first .= '<div class="wpintg-wall-box-first">';
	$wp_scintg_html_content_first .= '<img class="wpintg-wall-avatar" src="'. wp_scintg_Wall_Get_Avatar_Url($wp_scintg_fbid) .'" />';
	$wp_scintg_html_content_first .= '<div class="wpintg-wall-data">';
	$wp_scintg_html_content_first .= '<span class="wpintg-wall-message">Call to Facebook API failed or no records returned. Make sure system requirements are met(look for the tab in settings page), user access token(<a target="_blank" href="https://developers.facebook.com/tools/debug/">check here</a>) are right</span>';
	$wp_scintg_html_content_first .= '</div>';
	$wp_scintg_html_content_first .= '</div>'; $error_flag=true;
}   
else 
{
	//Enqueue stylesheet
	//add_action( 'wp_enqueue_scripts', 'wp_scintg_add_my_stylesheet' );

	wp_register_style( 'wp_scintg_reg_style_file', plugins_url('css/jquery.wp_scintg.home.feed.css', __FILE__) );
	wp_enqueue_style( 'wp_scintg_reg_style_file' );
		
	//get like info per post seperately because of the hassle of first call don't show like count.
	
	if(wp_scintg_exists($wpintgData->data[0]->id)) { $wp_scintg_source_id = explode("_",$wpintgData->data[0]->id);}	//check again
			   
	$random_length = 3;
	//generate a random id encrypt it and store it in $rnd_id
	$rnd_id = crypt(uniqid(rand(),1));
	//to remove any slashes that might have come
	$rnd_id = strip_tags(stripslashes($rnd_id));
	//Removing any . or / and reversing the string
	$rnd_id = str_replace(".","",$rnd_id);
	$rnd_id = strrev(str_replace("/","",$rnd_id));
	//finally I take the first 2 characters from the $rnd_id
	$rnd_id = substr($rnd_id,0,$random_length);
//////////////////////sample test post start ////use cache instaed
////use cached data for test post   
/////////////////////testpost ends
 //construct class here to minize checks for youtube embed container div to match pic sizes
 $embdVdSrcClass = "wpintg-wall-vcon wpintg-wall-vcon-thumb"; $show_more_len = 300;
 foreach ($wpintgData->data as $fdata) 
 { 
 		
 	$wp_scintg_show_post = true;
 	//if(($fdata->type=="video")||($fdata->type=="photo")) { $wp_scintg_show_post = false; } // upcoming
 	 
    if($wp_scintg_show_post)
    {	
	//try block level elements not child of inline elements
	$wp_scintg_html_content_first .= ($wp_scintg_counter==0) ? '<div class="wpintg-layout wpintg-wall-box wpintg-wall-box-first">' : '<div class="wpintg-layout wpintg-wall-box">';
	/////////show avatar
	 
		$wp_scintg_html_content_first .= '<div class="avatar"><a href="https://www.facebook.com/'.$fdata->from->id.'" target="_blank">';
		$wp_scintg_html_content_first .= '<img class="wpintg-wall-avatar" src="'. wp_scintg_Wall_Get_Avatar_Url($fdata->from->id) .'" />';
		$wp_scintg_html_content_first .= '</a></div>';
	
	$wp_scintg_html_content_first .= '<div class="wpintg-wall-data">';
	$wp_scintg_html_content_first .= '<span class="wpintg-wall-message">';
	$wp_scintg_html_content_first .= '<a href="https://www.facebook.com/'. $fdata->from->id .'" class="wpintg-wall-message-from" target="_blank">'. $fdata->from->name .'</a> '; 
	
	$primary=""; $primary2="";
	if(wp_scintg_exists($fdata->message))
	{
	     if(strlen($fdata->message)>$show_more_len)
		 {
			 $str_pos = (strpos($fdata->message," ",$show_more_len)-$show_more_len)+$show_more_len;
			 if($str_pos>0)
			 {
			    $primary = substr($fdata->message,0,$str_pos);
			    $primary2 = substr($fdata->message, $str_pos); $wp_scintg_html_content_first .= '<span style="display:block;margin-top:4px;"><span>'.wp_scintg_modText($primary).'</span><span class="wp_scintg_show_more_link" style="cursor:pointer; cursor:hand; margin:0 2px 0 4px;">See More</span>'.'<span style="display:none;">'.wp_scintg_modText($primary2) .'<span class="wp_scintg_show_less_link" style="cursor:pointer; cursor:hand; margin:0 2px 0 4px;">See Less</span></span></span>'; 
			 } else { $wp_scintg_html_content_first .= '<span style="display:block;margin-top:4px;">'. wp_scintg_modText($fdata->message) .'</span>'; }			     		    			    
		 } else { $wp_scintg_html_content_first .= '<span style="display:block;margin-top:4px;">'. wp_scintg_modText($fdata->message) .'</span>'; }		
	}
	if(wp_scintg_exists($fdata->story))
	{				  		
		    //use strlen(though it counts spaces too) instead of count as count for arrays or objects
	    	if(strlen($fdata->story)>$show_more_len)
		    {		    	
			      $str_pos = (strpos($fdata->story," ",$show_more_len)-$show_more_len)+$show_more_len;			      
			      if($str_pos>0) 
			      { 
			        $primary = substr($fdata->story, 0, $str_pos);
			        $primary2 = substr($fdata->story, $str_pos);$wp_scintg_html_content_first .= '<span style="display:block;margin-top:4px;"><span>'.wp_scintg_modText($primary).'</span><span id="wp_scintg_seemore_'.$wp_scintg_source_id[0].'_'.$rnd_id.'" class="wp_scintg_show_more_link" style="cursor:pointer; cursor:hand; margin:0 2px 0 2px;">See More</span>'.'<span style="display:none;">'.wp_scintg_modText($primary2) .'<span id="wp_scintg_seeless_'.$wp_scintg_source_id[0].'_'.$rnd_id.'" class="wp_scintg_show_less_link" style="cursor:pointer; cursor:hand; margin:0 2px 0 2px;">See Less</span></span></span>'; 
			      } else { $wp_scintg_html_content_first .= '<span style="display:block;margin-top:4px;">'. wp_scintg_modText($fdata->story) .'</span>'; }			     
		    				    
		    }
			else { $wp_scintg_html_content_first .= '<span style="display:block;margin-top:4px;">'. wp_scintg_modText($fdata->story) .'</span>'; }
	} 
	$wp_scintg_html_content_first .= '</span>';	
	//////////////media
	
	//date, share, link section	
		$wp_scintg_html_content_first .= '<span class="wpintg-wall-date">';		
		$wp_scintg_html_content_first .= wp_scintg_FormatDate($fdata->created_time, "at", $wp_scintg_dateformat);		
		
		$wp_scintg_html_content_first .='</span>';	
	 //Likes -------------------------------------------------------------------------------------------------------------------------------

	 // Comments -------------------------------------------------------------------------------------------------------------------------------	
	 $wp_scintg_html_content_first .= '</div><div class="wpintg-wall-clean"></div>';
	 $wp_scintg_html_content_first .='</div>';	
	}// show post type ends
	$wp_scintg_counter++;
  } //for each ends  
  
  ///////////construct current plugin styles start 
  //try adding inherit so that it inherit color and font from parent
  $curmod_styles="";
  $heightop="";
  $heightop='height:'.$wp_scintg_facebookheight.'px;';
  $curmod_styles.= "\r\n#wpintgmain-div$rnd_id .scroll-content  { overflow: auto; margin:7px 1px 2px 0; $heightop }";
  
  $showborder="";
  //if($wp_scintg_showborder){ $showborder='border: 1px solid #e5e5e5; margin:3px; -webkit-border-radius: 4px; -moz-border-radius: 4px; border-radius: 4px; -webkit-box-shadow: 1px 1px 8px rgba(0,0,0,.14); -moz-box-shadow: 1px 1px 8px rgba(0,0,0,.14); box-shadow: 1px 1px 8px rgba(0,0,0,.14);'; }
  $curmod_styles.="\r\n#wpintgmain-div$rnd_id { background-color: $wp_scintg_backcolor; $showborder }";
  
  $showborder="";
  $showborder='border:1px solid buttonface;';
  $curmod_styles.="\r\n#wpintgmain-div$rnd_id .msheader-bar { $showborder }";  
  
  $curmod_styles.="\r\n#wpintgmain-div$wpintg .wpintg-wall { background-color: $wp_scintg_backcolor; }";
  
  $curmod_styles.="\r\n#wpintgmain-div$rnd_id .wpintg-layout { border: 1px solid $wp_scintg_postbordercolor;}";   
//message  
  	$curmod_styles.="\r\n#wpintgmain-div$rnd_id .wpintg-wall-message{color: $wp_scintg_posttextcolor;}";
  	$curmod_styles.="\r\n#wpintgmain-div$rnd_id .wpintg-wall-message a.wpintg-wall-auto-link {color:$wp_scintg_posttextlinkcolor !important;}";
  	$curmod_styles.="\r\n#wpintgmain-div$rnd_id .wpintg-wall-message .wpintg-wall-auto-link:hover,#wpintgmain-div$rnd_id .wpintg-wall-message .wpintg-wall-auto-link:active,#wpintgmain-div$rnd_id wpintg-wall-message .wpintg-wall-auto-link:focus
  	{ font-size:12px !important; color:$wp_scintg_posttextlinkcolor !important; }";    
//mesasge author  
  	$curmod_styles.="\r\n#wpintgmain-div$rnd_id .wpintg-wall-message-from { color:$wp_scintg_postauthorcolor !important; }";
  	$curmod_styles.="\r\n#wpintgmain-div$rnd_id .wpintg-wall-message-from:hover,#wpintgmain-div$rnd_id .wpintg-wall-message-from:active,#wpintgmain-div$rnd_id .wpintg-wall-message-from:focus
  	{ font-size: $wp_scintg_postauthorsize !important; color:12px !important; }";  
 
//date  
  $curmod_styles.="\r\n#wpintgmain-div$rnd_id .wpintg-wall-date { color:$wp_scintg_datecolor;}";
//comments  
  
  //////////////styles end  
 } //records !=0 condition ends
} //no errors condition ends   

} //no error on fbid, token... condition ends at first  
$wp_scintg_source_id = explode("_",$wpintgData->data[0]->id); //if not define here, things get messed up
/* If we want to create more complex html code it's easier to capture the output buffer and return it */ 
 ob_start();  
 ?>
 		
<style type="text/css">      
 <?php echo $curmod_styles; ?>
</style> 
   
<?php if($re_facebookwidth!=""){ $fb_width=$re_facebookwidth; } ?>
<div class="wpintg-wall-main"> <div class="wpintg-container"> <div class="wpintg-row"> <div class="span_len<?php echo $fb_width; ?>">
<div id="wpintgmain-div<?php echo $rnd_id; ?>">
<div id="wpintg-content-main-<?php echo $rnd_id; ?>" class="scroll-content" style="overflow: auto;<?php echo ' height:'. $wp_scintg_facebookheight .'px !important;';  ?>margin:7px 1px 2px 0;"><div class="wpintg-wall"><!-- social intg version1.0 --><?php echo $wp_scintg_html_content_first; ?></div></div>  
</div> 
</div></div></div></div>
 <?php  
    /* Return the buffer contents into a variable */
    $wp_scintg_html_content = ob_get_contents(); 
    /* Empty the buffer without displaying it. We don't want the previous html shown */
    ob_end_clean(); 
    /* The text returned will replace our shortcode matching text */       
    return $wp_scintg_html_content;
}

//format date from facebook per post
function wp_scintg_FormatDate($dateStr,$atTranslated, $dateFormat){				
	
	$unixtime=strtotime($dateStr);
	$day=date("d",$unixtime);
	$month=date("m",$unixtime);
	$year=date("Y",$unixtime);
	$hour=date("h",$unixtime);
	$minutes=date("i",$unixtime);
	$ampm= ((date("H",$unixtime))<12) ? 'am' : 'pm';
	if($dateFormat=="us") { return $month.'.'.$day.'.'.$year.' '.$atTranslated.' '.$hour.':'.$minutes.' '.$ampm; }
	return $day.'.'.$month.'.'.$year.' '.$atTranslated.' '.$hour.':'.$minutes.' '.$ampm;
	
}
function wp_scintg_exists($data){
	if(!$data || $data==null || $data=='undefined') return false;
	else return true;
}
function wp_scintg_modText($text){	
	return wp_scintg_nl2br(wp_scintg_escapeTags($text));
}
	
function wp_scintg_escapeTags($str){	
	$new_str1=str_replace("<","&lt;",$str);
	$new_str2=str_replace(">","&gt;",$new_str1);
	return  $new_str2;
}
	
function wp_scintg_nl2br($str){	
	return str_replace(array("\r\n","\n\r","\r","\n","\n\n"),"<br>", $str);
}
function wp_scintg_Wall_Get_Avatar_Url($id){ $wpintgAvURL='https://graph.facebook.com/'.$id.'/picture?type=square'; return $wpintgAvURL; }
//Get JSON object of feed data
function wp_scintg_Wall_Get_Graph_API_Data($url){
	//if cURL is available and enabled in server
 if(is_callable('curl_init')){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_HEADER, 0);//addition
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 20);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$wpintgJsonData = curl_exec($ch);
	curl_close($ch);
//curl is not available use file_get_contents
} else { if ( ini_get('allow_url_fopen') == 1 || ini_get('allow_url_fopen') === TRUE ) {
	$wpintgJsonData = @file_get_contents($url);	
	//If above ways fails use wordpress HTTP API
} else {
	if( !class_exists( 'WP_Http' ) ) include_once( ABSPATH . WPINC. '/class-http.php' );
	$request = new WP_Http;
	$result = $request->request($url);
	$wpintgJsonData = $result1['body'];
} }
	return $wpintgJsonData;
}		

function wp_social_integration_like_button_function($atts) {
	$atts = shortcode_atts( array(
			'href' => '',
			//'header' => ''. ($wp_scintg_options['wp_scintg_header']) ? "true" : '',
			'width' => '300',
			'layout' => 'standard',
            'share' => 'true'
	), $atts);

	if(!defined('facebook_wall_and_social_integration_13478987')){ echo '<div id="fb-root"></div>
	<script>(function(d, s, id) {
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) return;
	js = d.createElement(s); js.id = id;
	js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
	fjs.parentNode.insertBefore(js, fjs);
	}(document, "script", "facebook-jssdk")); </script>'; define('facebook_wall_and_social_integration_13478987', true); }

	$new_content = '<fb:like href="'.$atts['href'].'" width="'.$atts['width'].'" share="'.$atts['share'].'" layout="'.$atts['layout'].'"></fb:like>';

	return $new_content;
}

function wp_social_integration_follow_button_function($atts) {
	$atts = shortcode_atts( array(
			'href' => 'https://www.facebook.com/Mridulcs',
			//'header' => ''. ($wp_scintg_options['wp_scintg_header']) ? "true" : '',
			'width' => '300',
			'layout' => 'standard',
            'colorscheme' => 'light',
	), $atts);

		if(!defined('facebook_wall_and_social_integration_13478987')){ echo '<div id="fb-root"></div>
	<script>(function(d, s, id) {
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) return;
	js = d.createElement(s); js.id = id;
	js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
	fjs.parentNode.insertBefore(js, fjs);
	}(document, "script", "facebook-jssdk")); </script>'; define('facebook_wall_and_social_integration_13478987', true); }

	$new_content = '<fb:follow href="'.$atts['href'].'" width="'.$atts['width'].'" layout="'.$atts['layout'].'" colorscheme="'.$atts['colorscheme'].'"></fb:follow>';

	return $new_content;
}

function wp_social_integration_send_button_function($atts) {
	$atts = shortcode_atts( array(
			'href' => '',
			'width' => '300',
			'colorscheme' => 'light',
	), $atts);

    $wp_scintg_PageURL = 'http://'; if ($_SERVER["HTTPS"] == "on") { $wp_scintg_PageURL .= "https://"; } $wp_scintg_PageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];    
    $href_val = trim($atts['href']); 
    if($href_val==""){ $href = 'href="'. $wp_scintg_PageURL .'"'; } else { $href='href="'. $href_val .'"'; }

		if(!defined('facebook_wall_and_social_integration_13478987')){ echo '<div id="fb-root"></div>
	<script>(function(d, s, id) {
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) return;
	js = d.createElement(s); js.id = id;
	js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
	fjs.parentNode.insertBefore(js, fjs);
	}(document, "script", "facebook-jssdk")); </script>'; define('facebook_wall_and_social_integration_13478987', true); }

	$new_content = '<fb:send '.$href.' width="'.$atts['width'].'" colorscheme="'.$atts['colorscheme'].'"></fb:send>';

	return $new_content;
}








