<?php 

/** Define the custom box in post editing panel*/
add_action( 'add_meta_boxes', 'wp_scintg_add_metadata_box' );
/**/
function wp_social_integration_css_all_page() {
	wp_enqueue_script('jquery');
	wp_register_style($handle = 'wp_social_integration_bootstrap', $src = plugins_url('css/bootstrap.css', __FILE__), $deps = array(), $ver = '1.0.0', $media = 'all');
	wp_enqueue_style('wp_social_integration_bootstrap');
}

/* admin functions */
function wp_social_integration_plugin_settings() {
	//add_options_page( 'social facebook by mitsol Plugin Settings', 'social facebook by mitsol Plugin', 'manage_options', 'social-facebook-by-mitsol-plugin-settings', 'facebook_wall_and_social_integration_plugin_settings_page' );
	add_menu_page('wp social integration settings', 'wp social integration', 'administrator', 'wp_social_integration_settings', 'wp_social_integration_display_settings');
}
//
function wp_social_integration_display_settings () {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __(
		'You do not have sufficient permissions to access this page.'
				) );
	}
	$options_meta = get_option('wp_scintg_plugin_meta_settings');
	$options_home_feed = get_option('wp_scintg_plugin_home_feed_settings');
		
	//meta
	if((isset($_REQUEST["wp_scintg_active_tab"]))&&($_REQUEST["wp_scintg_active_tab"] == "2"))
	{		
		
		$wp_scintg_basic_meta=$_REQUEST["wp_scintg_basic_meta"]; $options_meta['wp_scintg_basic_meta']= $wp_scintg_basic_meta;
				
		$wp_scintg_basic_auto_desc=$_REQUEST["wp_scintg_basic_auto_desc"]; $options_meta['wp_scintg_basic_auto_desc']= $wp_scintg_basic_auto_desc;
		$wp_scintg_basic_auto_keywords=$_REQUEST["wp_scintg_basic_auto_keywords"]; $options_meta['wp_scintg_basic_auto_keywords']= $wp_scintg_basic_auto_keywords;
		$wp_scintg_frontpg_desc=$_REQUEST["wp_scintg_frontpg_desc"]; $options_meta['wp_scintg_frontpg_desc']= $wp_scintg_frontpg_desc;
		$wp_scintg_frontpg_keywords=$_REQUEST["wp_scintg_frontpg_keywords"]; $options_meta['wp_scintg_frontpg_keywords']= $wp_scintg_frontpg_keywords;									
		
		update_option( 'wp_scintg_plugin_meta_settings', $options_meta );
	}
	//home feed
	if((isset($_REQUEST["wp_scintg_active_tab"]))&&($_REQUEST["wp_scintg_active_tab"] == "3"))
	{		
		$wp_scintg_accesstoken=$_REQUEST["wp_scintg_accesstoken"]; $options_home_feed['wp_scintg_accesstoken']= $wp_scintg_accesstoken;		
		$wp_scintg_facebookwidth=$_REQUEST["wp_scintg_facebookwidth"]; $options_home_feed['wp_scintg_facebookwidth']= $wp_scintg_facebookwidth;
		$wp_scintg_heightoption=$_REQUEST["wp_scintg_heightoption"]; $options_home_feed['wp_scintg_heightoption']= $wp_scintg_heightoption;
		$wp_scintg_facebookheight=$_REQUEST["wp_scintg_facebookheight"]; $options_home_feed['wp_scintg_facebookheight']= $wp_scintg_facebookheight;
		$wp_scintg_header=$_REQUEST["wp_scintg_header"]; $options_home_feed['wp_scintg_header']= $wp_scintg_header;
		$wp_scintg_headertext=$_REQUEST["wp_scintg_headertext"]; $options_home_feed['wp_scintg_headertext']= $wp_scintg_headertext;
		
		$wp_scintg_postnum=$_REQUEST["wp_scintg_postnum"]; $options_home_feed['wp_scintg_postnum']= $wp_scintg_postnum;		
		 
		$wp_scintg_showborder=$_REQUEST["wp_scintg_showborder"]; $options_home_feed['wp_scintg_showborder']= $wp_scintg_showborder;		
		
		$wp_scintg_dateformat=$_REQUEST["wp_scintg_dateformat"]; $options_home_feed['wp_scintg_dateformat']= $wp_scintg_dateformat;
		$wp_scintg_timezone=$_REQUEST["wp_scintg_timezone"]; $options_home_feed['wp_scintg_timezone']= $wp_scintg_timezone;
		
		$wp_scintg_cache_time=$_REQUEST["wp_scintg_cache_time"]; $options_home_feed['wp_scintg_cache_time']= $wp_scintg_cache_time;
		$wp_scintg_cache_time_unit=$_REQUEST["wp_scintg_cache_time_unit"]; $options_home_feed['wp_scintg_cache_time_unit']= $wp_scintg_cache_time_unit;

		$wp_scintg_backcolor=$_REQUEST["wp_scintg_backcolor"]; $options_home_feed['wp_scintg_backcolor']= $wp_scintg_backcolor;
		$wp_scintg_postbordercolor=$_REQUEST["wp_scintg_postbordercolor"]; $options_home_feed['wp_scintg_postbordercolor']= $wp_scintg_postbordercolor;
		
		$wp_scintg_postauthorcolor=$_REQUEST["wp_scintg_postauthorcolor"]; $options_home_feed['wp_scintg_postauthorcolor']= $wp_scintg_postauthorcolor;		
		$wp_scintg_posttextcolor=$_REQUEST["wp_scintg_posttextcolor"]; $options_home_feed['wp_scintg_posttextcolor']= $wp_scintg_posttextcolor;		
		$wp_scintg_datecolor=$_REQUEST["wp_scintg_datecolor"]; $options_home_feed['wp_scintg_datecolor']= $wp_scintg_datecolor;
		
		$wp_scintg_backcolorcom=$_REQUEST["wp_scintg_backcolorcom"]; $options_home_feed['wp_scintg_backcolorcom']= $wp_scintg_backcolorcom;
	
		update_option( 'wp_scintg_plugin_home_feed_settings', $options_home_feed );
	}		
	//meta	
	$wp_scintg_basic_meta= ($options_meta['wp_scintg_basic_meta'] == 'enabled') ? 'checked' : '' ;
			
	$wp_scintg_basic_auto_desc= ($options_meta['wp_scintg_basic_auto_desc'] == 'enabled') ? 'checked' : '' ;
	$wp_scintg_basic_auto_keywords= ($options_meta['wp_scintg_basic_auto_keywords'] == 'enabled') ? 'checked' : '' ;
	
	$wp_scintg_frontpg_desc = ($options_meta['wp_scintg_frontpg_desc'] != '') ? $options_meta['wp_scintg_frontpg_desc'] : '';
	$wp_scintg_frontpg_keywords = ($options_meta['wp_scintg_frontpg_keywords'] != '') ? $options_meta['wp_scintg_frontpg_keywords'] : '';
		
    //home page feed	
	$wp_scintg_accesstoken = ($options_home_feed['wp_scintg_accesstoken'] != '') ? $options_home_feed['wp_scintg_accesstoken'] : '';
	
	$wp_scintg_facebookwidth = ($options_home_feed['wp_scintg_facebookwidth'] != '') ? $options_home_feed['wp_scintg_facebookwidth'] : '90';
	$wp_scintg_heightoptionfixed = ($options_home_feed['wp_scintg_heightoption'] == 'fixed') ? 'selected' : '';
	$wp_scintg_heightoptionsize = ($options_home_feed['wp_scintg_heightoption'] == 'size of posts') ? 'selected' : '';
	$wp_scintg_facebookheight = ($options_home_feed['wp_scintg_facebookheight'] != '') ? $options_home_feed['wp_scintg_facebookheight'] : '550';
	$wp_scintg_header  = ($options_home_feed['wp_scintg_header'] == 'enabled') ? 'checked' : '' ;	
	$wp_scintg_headertext= ($options_home_feed['wp_scintg_headertext'] != '') ? $options_home_feed['wp_scintg_headertext'] : 'Follow me in facebook';
	$wp_scintg_postnum = ($options_home_feed['wp_scintg_postnum'] != '') ? $options_home_feed['wp_scintg_postnum'] : '10';	
	$wp_scintg_showborder = ($options_home_feed['wp_scintg_showborder'] == 'enabled') ? 'checked' : '' ;
	$wp_scintg_cache_time = ($options_home_feed['wp_scintg_cache_time'] != '') ? $options_home_feed['wp_scintg_cache_time'] : '';
	$wp_scintg_cache_time_unit = ($options_home_feed['wp_scintg_cache_time_unit'] != '') ? $options_home_feed['wp_scintg_cache_time_unit'] : '';
		
	$wp_scintg_dateformat_us = ($options_home_feed['wp_scintg_dateformat'] == 'us') ? 'selected' : '';
	$wp_scintg_dateformat_nonus = ($options_home_feed['wp_scintg_dateformat'] == 'nonus') ? 'selected' : '';
	$wp_scintg_timezone = ($options_home_feed['wp_scintg_timezone'] != '') ? $options_home_feed['wp_scintg_timezone'] : 'Europe/London';	

	$wp_scintg_backcolor = ($options_home_feed['wp_scintg_backcolor'] != '') ? $options_home_feed['wp_scintg_backcolor'] : '#ffffff';
	$wp_scintg_postbordercolor = ($options_home_feed['wp_scintg_postbordercolor'] != '') ? $options_home_feed['wp_scintg_postbordercolor'] : '#F0F0F0';
	$wp_scintg_postauthorcolor=($options_home_feed['wp_scintg_postauthorcolor'] != '') ? $options_home_feed['wp_scintg_postauthorcolor'] : '#3B5998';
	$wp_scintg_posttextcolor=($options_home_feed['wp_scintg_posttextcolor'] != '') ? $options_home_feed['wp_scintg_posttextcolor'] : '#333333';
	$wp_scintg_datecolor=($options_home_feed['wp_scintg_datecolor'] != '') ? $options_home_feed['wp_scintg_datecolor'] : '#777';
	 
	$wp_scintg_posttextlinkcolor = ($options_home_feed['wp_scintg_posttextlinkcolor'] != '') ? $options_home_feed['wp_scintg_posttextlinkcolor'] : '#3B5998';	
	
	if(isset($_REQUEST["wp_scintg_active_tab"])) { //if($_REQUEST["settings-updated"] == "true"){
		if($_REQUEST["wp_scintg_active_tab"] == "1"){ $setting_section="Social login"; }    if($_REQUEST["wp_scintg_active_tab"] == "2"){ $setting_section="Meta data"; }    if($_REQUEST["wp_scintg_active_tab"] == "3"){ $setting_section="FB home feed"; }  if($_REQUEST["wp_scintg_active_tab"] == "4"){ $setting_section="Social plugin"; }
		$wp_scintg_success_error='<div class="alert alert-success">
        <a class="close" data-dismiss="alert">x</a>
        '. $setting_section .' settings saved successfully
        </div>';
	}

	(!isset($_REQUEST["wp_scintg_active_tab"])) ? $wp_scintg_active_tab="1": $wp_scintg_active_tab = $_REQUEST["wp_scintg_active_tab"];

			if($wp_scintg_active_tab =="1"){ $active=""; $active2='style="display:none;"';$active3='style="display:none;"'; $active4='style="display:none;"'; $active5='style="display:none;"'; $active6='style="display:none;"'; $activetab='class="active"'; $activetab2='';  $activetab3=''; $activetab4=''; $activetab5=''; $activetab6=''; }
			if($wp_scintg_active_tab =="2"){ $active2=""; $active='style="display:none;"'; $active3='style="display:none;"'; $active4='style="display:none;"'; $active5='style="display:none;"'; $active6='style="display:none;"'; $activetab2='class="active"'; $activetab='';  $activetab3=''; $activetab4=''; $activetab5=''; $activetab6=''; }
			if($wp_scintg_active_tab =="3"){ $active3=""; $active='style="display:none;"';  $active2='style="display:none;"';  $active4='style="display:none;"'; $active5='style="display:none;"'; $active6='style="display:none;"';  $activetab3='class="active"'; $activetab='';  $activetab2=''; $activetab4=''; $activetab5=''; $activetab6=''; }
			if($wp_scintg_active_tab =="4"){ $active4=""; $active3='style="display:none;"'; $active='style="display:none;"'; $active2='style="display:none;"'; $active5='style="display:none;"'; $active6='style="display:none;"';  $activetab4='class="active"'; $activetab3=''; $activetab='';  $activetab2=''; $activetab5=''; $activetab6=''; }
			if($wp_scintg_active_tab =="5"){ $active5=""; $active3='style="display:none;"'; $active='style="display:none;"'; $active2='style="display:none;"'; $active4='style="display:none;"'; $active6='style="display:none;"'; $activetab5='class="active"'; $activetab3=''; $activetab='';  $activetab2=''; $activetab4=''; $activetab6=''; }

			?>
<div class="msmain_container" style="margin-top:10px;">	
<script type="text/javascript">	
var ms_js = jQuery.noConflict();  	
ms_js(function(){		 	
 ms_js("#ms_1st_tablink").click(function() {
     ms_js("#ms_1st_tab").show();  ms_js("#ms_2nd_tab").hide();  ms_js("#ms_third_tab").hide();	 ms_js("#ms_fourth_tab").hide();  	
	 ms_js("#ms_fifth_tab").hide(); ms_js("#ms_sixth_tab").hide();
	  
  	 ms_js("#ms_1st_list").addClass("active"); ms_js("#ms_2nd_list").removeClass("active"); ms_js("#ms_third_list").removeClass("active");
	 ms_js("#ms_fourth_list").removeClass("active"); ms_js("#ms_fifth_list").removeClass("active");	 
  });
 
  ms_js("#ms_2nd_tablink").click(function() {
     ms_js("#ms_2nd_tab").show(); 
	 ms_js("#ms_1st_tab").hide(); 
	 ms_js("#ms_third_tab").hide();	
	 ms_js("#ms_fourth_tab").hide();
	 ms_js("#ms_fifth_tab").hide(); 
	 ms_js("#ms_sixth_tab").hide();
	  
  	 ms_js("#ms_2nd_list").addClass("active"); 	 
	 ms_js("#ms_1st_list").removeClass("active");
  	 ms_js("#ms_third_list").removeClass("active"); 
	 ms_js("#ms_fourth_list").removeClass("active"); 
	 ms_js("#ms_fifth_list").removeClass("active");
  });
   ms_js("#ms_third_tablink").click(function() {
     ms_js("#ms_1st_tab").hide(); 
	 ms_js("#ms_2nd_tab").hide(); 
	 ms_js("#ms_sixth_tab").hide();
	 ms_js("#ms_third_tab").show();	 
 	 ms_js("#ms_fourth_tab").hide();
 	ms_js("#ms_fifth_tab").hide(); 	
	  
  	 ms_js("#ms_1st_list").removeClass("active"); 	 
	 ms_js("#ms_2nd_list").removeClass("active");
  	 ms_js("#ms_third_list").addClass("active"); 
	 ms_js("#ms_fourth_list").removeClass("active"); 
	 ms_js("#ms_fifth_list").removeClass("active");
  });
  ms_js("#ms_fourth_tablink").click(function() {
     ms_js("#ms_1st_tab").hide(); 
	 ms_js("#ms_2nd_tab").hide(); 
	 ms_js("#ms_third_tab").hide();
	 ms_js("#ms_sixth_tab").hide();
	 ms_js("#ms_fourth_tab").show();
	 ms_js("#ms_fifth_tab").hide(); 	 	
	  
     ms_js("#ms_fourth_list").addClass("active");
  	 ms_js("#ms_1st_list").removeClass("active"); 	 
	 ms_js("#ms_2nd_list").removeClass("active");
  	 ms_js("#ms_third_list").removeClass("active"); 
  	 ms_js("#ms_fifth_list").removeClass("active"); 
  });
  ms_js("#ms_fifth_tablink").click(function() {
	     ms_js("#ms_1st_tab").hide(); 
		 ms_js("#ms_2nd_tab").hide(); 
		 ms_js("#ms_third_tab").hide();
		 ms_js("#ms_fourth_tab").hide(); 
		 ms_js("#ms_sixth_tab").hide();
		 ms_js("#ms_fifth_tab").show(); 	 			 
		  	     
	  	 ms_js("#ms_1st_list").removeClass("active"); 	 
		 ms_js("#ms_2nd_list").removeClass("active");
	  	 ms_js("#ms_third_list").removeClass("active");
	  	ms_js("#ms_fourth_list").removeClass("active");
	  	ms_js("#ms_fifth_list").addClass("active"); 
	  });
  ms_js("#ms_sixth_tablink").click(function() {
	     ms_js("#ms_1st_tab").hide(); 
		 ms_js("#ms_2nd_tab").hide(); 
		 ms_js("#ms_third_tab").hide();
		 ms_js("#ms_fourth_tab").hide(); 
		 ms_js("#ms_fifth_tab").hide(); 
		 ms_js("#ms_sixth_tab").show(); 	 	
		  
	     ms_js("#ms_fourth_list").removeClass("active");
	  	 ms_js("#ms_1st_list").removeClass("active"); 	 
		 ms_js("#ms_2nd_list").removeClass("active");
	  	 ms_js("#ms_third_list").removeClass("active");
	  	ms_js("#ms_fifth_list").removeClass("active"); 
	  	ms_js("#ms_sixth_list").addClass("active"); 
	  });
  ms_js(".msmain_container .close").click( function() {
    ms_js(this).parent("div").hide();
  });
});	 

</script>
<style type="text/css">

.msmain_container select,.msmain_container 
textarea,.msmain_container 
input[type="text"],.msmain_container 
input[type="password"],.msmain_container 
input[type="datetime"],.msmain_container 
input[type="datetime-local"],.msmain_container 
input[type="date"],.msmain_container 
input[type="month"],.msmain_container 
input[type="time"],.msmain_container 
input[type="week"],.msmain_container 
input[type="number"],.msmain_container 
input[type="email"],.msmain_container 
input[type="url"],.msmain_container 
input[type="search"],.msmain_container 
input[type="tel"],.msmain_container 
input[type="color"],.msmain_container 
.uneditable-input {
  height: 28px; 
}
</style>

<div class="container-fluid" style="margin-top:30px; padding-top:20px; background-color:white">  
<div class="row-fluid">  
<div class="span12"> <?php echo $wp_scintg_success_error; ?> 
<ul class="nav nav-tabs">  		 
<li  id="ms_1st_list" style="cursor:pointer; cursor:hand" <?php echo $activetab ; ?> ><a id="ms_1st_tablink">Social login</a></li>  
<li id="ms_2nd_list" style="cursor:pointer; cursor:hand" <?php echo $activetab2 ; ?>><a id="ms_2nd_tablink">Open graph & basic metadata</a></li>  
<li id="ms_third_list" style="cursor:pointer; cursor:hand"  <?php echo $activetab3 ; ?>><a id="ms_third_tablink">FB home news feed</a></li>
<li id="ms_fourth_list" style="cursor:pointer; cursor:hand"  <?php echo $activetab4; ?>><a id="ms_fourth_tablink">Social plugins</a></li>  
<li id="ms_fifth_list" style="cursor:pointer; cursor:hand" <?php echo $activetab6 ; ?>><a id="ms_fifth_tablink">System requirements</a></li>
  
</ul>
<div  id="ms_1st_tab" <?php echo $active; ?>> 
<form method="post" name="login_options" action="" class="form-horizontal">  
        <fieldset>  
          <legend>Social login settings </legend>
          <div class="control-group">   
            <div class="controls">  
			  <b>Available in pro version</b>
            </div>  
          </div> 
          <div class="control-group">  
            <label class="control-label" for="wp_scintg_fbapp_id">Facebook Applicatin ID</label>  
            <div class="controls">  
			<input type="text" disabled="disabled" class="input-xxlarge" name="wp_scintg_fbapp_id" value="<?php echo esc_attr_e($wp_scintg_fbapp_id); ?>" id="wp_scintg_fbapp_id" />
			<p class="help-block"><a target="_blank" href="http://extensions.techhelpsource.com/wp_social_integration_documentation.html">read doc</a> to get facebook application id</p> 
            </div>  
          </div>  		  
		  <!--  <div class="control-group">  
            <label class="control-label" for="wp_scintg_sdk_script">Add Facebook Sdk initialization script</label>  
            <div class="controls">  
              <label class="checkbox">  
                <input  disabled="disabled"  type="checkbox" <?php echo esc_attr_e($wp_scintg_sdk_script); ?> name="wp_scintg_sdk_script" id="wp_scintg_sdk_script" value="enabled" />  
                 
              </label>  
            </div>  
          </div> -->
          <div class="control-group">  
            <label class="control-label" for="wp_scintg_sdk_fbml">Set fbml to true in Facebook Sdk initialization script</label>  
            <div class="controls">  
              <label class="checkbox">  
                <input  disabled="disabled" type="checkbox" <?php echo esc_attr_e($wp_scintg_sdk_fbml); ?> name="wp_scintg_sdk_fbml" id="wp_scintg_sdk_fbml" value="enabled" /> 
                <p class="help-block">set fbml to true</p>
                 
              </label>  
            </div>  
          </div>
          <div class="control-group">  
            <label class="control-label" for="wp_scintg_reg_email_admin">Send registration success email to admin</label>  
            <div class="controls">  
              <label class="checkbox">  
                <input   disabled="disabled" type="checkbox" <?php echo esc_attr_e($wp_scintg_reg_email_admin); ?> name="wp_scintg_reg_email_admin" id="wp_scintg_reg_email_admin" value="enabled" />  
                 
              </label>  
            </div>  
          </div>
          <div class="control-group">
          <label class="control-label" for="wp_scintg_login_form">Show login form</label>  
            <div class="controls">  
              <label class="checkbox">  
                <input disabled="disabled"  type="checkbox" <?php echo esc_attr_e($wp_scintg_login_form); ?> name="wp_scintg_login_form" id="wp_scintg_login_form" value="enabled" />  
                 
              </label>  
            </div>  
          </div>
          <div class="control-group">  
             <label class="control-label" for="wp_scintg_login_redirect">Login redirection page url</label>    
            <div class="controls">  
			<input disabled="disabled" type="text" class="input-xxlarge" name="wp_scintg_login_redirect" value="<?php echo esc_attr_e($wp_scintg_login_redirect); ?>" id="wp_scintg_login_redirect" />			
            </div>  
          </div>  
          <div class="control-group">
          <label class="control-label" for="wp_scintg_show_register">Show register url</label>  
            <div class="controls">  
              <label class="checkbox">  
                <input disabled="disabled"  type="checkbox" <?php echo esc_attr_e($wp_scintg_show_register); ?> name="wp_scintg_show_register" id="wp_scintg_show_register" value="enabled" />                   
              </label>  
            </div>  
          </div>
          <div class="control-group">
          <label class="control-label" for="wp_scintg_show_forgotpass">Show forgot password url</label>  
            <div class="controls">  
              <label class="checkbox">  
                <input  disabled="disabled" type="checkbox" <?php echo esc_attr_e($wp_scintg_show_forgotpass); ?> name="wp_scintg_show_forgotpass" id="wp_scintg_show_forgotpass" value="enabled" />                   
              </label>  
            </div>  
          </div>  
		  									               
          <div class="form-actions"> 
  		   <input type="hidden" name="wp_scintg_active_tab" value="1" /> 
            <input type="submit" disabled="disabled" name="submit" class="btn btn-primary" value="Update"/>   
          </div>  
        </fieldset>  
</form>  
  
</div> 
<div id="ms_2nd_tab" <?php echo $active2; ?>>  
<form method="post" name="og_options" action="" class="form-horizontal">  
        <fieldset>  
          <legend>Open graph and other meta tag settings</legend>
          
           <div class="control-group">  
            <label class="control-label" for="wp_scintg_add_open_graph">Add Open graph metadata</label>  
            <div class="controls">  
              <label class="checkbox">  
                <input type="checkbox" disabled="disabled" <?php echo esc_attr_e($wp_scintg_add_open_graph); ?> name="wp_scintg_add_open_graph" id="wp_scintg_add_open_graph" value="enabled" />  
              </label>  
            </div>  
          </div> 
          
         <div class="control-group">  
            <label class="control-label" for="wp_scintg_add_twitter_card">Add twitter cards metadata</label>  
            <div class="controls">  
              <label class="checkbox">  
                <input type="checkbox" disabled="disabled" <?php echo esc_attr_e($wp_scintg_add_twitter_card); ?> name="wp_scintg_add_twitter_card" id="wp_scintg_add_twitter_card" value="enabled" />  
              </label>  
            </div>  
          </div> 
          
         <div class="control-group">  
            <label class="control-label" for="wp_scintg_basic_meta">Add basic metadata</label>  
            <div class="controls">  
              <label class="checkbox">  
                <input type="checkbox" <?php echo esc_attr_e($wp_scintg_basic_meta); ?> name="wp_scintg_basic_meta" id="wp_scintg_basic_meta" value="enabled" />  
              </label>  
            </div>  
          </div>
          <div class="control-group">  
            <label class="control-label" for="wp_scintg_basic_auto_desc">Auto basic metadata description</label>  
            <div class="controls">  
              <label class="checkbox">  
                <input type="checkbox" <?php echo esc_attr_e($wp_scintg_basic_auto_desc); ?> name="wp_scintg_basic_auto_desc" id="wp_scintg_basic_auto_desc" value="enabled" />  
              </label>  
            </div>  
          </div> 
           <div class="control-group">  
            <label class="control-label" for="wp_scintg_basic_auto_keywords">Auto basic metadata keywords</label>  
            <div class="controls">  
              <label class="checkbox">  
                <input type="checkbox" <?php echo esc_attr_e($wp_scintg_basic_auto_keywords); ?> name="wp_scintg_basic_auto_keywords" id="wp_scintg_basic_auto_keywords" value="enabled" />  
              </label>  
            </div>  
          </div>  
          <div class="control-group">  
            <label class="control-label" for="wp_scintg_add_site_wide_meta">Site wide meta tags</label>  
            <div class="controls">  
			<textarea class="input-xxlarge" disabled="disabled" style="height:100px;" name="wp_scintg_add_site_wide_meta" rows="4" id="wp_scintg_add_site_wide_meta"><?php echo esc_attr_e(stripslashes($wp_scintg_add_site_wide_meta)); ?></textarea>
			<p class="help-block">Enter the full code of extra meta elements you like to add to all the pages of your site, example - <code>&lt;meta name="google-site-verification" content="1234567" /&gt;</code><code>&lt;meta name="robots" content="noimageindex" /&gt;</code></p> 
            </div>  
          </div> 
          <div class="control-group">  
            <label class="control-label" for="wp_scintg_frontpg_desc">Front page description</label>  
            <div class="controls">  
			<textarea class="input-xxlarge" style="height:100px;" name="wp_scintg_frontpg_desc" rows="4" id="wp_scintg_frontpg_desc"><?php echo esc_attr_e($wp_scintg_frontpg_desc); ?></textarea>			
			<p class="help-block">Enter a short (150-250 characters) description of your blog. This text will be set as description and other <br/>similar metatags(open graph..) on front page. If it's empty, then the blog's tagline from the general options will be used. </p> 
            </div>  
          </div> 
                    <div class="control-group">  
            <label class="control-label" for="wp_scintg_frontpg_keywords">Front page keywords</label>  
            <div class="controls">  
			<textarea class="input-xxlarge" style="height:100px;" name="wp_scintg_frontpg_keywords" rows="4" id="wp_scintg_frontpg_keywords"><?php echo esc_attr_e($wp_scintg_frontpg_keywords); ?></textarea>
			<p class="help-block">Enter a list of keywords by comma. These keywords will be used in the keywords meta tag on front page.<br/>If this field is empty, then all of your blog's categories will be used as keywords.</p> 
            </div>  
          </div> 
          <div class="control-group">  
            <label class="control-label" for="wp_scintg_defult_img">Default image</label>  
            <div class="controls">  
			<input type="text" disabled="disabled" class="input-xxlarge" name="wp_scintg_defult_img" value="<?php echo esc_attr_e($wp_scintg_defult_img); ?>" id="wp_scintg_defult_img" />
			<p class="help-block">Enter absolute url of the image. This image will be used in the metadata if no other images have been attached or<br/>embedded.
Example - http://mysite.com/images/logo.png</p> 
            </div>  
          </div> 
          <div class="control-group">  
            <label class="control-label" for="wp_scintg_og_appid">Facebook applicatin id</label>  
            <div class="controls">  
			<input type="text" disabled="disabled" class="input-xxlarge" name="wp_scintg_og_appid" value="<?php echo esc_attr_e($wp_scintg_og_appid); ?>" id="wp_scintg_og_appid" />
			<p class="help-block">Facebook application id to be set as open graph app_id meta </p> 
            </div>  
          </div>                            
          <div class="control-group">               
            <div class="controls">  
            <b>disabled settings are available in pro version</b> 
            </div>  
          </div>   		  									               
          <div class="form-actions"> 
  		   <input type="hidden" name="wp_scintg_active_tab" value="2" />
            <input type="submit" name="submit" class="btn btn-primary" value="Update"/>  
          </div>  
        </fieldset>  
</form>  
</div> 
 
<div id="ms_third_tab"  <?php echo $active3; ?>>
<form method="post" name="feed_options" action="" class="form-horizontal">  
        <fieldset>  
          <legend>Facebook home feed</legend> 
		  <div class="control-group">  
            <label class="control-label" for="wp_scintg_accesstoken">Access token</label>  
            <div class="controls">  
			<input type="text" class="input-xlarge" name="wp_scintg_accesstoken" value="<?php echo esc_attr_e($wp_scintg_accesstoken); ?>" id="wp_scintg_accesstoken" />
               <p class="help-block">to get token create facebook application first and configure application basic setting correctly, written in the <a target="_blank" href="http://extensions.techhelpsource.com/wp_social_integration_documentation.html">doc here</a>)</p>  
            </div>  
          </div> 		  
		 		  
		   <div class="control-group">  
            <label class="control-label" for="wp_scintg_facebookwidth">Width</label>  
            <div class="controls">  
			<input type="text" class="input-xlarge" name="wp_scintg_facebookwidth" value="<?php echo esc_attr_e($wp_scintg_facebookwidth); ?>" id="wp_scintg_facebookwidth" />
			<p class="help-block">width value in %. Ex - 100,80,50..</p> 
            </div>  
          </div>  		 		  		        
		  <div class="control-group">  
            <label class="control-label" for="wp_scintg_facebookheight">Height</label>  
            <div class="controls">  
			<input type="text" class="input-xlarge" name="wp_scintg_facebookheight" value="<?php echo esc_attr_e($wp_scintg_facebookheight); ?>" id="wp_scintg_facebookheight" />                        
            </div>  
          </div>              		 
		  
		  <div class="control-group">  
            <label class="control-label" for="wp_scintg_postnum">Show number of posts</label>  
            <div class="controls">  
			<input type="text"  class="input-xlarge" name="wp_scintg_postnum" value="<?php echo esc_attr_e($wp_scintg_postnum); ?>" id="wp_scintg_postnum" />
            </div>  
          </div>
          <div class="control-group">  
            <label class="control-label" for="wp_scintg_dateformat">Date format</label>  
            <div class="controls">  
              <select id="wp_scintg_dateformat" name="wp_scintg_dateformat">  
                 <option value="us" <?php echo esc_attr_e($wp_scintg_dateformat_us); ?>>05.25.2014 08:20</option>
                 <option value="nonus" <?php echo esc_attr_e($wp_scintg_dateformat_nonus ); ?>>25.05.2014 08:20</option></select>
            </div>  
          </div>
           <div class="control-group">  
            <label class="control-label" for="wp_scintg_timezone">Date TimeZone</label>  
            <div class="controls">  
           <select id="wp_scintg_timezone" name="wp_scintg_timezone">
                                        <option value="Pacific/Midway" <?php if($wp_scintg_timezone == "Pacific/Midway") echo 'selected="selected"' ?> ><?php _e('(GMT-11:00) Midway Island, Samoa'); ?></option>
                                        <option value="America/Adak" <?php if($wp_scintg_timezone == "America/Adak") echo 'selected="selected"' ?> ><?php _e('(GMT-10:00) Hawaii-Aleutian'); ?></option>
                                        <option value="Etc/GMT+10" <?php if($wp_scintg_timezone == "Etc/GMT+10") echo 'selected="selected"' ?> ><?php _e('(GMT-10:00) Hawaii'); ?></option>
                                        <option value="Pacific/Marquesas" <?php if($wp_scintg_timezone == "Pacific/Marquesas") echo 'selected="selected"' ?> ><?php _e('(GMT-09:30) Marquesas Islands'); ?></option>
                                        <option value="Pacific/Gambier" <?php if($wp_scintg_timezone == "Pacific/Gambier") echo 'selected="selected"' ?> ><?php _e('(GMT-09:00) Gambier Islands'); ?></option>
                                        <option value="America/Anchorage" <?php if($wp_scintg_timezone == "America/Anchorage") echo 'selected="selected"' ?> ><?php _e('(GMT-09:00) Alaska'); ?></option>
                                        <option value="America/Ensenada" <?php if($wp_scintg_timezone == "America/Ensenada") echo 'selected="selected"' ?> ><?php _e('(GMT-08:00) Tijuana, Baja California'); ?></option>
                                        <option value="Etc/GMT+8" <?php if($wp_scintg_timezone == "Etc/GMT+8") echo 'selected="selected"' ?> ><?php _e('(GMT-08:00) Pitcairn Islands'); ?></option>
                                        <option value="America/Los_Angeles" <?php if($wp_scintg_timezone == "America/Los_Angeles") echo 'selected="selected"' ?> ><?php _e('(GMT-08:00) Pacific Time (US & Canada)'); ?></option>
                                        <option value="America/Denver" <?php if($wp_scintg_timezone == "America/Denver") echo 'selected="selected"' ?> ><?php _e('(GMT-07:00) Mountain Time (US & Canada)'); ?></option>
                                        <option value="America/Chihuahua" <?php if($wp_scintg_timezone == "America/Chihuahua") echo 'selected="selected"' ?> ><?php _e('(GMT-07:00) Chihuahua, La Paz, Mazatlan'); ?></option>
                                        <option value="America/Dawson_Creek" <?php if($wp_scintg_timezone == "America/Dawson_Creek") echo 'selected="selected"' ?> ><?php _e('(GMT-07:00) Arizona'); ?></option>
                                        <option value="America/Belize" <?php if($wp_scintg_timezone == "America/Belize") echo 'selected="selected"' ?> ><?php _e('(GMT-06:00) Saskatchewan, Central America'); ?></option>
                                        <option value="America/Cancun" <?php if($wp_scintg_timezone == "America/Cancun") echo 'selected="selected"' ?> ><?php _e('(GMT-06:00) Guadalajara, Mexico City, Monterrey'); ?></option>
                                        <option value="Chile/EasterIsland" <?php if($wp_scintg_timezone == "Chile/EasterIsland") echo 'selected="selected"' ?> ><?php _e('(GMT-06:00) Easter Island'); ?></option>
                                        <option value="America/Chicago" <?php if($wp_scintg_timezone == "America/Chicago") echo 'selected="selected"' ?> ><?php _e('(GMT-06:00) Central Time (US & Canada)'); ?></option>
                                        <option value="America/New_York" <?php if($wp_scintg_timezone == "America/New_York") echo 'selected="selected"' ?> ><?php _e('(GMT-05:00) Eastern Time (US & Canada)'); ?></option>
                                        <option value="America/Havana" <?php if($wp_scintg_timezone == "America/Havana") echo 'selected="selected"' ?> ><?php _e('(GMT-05:00) Cuba'); ?></option>
                                        <option value="America/Bogota" <?php if($wp_scintg_timezone == "America/Bogota") echo 'selected="selected"' ?> ><?php _e('(GMT-05:00) Bogota, Lima, Quito, Rio Branco'); ?></option>
                                        <option value="America/Caracas" <?php if($wp_scintg_timezone == "America/Caracas") echo 'selected="selected"' ?> ><?php _e('(GMT-04:30) Caracas'); ?></option>
                                        <option value="America/Santiago" <?php if($wp_scintg_timezone == "America/Santiago") echo 'selected="selected"' ?> ><?php _e('(GMT-04:00) Santiago'); ?></option>
                                        <option value="America/La_Paz" <?php if($wp_scintg_timezone == "America/La_Paz") echo 'selected="selected"' ?> ><?php _e('(GMT-04:00) La Paz'); ?></option>
                                        <option value="Atlantic/Stanley" <?php if($wp_scintg_timezone == "Atlantic/Stanley") echo 'selected="selected"' ?> ><?php _e('(GMT-04:00) Faukland Islands'); ?></option>
                                        <option value="America/Campo_Grande" <?php if($wp_scintg_timezone == "America/Campo_Grande") echo 'selected="selected"' ?> ><?php _e('(GMT-04:00) Brazil'); ?></option>
                                        <option value="America/Goose_Bay" <?php if($wp_scintg_timezone == "America/Goose_Bay") echo 'selected="selected"' ?> ><?php _e('(GMT-04:00) Atlantic Time (Goose Bay)'); ?></option>
                                        <option value="America/Glace_Bay" <?php if($wp_scintg_timezone == "America/Glace_Bay") echo 'selected="selected"' ?> ><?php _e('(GMT-04:00) Atlantic Time (Canada)'); ?></option>
                                        <option value="America/St_Johns" <?php if($wp_scintg_timezone == "America/St_Johns") echo 'selected="selected"' ?> ><?php _e('(GMT-03:30) Newfoundland'); ?></option>
                                        <option value="America/Araguaina" <?php if($wp_scintg_timezone == "America/Araguaina") echo 'selected="selected"' ?> ><?php _e('(GMT-03:00) UTC-3'); ?></option>
                                        <option value="America/Montevideo" <?php if($wp_scintg_timezone == "America/Montevideo") echo 'selected="selected"' ?> ><?php _e('(GMT-03:00) Montevideo'); ?></option>
                                        <option value="America/Miquelon" <?php if($wp_scintg_timezone == "America/Miquelon") echo 'selected="selected"' ?> ><?php _e('(GMT-03:00) Miquelon, St. Pierre'); ?></option>
                                        <option value="America/Godthab" <?php if($wp_scintg_timezone == "America/Godthab") echo 'selected="selected"' ?> ><?php _e('(GMT-03:00) Greenland'); ?></option>
                                        <option value="America/Argentina/Buenos_Aires" <?php if($wp_scintg_timezone == "America/Argentina/Buenos_Aires") echo 'selected="selected"' ?> ><?php _e('(GMT-03:00) Buenos Aires'); ?></option>
                                        <option value="America/Sao_Paulo" <?php if($wp_scintg_timezone == "America/Sao_Paulo") echo 'selected="selected"' ?> ><?php _e('(GMT-03:00) Brasilia'); ?></option>
                                        <option value="America/Noronha" <?php if($wp_scintg_timezone == "America/Noronha") echo 'selected="selected"' ?> ><?php _e('(GMT-02:00) Mid-Atlantic'); ?></option>
                                        <option value="Atlantic/Cape_Verde" <?php if($wp_scintg_timezone == "Atlantic/Cape_Verde") echo 'selected="selected"' ?> ><?php _e('(GMT-01:00) Cape Verde Is.'); ?></option>
                                        <option value="Atlantic/Azores" <?php if($wp_scintg_timezone == "Atlantic/Azores") echo 'selected="selected"' ?> ><?php _e('(GMT-01:00) Azores'); ?></option>
                                        <option value="Europe/Belfast" <?php if($wp_scintg_timezone == "Europe/Belfast") echo 'selected="selected"' ?> ><?php _e('(GMT) Greenwich Mean Time : Belfast'); ?></option>
                                        <option value="Europe/Dublin" <?php if($wp_scintg_timezone == "Europe/Dublin") echo 'selected="selected"' ?> ><?php _e('(GMT) Greenwich Mean Time : Dublin'); ?></option>
                                        <option value="Europe/Lisbon" <?php if($wp_scintg_timezone == "Europe/Lisbon") echo 'selected="selected"' ?> ><?php _e('(GMT) Greenwich Mean Time : Lisbon'); ?></option>
                                        <option value="Europe/London" <?php if($wp_scintg_timezone == "Europe/London") echo 'selected="selected"' ?> ><?php _e('(GMT) Greenwich Mean Time : London'); ?></option>
                                        <option value="Africa/Abidjan" <?php if($wp_scintg_timezone == "Africa/Abidjan") echo 'selected="selected"' ?> ><?php _e('(GMT) Monrovia, Reykjavik'); ?></option>
                                        <option value="Europe/Amsterdam" <?php if($wp_scintg_timezone == "Europe/Amsterdam") echo 'selected="selected"' ?> ><?php _e('(GMT+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna'); ?></option>
                                        <option value="Europe/Belgrade" <?php if($wp_scintg_timezone == "Europe/Belgrade") echo 'selected="selected"' ?> ><?php _e('(GMT+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague'); ?></option>
                                        <option value="Europe/Brussels" <?php if($wp_scintg_timezone == "Europe/Brussels") echo 'selected="selected"' ?> ><?php _e('(GMT+01:00) Brussels, Copenhagen, Madrid, Paris'); ?></option>
                                        <option value="Africa/Algiers" <?php if($wp_scintg_timezone == "Africa/Algiers") echo 'selected="selected"' ?> ><?php _e('(GMT+01:00) West Central Africa'); ?></option>
                                        <option value="Africa/Windhoek" <?php if($wp_scintg_timezone == "Africa/Windhoek") echo 'selected="selected"' ?> ><?php _e('(GMT+01:00) Windhoek'); ?></option>
                                        <option value="Asia/Beirut" <?php if($wp_scintg_timezone == "Asia/Beirut") echo 'selected="selected"' ?> ><?php _e('(GMT+02:00) Beirut'); ?></option>
                                        <option value="Africa/Cairo" <?php if($wp_scintg_timezone == "Africa/Cairo") echo 'selected="selected"' ?> ><?php _e('(GMT+02:00) Cairo'); ?></option>
                                        <option value="Asia/Gaza" <?php if($wp_scintg_timezone == "Asia/Gaza") echo 'selected="selected"' ?> ><?php _e('(GMT+02:00) Gaza'); ?></option>
                                        <option value="Africa/Blantyre" <?php if($wp_scintg_timezone == "Africa/Blantyre") echo 'selected="selected"' ?> ><?php _e('(GMT+02:00) Harare, Pretoria'); ?></option>
                                        <option value="Asia/Jerusalem" <?php if($wp_scintg_timezone == "Asia/Jerusalem") echo 'selected="selected"' ?> ><?php _e('(GMT+02:00) Jerusalem'); ?></option>
                                        <option value="Europe/Minsk" <?php if($wp_scintg_timezone == "Europe/Minsk") echo 'selected="selected"' ?> ><?php _e('(GMT+02:00) Minsk'); ?></option>
                                        <option value="Asia/Damascus" <?php if($wp_scintg_timezone == "Asia/Damascus") echo 'selected="selected"' ?> ><?php _e('(GMT+02:00) Syria'); ?></option>
                                        <option value="Europe/Moscow" <?php if($wp_scintg_timezone == "Europe/Moscow") echo 'selected="selected"' ?> ><?php _e('(GMT+03:00) Moscow, St. Petersburg, Volgograd'); ?></option>
                                        <option value="Africa/Addis_Ababa" <?php if($wp_scintg_timezone == "Africa/Addis_Ababa") echo 'selected="selected"' ?> ><?php _e('(GMT+03:00) Nairobi'); ?></option>
                                        <option value="Asia/Tehran" <?php if($wp_scintg_timezone == "Asia/Tehran") echo 'selected="selected"' ?> ><?php _e('(GMT+03:30) Tehran'); ?></option>
                                        <option value="Asia/Dubai" <?php if($wp_scintg_timezone == "Asia/Dubai") echo 'selected="selected"' ?> ><?php _e('(GMT+04:00) Abu Dhabi, Muscat'); ?></option>
                                        <option value="Asia/Yerevan" <?php if($wp_scintg_timezone == "Asia/Yerevan") echo 'selected="selected"' ?> ><?php _e('(GMT+04:00) Yerevan'); ?></option>
                                        <option value="Asia/Kabul" <?php if($wp_scintg_timezone == "Asia/Kabul") echo 'selected="selected"' ?> ><?php _e('(GMT+04:30) Kabul'); ?></option>
                                        <option value="Asia/Yekaterinburg" <?php if($wp_scintg_timezone == "Asia/Yekaterinburg") echo 'selected="selected"' ?> ><?php _e('(GMT+05:00) Ekaterinburg'); ?></option>
                                        <option value="Asia/Tashkent" <?php if($wp_scintg_timezone == "Asia/Tashkent") echo 'selected="selected"' ?> ><?php _e('(GMT+05:00) Tashkent'); ?></option>
                                        <option value="Asia/Kolkata" <?php if($wp_scintg_timezone == "Asia/Kolkata") echo 'selected="selected"' ?> ><?php _e('(GMT+05:30) Chennai, Kolkata, Mumbai, New Delhi'); ?></option>
                                        <option value="Asia/Katmandu" <?php if($wp_scintg_timezone == "Asia/Katmandu") echo 'selected="selected"' ?> ><?php _e('(GMT+05:45) Kathmandu'); ?></option>
                                        <option value="Asia/Dhaka" <?php if($wp_scintg_timezone == "Asia/Dhaka") echo 'selected="selected"' ?> ><?php _e('(GMT+06:00) Astana, Dhaka'); ?></option>
                                        <option value="Asia/Novosibirsk" <?php if($wp_scintg_timezone == "Asia/Novosibirsk") echo 'selected="selected"' ?> ><?php _e('(GMT+06:00) Novosibirsk'); ?></option>
                                        <option value="Asia/Rangoon" <?php if($wp_scintg_timezone == "Asia/Rangoon") echo 'selected="selected"' ?> ><?php _e('(GMT+06:30) Yangon (Rangoon)'); ?></option>
                                        <option value="Asia/Bangkok" <?php if($wp_scintg_timezone == "Asia/Bangkok") echo 'selected="selected"' ?> ><?php _e('(GMT+07:00) Bangkok, Hanoi, Jakarta'); ?></option>
                                        <option value="Asia/Krasnoyarsk" <?php if($wp_scintg_timezone == "Asia/Krasnoyarsk") echo 'selected="selected"' ?> ><?php _e('(GMT+07:00) Krasnoyarsk'); ?></option>
                                        <option value="Asia/Hong_Kong" <?php if($wp_scintg_timezone == "Asia/Hong_Kong") echo 'selected="selected"' ?> ><?php _e('(GMT+08:00) Beijing, Chongqing, Hong Kong, Urumqi'); ?></option>
                                        <option value="Asia/Irkutsk" <?php if($wp_scintg_timezone == "Asia/Irkutsk") echo 'selected="selected"' ?> ><?php _e('(GMT+08:00) Irkutsk, Ulaan Bataar'); ?></option>
                                        <option value="Australia/Perth" <?php if($wp_scintg_timezone == "Australia/Perth") echo 'selected="selected"' ?> ><?php _e('(GMT+08:00) Perth'); ?></option>
                                        <option value="Australia/Eucla" <?php if($wp_scintg_timezone == "Australia/Eucla") echo 'selected="selected"' ?> ><?php _e('(GMT+08:45) Eucla'); ?></option>
                                        <option value="Asia/Tokyo" <?php if($wp_scintg_timezone == "Asia/Tokyo") echo 'selected="selected"' ?> ><?php _e('(GMT+09:00) Osaka, Sapporo, Tokyo'); ?></option>
                                        <option value="Asia/Seoul" <?php if($wp_scintg_timezone == "Asia/Seoul") echo 'selected="selected"' ?> ><?php _e('(GMT+09:00) Seoul'); ?></option>
                                        <option value="Asia/Yakutsk" <?php if($wp_scintg_timezone == "Asia/Yakutsk") echo 'selected="selected"' ?> ><?php _e('(GMT+09:00) Yakutsk'); ?></option>
                                        <option value="Australia/Adelaide" <?php if($wp_scintg_timezone == "Australia/Adelaide") echo 'selected="selected"' ?> ><?php _e('(GMT+09:30) Adelaide'); ?></option>
                                        <option value="Australia/Darwin" <?php if($wp_scintg_timezone == "Australia/Darwin") echo 'selected="selected"' ?> ><?php _e('(GMT+09:30) Darwin'); ?></option>
                                        <option value="Australia/Brisbane" <?php if($wp_scintg_timezone == "Australia/Brisbane") echo 'selected="selected"' ?> ><?php _e('(GMT+10:00) Brisbane'); ?></option>
                                        <option value="Australia/Hobart" <?php if($wp_scintg_timezone == "Australia/Hobart") echo 'selected="selected"' ?> ><?php _e('(GMT+10:00) Hobart'); ?></option>
                                        <option value="Asia/Vladivostok" <?php if($wp_scintg_timezone == "Asia/Vladivostok") echo 'selected="selected"' ?> ><?php _e('(GMT+10:00) Vladivostok'); ?></option>
                                        <option value="Australia/Lord_Howe" <?php if($wp_scintg_timezone == "Australia/Lord_Howe") echo 'selected="selected"' ?> ><?php _e('(GMT+10:30) Lord Howe Island'); ?></option>
                                        <option value="Etc/GMT-11" <?php if($wp_scintg_timezone == "Etc/GMT-11") echo 'selected="selected"' ?> ><?php _e('(GMT+11:00) Solomon Is., New Caledonia'); ?></option>
                                        <option value="Asia/Magadan" <?php if($wp_scintg_timezone == "Asia/Magadan") echo 'selected="selected"' ?> ><?php _e('(GMT+11:00) Magadan'); ?></option>
                                        <option value="Pacific/Norfolk" <?php if($wp_scintg_timezone == "Pacific/Norfolk") echo 'selected="selected"' ?> ><?php _e('(GMT+11:30) Norfolk Island'); ?></option>
                                        <option value="Asia/Anadyr" <?php if($wp_scintg_timezone == "Asia/Anadyr") echo 'selected="selected"' ?> ><?php _e('(GMT+12:00) Anadyr, Kamchatka'); ?></option>
                                        <option value="Pacific/Auckland" <?php if($wp_scintg_timezone == "Pacific/Auckland") echo 'selected="selected"' ?> ><?php _e('(GMT+12:00) Auckland, Wellington'); ?></option>
                                        <option value="Etc/GMT-12" <?php if($wp_scintg_timezone == "Etc/GMT-12") echo 'selected="selected"' ?> ><?php _e('(GMT+12:00) Fiji, Kamchatka, Marshall Is.'); ?></option>
                                        <option value="Pacific/Chatham" <?php if($wp_scintg_timezone == "Pacific/Chatham") echo 'selected="selected"' ?> ><?php _e('(GMT+12:45) Chatham Islands'); ?></option>
                                        <option value="Pacific/Tongatapu" <?php if($wp_scintg_timezone == "Pacific/Tongatapu") echo 'selected="selected"' ?> ><?php _e('(GMT+13:00) Nuku\'alofa'); ?></option>
                                        <option value="Pacific/Kiritimati" <?php if($wp_scintg_timezone == "Pacific/Kiritimati") echo 'selected="selected"' ?> ><?php _e('(GMT+14:00) Kiritimati'); ?></option>
                                    </select>
		  </div></div>  
          <div class="control-group"> 
          <label class="control-label" for="wp_scintg_cache_time_unit">Check for new posts directly from facebook in every</label>                       
            <div class="controls">  
               <input name="wp_scintg_cache_time" style="width: 100px;" id="wp_scintg_cache_time" type="text" value="<?php echo esc_attr_e( $wp_scintg_cache_time); ?>" size="4" />
               <select name="wp_scintg_cache_time_unit" style="width: 150px;">
                   <option value="minutes" <?php if($wp_scintg_cache_time_unit== "minutes") echo 'selected' ?> >minutes</option>
                   <option value="hours" <?php if($wp_scintg_cache_time_unit == "hours") echo 'selected' ?> >hours</option>
                   <option value="days" <?php if($wp_scintg_cache_time_unit == "days") echo 'selected' ?> >days</option>                                                        
              </select>
              <p class="help-block">If you want to cache facebook posts temporarily in database so that on next page load, feed will be shown from cached data.You can set how long <br/>you want to keep cached data in database by entering value in textbox.If you don't want to cache, simply leave the textbox blank.   </p>
            </div>  
          </div>                                            
                              		    
		    <div class="control-group">  
            <label class="control-label" for="wp_scintg_showborder">Show outer border</label>  
            <div class="controls">  
              <label class="checkbox">  
                <input type="checkbox" <?php echo esc_attr_e($wp_scintg_showborder); ?> name="wp_scintg_showborder" id="wp_scintg_showborder" value="enabled" />  
              </label>  
            </div>  
          </div>
          <div class="control-group">  
            <label class="control-label" for="wp_scintg_backcolor">Background color of wall (#ffffff...)</label>  
            <div class="controls">  
			<input type="text" class="input-xlarge" name="wp_scintg_backcolor" value="<?php echo esc_attr_e($wp_scintg_backcolor); ?>" id="wp_scintg_backcolor" />
						 <p class="help-block"><a href="http://www.colorpicker.com/" target="_blank">Ex. #EG9A10 color picker</a></p>  
            </div>  
          </div> 	
          <div class="control-group">  
            <label class="control-label" for="wp_scintg_postbordercolor">Post border color(#E6E8E8...)</label>  
            <div class="controls">  
			<input type="text" class="input-xlarge" name="wp_scintg_postbordercolor" value="<?php echo esc_attr_e($wp_scintg_postbordercolor); ?>" id="wp_scintg_postbordercolor" />			 
			<p class="help-block"><a href="http://www.colorpicker.com/" target="_blank">Ex. #EG9A10 color picker</a></p>  
            </div>  
          </div> 

          <div class="control-group">  
            <label class="control-label" for="wp_scintg_postauthorcolor">Post author name color(#3B5998...)</label>  
            <div class="controls">  
			<input type="text" class="input-xlarge" name="wp_scintg_postauthorcolor" value="<?php echo esc_attr_e($wp_scintg_postauthorcolor); ?>" id="wp_scintg_postauthorcolor" />			 
			<p class="help-block"><a href="http://www.colorpicker.com/" target="_blank">Ex. #3B5998 color picker</a></p>  
            </div>  
          </div>

          <div class="control-group">  
            <label class="control-label" for="wp_scintg_posttextcolor">Post text color(#333333...)</label>  
            <div class="controls">  
			<input type="text" class="input-xlarge" name="wp_scintg_posttextcolor" value="<?php echo esc_attr_e($wp_scintg_posttextcolor); ?>" id="wp_scintg_posttextcolor" />			 
			<p class="help-block"><a href="http://www.colorpicker.com/" target="_blank">Ex. #333333 color picker</a></p>  
            </div>  
          </div>
      
          <div class="control-group">  
            <label class="control-label" for="wp_scintg_datecolor">Date text color(#777...)</label>  
            <div class="controls">  
			<input type="text" class="input-xlarge" name="wp_scintg_datecolor" value="<?php echo esc_attr_e($wp_scintg_datecolor); ?>" id="wp_scintg_datecolor" />			 
			<p class="help-block"><a href="http://www.colorpicker.com/" target="_blank">Ex. #777 color picker</a></p>  
            </div>  
          </div>   
           <div class="control-group">  
            <div class="controls">  
			  <b>More settings in pro version</b>   
            </div>  
          </div>   
		  		   		 		 		  								               
          <div class="form-actions"> 
  		   <input type="hidden" name="wp_scintg_active_tab" value="3" />		             				 
            <input type="submit" name="submit" class="btn btn-primary" value="Update"/>  
          </div>  
        </fieldset>  
</form>  
</div> 

<div id="ms_fourth_tab"  <?php echo $active4; ?>>
<form method="post" name="feed_options" action="" class="form-horizontal">  
        <fieldset>  
          <legend>Facebook Social plugins</legend> 
		 <div class="control-group">    
            <div class="controls">  			 			
			 <b>FB like button short code - [wp_social_integration_like_button] </b><br/><br/></b>			 			 
			 <b>FB follow button short code - [wp_social_integration_follow_button]</b><br/><br/>			 
			 <b>FB send button short code -   [wp_social_integration_send_button]</b><br/><br/>
			 <b>More social plugins in pro version</b>
            </div>  
          </div>   
        </fieldset>  
</form>  
</div> 

<div id="ms_fifth_tab"  <?php echo $active5; ?>>
<form method="post" name="system_options" action="" class="form-horizontal">  
        <fieldset>  
          <legend>System requirements check</legend>
          <div class="control-group">  
           <label class="control-label">To get FB home feed some of these functions should be enabled in server</label>
            <div class="controls"> 
               Server & php info:&nbsp;&nbsp; <?php echo $_SERVER['SERVER_SOFTWARE']?><br/><br/>                
			   Is cURL enabled:&nbsp;&nbsp;<input type="checkbox" <?php if(is_callable('curl_init')) echo "checked"; ?> disabled value="enabled" /><br/><br/>
			   Is url fopen enabled:&nbsp;&nbsp;<input type="checkbox" <?php if(ini_get( 'allow_url_fopen' )) echo "checked"; ?> disabled value="enabled" /><br/><br/>
			   Is Json enabled:&nbsp;<input type="checkbox" <?php if(function_exists("json_decode")) echo "checked"; ?> disabled value="enabled" /><br/><br/>			 	 
            </div>  
          </div> 
          <div>              
           * If either cUrl or allow_url_fopen(fopen) enabled, it's ok. If both of them disabled, ask your hosting to enable it or if you own your server it's easy to do.<br/>
           Also without these, it may still work by the fallback method. But if feed do not load after all, contact us, if needed we send you previous javascript version <br/>
           which not depends on the availability of above methods.<br/><br/>
           * Json should be enabled(checked), but in any case it's disabled ask your host to enable it
                         
		  </div>
        </fieldset>  
</form>  
</div>

</div>  
</div><hr/>  

<div class="row-fluid">
<div class="well" style="color: navy">
Please check "System rquirements" tab above to know if your server has required methods enabled to display the content of the facebook home page feed.Also if feed display<br/>
not works, make sure access token(<a target="_blank" href="https://developers.facebook.com/tools/debug/">check here</a>) are right.<br/><br/>
Read all the features, instructions in <a target="_blank" href="http://extensions.techhelpsource.com/wp_social_integration_documentation.html">Documentation</a> 
</div> 
<div class="well">
<h4>how to display features</h4> 
To display Facebook login(pro) button along with login form copy and paste this short code anywhere of page or post - <strong>[wp_social_integration_login_short_code]</strong> <br/><br/>
To display Facebook home page news feed copy and paste this short code anywhere of page or post - <strong>[wp_social_integration_feed_short_code]</strong> <br/><br/>
<b>View "Social plugins" tab for social short codes </b>
</div>
<div class="well">
<a class="btn btn-info" target="_blank" style="font-weight:bold;" href="http://extensions.techhelpsource.com/wordpress/wordpress-social-integration-pro">Click to Buy pro version now for all these stunning features</a><br/><br/>
1. <strong>Social login(FB) button along with login form - show/hide different form fields, user logging in first time via Facebook can connect existing account or creating new one...</strong><br/><br/>
2. <strong>Open graph & twitter cards metadata - add Open graph protocol & twitter cards metadata, metadata input section for each post/page in edit panel, facebook and twitter profile/username inputs for each user for meta tags, site wide meta tags, set default image for different meta tags...read doc for more</strong><br/><br/>
3. <strong>Facebook home page news feed - show images/links/youtube videos, show first comments, show all kinds of posts, display header bar, links and hashtags are linkable in text and other settings.</strong><br/><br/>
4. <strong>Social plugins - more social plugins like FB share button, comments, any FB post embed...</strong><br/><br/>
<strong>View pro version demo for all the features here - <a class="btn btn-info" target="_blank" href="http://wordpress.techhelpsource.com/wp_social_integration/">Pro Demo</a></strong>

</div>
</div>
 
</div> <!--  container-fluid div ends  -->

</div> <!-- main div ends -->
 <?php  } /* end of admin settings function */ 
 
 //activation
 function wp_social_integration_activation()
 {
 
 	if(!get_option('wp_scintg_plugin_meta_settings')) {
 		$wp_scintg_plugin_meta_settings = array(
 				'wp_scintg_add_open_graph' => 'enabled',
 				'wp_scintg_add_twitter_card' => 'enabled',
 				'wp_scintg_basic_meta' => 'enabled',
 				'wp_scintg_add_site_wide_meta' => '',
 				'wp_scintg_basic_auto_desc' => 'enabled',
 				'wp_scintg_basic_auto_keywords' => 'enabled',
 				'wp_scintg_frontpg_desc' => '',
 				'wp_scintg_frontpg_keywords' => '',
 				'wp_scintg_og_appid' => '',
 				'wp_scintg_defult_img' => ''
 		);
 
 		add_option( 'wp_scintg_plugin_meta_settings', $wp_scintg_plugin_meta_settings);
 	}
 
 	if(!get_option('wp_scintg_plugin_home_feed_settings')) {
 		$wp_scintg_plugin_home_feed_settings = array(
 			 'wp_scintg_accesstoken' => '',
 				'wp_scintg_facebookwidth' => '90',
 				'wp_scintg_heightoption' => 'fixed',
 				'wp_scintg_facebookheight' => '550',
 				'wp_scintg_header' => 'enabled',
 				'wp_scintg_headertext' => 'Follow me',
 				'wp_scintg_postnum' => '30',
 				'wp_scintg_showborder' => 'enabled',
 				'wp_scintg_dateformat' => 'nonus',
 				'wp_scintg_timezone' => 'Europe/London',
 				'wp_scintg_cache_time' => '',
 				'wp_scintg_cache_time_unit' => '',
 				'wp_scintg_backcolor' => '#ffffff',
 				'wp_scintg_postbordercolor' => '#F0F0F0',
 				'wp_scintg_postauthorcolor' => '#3B5998',
 				'wp_scintg_posttextcolor' => '#333333',
 				'wp_scintg_medianamecolor' => '#3B5998',
 				'wp_scintg_mediadescolor' => '#4e4f4e',
 				'wp_scintg_datecolor' => '#777',
 				'wp_scintg_comauthorcolor' => '#3B5998',
 				'wp_scintg_colorcom' => '#333333',
 				'wp_scintg_posttextlinkcolor' => '#3B5998',
 		);
 
 		add_option( 'wp_scintg_plugin_home_feed_settings', $wp_scintg_plugin_home_feed_settings);
 	}
 
 }
 function wp_social_integration_deactivation()
 {
 	if (!current_user_can( 'activate_plugins' ))
 		return;
 	delete_option( 'wp_scintg_plugin_home_feed_settings' );
 	delete_option( 'wp_scintg_plugin_meta_settings' );
 }
 
 
