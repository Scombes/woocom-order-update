<?php
/*
Plugin Name: Order Upload plugin
Description: A  plugin to upload XML file from FTP server and update orders
Author: Scott Combes
Version: 0.5
*/

defined( 'ABSPATH' ) or exit;



/**
*
*Include all needed files for plugin
*
**/
// Required functions. If connect function is not present require ftp_class file
if ( ! function_exists( 'connect' ) ) {
	require_once( plugin_dir_path( __FILE__ ) . '/classes/ftp_class.php' );
}

// Required functions. If readXML is not present require orderupdate_class file
if ( ! function_exists( 'readXML' ) ) {
	require_once( plugin_dir_path( __FILE__ ) . '/classes/orderupdate_class.php' );
}

// Required functions. If get_orders is not present require orderupdate-cron  file
if ( ! function_exists( 'get_orders' ) ) {
	require_once( plugin_dir_path( __FILE__ ) . 'orderupdate-cron.php' );
}

// Required functions. If orderupdateSettings is not present require orderupdate-settings file
if ( ! function_exists( 'orderupdateSettings' ) ) {
	require_once( plugin_dir_path( __FILE__ ) . 'orderupdate-settings.php' );
}

// Required functions. If orderupdatePastOrders is not present require orderupdate-orders file
if ( ! function_exists( 'orderupdatePastOrders' ) ) {
	require_once( plugin_dir_path( __FILE__ ) . 'orderupdate-orders.php' );
}



/**
*
* Add menu, pages, and options for plugin
*
**/
//Add page to Admin Menu
add_action('admin_menu', 'order_update_plugin_setup_menu');

//Add menu page,  when opened call upload_init function
function order_update_plugin_setup_menu(){
         add_menu_page( 'Order Update Page', 'Order Update', 'manage_options', 'orderupdate-plugin', 'upload_init' );
				 add_submenu_page( 'orderupdate-plugin', 'Order Update', 'Order Update', 'manage_options', 'orderupdate-plugin');
				 add_submenu_page( 'orderupdate-plugin', 'Settings', 'Settings', 'manage_options', 'orderupdate-plugin-setup', 'orderupdateSettings');
				 add_submenu_page( 'orderupdate-plugin', 'Past Orders', 'Past Orders', 'manage_options', 'orderupdate-past-orders', 'orderupdatePastOrders');
			 	 }



/**
*
* Initial function to run when main page opens
*
**/
function upload_init(){

	//Set default timezone for dates
	date_default_timezone_set('America/Chicago');

	//Check if last run option exist, if it doesn't then add it to the database.
	if(!get_option('order_update_last_run')){
    	update_option('order_update_last_run', 'Update has not run.');
		}

	//Check if last run option exist, if it doesn't then add it to the database.
	if(!get_option('ftp_logs_sc')){
    	update_option('ftp_logs_sc', 'Update has not run.');
		}

	//Check if last run option exist, if it doesn't then add it to the database.
	if(!get_option('orderUpdate_logs_sc')){
    	update_option('orderUpdate_logs_sc', 'No orders updated on last run.');
		}

	//If get_file_update_orders cron job is not set, let users know.
  if(!wp_next_scheduled( 'get_file_update_orders' ) ) {
  		$nocron = true;
  	}

	//If $nocron exist then run initialRender function.  If not, run stndRender function.
	($nocron) ? initialRender() : stndRender();

}

//Function that renders main page if no settings have been saved.
function initialRender(){
?>
	<div class="wrap">
	<h2>Order Update</h2>
	<p>Please fill out the settings page.</p>
	</div>
<?php
}

//Function that renders main page, after settings have been saved
function stndRender(){
	//Create Variable to display on page
  $timestamp = wp_next_scheduled( 'get_file_update_orders' );
  $nextTime = date("m/d/Y @ g:i:A", $timestamp);
	$lastRun = get_option( 'order_update_last_run', $default = false );
	$ftpLogs = get_option( 'ftp_logs_sc', $default = false );
	$orderLogs = get_option( 'orderUpdate_logs_sc', $default = false );
		?>
		<div class="wrap">
		<h2>Order Update</h2>
		<p><strong>Next Run:</strong> <?php echo $nextTime; ?></p>
		<p><strong>Last Run:</strong> <?php echo $lastRun; ?></p>
		<p><Strong>Results Of Last Run:</strong></p>
		<?php
			if(is_array($orderLogs)){
				 foreach($orderLogs as $log=>$order){
	 					foreach($order as $key=>$value){
	 						echo $value."<br />";
	 					}
	 			}
			} else {
				echo $orderLogs;
			}

		?>
		</div>
		<?php
		}

?>
