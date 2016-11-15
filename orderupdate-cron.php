<?php
/**
*
* Functions that get and update orders
*
**/
//Define host, username, and password for FTP Connection
define('FTP_HOST', esc_attr( get_option('ftp_host_sc') ));
define('FTP_USER', esc_attr( get_option('ftp_user_sc') ));
define('FTP_PASS',  esc_attr( get_option('ftp_pass_sc') ));

//Run get_orders function when cron job get_file_update_orders triggers
add_action( 'get_file_update_orders', 'get_orders' );

//Function to get orders from FTP server
function get_orders(){

	// Get current date
  $date = date('Y-m-d-H:i:s');
	//Set up local file name
  $local_file = plugin_dir_path( __FILE__ ).'orders/order-complete-'.$date.'.xml';
  //Create new FTP instance
  $ftpObj = new FTP_Client();
  //Connect to server
  $ftpObj -> connect(FTP_HOST, FTP_USER, FTP_PASS );
  //Check if connection was successfull
  if(!$ftpObj){
		$logs = $ftpObj -> getMessages();
		update_option('ftp_logs_sc', $logs);
		exit;
  } else {
    //Get list of files from server
    $contents =  $ftpObj -> listAllFiles();
  }
  //check for XML Files
  function is_xml($file) {
      return preg_match('/.*\.xml/', $file) > 0;
  }
  //Save array of xml files.
  $filtered = array_values(array_filter($contents, is_xml));
	$resultLog = array();
  //Loop through xml files download then delete from server
  foreach ($filtered as $filename=>$file) {
      		 $ftpObj -> downloadFile($file, $local_file);
      		 if($ftpObj){
				 	 	$ftpObj -> deleteFile($file);
 						//Run order updates function on each XML file found.
 						$resultLog[] = order_updates($local_file);
      			}
  			 	 }
	//Close FTP Connection
  $ftpObj -> closeConnection();
	$logs = $ftpObj -> getMessages();
	update_option('ftp_logs_sc', $logs);
	update_option('orderUpdate_logs_sc', $resultLog);
}

//Function the updates orders with tracking code and changes status to complete.
function order_updates($local_file){
	//Set timezone
	date_default_timezone_set('America/Chicago');
	//Create new order objecct
	$orderObj = new orderupdate();
	//Read and extract XML data from file
	$orders = $orderObj -> readXML($local_file);
	//Pull order numbers and tracking data to update orders
	$results = $orderObj -> processOrders($orders);
	//Get message log
	$logs = $orderObj -> getMessages();
	//Format date into readable format
	$date = date('m/d/Y @ g:i:A');
	//update database with last run time
	update_option('order_update_last_run', $date);
	//return logs
	return $logs;
}


?>
