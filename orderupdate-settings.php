<?php
/**
*
* Functions For Settings Page
*
**/
//Add custom JavaScript to admin footer
add_action( 'admin_footer', 'orderUpdate_sc_javascript' );
//Enque Javscript file
function orderUpdate_sc_javascript() {
    wp_enqueue_script('orderupdate', plugin_dir_url(__FILE__) . 'js/orderupdate.js', array('jquery'));
}

//call register settings function
add_action( 'admin_init', 'register_SCorderupdate_settings' );

//register options to store in database
function register_SCorderupdate_settings() {
				 register_setting( 'SCorderupdate-settings-group', 'ftp_host_sc' );
				 register_setting( 'SCorderupdate-settings-group', 'ftp_pass_sc' );
				 register_setting( 'SCorderupdate-settings-group', 'ftp_user_sc' );
				 register_setting( 'SCorderupdate-settings-group', 'ftp_frequency_sc' );
				 register_setting( 'SCorderupdate-settings-group', 'ftp_time_sc' );
				 register_setting( 'SCorderupdate-settings-group', 'ftp_oldTime_sc' );
			 	 }



//add AJAX function
add_action( 'wp_ajax_order_test_ftp', 'order_update_ftpTest_sc_callback' );

function order_update_ftpTest_sc_callback(){

	$order_host = $_POST['order_host'];
	$order_user = $_POST['order_user'];
	$order_password = $_POST['order_password'];

	//Create new FTP instance
  $ftpObjTest = new FTP_Client();

  //Connect to server
  $testResult = $ftpObjTest -> connect($order_host, $order_user, $order_password );
	//Test Connection and return results
	$ftpObjTest -> closeConnection();
	if(!$testResult){
		echo "<p style='color: red;'>FTP connection has failed!</p>";
	} else {
		echo "<p style='color: green;'>Connected to server. Don't forget to save changes.</p>";
	}
	wp_die(); //terminate immediately and return a proper response
}



//add AJAX function
add_action( 'wp_ajax_create_cronjob_sc', 'create_cronjob_sc_callback' );

//Ajax function to update cron schedule
function create_cronjob_sc_callback() {
	//Get post values and save to variables
	$newTime = $_POST['newTime'];
	$oldTime = $_POST['oldTime'];
	$frequency = $_POST['frequency'];
	// Get the timestamp of the next scheduled run
	$timestamp = wp_next_scheduled( 'get_file_update_orders' );
	// Un-schedule the event
	wp_unschedule_event( $timestamp, 'get_file_update_orders' );
	//Convert local time to UTC time
	$datetime = $newTime;
	$tz_from = 'America/Chicago';
	$tz_to = 'UTC';
	$format = 'Ymd\THis\Z';
	$dt = new DateTime($datetime, new DateTimeZone($tz_from));
	$dt->setTimeZone(new DateTimeZone($tz_to));
	$unitime = strtotime($dt->format($format));
	 //Make sure this event hasn't been scheduled
  if( !wp_next_scheduled( 'get_file_update_orders' ) ) {
  	// Schedule the event
  	wp_schedule_event( $unitime, $frequency, 'get_file_update_orders' );
  }
  echo true;
	wp_die(); //terminate immediately and return a proper response
}



//Function to render settings page
function orderupdateSettings(){
?>
<div class="wrap">
<div class="row clearfix">
<div class="col-sm-6">
<h1>Order Update Settings</h1>

<form method="post" action="options.php" id="orderUpdateSettingForm">
    <?php settings_fields( 'SCorderupdate-settings-group' ); ?>
    <?php do_settings_sections( 'SCorderupdate-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">FTP Host</th>
        <td><input type="text" name="ftp_host_sc" id="ftp_host_sc" value="<?php echo esc_attr( get_option('ftp_host_sc') ); ?>" /></td>
        </tr>

        <tr valign="top">
        <th scope="row">FTP User</th>
        <td><input type="text" name="ftp_user_sc" id="ftp_user_sc" value="<?php echo esc_attr( get_option('ftp_user_sc') ); ?>" /></td>
        </tr>

        <tr valign="top">
        <th scope="row">FTP Password</th>
        <td><input type="text" name="ftp_pass_sc" id="ftp_pass_sc" value="<?php echo esc_attr( get_option('ftp_pass_sc') ); ?>" /></td>
        </tr>
				  <tr valign="top">
	        <th scope="row">Test FTP Connection</th>
	        <td><button id="orderupdate-FTPTest" class="button button-primary">Run Test</button> <img src="/wp-admin/images/spinner.gif" alt="loading Gif" id="order_update_loading_gif" style="display: none;"> <br /><span id="ftpTestResults"></span></td>
	        </tr>

				<tr valign="top">
        <th scope="row">Frequency</th>
        <td><select name="ftp_frequency_sc" id="frequency">
					<option value="<?php echo esc_attr( get_option('ftp_frequency_sc') ); ?>"><?php echo esc_attr( get_option('ftp_frequency_sc') ); ?></option>
					<option value="daily">daily</option>
					<option value="twicedaily">twicedaily</option>
					<option value="hourly">hourly</option>
				</select></td>
        </tr>

				<tr valign="top">
        <th scope="row">Start Time</th>
        <td>
						<input type="datetime-local" id="newTime" name="ftp_time_sc" value="<?php echo esc_attr( get_option('ftp_time_sc') ); ?>" />
						<input type="hidden" id="oldTime" name="ftp_oldTime_sc" value="<?php echo esc_attr( get_option('ftp_time_sc') ); ?>" />
						<p><small>Initial start time.  All future events will be based off this time.</small></p>
				</td>
        </tr>
    </table>
		<input type="submit" name="submit" id="OrderUpdateSCsubmit" class="button button-primary" value="Save Changes">
		<div id="saveResutls"></div>

</form>
</div>
</div>
</div>
<?php
}
?>
