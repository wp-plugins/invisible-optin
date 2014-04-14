<?php

$invisible_optin_db_version = "0.4";
add_action('admin_init', 'editor_admin_init');
add_action('admin_head', 'editor_admin_head');

?>




 <div id="mWrapper">
   <?php  require_once dirname( __FILE__ ) . '/invisible_optin_header.php';   ?>
   
   	<div id="container">
		<div id="listapps">
			<div id="appHeader">
					<span>Global Settings:</span>		
			</div>
			<div cloneid="ORG" id="app-1" class="appitem clone-ORG">
			   <div style="clear: both"></div>
   <?php if($_REQUEST["act"] == 'update'){ ?>
			<div class="updated fade" id="message"><p>Settings Updated Successfully.</p></div>
   <?php } ?>
   
   
<?php
	
	if (isset($_REQUEST["act"]))
	{
		
		switch ($_REQUEST["act"]) {
			case 'update':
				$msg = invisible_optin_settings_update($_POST);
				break;
			default:
				invisible_optin_settings_form();
				break;	
		}
	}
	else{
		invisible_optin_settings_form();
	}
?>
		</div>
		<br clear="left">
	 </div>
	</div>
</div>	
<?php  
function invisible_optin_settings_form($msg = null){
	global $wpdb;	
	$table_name = $wpdb->prefix . "invisible_optin_settings";

	$conf_script_code = $wpdb->get_var( 
		$wpdb->prepare( 
			"SELECT      option_value 
			FROM        $table_name 
			WHERE       option_name = %s",
			'CONF_SCRIPT_CODE'
		) 
	); 

?>


<form name="form1" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
	<div style="padding:20px; " id="formArea">	


	<div class="frm_fields">
		<h3 style="margin-top: 0px;">Custom Code</h3>(Copy Facebook's Custom Audience Re-Marketing Pixel Code here.)		
		<textarea name="conf_script_code" id="conf_script_code" rows="15"><?php echo $conf_script_code; ?></textarea>
	</div>
	
			<p class="submit">
			
			
			<input type="submit" name="Submit" value="Save Changes" class="blue-btn-2" />

		</p>
	
	</div>
	<input type="hidden" name="act" value="update"/>
</form>

	
	 
<?php } ?>
<?php

	function invisible_optin_settings_update($data){
		    global $wpdb, $current_user;
			$table_name = $wpdb->prefix . "invisible_optin_settings";		
			
			$wpdb->update( 
				$table_name, 
				array( 
					'option_value' => $data['conf_script_code']
				), 
				array( 'option_name' => 'CONF_SCRIPT_CODE' ), 
				array( 
					'%s'
				), 
				array( '%s' ) 
			);

			invisible_optin_settings_form('updated');
		
			return true;
	}
?>